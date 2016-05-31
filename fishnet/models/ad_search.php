<?php
/**
 * @author cravelo
 */

/**
 * To sort query results by displayname
 * @param array $a
 * @param array $b
 * @return int
 */
function cmp($a, $b)
{
	$nameA = isset($a['displayname']) ? $a['displayname'] : "";
	$nameB = isset($b['displayname']) ? $b['displayname'] : "";
	if ($nameA == $nameB) {
		return 0;
	}
	return ($nameA < $nameB) ? -1 : 1;
}

/**
 * The active directory search engine
 * @package Models
 * @author cravelo
 */
class Ad_search extends CI_Model {
	private $ldapconn;
	private $dn;
	private $excludegroups;
	public $justthese;

	public function __construct(){
		parent::__construct();

		$this->ldapconn = ldap_connect("ldaps://efadc01.eileenfisher.net/", 636);
		if ($this->ldapconn === false){
			$this->ldapconn = ldap_connect("ldaps://efadc02.eileenfisher.net/", 636);
		}

		if ($this->ldapconn !== false){
			ldap_set_option($this->ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);//THIS IS VERY IMPORTANT
			ldap_set_option($this->ldapconn, LDAP_OPT_REFERRALS, 0);//THIS IS VERY IMPORTANT
			//I never have more than 1000 results, I could even make this number smaller
			ldap_set_option($this->ldapconn, LDAP_OPT_SIZELIMIT, 1000);//this is just for speed.

			if (ldap_bind($this->ldapconn, "svcintranet@eileenfisher.net", "JApvxThGrzRBsVp9") === false){
                show_error("Couldn't connect to the domain controllers, please try again if the error persists report it to the Helpdesk at x4024.");
            }

			$this->dn = "DC=eileenfisher, DC=net";
			$this->justthese = array("samaccountname", "givenname", "sn", "mail", "displayname", "physicaldeliveryofficename", "telephoneNumber", "facsimiletelephonenumber", "title", "memberof", "useraccountcontrol");

            //BEGIN EF Specific
			$this->excludegroups = array(
				"CN=Service Accounts,OU=User Groups,DC=eileenfisher,DC=net",
				"CN=Temp Accounts,OU=User Groups,DC=eileenfisher,DC=net"
			);
		}else{
            show_error("Couldn't connect to the domain controllers, please try again if the error persists report it to the Helpdesk at x4024.");
        }
	}

	public function __destruct() {
		ldap_unbind($this->ldapconn);
	}

	/**
	 * @return bool|int
	 */
	function getPasswMaxAge(){
		$result = ldap_read($this->ldapconn, $this->dn, "objectclass=*", array('maxPwdAge'));
		if ($result){
			$entries = ldap_get_entries($this->ldapconn, $result);
			if ($entries){
				$maxPwdAge = $entries[0]['maxpwdage'][0];
				//if the low 32 bits of $maxPwdAge are 0 then the passwords dont expire
				if (bcmod ($maxPwdAge, 4294967296) === '0') {
					return 0;
				}else{
					return $maxPwdAge;
				}

			}
		}

		return false;
	}

