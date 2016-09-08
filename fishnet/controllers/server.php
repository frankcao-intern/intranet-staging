<?php
/**
 * @todo all of this functions should check for permissions
 * @author cravelo
 */

/**
 * The server controller contains mainly AJAX handlers
 * @author Carlos Ravelo
 * @package Controllers
 * @property Templates $templates
 * @property Permissions $permissions
 */

 
class Server extends CI_Controller {
	/**
	 * @var \CResult
	 */
	private $result;

	/**
	 * Create new result instance and load audit model
	 */
	public function __construct(){
		parent::__construct();

		$this->result = new CResult();
		$this->load->model('audit');
	}

	public function __destruct(){
		unset($this->result);
	}

	//TEMPLATES --------------------------------------------------------------------------------------------
	function templates(){
		$this->load->model('templates');
		$templates = $this->templates->getTemplate();

		if (!$templates){
			$this->result->isError = true;
			$this->result->errorStr = "There was an error retrieving the templates, please try again.";
		}

		$categories = array();
		foreach($templates as $templ){
			$categories[] = $templ['category'];
		}
		$categories = array_values(array_unique($categories));
		sort($categories);

		$this->result->data = array(
			'categories' => $categories,
			'templates' => $templates
		);

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	//PERMISSIONS -------------------------------------------------------------------------------------------
    /**
     * Permissions add new record
     * @return void
     */
    function permadd(){
        $page_id = $this->input->post('pid');
        $data = json_decode($this->input->post('data'));

        //load the pages model
        $this->load->model('permissions');
        //update title
        if (!$this->permissions->add($page_id, $data)){
            $this->result->isError = true;
            $this->result->errorStr = "There was an error adding that group to the permissions table. Check that the
				selected group is not already on the table and try again. Contact the Helpdesk at x4024 if the
				problem persists.";
        }

        $this->result->data = "New group added successfully.";
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
    }

    /**
	 * Permissions update, return json like every other function in this controller.
	 * @return void
	 */
	function permupdate(){
		$page_id = $this->input->post('pid');
		$data = json_decode($this->input->post('data'));

		//load the pages model
		$this->load->model('permissions');
		//update title
		if (!$this->permissions->updateAccess($page_id, $data)){
			$this->result->isError = true;
			$this->result->errorStr = "There was an error while updating the permissions for this page. Please try again. If the problem persists call the Helpdesk at x4024.";
		}

		$this->result->data = "Page properties - group permissions updated successfully";

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	/**
	 * Permissions delete a record, return json like every other function in this controller.
	 * @return void
	 */
	function permdelete(){
		$page_id = $this->input->post('pid');
		$data = json_decode($this->input->post('data'));

		//load the pages model
		$this->load->model('permissions');
		//update title
		if (!$this->permissions->delete($page_id, $data))
		{
			$this->result->isError = true;
			$this->result->errorStr = "There was an error deleting the selected permission record. Please try again. If the problem persists call the Helpdesk at x4024.";
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	//REVISIONS ------------------------------------------------------------------------------------------
	/**
	 * delete a revision
	 */
	function revdelete(){
		$page_id = $this->input->post('pid');
		$rev_id = $this->input->post('revid');

		//load the pages model
		$this->load->model('revisions');
		//update title
		if (!$this->revisions->delete($page_id, $rev_id))
		{
			$this->result->isError = true;
			$this->result->errorStr = "There was an error saving that revision.";
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	/**
	 * Revert to a revision
	 */
	function revrev(){
		//$page_id = $this->input->post('pid');
		$rev_id = $this->input->post('revid');

		//load the pages model
		$this->load->model('revisions');
		//update title
		if (!$this->revisions->clonerev($rev_id)){
			$this->result->isError = true;
			$this->result->errorStr = "There was an error saving the new revision.";
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	/**
	 * Sends the feedback email gathered from the feedback form.
	 * @todo make generic email sending function
	 * @return void
	 */
	function feedback(){
		//data
		$from = '"'.$this->session->userdata('display_name').'" <'.$this->session->userdata('email').'>';
		
		if($this->input->post('requestType') == "Who's Who Correction"){
			$to = '"Gail Ferguson" <gferguson@eileenfisher.com>'.",".'"FishNET" <fishnet@eileenfisher.com>';
		}
		else
		{
			$to = '<fishnet@eileenfisher.com>';
	
		}
		
		$subject = 'Intranet Feedback - '.$this->input->post("requestType");
		//$specific = $this->input->post("subject_specific");
		//if ($specific == ''){
		//	$subject .= " - $specific";
		//}
		$body = 'From: '.$this->session->userdata('display_name')."\r\n\r\n".'Requestor Email: '.$this->input->post('email').
		"\r\n\r\n".'Department: '.$this->input->post('dept')."\r\n\r\n".'Date Needed: '.$this->input->post('date')."\r\n\r\n".$this->input->post('desc')."\r\n\r\n".$this->input->post('file');

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
                ->set_output("There was an error sending the email with your feedback, please try again later. If the problem persists please call the Helpdesk at x4024. Thank you");
		}else{
			//$this->output
	            //->set_content_type('text/html');
		    header('Location: /home');
		    
		    
		    
              //  ->set_output("Thank you for your feedback.<br /><br />".anchor('home', "< Back to the homepage"));
		}		
		
	}
	
	//function to send attachment and rest of feedback form to the fishnet mailbox
	function mail_file($to, $from, $subject, $body, $file)	
	{
		$from = '"'.$this->session->userdata('display_name').'" <'.$this->session->userdata('email').'>';

		if($this->input->post('requestType') == "Who's Who Correction"){
			$to = '"Gail Ferguson" <gferguson@eileenfisher.com>'.",".'"FishNET" <fishnet@eileenfisher.com>';
		}
		else
		{
			$to = '<fishnet@eileenfisher.com>';
		}
	
		$subject = 'Intranet Feedback - '.$this->input->post('requestType');
		$body = 'From: '.$this->session->userdata('display_name')."\r\n\r\n".'Requestor Email:'.$this->input->post('email').
		"\r\n\r\n".'Department:'.$this->input->post('dept')."\r\n\r\n".'Date Needed:'.$this->input->post('date')."\r\n\r\n".$this->input->post('desc')."\r\n\r\n".$this->input->post('file');

		$boundary = md5(rand());
		
		$headers = array(
			'MIME-Version 1.0',
			'Content-Type: multipart/mixed; boundary=\"{$boundary}\"',
			'From: {$from}'
			);
			
		$message = array(
		//	"--{$boundary}",
		//	'Content-Type: text/plain',
		//	'Content-Transfer-Encoding: 7bit',
			'',
			$body,
		//	"--{$boundary}",
		//	"Content-Type: {$file['type']}; name =\"{$file['name']}\"",
		//	"Content-Disposition: attachment; filename=\"{$file['name']}\"",
		//	"Content-Transfer-Encoding: base64",
		//	'',
		//	chunk_split(base64_encode(file_get_contents($file['tmp_name']))),
		//	"--{$boundary}--"
		);

//		$host = "efexc01.eileenfisher.net";
//		$port = "25";
//		ini_set('SMTP', $host);
//		ini_set('smtp_port', $port);
		
		//		$body = 'From: '.$this->session->userdata('display_name')."\r\n\r\n".'Requestor Email:'.$this->input->post('email').
//		"\r\n\r\n".'Department:'.$this->input->post('dept')."\r\n\r\n".'Date Needed:'.$this->input->post('date')."\r\n\r\n".$this->input->post('desc')."\r\n\r\n".$this->input->post('file');
		
//		mail($to, $subject, implode("\r\n", $message), implode("\r\n", $headers));

	
		if (mail($to, $subject, implode("\r\n", $message), implode("\r\n", $headers))  === FALSE){
				$this->output
					->set_content_type('text/html')
					->set_output("There was an error sending the email, please try again later. If the problem persists please call the Helpdesk at x4024. Thank you");
		}
		else{
			header('Location: /home');
		}	
	
	}
	
		
	//TAGS ------------------------------------------------------------------------------------------
	function gettags(){
		$tag_name = $this->input->post('tag_name');

		$this->load->model("tags");
		$tags = $this->tags->getList($tag_name);

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($tags));
	}

	//STORES ------------------------------------------------------------------------------------------
	function suggest_store(){
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('from', 'From', 'trim|required');
		$this->form_validation->set_rules('name', 'Store Name', 'trim|required');
		$this->form_validation->set_rules('website', 'Website', 'trim');
		$this->form_validation->set_rules('contact_name', 'Owner\'s Name', 'trim');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('fax', 'Fax', 'trim');
		$this->form_validation->set_rules('street_address1', 'Street Address 1', 'trim|required');
		$this->form_validation->set_rules('street_address2', 'Street Address 2', 'trim');
		$this->form_validation->set_rules('city', 'City', 'trim|required');
		$this->form_validation->set_rules('state', 'State', 'trim|required');
		$this->form_validation->set_rules('zip', 'Zip', 'trim|required');
		$this->form_validation->set_rules('brands', 'Brands they carry', 'trim|required');
		$this->form_validation->set_rules('message', 'Tell us about the store', 'trim|required');

		if ( $this->form_validation->run() !== false ) {
			if ($this->db->insert('suggested_stores', $this->input->post()) !== false){
				$this->output
					->set_output('Your submission was successfully received. <a href="/">Go back to the homepage</a>');
			}
		}else{
			$page_data['template_name'] = 'static/specialty_suggest';
			$page_data['page_id'] = 0;
			$page_data['title'] = 'General Content';

			$this->load->view("layouts/default", $page_data);
		}
	}
}
