<?php
/**
 * @author Carlos Ravelo
 */

/**
 * Login controller - implements different ways to login, for now NTLM only and creates the session
 * @author cravelo
 * @package Controllers
 * @property Ad_search $ad_search
 * @property Groups $groups
 * @property Users $users
 * @property CI_Loader $load
 * @property CI_Form_validation $form_validation
 * @property CI_Output $output
 */
class Login extends CI_Controller {
	public function index(){
		$username = $this->input->post('username');
		if ($username !== false){
			$this->checkpassword();
		}else{
			$user_name = $this->session->userdata('username');
			if ($user_name === false){
				$this->ntlm();
			}else{
				$this->redirect();
			}
		}
	}

	public function checkpassword() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');

		if ( $this->form_validation->run() !== false ) {
			$this->load->model('ad_search');
			try {
				$this
					->ad_search
					->verify_user(
						$this->input->post('username'),
						$this->input->post('password')
					);
			} catch (Exception $e){
				switch($e->getCode()){
					case E_AD_PWD_FORCE_CHANGE:
						$this->loadtemplate('changepassword', $e->getMessage(), $this->input->post('username'));
						return;
					break;
					case E_AD_PWD_WARN:
						$this->session->set_userdata('message', $e->getMessage());
					break;
					default:
						$this->loadtemplate('login', $e->getMessage());
						return;
					break;
				}
			}

			$this->_login($this->input->post('username'));
		}

