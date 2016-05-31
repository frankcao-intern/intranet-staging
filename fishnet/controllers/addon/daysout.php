<?php
/**
 * @filename daysout.php
 * @author cravelo
 * @date 6/14/12 12:05 PM
 */

require_once __DIR__."/attendance.php";

/**
 * @package Addon/Controllers
 * @name Daysout
 * @author cravelo
 * @property CI_Loader $load
 */
class Daysout extends Attendance {
	/**
	 * @param array $data
	 */
	protected function _approve($data){
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
					//login failed
					case E_AD_LOGIN_FAILED:
					case E_AD_EMPTY:
					//Account problem
					case E_AD_PWD_FORCE_CHANGE:
					case E_AD_ACCT_LOCKED:
					case E_AD_PWD_EXPIRED:
					case E_AD_PWD_NEVER_SET:
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
				if ($this->attendance->changeStatus($data['request_id'], 'confirmed') === true){

					$msg = "Your days out request for the dates below has been approved.\r\n\r\n";
					foreach($data['days'] as $day){
						$msg .= "{$day['name']} - ".date('F j, Y', strtotime($day['date']))."\r\n";
					}
					$msg .= "\r\nTo review your attendance report visit your Who's Who profile on fishNET and ".
						"click on the \"Attendance\" tab.\r\n\r\n".$this->config->item('site_url').'my/profile';

					$emails = array();
					$emails[] = array(
						'subject' => "Your days out request was approved",
						'from' => $this->session->userdata('email'),
						'to' => "{$requestor['email']}, {$this->session->userdata('email')}",
						'message' => $msg
					);

					$this->load->model('mail_queue');
					if ($this->mail_queue->add($emails) !== false){
						$this->result->isError = false;
						$this->result->data = "This request was approved. You and {$requestor['display_name']} will ".
							"receive confirmation emails.";
					}
				}
			}
		}
	}

	/**
	 * @param array $data
	 */
	protected function _reply($data){
		$this->result->isError = true;
		$this->result->errorStr = "There was an error. Try again later. If the problem persists call the Helpdesk.";
		$this->result->data = "";

		$this->load->model('users');
		$requester = $this->users->get($data['requester_id']);
		if ($requester !== false){
			$requester = $requester[0];

			$this->load->model('addon/attendance_model', 'attendance');
			if ($this->attendance->changeStatus($data['request_id'], 'planned') === true){

				$msg = "This is a response to your days out request for the following dates:\r\n\r\n";
				foreach($data['days'] as $day){
					$msg .= "{$day['name']} - ".date('F j, Y', strtotime($day['date']))."\r\n";
				}
				$msg .= "\r\n".htmlspecialchars($data['msg'])."\r\n\r\n".
					"To review your attendance report visit your Who's Who profile on FishNET and click on the ".
					"\"Attendance\" tab.\r\n\r\n".$this->config->item('site_url').'my/profile';

				$emails = array();
				$emails[] = array(
					'subject' => "RE: Days out request",
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
	 * @return mixed
	 */
	function daysout_submit(){
		$approved = $this->input->post('approve');
		$replayed = $this->input->post('reply');
		$request_id = $this->input->post('request_id');
		$requester_id = $this->input->post('user_id');
		$days = json_decode($this->input->post('days'), true);

		if ($approved !== false){
			$this->_approve(array(
				'username' => $this->input->post('username'),
			    'password' => $this->input->post('password'),
			    'request_id' => $request_id,
			    'requester_id' => $requester_id,
			    'days' => $days
			));
		}elseif($replayed !== false){
			$this->_reply(array(
				'request_id' => $request_id,
				'requester_id' => $requester_id,
				'days' => $days,
			    'msg' => $this->input->post('msg')
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

	/**
	 * @param int $request_id
	 */
	function load($request_id = null){
		if (!isset($request_id)){
			$this->index();
		}else{
			$page_data = array(
				'template_name' => 'addon/daysout',
				'title' => 'Days out request',
				'request_id' => $request_id
			);

			$this->load->model('addon/attendance_model', 'attendance');
			$page_data['days'] = $this->attendance->getSubmitted($request_id);

			if ($page_data['days'] !== false){
				$this->load->view("layouts/default", $page_data);
			}else{
				show_404();
			}
		}
	}

	/**
	 * Ajax responder for submitting days for approval
	 * @var CResult $result
	 */
	function request(){
		$result = new CResult();
		$data = json_decode($this->input->post('data', true), true);
		$user_id = $this->input->post('user_id', true);
		$user_id = ($user_id === false) ? $this->session->userdata('user_id') : $user_id;
		$this->load->model('users');
		$user = $this->users->get($user_id);
		$leader = $this->users->getLeader($user_id);

		if (is_array($data)){
			$this->load->model('addon/attendance_model', 'attendance');
			$timestamp = $this->attendance->submit($data, $user_id);

			if ($timestamp !== false){
				$msg = "{$user[0]['display_name']} is requesting days out of the office. Please review the request ".
					$this->config->item('site_url')."addon/daysout/load/$timestamp";

				$emails = array();
				$emails[] = array(
					'subject' => "Request for days out from {$user[0]['display_name']}",
					'from' => $user[0]['email'],
					'to' => isset($leader['email']) ? $leader['email'] : $user[0]['email'],
					'message' => $msg
				);

				$this->load->model('mail_queue');
				$this->mail_queue->add($emails);
			}else{
				$result->isError = true;
			}
		}else{
			$result->isError = true;
		}

		$result->errorStr = "There was an error saving the information on the server. Try again later,
				if the problem persists please contact the Helpdesk.";
		$result->data = "Your request was processed successfully.";

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}
}
