<?php
/**
 * @filename request.php
 * @author: cravelo
 * @date: 11/14/13 3:29 PM
 */
require_once dirname(__FILE__).'/article.php';

class request extends CI_Controller {

	function createForm(){

		//data
		$from = '"'.$this->session->userdata('display_name').'" <'.$this->session->userdata('email').'>';
		$to = '"kyoung" <kyoung@eileenfisher.com>';
		$subject = 'Automated Forms Request';
		$body ='Requested By: '.$this->input->post('requester')."\r\n\r\n".
			'Email: '.$this->input->post('email')."\r\n\r\n".
			'Date of Request: '.$this->input->post('requestDate')."\r\n\r\n".
			'Department Requesting Automation: '.$this->input->post('dept')."\r\n\r\n".
			'Requested Date & Time of Meeting: '.$this->input->post('meeting').' at: '.$this->input->post('time').' '
			.$this->input->post('timeAMPM')."\r\n\r\n".
			'--------FORM INFORMATION--------'."\r\n\r\n".
			'Do you have a team page? '.$this->input->post('tp')."\r\n\r\n".
			'Where would you like your forms to live on fishNET? '.$this->input->post('location');

		//config
		$host = "efexc01.eileenfisher.net";
		$port = "25";
		ini_set('SMTP', $host);
		ini_set('smtp_port', $port);
		//ini_set('sendmail_from', 'noreply@eileenfisher.com');

		//headers
		$headers  = "Content-Type:  text/plain\n";
		$headers .= "From: $from\n";
		$headers .= "To: $to\n";
		$headers .= "Subject: $subject\n";

		//send it
		if (mail($to, $subject, $body, $headers) === FALSE){
			$this->output
				->set_content_type('text/html')
				->set_output("There was an error sending the email, please try again later. If the problem persists please call the Helpdesk at x4024. Thank you");
		}else{
			$this->output
				->set_content_type('text/html')
				->set_output("Your Automated Forms Request has been submitted. Thank you!<br /><br />".
					             anchor('home'," Back to the homepage <br /><br />")
					             .anchor('article/3251',"Back to the Automated Forms Request Form"));
		}
	}
	function UpdateForm(){

		//data
		$from = '"'.$this->session->userdata('display_name').'" <'.$this->session->userdata('email').'>';
		$to = '"kyoung" <kyoung@eileenfisher.com>';
		$subject = 'Automated Forms Update';
		$body ='Requested By: '.$this->input->post('requester')."\r\n\r\n".
			'Email: '.$this->input->post('email')."\r\n\r\n".
			'Date of Request: '.$this->input->post('requestDate')."\r\n\r\n".
			'Department Requesting Update: '.$this->input->post('dept')."\r\n\r\n".
			'Updated Form Needed By: '.$this->input->post('updateDate')."\r\n\r\n".
			'--------FORM INFORMATION--------'."\r\n\r\n".
			'Name of Form to be Updated: '.$this->input->post('formName')."\r\n\r\n".
			'Do forms need to be updated often? '.$this->input->post('frequency')."\r\n\r\n".
			'Who maintains the forms? '.$this->input->post('owner')."\r\n\r\n".
			'Describe changes: '.$this->input->post('changes');

		//config
		$host = "efexc01.eileenfisher.net";
		$port = "25";
		ini_set('SMTP', $host);
		ini_set('smtp_port', $port);
		//ini_set('sendmail_from', 'noreply@eileenfisher.com');

		//headers
		$headers  = "Content-Type:  text/plain\n";
		$headers .= "From: $from\n";
		$headers .= "To: $to\n";
		$headers .= "Subject: $subject\n";

		//send it
		if (mail($to, $subject, $body, $headers) === FALSE){
			$this->output
				->set_content_type('text/html')
				->set_output("There was an error sending the email, please try again later. If the problem persists please call the Helpdesk at x4024. Thank you");
		}else{
			$this->output
				->set_content_type('text/html')
				->set_output("Your Automated Forms Update has been submitted. Thank you!<br /><br />".
					             anchor('home'," Back to the homepage <br /><br />")
					             .anchor('article/3257',"Back to the Automated Forms Update Form"));
		}
	}

}
