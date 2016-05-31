<?php
/**
 * Created by: cravelo
 * Date: 9/6/11
 * Time: 1:22 PM
 */

set_time_limit(600); //10 minutes

$f = fopen(dirname(__FILE__)."/synchronize_ad_groups-".date("Y").".log", "a+");

$groups = array(
	array(
		'CN' => "CN=\*ALL EF EMPLOYEES,OU=Irvington Groups,OU=Emails Groups,DC=eileenfisher,DC=net",
		'group_id' => '3'
	),
	array(
		'CN' => "CN=SeCe Apparel Employees,OU=User Groups,DC=eileenfisher,DC=net",
		'group_id' => '5'
	),
	array(
		'CN' => "CN=Retail Associates Group,OU=User Groups,DC=eileenfisher,DC=net",
		'group_id' => '4'
	)
);

fwrite($f, date("Y-m-d H:i:s")." -------------------------------------------------------------------------\n");
fwrite($f, date("Y-m-d H:i:s")." Synchronizing AD groups\n");

$ldapconn = ldap_connect("efadc01.eileenfisher.net")
	or $ldapconn = ldap_connect("efadc02.eileenfisher.net");

if ($ldapconn){
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);//THIS IS VER IMPORTANT
	ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);//THIS IS VER IMPORTANT
	ldap_set_option($ldapconn, LDAP_OPT_SIZELIMIT, 1000);//this is just for speed. I never have more than 1000 results, I could even make this number smaller

	if (@ldap_bind($ldapconn, "svcintranet@eileenfisher.net", "JApvxThGrzRBsVp9") === false){
		fwrite($f, date("Y-m-d H:i:s")." Couldn't connect to the domain controllers.\n");
	}else{
		$dn = "DC=eileenfisher, DC=net";
		$justthese = array('memberof', 'useraccountcontrol', 'samaccountname');

		$db = new PDO("mysql:dbname=intranet;host=localhost", 'intranet', 'JApvxThGrzRBsVp9');

		for ($i = 0; $i < count($groups); $i++){
			$filtered = array();
			$filter = "(&(objectCategory=person)(objectClass=user)(memberof=".$groups[$i]['CN']."))";
			//echo $filter;

			$result = ldap_search($ldapconn, $dn, $filter, $justthese)
								or die("ERROR: Failed to search the AD Tree|\n");
			$entries = ldap_get_entries($ldapconn, $result);
			//print_r($entries);

			//Exclude disabled accounts and members of excludegroups
			unset($entries['count']);
			for ($j = 0; $j < count($entries); $j++){
				$accountDisabled = (((int)$entries[$j]['useraccountcontrol'][0] & 2) != 2);
				if($accountDisabled){
					$filtered[] = "username='".strtolower($entries[$j]['samaccountname'][0])."'";
				}
			}

			if (count($filtered) > 0){
				//produce the insert query and execute it
				$insertQuery = "INSERT IGNORE INTO fn_groups_users(group_id, user_id)
					SELECT ".$groups[$i]['group_id'].", user_id FROM fn_users WHERE ".implode(' OR ', $filtered);
				//echo $insertQuery;
				$q = $db->exec($insertQuery);
				//var_dump($q);
				fwrite($f, date("Y-m-d H:i:s")." Sync complete for: ".$groups[$i]['group_id']." - members added to FISHNET: $q\n");
			}
		}

		unset($db);
	}
}else{
	fwrite($f, date("Y-m-d H:i:s")." -------------------------------------------------------------------------\n");
	fwrite($f, date("Y-m-d H:i:s")." Couldn't connect to the domain controllers.\n");
}

fwrite($f, date("Y-m-d H:i:s")." Sync complete\n");

fclose($f);