		$this->loadtemplate('login', 'Login failed: Incorrect username and/or password. Please
				    <a href="Https://password.eileenfisher.com">CLICK HERE</a> to reset your password. If you still have trouble '.
				'contact the Helpdesk at 914-721-4024.');
	}

	/**
	 * Auth using NTLM
	 */
	private function ntlm(){
		//print_r($_SERVER);
		//get user_name from apache and set it on the session data
		$user_name = (isset($_SERVER['PHP_AUTH_USER'])) ? strtolower($_SERVER['PHP_AUTH_USER']) : null;
		$user_name = (isset($_SERVER['REDIRECT_REMOTE_USER']) and !isset($user_name)) ? strtolower($_SERVER['PHP_AUTH_USER']) : $user_name;
		$user_name = (isset($_SERVER['REMOTE_USER']) and !isset($user_name)) ? strtolower($_SERVER['REMOTE_USER']) : $user_name;
		$user_name = (isset($_SERVER['REMOTE_USERNAME']) and !isset($user_name)) ? strtolower($_SERVER['REMOTE_USERNAME']) : $user_name;
		//$user_name = 'svcscan';

		if (isset($user_name)){
			$user_name = str_replace("@eileenfisher.net", "", $user_name);
			$user_name = str_replace("eileenfisher\\", "", $user_name);

			$this->_login($user_name);
		}else{
			$this->logout();
		}
	}

	/**
	 * Retrieve the user record form auth source
	 * @param $user_name
	 * @return array the user record
	 */
	private function getuserrecord($user_name){
		//get the user record from Active Directory and put it into the session data
		$this->load->model('ad_search');
		$justthese = $this->ad_search->justthese;
		$this->ad_search->justthese = array(
			"samaccountname", "displayname", "givenname", "mail", "facsimiletelephonenumber", "useraccountcontrol", "pwdlastset", 'memberof'
		);
		$adRecord = $this->ad_search->getUser_login($user_name);
		$this->ad_search->justthese = $justthese;
		//var_dump($adRecord); die();

		$userRecord = array(
			"username" => strtolower($adRecord['samaccountname'][0]),
			"first_name" => strtolower($adRecord['givenname'][0]),
			"display_name" => strtolower($adRecord['displayname'][0]),
		);
		if (isset($adRecord['mail'])) {
			$userRecord['email'] = strtolower($adRecord['mail'][0]);
		}
		if (isset($adRecord['facsimiletelephonenumber']) and ($adRecord['facsimiletelephonenumber'][0] != '')) {
			$userRecord['fax'] = $adRecord['facsimiletelephonenumber'][0];
		}

		//user->login will check if the user is in the DB and update its info and if its not it will add it.
		$this->load->model('users');
		$user = $this->users->login($userRecord, $adRecord['memberof']);

		return $user;
	}

	/**
	 * This function actually does the login, creates the session, etc.
	 *
	 * @param $user_name
	 */
	private function _login($user_name){
		if (isset($user_name)){
			//echo $user_name;
			$userRecord = $this->getuserrecord($user_name);

			//print_r($userRecord);
			$this->session->set_userdata('username', $userRecord['username']);
			$this->session->set_userdata('display_name', $userRecord['display_name']);
			$this->session->set_userdata('first_name', $userRecord['first_name']);
			$this->session->set_userdata('email', $userRecord['email']);
			$this->session->set_userdata('user_id', $userRecord['user_id']);
			$this->session->set_userdata('role', $userRecord['role']);
			$this->session->set_userdata('person_id', $userRecord['person_id']);
			if ($userRecord['first_time']){
				$this->session->set_userdata('message', 'info@Welcome to FISHNET, our Intranet site in EILEEN FISHER!
				 To	read more about the site, please '.anchor('about', 'click here'));
			}

			$this->load->model('groups');
			$group_config = $this->config->item('groups_config');
			$groups = $group_config['group_names'];
			foreach($groups as $group_name){
				if ($this->groups->is_memberOf($group_name)){
					$this->session->set_userdata('logo', $group_config[$group_name]['logo']);
					$this->session->set_userdata('who_link', $group_config[$group_name]['who_link']);
					$this->session->set_userdata('show_apps', $group_config[$group_name]['show_apps']);
					break;
				}
			}

			$this->redirect();
		}else{
			$this->logout();
		}
	}

	function changepassword(){
		$username = $this->input->post('username');
		if ($username !== false){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('username',		'Username', 'trim|required');
			$this->form_validation->set_rules('old_password',	'Current Password', 'trim|required|min_length[6]');
			$this->form_validation->set_rules('password',		'New Password',
				'trim|required|matches[repassword]|min_length[6]');
			$this->form_validation->set_rules('repassword',		'New Password Confirmation',
				'trim|required|min_length[6]');

			if ( $this->form_validation->run() !== false ) {
				$old_password = $this->input->post('old_password');
				$new_password = $this->input->post('password');

				$this->load->model('ad_search');
				try{
					$this->ad_search->changePassword($username, $old_password, $new_password);
				}catch(Exception $e){
					$this->loadtemplate('changepassword', $e->getMessage(), $username);
					return;
				}

				$this->redirect();
			}else{
				$this->loadtemplate('changepassword', 'All form fields are required. If you have trouble changing your
					password, contact the Helpdesk at x4024.');
			}
		}else{
			$this->loadtemplate('changepassword');
		}
	}

	function logout($template_name = 'login'){
		$this->session->sess_destroy();
		$this->loadtemplate($template_name);
	}

	/**
	 * Load a template either the login form or change password form
	 *
	 * @param $template_name
	 * @param null $error_message
	 * @param null $username
	 */
	function loadtemplate($template_name = 'login', $error_message = null, $username = null){
		$this->load->helper('form');
		$page_data['template_name'] = "static/$template_name";
		$page_data['title'] = 'Login';
		$page_data['error'] = $error_message;
		$page_data['username'] = $username;

		$this->load->view('layouts/login', $page_data);
	}

	/**
	 * Redirect to the URL the user requested or the home page if empty
	 */
	private function redirect(){
		$redirect_url = $this->uri->uri_string();
		//$redirect_url = str_replace('/index.php', "/home", $_SERVER['REQUEST_URI']);
		$redirect_url = preg_replace('/(login\/index)+/', "", $redirect_url);
		$redirect_url = str_replace("login", "", $redirect_url);
		$redirect_url = str_replace("logout", "", $redirect_url);
		$redirect_url = str_replace("changepassword", "", $redirect_url);
		$redirect_url = preg_replace('/\/\/+/', "", $redirect_url);
		$redirect_url = (empty($redirect_url) or ($redirect_url == "/")) ? "/home" : $redirect_url;
		//echo $redirect_url;
		$this->output->set_header("Location: $redirect_url")->set_status_header(302);
	}
}
