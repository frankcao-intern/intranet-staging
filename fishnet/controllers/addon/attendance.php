<?php
/**
 * @author cravelo
 * @date   2/21/12
 * @time   2:04 PM
 */

/**
 * Attendance Controller
 * @package Controllers
 * @author  cravelo
 * @property CI_Loader $load
 * @property CI_Security $security
 * @property Attendance_model $attendance
 * @property Mail_queue $mail_queue
 * @property Ad_search $ad_search
 */
class Attendance extends MY_Controller {
	/**
	 * This loads the interface for the attendance report
	 * @param int $year
	 * @param int $user_id
	 * @param string $layout
	 */
	function index($year = null, $user_id = null, $layout = 'default'){
		$year = isset($year) ? $year : date('Y');
		$user_id = isset($user_id) ? $user_id : $this->session->userdata('user_id');
		$its_me = ($user_id == $this->session->userdata('user_id'));

		$this->load->model('users');
		$user = $this->users->get($user_id);
		$user = $user[0];
		$its_my_boss = ($user['leader'] == $this->session->userdata('person_id'));

		if (isset($user['rate_type']) and !empty($user['rate_type']) and ($user['rate_type'] == 'AN')){
			if ($its_me or $its_my_boss or ($this->session->userdata('role') == 'payroll') or ($this->session->userdata('role') == 'admin')){
				$page_data = array(
					'template_name' => 'addon/attendance',
					'title' => 'Attendance Report',
					'year' => $year,
					'its_me' => $its_me,
					'its_my_boss' => $its_my_boss
				);

				$this->load->model('addon/attendance_model', 'attendance');
				$data = $this->attendance->getYear($year, $user_id);
				$page_data['day_types'] = $this->attendance->getDayTypes();
				$page_data['direct_reports'] = $this->users->getDirectReports($this->session->userdata('person_id'));
				$page_data[$layout] = true;

				$this->load->view("layouts/$layout", array_merge($page_data, $data));
			}else{
				show_error("You don't have permission to see the requested attendance report.", 200);
			}
		}else{
			show_error('Only exempt employees are required to use this application.', 200);
		}
	}

	function tab(){
		$this->index(date('Y'), $this->session->userdata('user_id'), 'tab');
	}

	/**
	 * @param array $data
	 */
	protected function _month_approve($data){
		$this->result->isError = true;
		$this->result->errorStr = "There was an error. Try again later. If the problem persists call the Helpdesk.";
		$this->result->data = "";

		if (isset($data['username']) and ($data['username'] !== false)){
			//check the username an password
			$this->load->model('ad_search');
			try {
				$this->ad_search->verify_user($data['username'], $data['password']);
			} catch (Exception $e){
				switch($e->getCode()){
					case E_AD_LOGIN_FAILED:
					case E_AD_PWD_FORCE_CHANGE:
					case E_AD_ACCT_LOCKED:
						$this->result->errorStr = $e->getMessage();
						return;
					break;
				}
			}

			$this->load->model('users');
			$requestor = $this->users->get($data['requester_id']);
			if ($requestor !== false){
				$requestor = $requestor[0];

				$this->load->model('addon/attendance_model', 'attendance');
				if ($this->attendance->changeMonthStatus($data['request_id'], 'confirmed') === true){

					$msg = "Your attendance report for the month of {$data['month']}/{$data['year']} is signed. ".
						"To view it visit your Who's Who profile on fishNET and click on the ".
						"\"Attendance\" tab.\r\n\r\n".$this->config->item('site_url').'my/profile';

					$emails = array();
					$emails[] = array(
						'subject' => "Your {$data['month']}/{$data['year']}'s Attendance Report was approved",
						'from' => $this->session->userdata('email'),
						'to' => "{$requestor['email']}, {$this->session->userdata('email')}",
						'message' => $msg
					);

					$this->load->model('mail_queue');
					if ($this->mail_queue->add($emails) !== false){
						$this->result->isError = false;
						$this->result->data = "The month is signed. You and {$requestor['display_name']} will ".
								"receive confirmation emails.";
					}
				}
			}
		}
	}