	function getUser_login($user_name) {
		$filter = "(&(samaccountname=$user_name*)(objectCategory=person)(objectClass=user))";
		$result = ldap_search($this->ldapconn, $this->dn, $filter, $this->justthese)
				or die("ERROR: Failed to search the AD Tree.");

		$entries = ldap_get_entries($this->ldapconn, $result);
		//print_r($entries);

		if ($entries["count"] > 0){
			//if the account is not disabled
			if (isset($entries[0]['useraccountcontrol']) and (((int)($entries[0]['useraccountcontrol'][0]) & 2) != 2)){
				return $entries[0];
			}
		}

		return false;
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return bool
	 * @throws Exception
	 */
	function verify_user($username, $password){
		if (empty($username) or empty($password)){
			throw(new Exception('Username and password cannot be empty.', E_AD_EMPTY));
		}

		$maxPwdPage = '-77760000000000'; //$this->ad_search->getPasswMaxAge(); current value;

		$justthese = $this->justthese;
		$this->ad_search->justthese = array(
			"samaccountname", "useraccountcontrol", "pwdlastset", "lockouttime"
		);
		$adRecord = $this->getUser_login($username);
		//var_dump($adRecord); die();
		$this->justthese = $justthese;

		//lockouttime might not be set at all for new users
		$adRecord['lockouttime'] = isset($adRecord['lockouttime']) ? $adRecord['lockouttime'] : array('0');

		//check if user has ever set a password in which case they wouldn't be in this point but...
		if (isset($adRecord['pwdlastset'])){
			//check if the account is locked
			if (isset($adRecord['lockouttime']) and ($adRecord['lockouttime'][0] == '0')){
				//check if password expires
				if (isset($adRecord['useraccountcontrol']) and (((int)($adRecord['useraccountcontrol'][0]) & 65536) != 65536)){
					//check how many days until expiration
					$timeToExpire = bcsub($adRecord['pwdlastset'][0], $maxPwdPage);
					$timeToExpire = bcsub(bcdiv($timeToExpire, '10000000'), '11644473600');

					//from milliseconds to days
					$daysToExpire = $timeToExpire - time();
					$daysToExpire = floor($daysToExpire / 60 / 60 / 24);
					//echo $daysToExpire; die();

					if ($daysToExpire < 0){
						throw(new Exception('Your password is expired, please call the helpdesk (x4024) to set a new
											password.',	E_AD_PWD_EXPIRED));
					}else if ($daysToExpire <= 5){
						throw(new Exception('Your password is expired and you need to change it.', E_AD_PWD_FORCE_CHANGE));
					}else if ($daysToExpire <= 10){
						throw(new Exception("warning@Your password will expire in $daysToExpire days. Please consider
							changing it now. ".anchor('changepassword', 'Click here to change it.'), E_AD_PWD_WARN));
					}
				}
			}else{
				throw(new Exception('Your account is locked because of too many incorrect tries. Please
					<a href="http://efpss01.eileenfisher.net/QPMUser/">CLICK HERE</a> to unlock it.',
					E_AD_ACCT_LOCKED));
			}
		}else{
			throw(new Exception('Password has never been set.', E_AD_PWD_NEVER_SET));
		}

		if (@ldap_bind($this->ldapconn, "$username@eileenfisher.net", $password) === false){
			throw(
				new Exception(
					'Incorrect username and/or password. If you forgot your password please
					<a href="http://efpss01.eileenfisher.net/QPMUser/">CLICK HERE</a> to reset it.',
					E_AD_LOGIN_FAILED
				)
			);
		}

		return true;
	}

	/**
	 * Attempts to change user's password, throws exception if it can't
	 * @param $username
	 * @param $old_password
	 * @param $new_password
	 * @return bool
	 * @throws Exception
	 */
	function changePassword($username, $old_password, $new_password){
		if (empty($username) or empty($old_password) or empty($new_password)){
			throw(new Exception('Username and password cannot be empty.', E_AD_EMPTY));
		}

		if (@ldap_bind($this->ldapconn, "$username@eileenfisher.net", $old_password) === false){
			throw(
				new Exception(
					'Incorrect username and/or password. If you have trouble login in contact the Helpdesk at x4024.',
					E_AD_LOGIN_FAILED
				)
			);
		}else{
			$entries = $this->getUser_login($username);
			if ($entries !== false){
				$dn = $entries['dn'];
				if (ldap_mod_replace($this->ldapconn, $dn, array(
						'unicodepwd' => mb_convert_encoding('"'.$new_password.'"', 'utf-16le')
				)) === true){
					return true;
				}else{
					throw(
						new Exception(
							'FishNET is not able to change your password. Please change it using windows or contact
							the helpdesk.',
							E_AD_RECORD_ACCESS
						)
					);
				}
			}else{
				throw(
					new Exception(
						'There was a problem accessing your user account to update the password. Please try again.',
						E_AD_RECORD_ACCESS
					)
				);
			}
		}
	}

	/**
	 * @param $user_name
	 * @return bool
	 */
	function getUserRecord($user_name) {
		$filter = "(&(samaccountname=$user_name*)(objectCategory=person)(objectClass=user))";
		$result = ldap_search($this->ldapconn, $this->dn, $filter, $this->justthese)
				or die("ERROR: Failed to search the AD Tree.");

		$entries = ldap_get_entries($this->ldapconn, $result);
		//print_r($entries);

		if ($entries["count"] > 0)
		{
			if (isset($entries[0]['useraccountcontrol']) and (((int)($entries[0]['useraccountcontrol'][0]) & 2) != 2)){//if the account is not disabled
				$entries[0]['user_picture'] = site_url("images/profile/$user_name");
				$entries[0]['i_department'] = $this->getUserDepartment($entries[0]['samaccountname'][0]);

				return $entries[0];
			}
		}

		return false;
	}

	/*
	 * this function gets the user record based on their DN and is the most used by all other functions
	 * It also includes the picture and the iDepartments
	 * */
	function getUserRecord_dn($dn) {
		$filter = "(&(objectCategory=person)(objectClass=user))";
		$result = ldap_search($this->ldapconn, $dn, $filter, $this->justthese)
				or die("ERROR: Failed to search the AD Tree.");

		$entries = ldap_get_entries($this->ldapconn, $result);
		//print_r($entries);

		if ($entries["count"] > 0)
		{
			$exclude = count(array_intersect($this->excludegroups, $entries[0]['memberof']));
			$accountDisabled = (isset($entries[0]['useraccountcontrol']) and (((int)$entries[0]['useraccountcontrol'][0] & 2) != 2));
			if(($exclude == 0) and $accountDisabled){
				$user_name = strtolower($entries[0]['samaccountname'][0]);
				$entries[0]['user_picture'] = site_url("images/profile/$user_name");
				$entries[0]['i_department'] = $this->getUserDepartment($entries[0]['samaccountname'][0]);

				return $entries[0];
			}
		}

		return FALSE;
	}

	/*
	 * This function gets the user's iDepartment
	 * The iDepartments are groups in the Intranet Security Groups OU in AD
	 * */
	function getUserDepartment($user_name){
		$justthese = array('memberof');
		$filter = "(samaccountname=$user_name)";

		$result = ldap_search($this->ldapconn, $this->dn, $filter, $justthese)
						or die("ERROR: Failed to search the AD Tree|\n");
		$entries = ldap_get_entries($this->ldapconn, $result);

		$iDepts = array();
		//groups to get the iDepartment
		if (count($entries) > 0){
			if (isset($entries[0]['memberof'])){
				$groups = $entries[0]['memberof'];
				unset($groups["count"]);
				for ($i = 0; $i < count($groups); $i++){
					$tmp = ldap_explode_dn($groups[$i], 1);
					if (substr($tmp[0], 0, 1) == '_'){
						$iDepts[] = str_replace('\2C', ',', substr($tmp[0], 1));
						//Check if there is a parent department
						$result = ldap_search($this->ldapconn, $groups[$i], "(objectCategory=group)", array('memberof'))
								or die("ERROR: Searching Active Directory\n");
						$entries = ldap_get_entries($this->ldapconn, $result);
						if ($entries['count'] > 0){//there is a posibility of a parent department
							$parents = isset($entries[0]['memberof']) ? $entries[0]['memberof'] : array('count' => 0);
							unset($parents["count"]);
							for ($j = 0; $j < count($parents); $j++){
								$tmp = ldap_explode_dn($parents[$j], 1);
								if (substr($tmp[0], 0, 1) == '_'){
									$iDepts[count($iDepts) - 1] .= ' > '.str_replace('\2C', ',', substr($tmp[0], 1));
								}
							}
						}
					}
				}
			}
		}

		return $iDepts;
	}

	function getUserDistros($user_name) {
		$exclude = array("ALL EF EMPLOYEES", "Irvington Employees", "vmpublish", "Secaucus Employees", "NY 111 Fifth Ave 13th Floor", "Small Leadership Forum");
		$justthese = array("memberof");
		$filter = "(samaccountname=$user_name*)";

		$result = ldap_search($this->ldapconn, $this->dn, $filter, $justthese)
						or die("ERROR: Searching Active Directory\n");
		$entries = ldap_get_entries($this->ldapconn, $result);
		//print_r($entries);
		if ($entries["count"] == 0){
			return null;
		}else{
			if (isset($entries[0]['memberof']))
			{
				unset($entries[0]['memberof']['count']);
				$distrosArr = array();
				foreach ($entries[0]['memberof'] as $group)
				{
					$is_distro = (substr($group, 3, 1) == '*');
					if ($is_distro)
					{
						$group = ldap_explode_dn($group, 0);
						//print_r($group);
						$group[0] = str_replace('\2C', '\\\\,', $group[0]);
						$group_name = substr(str_replace('\\\\,', ',', $group[0]), 4);
						unset($group['count']);
						if (!in_array($group_name, $exclude)){
							$distrosArr[] = array(
								'name' => $group_name,
								'dn' => implode(',', $group)
							);
						}
					}
				}

				//sort and return
				sort($distrosArr);
				return $distrosArr;
			}else{ return null; }
		}
	}

	/*
	 * Search AD for $qKey = $q and return all user records that are not disabled and dont belong to the exclude groups.
	 * */
	function getUsers($qKey, $q, $justthese) {
		$filter = "(&(objectCategory=person)(objectClass=user)($qKey=$q))";
		//echo $filter;
		$justthese = (isset($justthese)) ? $justthese : $this->justthese;
		$justthese[] = 'memberof'; //always get memberof to exclude service accounts
		$justthese[] = 'useraccountcontrol'; //always get useraccountcontrol to exclude disabled accounts
		/*$justthese[] = 'objectCategory';
		$justthese[] = 'objectClass';*/

		$result = ldap_search($this->ldapconn, $this->dn, $filter, $justthese)
						or die("ERROR: Failed to search the AD Tree|\n");
		$entries = ldap_get_entries($this->ldapconn, $result);
		//print_r($entries);

		$filtered = array();

		//Exclude disabled accounts and members of excludegroups
		unset($entries['count']);
		for ($i = 0; $i < count($entries); $i++){
			if (isset($entries[$i]['memberof'])){
				$exclude = count(array_intersect($this->excludegroups, $entries[$i]['memberof']));
				$accountDisabled = (((int)$entries[$i]['useraccountcontrol'][0] & 2) != 2);
				if(($exclude == 0) and ($accountDisabled)){
					$filtered[] = $entries[$i];
				}
			}
		}
		//print_r($filtered);

		if (count($filtered) == 0){
			return array();
		}else{
			//sort by first name
			usort($filtered, "cmp");
			return $filtered;
		}
	}

	/*
	 * Return members from all groups that match group_name from the iDepartments container
	 * */
	function getMembers($group_name){
		$members = array();

		$departmentsArr = $this->getWhoDepartments($group_name);
		foreach($departmentsArr as $department => $sub){
			$members = array_merge($members, $this->getMembers_dn("CN=_$department,OU=Intranet Security Groups,DC=eileenfisher,DC=net"));
			if (is_array($sub)){
				foreach($sub as $sub_name){
					$members = array_merge($members, $this->getMembers_dn("CN=_".str_replace(",", "\,", $sub_name).",OU=Intranet Security Groups,DC=eileenfisher,DC=net"));
				}
			}
		}

		$results = array();
		for($i = 0; $i < count($members); $i++){
			$entry = $this->ad_search->getUserRecord_dn($members[$i]);
			if ($entry !== FALSE){
				$results[] = $entry;
			}

		}

		return $results;
	}

	/*
	 * Simply return the members of a group based on DN
	 * */
	function getMembers_dn($group_dn) {
		$result = ldap_search($this->ldapconn, $group_dn, "(objectClass=group)", array("member"))
						or die("ERROR: Failed to search the AD Tree|\n");
		$entries = ldap_get_entries($this->ldapconn, $result);

		if ($entries["count"] != 0){
			if (isset($entries[0]['member'])){
				unset($entries[0]['member']['count']);
				return $entries[0]['member'];
			}
		}

		return array();
	}

	/*
	 * Get an alphabetical list of all departments
	 * Departments are groups in AD in the Intranet Security Groups OU
	 * */
	function getWhoDepartments($q){
		$container = 'OU=Intranet Security Groups,DC=eileenfisher,DC=net';
		$q = (isset($q)) ? "_$q*" : '*';
		$result = ldap_search($this->ldapconn, $container, "(&(objectClass=group)(cn=$q))", array('member', 'cn'))
						or die("ERROR: Failed to search the AD Tree|\n");
		$entries = ldap_get_entries($this->ldapconn, $result);
		$departments = array();
		$twice = array();

		unset($entries['count']);
		foreach($entries as $department){
			if (substr($department['cn'][0], 0, 1) != '_') {
				continue;
			} //if it doesnt start with a _ I dont care
			$name = substr($department['cn'][0], 1);

			if (!in_array($name, $twice)){
				$departments[$name] = array();

				if (isset($department['member'])){
					unset($department['member']['count']);
					foreach($department['member'] as $member){
						$result = ldap_search($this->ldapconn, $member, "(objectClass=group)", array('member', 'cn'))
								or die("ERROR: Failed to search the AD Tree|\n");
						$members = ldap_get_entries($this->ldapconn, $result);
						if ($members['count'] != 0){ //is a person not a group
							if (substr($members[0]['cn'][0], 0, 1) != '_') {
								continue;
							} //if it doesnt start with a _ I dont care
							$member = substr($members[0]['cn'][0], 1);
							$departments[$name][] = $member;
							//add it to this group to prevent doubles and unset it its in the top level
							$twice[] = $member;
							if (isset($departments[$member])) {
								unset($departments[$member]);
							}
						}
					}

					sort($departments[$name]);
				}
			}
		}

		ksort($departments);

		return $departments;
	}

	function whosearch($qkey, $q, $limit){
		$entries = array();

		if ($qkey == "memberof"){//memberof off needs to be exact all other searches work with the asterisk
			$entries = $this->getUsers($qkey, str_replace("*", "\*", $q), null);
		}else{
			$entries = $this->getUsers($qkey, (substr($q, -1) != '*') ? $q.'*' : $q, null);
		}

		if (count($entries) != 0){
			//limit the results
			if ($limit) {
				array_splice($entries, $limit);
			}
			//print_r($entries);

			//load pictures
			for ($i = 0; $i < count($entries); $i++){
				$user_name = strtolower($entries[$i]['samaccountname'][0]);
				$entries[$i]['user_picture'] = site_url("images/profile/$user_name");
				//get the pretty departments
				$entries[$i]['i_department'] = $this->getUserDepartment($user_name);
			}
		}

		return $entries;
	}
}
?>
