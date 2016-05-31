<?php
/**
 * @author Carlos Ravelo
 * @date Oct 12, 2010
 * @time 2:58:24 PM
 */

/**
 * To sort the results by display_name
 * @param array $a
 * @param array $b
 * @return int
 */
function peopleSort($a, $b){
	$nameA = isset($a['display_name']) ? $a['display_name'] : "";
	$nameB = isset($b['display_name']) ? $b['display_name'] : "";
	if ($nameA == $nameB) {
		return 0;
	}
	return ($nameA < $nameB) ? -1 : 1;
}

require_once dirname(__FILE__).'/article.php';

/**
 * The who controller is the interface to Who's Who the employee directory
 * @author Carlos Ravelo
 * @package Controllers
 * @property CI_Output $output
 * @property CI_Loader $load
 * @property Groups $groups
 * @property Users $users
 */
class Who extends MY_Controller {
	/**
	 * load a user's profile page based on username
	 * @param string $user_name
	 * @return void
	 */
	function profile_username($user_name){
		$user_name = strtolower($user_name);
		//load AD model and search for the username, if it doesn't exist show 404
		$this->load->model('users');
		$user = $this->users->get(null, $user_name);
		if ($user !== FALSE){
			$this->profiles($user[0]);
		}else{
			show_404();
		}
	}

	/**
	 * load a user's profile page based on user_id
	 * @param int $user_id
	 * @return void
	 */
	function profile_id($user_id = null){
		if (($user_id == null) or ($user_id == 'null')) { $user_id = $this->session->userdata('user_id'); }
		//load users model and search for the user, if it doesn't exist show 404
		$this->load->model('users');
		$user = $this->users->get($user_id);
		if ($user !== FALSE){
			$this->profiles($user[0]);
		}else{
			show_404();
		}
	}

	/**
	 * Load other information about a user and display the profile view
	 * @param array $user the user record
	 * @return void
	 */
	function profiles($user){
		$username = strtolower($user['username']);
		$user_id = $user['user_id'];

		//$page_data will be passed to the view
		$page_data = array();
		$page_data['its_me'] = false;
		$page_data['category'] = "profiles";//template to load
		$page_data['page_type'] = "profile";//template to load
		$page_data['template_name'] = "sys_profile";//template to load
		if ($this->uri->rsegment(4) == 'edit'){
			$page_data['edit'] = true;
		}
		$page_data['page_id'] = "profile/$user_id"; //page id is the user_id
		$page_data['title'] = 'Who\'s Who - '.$user['display_name']; //title set to their display name

		//Setup breadcrumbs
		$this->session->set_userdata('breadcrumbs', json_encode(array(
			"url" => anchor($this->uri->uri_string(), $page_data['title']),
			"section_id" => 4
		)));


		//print_r($user);
		$this->load->model('groups');
		$dep = $this->groups->getUserDepartments($user_id);
		if ($dep){
			$page_data['user'] = array_merge($user, $dep);
		}else{
			$page_data['user'] = $user;
		}

		$page_data['user_picture'] = $this->users->getUserPicture($username);

		//if the user has logged in before:
		if (isset($user['username']) and !empty($user['username'])){
			//load group affiliations (distro lists)
			$this->load->model('ad_search');
			$page_data['distros'] = $this->ad_search->getUserDistros($username);

			//load My Pages
			$this->load->model('pages');
			$page_data['mypages'] = $this->pages->my_pages($user_id);

			//check if the visitor is the owner of the profile.
			//echo $user_name;
			//echo $this->session->userdata('username');
			if ($username == strtolower($this->session->userdata('username'))){
				//load my private pages
				$page_data['my_private'] = $this->pages->my_private($user_id);
				//load pages user has write perms
				$page_data['edit_pages'] = $this->pages->edit_pages($user_id);
				//load the drafts
				$page_data['drafts'] = $this->pages->drafts($user_id);
				//load the trash can
				$page_data['trashcan'] = $this->pages->trashcan($user_id);
				//load the orphaned events
				$this->load->model('events');
				$page_data['events'] = $this->events->orphaned($user_id);
				//load my subscriptions
				$this->load->model('subscriptions');
				$page_data['subscriptions'] = $this->subscriptions->get($user_id);
				//set permissions to edit
				$page_data['canWrite'] = true;
				$page_data['its_me'] = true;
			}

			if (strtolower($this->session->userdata('role')) == 'admin'){
				//set permissions to edit
				$page_data['canWrite'] = true;
			}
		}

		$this->load->view('layouts/default', $page_data);
	}