	/**
	 * @param array $data
	 */
	protected function _month_reply($data){
		$this->result->isError = true;
		$this->result->errorStr = "There was an error. Try again later. If the problem persists call the Helpdesk.";
		$this->result->data = "";

		$this->load->model('users');
		$requester = $this->users->get($data['requester_id']);
		if ($requester !== false){
			$requester = $requester[0];

			$this->load->model('addon/attendance_model', 'attendance');
			if ($this->attendance->changeMonthStatus($data['request_id'], 'planned') === true){

				$msg = htmlspecialchars($data['msg'])."\r\n\r\n".
					"To review your attendance report visit your Who's Who profile on fishNET and click on the ".
					"\"Attendance\" tab.\r\n\r\n".$this->config->item('site_url').'my/profile';

				$emails = array();
				$emails[] = array(
					'subject' => "RE: {$data['month']}/{$data['year']}'s Attendance Report",
					'from' => $this->session->userdata('email'),
					'to' => "{$requester['email']}, {$this->session->userdata('email')}",
					'message' => $msg
				);

				$this->load->model('mail_queue');
				if ($this->mail_queue->add($emails) !== false){
					$this->result->isError = false;
					$this->result->data = "Your message was sent.";
				}
			}
		}
	}

	/**
	 * Ajax responder to create a new item in the report
	 * @var CResult $result
	 */
	function save(){
		$result = new CResult();
		$data = $this->security->xss_clean(json_decode($_POST['data'], true));
		$user_id = $this->session->userdata('user_id');

		$this->load->model('addon/attendance_model', 'attendance');
		if (!$this->attendance->insert($data, $user_id)){
			$result->isError = true;
			$result->errorStr = "There was an error saving the information on the server. Try again later, ".
				"if the problem persists please contact the Helpdesk.";
		}else{
			$result->data = $data[0]['type'];
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	/**
	 * Ajax responder to delete an item from the report
	 * @var CResult $result
	 */
	function delete(){
		$result = new CResult();
		$data = json_decode($this->input->post('data', true), true);
		$user_id = $this->session->userdata('user_id');

		if (is_array($data)){
			$this->load->model('addon/attendance_model', 'attendance');
			$result->isError = !$this->attendance->delete($data, $user_id);
		}else{
			$result->isError = true;
		}

		$result->errorStr = "There was an error saving the information on the server. Try again later,
			if the problem persists please contact the Helpdesk.";

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	/**
	 * Ajax responder for submitting a month for approval
	 * @var CResult $result
	 */
	function sign_month(){
		$result = new CResult();
		$result->isError = true;

		$data = json_decode($this->input->post('data', true), true);
		$user_id = $this->input->post('user_id', true);
		$user_id = ($user_id === false) ? $this->session->userdata('user_id') : $user_id;

		if (is_array($data)){
			$this->load->model('users');
			$user = $this->users->get($user_id);
			$leader = $this->users->getLeader($user_id);
			//print_r($leader);

			foreach($data as $date){
				$month_ts = strtotime($date);
				$month_name = date('F', $month_ts);
				$year = date('Y', $month_ts);

				if ($month_ts !== false){
					$this->load->model('addon/attendance_model', 'attendance');
					$ref = $this->attendance->submitMonth($month_ts, $user_id);

					if ($ref !== false){
						$msg = "Please sign my attendance sheet for the month of $month_name/$year ".
							$this->config->item('site_url')."addon/attendance/month/$ref";

						$emails = array();
						$emails[] = array(
							'subject' => "{$user[0]['display_name']} - $month_name $year attendance sheet",
							'from' => $user[0]['email'],
							'to' => isset($leader['email']) ? $leader['email'] : $user[0]['email'],
							'message' => $msg
						);

						$this->load->model('mail_queue');
						if ($this->mail_queue->add($emails) !== false){
							$result->isError = false;
						}
					}
				}
			}
		}

		$result->errorStr = "There was an error saving the information on the server. Try again later, ".
				"if the problem persists please contact the Helpdesk.";

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	/**
	 * @param int $request_id
	 */
	function month($request_id = null){
		if (!isset($request_id)){
			$this->index();
		}else{
			$this->load->model('addon/attendance_model', 'attendance');
			$page_data = $this->attendance->getMonth($request_id);
			if ($page_data !== false){

				$page_data['template_name'] = 'addon/attendance_month';
				$page_data['title'] = 'Attendance Report - Month Signature';

				$this->load->view("layouts/default", $page_data);
			}else{
				show_404();
			}
		}
	}

	/**
	 * @return mixed
	 */
	function month_submit(){
		$approved = $this->input->post('approve');
		$replayed = $this->input->post('reply');
		$request_id = $this->input->post('request_id');
		$requester_id = $this->input->post('user_id');
		$month = $this->input->post('month');
		$year = $this->input->post('year');

		if ($approved !== false){
			$this->_month_approve(array(
				'username' => $this->input->post('username'),
			    'password' => $this->input->post('password'),
			    'request_id' => $request_id,
			    'requester_id' => $requester_id,
			    'month' => $month,
			    'year' => $year
			));
		}elseif($replayed !== false){
			$this->_month_reply(array(
				'request_id' => $request_id,
				'requester_id' => $requester_id,
				'msg' => $this->input->post('msg'),
				'month' => $month,
				'year' => $year
            ));
		}else{
			$this->result->isError = true;
			$this->result->errorStr = "There was an error. Try again later. If the problem persists call the Helpdesk.";
		}

		if (!isAjax()){
			if ($this->result->isError){
				$this->output->set_output($this->result->errorStr);
			}else{
				$this->output->set_output($this->result->data."<br><br>".anchor('/addon/attendance', '&#x25c4; Attendance Report'));
			}
		}else{
			$this->output->set_content_type('application/json')->set_output(json_encode($this->result));
		}
	}

	function admin(){
		if (($this->session->userdata('role') === 'admin') or ($this->session->userdata('role') === 'payroll')){
			$page_data = array();
			$page_data['template_name'] = "addon/attendance_admin";
			$page_data['revision'] = false;
			$page_data['page_id'] = 0;
			$page_data['title'] = 'Excempt Attendance Report - Admin Tool';

			$this->load->view('layouts/default', $page_data);
		}else{
			redirect('/');
		}
	}

	/**
	 * Retrieves a YTD report of people who haven't filled out their attendance
	 */
	function getexceptionreport(){
		$this->load->model('addon/attendance_model', 'attendance');
		$report = $this->attendance->findNonCompliant(date('Y'));
		//var_dump($report);

		//generate excel file with the results
		$keys = array_keys(val($report[0], array()));
		$file = '"'.implode('","', $keys)."\"\n";
		for($i = 0; $i < count($report); $i++){
			$file .= '"'.implode('","', $report[$i])."\"\n";
		}

		//echo $file;
		$this->output
			->set_content_type('application/octet-stream')
			->set_header("Content-Disposition: attachment; filename=\"report.csv\"")
			->set_output($file);
	}

	/**
	 * Retrieves the YTD report of days taken
	 */
	function getreport(){
		$this->load->model('addon/attendance_model', 'attendance');
		$report = $this->attendance->getDates(date('Y'));
		//var_dump($report);

		//generate excel file with the results
		$keys = array_keys(val($report[0], array()));
		$file = '"'.implode('","', $keys)."\"\n";
		for($i = 0; $i < count($report); $i++){
			$file .= '"'.implode('","', $report[$i])."\"\n";
		}

		//echo $file;
		$this->output
            ->set_content_type('application/octet-stream')
			->set_header("Content-Disposition: attachment; filename=\"report.csv\"")
            ->set_output($file);
	}
}