	/**
	 * This function checks if job overview is empty to give users a notification on login if it is
	 */
	function checkjoboverview(){
		$this->load->model('users');
		$user = $this->users->get($this->session->userdata('user_id'));
		//print_r($user);
		if ($user and (($user[0]['joboverview'] == null) or empty($user[0]['joboverview']))){
			$this->result->isError = true;
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	/**
	 * returns a list of groups from the groups table formatted for jquery-ui autocomplete
	 * Used by page properties to auto-complete permissions
	 * @return void
	 */
	function groups(){
		$query = $this->uri->uri_to_assoc(3);
		//print_r($query);
		$this->load->model('groups');
		$groups = $this->groups->get($query['q']);
		//print_r($groups);
		$result = array();
		for ($i = 0; $i < count($groups); $i++)
		{
			$result[] = array(
				'id' => $groups[$i]['group_id'],
				'value' => $groups[$i]['group_name']
			);
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
	}

	/**
	 * Searches users table
	 * The results are formatted for use in jquery-ui autocomplete
	 * Used by the search filed on Who's Who's
	 * @return void
	 */
	function search(){
		$query = $this->uri->uri_to_assoc(3);
		$q = base64_decode($query['q']);
		$limit = isset($query['limit']) ? $query['limit'] : null;

		//print_r($query);
		$this->load->model('users');
		$results = $this->users->search($q, $limit);
		if (count($results) == 0){ //if I empty try with wildcard
			$results = $this->users->search("%$q", $limit);
		}
//		print_r($results);
		$users = array();
		//$unique = array();
		for ($i = 0; $i < count($results); $i++){
			$users[] = array(
				'id' => $results[$i]['user_id'],
				'email' => (isset($results[$i]['email'])) ? $results[$i]['email'] : "",
				'value' => $results[$i]['display_name']
			);
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($users));
	}

	/**
	 * Performs a search on Active Directory for users belonging to a distro list by name.
	 */
	function memberof(){
		$query = $this->uri->uri_to_assoc(3);
		$query['qkey'] = "Members of";
		$q = $query['q'] = base64_decode(str_replace('_', ' ', $query['q']));
		$tmp = ldap_explode_dn(str_replace('\\\\,', '\2C', $q), 1);
		$query['q'] = str_replace('\2C', ',', $tmp[0]);

		//load AD model and perform the search
		$this->load->model('ad_search');
		$results = $this->ad_search->whosearch("memberof", $q, false);
		$users = array();
		for($i = 0; $i < count($results); $i++){
			$users[$i] = array(
				"username" => $results[$i]['samaccountname'][0],
				"display_name" => $results[$i]['displayname'][0],
				"first_name" => $results[$i]['givenname'][0],
				"email" => isset($results[$i]['mail']) ? $results[$i]['mail'][0] : null,
				"phonenumber" => isset($results[$i]['telephonenumber']) ? $results[$i]['telephonenumber'][0] : null,
				"fax" => isset($results[$i]['facsimiletelephonenumber']) ? $results[$i]['facsimiletelephonenumber'][0] : null,
				"title" => isset($results[$i]['title']) ? $results[$i]['title'][0] : null,
				"user_picture" => $results[$i]['user_picture']
			);
			if (isset($adRecord['mail'])) {
				$users[$i]['email'] = $results[$i]['mail'][0];
			}
			if (isset($adRecord['telephonenumber'])) {
				$users[$i]['phonenumber'] = $results[$i]['telephonenumber'][0];
			}
			if (isset($adRecord['facsimiletelephonenumber'])) {
				$users[$i]['fax'] = $results[$i]['facsimiletelephonenumber'][0];
			}

			//$dep = explode(" > ", $results[$i]['i_department'][0]);
			//$users[$i]['department'] = $dep[0];
			//$users[$i]['sub_department'] = isset($dep[1]) ? $dep[1] : null;
		}

		$export = isset($query['action']) ? $query['action'] : 'display';

		switch ($export){
			case 'export': {
				$this->export($users);
				break;
			}
			case 'phonenumbers': {
				$this->phoneexport($users);
				break;
			}
			case 'display':
			default: {
				$this->loadview($users, $query);
				break;
			}
		}
	}

	/**
	 * Search the users table with an optional limit
	 * Depending on the 'action' it will load the search results view, export a full file or phone numbers only.
	 * @return void
	 */
	function people(){
		$query = $this->uri->uri_to_assoc(3);
		$q = $query['q'] = str_replace("*", "%", base64_decode($query['q']));
		$limit = isset($query['limit']) ? $query['limit'] : null;
		$export = isset($query['action']) ? $query['action'] : 'display';
		//print_r($query);

		//load model and perform the search
		$this->load->model('users');
		$this->load->model('groups');
		$results = $this->users->search($q, $limit);
		if (count($results) == 0){ //if I empty try with wildcard
			$results = $this->users->search("%$q", $limit);
		}

//		$members = $this->groups->members(null, $q);//TODO: combine these two queries. See how long they take. consider cache.
//		if ($members !== false){
//			$results = array_merge($results, $members);
//		}

		$users = $results;
//		for($i = 0; $i < count($results); $i++){
//			$dep = $this->groups->getUserDepartments($results[$i]['user_id']);
//			if ($dep){
//				$users[] = array_merge($results[$i], $dep);
//			}else{
//				$users[] = $results[$i];
//			}
//		}
		//print_r($results);

		//export or load view?
		switch ($export){
			case 'export': {
				$this->export($users);
				break;
			}
			case 'phonenumbers': {
				$this->phoneexport($users);
				break;
			}
			case 'display':
			default: {
				$this->loadview($users, $query);
				break;
			}
		}
	}

	/**
	 * Search the users table for any key=value combination and with an optional limit
	 * This function does pretty much the same as people except that it expects clear text instead of base64encoded
	 * @return void
	 */
	function urlsearch(){
		$query = $this->uri->uri_to_assoc(3);
		$q = $query['q'] = str_replace("*", "%", $query['q']);
		$limit = isset($query['limit']) ? $query['limit'] : null;
		//print_r($query);

		//load model and perform the search
		$this->load->model('users');
		$this->load->model('groups');
		$results = $this->users->search($q, $limit);
		if (count($results) == 0){ //if I empty try with wildcard
			$results = $this->users->search("%$q", $limit);
		}

		$users = array();
		for($i = 0; $i < count($results); $i++){
			$dep = $this->groups->getUserDepartments($results[$i]['user_id']);
			if ($dep){
				$users[] = array_merge($results[$i], $dep);
			}else{
				$users[] = $results[$i];
			}
		}
		//print_r($results);

		$this->loadview($users, $query);
	}

	/**
	 * converts the ldap results for a key into a simple string, checking with isset first.
	 * @param array $arr
	 * @param string $key
	 * @return string
	 */
	private function arraytostr($arr, $key){
		$key = strtolower($key);
		if (isset($arr[$key]))
			return $arr[$key];
		else
			return "";
	}

	/**
	 * Exports a file with all of the details passed on the $users array
	 * @param array $users
	 * @return void
	 */
	private function export($users){
		//generate excel file with the results
		//print_r($users);
		$file = "Preferred Name, Phonenumber, Fax, Email, Title, Department, Sub Department, Desk Location\n";
		for($i = 0; $i < count($users); $i++){
			$file .= "\"".$this->arraytostr($users[$i], 'display_name').'",';
			$file .= "\"".$this->arraytostr($users[$i], 'phonenumber').'",';
			$file .= "\"".$this->arraytostr($users[$i], 'fax').'",';
			$file .= "\"".$this->arraytostr($users[$i], 'email').'",';
			$file .= "\"".$this->arraytostr($users[$i], 'title').'",';
			$file .= "\"".$this->arraytostr($users[$i], 'sub_department').'",';
			$file .= "\"".$this->arraytostr($users[$i], 'department').'",';
			$file .= "\"".$this->arraytostr($users[$i], 'location')."\"\n";
		}

		$this->output
            ->set_content_type('application/octet-stream')
			->set_header("Content-Disposition: attachment; filename=\"WhosWho_Export.csv\"")
            ->set_output($file);
	}

	/**
	 * Exports a file with only phone/fax numbers from the $users array
	 * @param array $users
	 * @return void
	 */
	private function phoneexport($users){
		//generate excel file with the results
		//print_r($users);
		$file = "Name, Phonenumber, Fax, Name, Phonenumber, Fax\n";
		$lines = array();
		$length = count($users);
		$file_length = ceil($length / 2);//NUMBER OF COLUMNS
		for($i = 0; $i < $length; $i++){
			$line = '"'.$this->arraytostr($users[$i], 'display_name').'",';
			$line .= '"'.$this->arraytostr($users[$i], 'phonenumber').'",';
			$line .= '"'.$this->arraytostr($users[$i], 'fax').'",';
			if (isset($lines[$i % $file_length])){
				$lines[$i % $file_length] .= $line;
			}else{
				$lines[$i % $file_length] = $line;
			}
		}

		array_walk($lines, function(&$line){
			$line = preg_replace('/,$/', "\n", $line);
		});

		$file .= implode('', $lines);

		$this->output
            ->set_content_type('application/octet-stream')
			->set_header("Content-Disposition: attachment; filename=\"WhosWho_Export.csv\"")
            ->set_output($file);
	}

	/**
	 * Load who's who search results view
	 * @param array $entries array with user records
	 * @param array $query the original user query
	 * @return void
	 */
	private function loadview($entries, $query){
		$page_data = array();
		$page_data['template_name'] = "sys_who_search";
		$page_data['revision']['results'] = $entries;
		$page_data['revision']['query'] = $query;
		$page_data['page_id'] = 4;
		$page_data['title'] = 'Who\'s Who - Search Results';

		$this->load->view('layouts/default', $page_data);
	}

	/**
	 * Autocomplete function for department type groups
	 * @return void
	 */
	function getdepartments(){
		$query = $this->uri->uri_to_assoc(3);
		//print_r($query);
		$q = base64_decode($query['q']);

		$this->load->model('groups');
		$results = $this->groups->get($q, 'department');
		if (count($results) === 0){
			$results = $this->groups->get("%$q", 'department');
		}
		//print_r($results);
		$result = array();
		for ($i = 0; $i < count($results); $i++)
		{
			if (isset($results[$i]['group_name'])){ //if is not already in the array
				$result[] = $results[$i]['group_name'];
			}
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
	}

	/**
	 * Who's who search by department name
	 * @return void
	 */
	function department_name(){
		$query = $this->uri->uri_to_assoc(3);
		$query['qkey'] = 'department';
		$query['q'] = utf8_encode(base64_decode($query['q']));
		$export = isset($query['action']) ? $query['action'] : 'display';

		$this->load->model('groups');
		$members = $this->groups->members(null, $query['q']);

		//export or load view?
		switch ($export){
			case 'export': {
				$this->export($members);
				break;
			}
			case 'phonenumbers': {
				$this->phoneexport($members);
				break;
			}
			case 'display':
			default: {
				$this->loadview($members, $query);
				break;
			}
		}
	}

	/**
	 * Who's Who search by department id
	 * @param int $gid the group id
	 * @return void
	 */
	function department($gid){
		$query = $this->uri->uri_to_assoc(2);
		$query['qkey'] = 'department';
		$export = isset($query['action']) ? $query['action'] : 'display';

		$this->load->model('groups');
		$members = $this->groups->members($gid);
		$query['q'] = $this->groups->get($gid);
		if (count($query['q']) > 0){
			$query['q'] = $query['q'][0]['group_name'];

			//export or load view?
			switch ($export){
				case 'export': {
					$this->export($members);
					break;
				}
				case 'phonenumbers': {
					$this->phoneexport($members);
					break;
				}
				case 'display':
				default: {
					$this->loadview($members, $query);
					break;
				}
			}
		}else{
			show_404();
		}
	}

	/**
	 * AJAX handler for user status update
	 * @return void
	 */
	function userupdatestatus(){
		$user_name = $this->session->userdata('username');
		$user_status = $this->input->post('t');

		$this->load->model('users');
		if (!$this->users->update(null, $user_name, array('status' => $user_status)))
		{
			$this->result->isError = true;
			$this->result->errorStr = "There was an error updating your status, try again later.";
		}

		$this->result->data = "Status updated successfully.";
		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));

	}

	/**
	 * AJAX handler that updates a user's record
	 * @return void
	 */
	function saveprofile(){
		$uid = $this->input->post('uid');
		$element_id = $this->input->post('element');
		$new_content = base64_decode(str_replace(' ', '+', $this->input->post('content')));

		$this->load->model('users');
		if (!$this->users->update($uid, null, array($element_id => $new_content))){
			$this->result->isError = true;
			$this->result->errorStr = "There was an error updating your profile, try again later.";
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	function sendcontact(){
		//setup mail
		$to = $this->session->userdata('email');
		$from = "\"FishNET\" <no-reply@eileenfisher.com>";
		$subject = "Requested Who's Who Contact";
		$host = "efexc01.eileenfisher.net";//this is using a local smtp server (IIS or other)
		$port = "25";
		ini_set('SMTP', $host);
		ini_set('smtp_port', $port);

		//generate boundary string
		$semi_rand = md5(time());
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

		//headers
		$headers  = "Content-Type:  text/plain\n";
		$headers .= "From: $from\n";
		$headers .= "To: $to\n";
		$headers .= "Subject: $subject\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: multipart/mixed; boundary=\"{$mime_boundary}\"\n";

		// Add a multipart boundary above the plain message
        $body  = "This is a multi-part message in MIME format.\n\n";
        $body .= "--{$mime_boundary}\n";
		$body .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
		$body .= "Content-Transfer-Encoding: 7bit\n\n";
		$body .= "Hello ".$this->session->userdata("first_name").",\n\nThis is the contact you requested from fishNET. ";
		$body .= "Double click on the attachment and add it to your contacts.";
		$body .= "\n\nThank you\n\n";

		//get user's info to fill the vCard:
		$this->load->model('users');
		$user_id = $this->input->post("user_id");
		$userRecord = $this->users->get($user_id, null);
		$userRecord = $userRecord[0];

		// Add file attachment to the message
		$body .= "--{$mime_boundary}\n";
		$body .= "Content-Type: text/plain;\n";
		$body .= " name=\"{$userRecord['display_name']}.vcf\"\n";
		$body .= "Content-Disposition: attachment;\n";
		$body .= " filename=\"{$userRecord['display_name']}.vcf\"\n";
		$body .= "Content-Transfer-Encoding: 7bit\n\n";
		$body .= "BEGIN:VCARD\n";
		$body .= "VERSION:2.1\n";
		$body .= "N:{$userRecord['last_name']};{$userRecord['first_name']}\n";
		$body .= "FN:{$userRecord['display_name']}\n";
		$a = isset($userRecord['sub_department']) ? $userRecord['sub_department'] : "";
		$body .= "ORG:EILEEN FISHER Inc.;$a\n";
		$a = isset($userRecord['title']) ? $userRecord['title'] : "";
		$body .= "TITLE:$a\n";
		$a = isset($userRecord['phonenumber']) ? $userRecord['phonenumber'] : "";
		$body .= "TEL;WORK;VOICE:$a\n";
		$a = isset($userRecord['fax']) ? $userRecord['fax'] : "";
		$body .= "TEL;WORK;FAX:$a\n";

		$sa = isset($userRecord['streetaddress']) ? $userRecord['streetaddress'] : "";
		$l = isset($userRecord['l']) ? $userRecord['l'] : "";
		$st = isset($userRecord['st']) ? $userRecord['st'] : "";
		$zip = isset($userRecord['postalcode']) ? $userRecord['postalcode'] : "";
		$c = isset($userRecord['c']) ? $userRecord['c'] : "";
		$body .= "ADR;WORK:;;$sa;$l;$st;$zip;$c\n";
		$body .= "LABEL;WORK;ENCODING=QUOTED-PRINTABLE:$sa=0D=0A$l, $st $zip=0D=0A$c\n";
		$a = isset($userRecord['email']) ? $userRecord['email'] : "";
		$body .= "EMAIL;PREF;INTERNET:$a\n";

		//get the user's picture
		$photoPath = APPPATH."/../uploads/profiles/".$userRecord['username']."/main.jpg";
		if (file_exists($photoPath)){
			$body .= "PHOTO;TYPE=JPEG;ENCODING=BASE64:";
			$i = 0;
			$photoFile = base64_encode(file_get_contents($photoPath));

			while($i < strlen($photoFile)){
				if(($i % 75) == 0){
					$body .= "\n ".$photoFile[$i];
				}else{
					$body .= $photoFile[$i];
				}
				$i++;
			}
		}

		$body .= "\n\nREV:".date("Ymd\THis\ZT\n");
		$body .= "END:VCARD\n";
		$body .= "--{$mime_boundary}--\n";

		//send it
		if (mail($to, $subject, $body, $headers) === FALSE){
			$this->result->isError = true;
			$this->result->errorStr = "There was an error sending the email with your contact, please try again later. If the error persists call the Helpdesk at x4024.";
		}

		$this->result->data = "Email sent successfully";

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	/**
	 * Updates the list of favorite links, used from user's profile in edit mode.
	 * @return void
	 */
	function updatemylinks(){
		$data = json_decode(base64_decode($this->input->post("data")));

		for($i = 0; $i < count($data); $i++){
			$data[$i]->user_id = $this->session->userdata('user_id');
			$data[$i]->type = 0;
		}

		$this->load->model("favorites");
		if (!$this->favorites->update($data)){
			$this->result->isError = true;
			$this->result->errorStr = "There was an error updating your favorites, please try again later. If the problem persists call the Helpdesk at x4024.";
		}

		$this->result->data = "Your changes were saved successfully!";

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	/**
	 * Adds a page to a user's favorites list
	 * @return void
	 */
	function addfavorite(){
		$user_id = $this->session->userdata('user_id');
		$fav_url = $this->input->post('fav_url');
		$fav_title = $this->input->post('fav_title');

		$this->load->model('favorites');
		if (!$this->favorites->newFavorite($user_id, $fav_title, $fav_url, 0)){
			$this->result->isError = true;
			$this->result->errorStr = "There was an error saving your favorite.";
		}

		$this->result->data = "Favorite added successfully";
		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}
}
