<?php
/**
 * @author cravelo
 * @date 8/9/11
 * @time 10:37 AM
 */

/**
 * Comments controller
 * @package Controllers
 * @author cravelo
 * @property CI_Loader $load
 * @property Subscriptions $subscriptions
 * @property Comments_model $comments_model
 */
class Comments extends MY_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('audit');
	}

	/**
	 * Create a new comment
	 * @return void
	 */
	function add(){
		$page_id = $this->input->post('page_id', true);
		$response_to = ($this->input->post('response_to') == "") ? false : $this->input->post('response_to', true);
		$subscribe = $this->input->post('subscribe', true);
		$subscribe = (($subscribe === true) or ($subscribe === 'true'));
		$content = $this->security->xss_clean(strip_tags($this->input->post('content'), "<strong><em><ul><li><ol><sup><sub>"));
		$subs_res = false;

		$this->load->model('comments_model');
		$this->load->model('subscriptions');

		$commID = $this->comments_model->newComment($page_id, $content, $response_to);

		$this->subscriptions->send($page_id, $commID);

		if ($subscribe){ $subs_res = $this->subscriptions->add($this->session->userdata('user_id'), $page_id); }

		if (!isAjax()){
			if ($subscribe and !$subs_res){
				show_error("There was an error subscribing you to this page but, your comment has been posted.");
			}else{
				redirect("article/".$page_id);
			}
		}else{
			if ($subscribe and !$subs_res){
				$this->result->isError = true;
				$this->result->errorStr = "There was an error subscribing you to this page but, your comment has been posted.";
			}else{
				$this->result->data['response_to'] = $response_to;
				$this->load->model('users');
				$comment = array (
					'response_to' => ($response_to) ? $response_to : $commID,
					'timestamp' => date("Y-m-d h:i:s", time()),
					'comment_id' => $commID,
					'comment_text' => $content,
					'display_name' => $this->session->userdata('display_name'),
					'username' => $this->session->userdata('username'),
					'user_id' => $this->session->userdata('user_id'),
					'email' => $this->session->userdata('email'),
					'role' => $this->session->userdata('role'),
					'user_picture' => $this->users->getUserPicture($this->session->userdata('username'))
				);
				$this->result->data['html'] = $this->load->view('/page_parts/comment', array(
						'allow_comments' => true,
				        'comment' => $comment
				), true);
			}

			$this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($this->result));
		}
	}

	function delete(){
		if (($this->session->userdata('role') == 'admin') or ($this->session->userdata('role') == 'editor')){
			$comment_id = $this->input->post('cid', true);

			$this->load->model('comments_model');
			try {
				$this->comments_model->delete($comment_id);
			} catch (Exception $e){
				$this->result->isError = true;
				$this->result->errorStr = $e->getMessage();
			}

			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($this->result));
		}
	}

	/**
	 * Provide the user with a direct link to unsubscribe from page comments notifications
	 * @param int $page_id
	 * @return void
	 */
	function unsubscribe($page_id = null){
		if (is_numeric($page_id)){
			$this->load->model('subscriptions');
			if ($this->subscriptions->remove($this->session->userdata('user_id'), $page_id)){
				$this->output->set_output("You've been un-subscribed from that page's comments. ".anchor("/", "Home Page"));
			}else{
				show_error("There was an error or you are already un-subscribed from this page. Please try again later. If the problem persists call the Helpdesk at extension 4024.");
			}
		}else{
			show_error("That page id is invalid, if you reached this message by following a link in a notification email please call the Helpdesk at extension 4024 and let them know.");
		}
	}

	/**
	 * Provide the user with a direct link to unsubscribe from page comments notifications
	 * @return void
	 */
	function unsubscribe_json(){
		$page_id = $this->input->post('pid');

		if (is_numeric($page_id)){
			$this->load->model('subscriptions');
			if ($this->subscriptions->remove($this->session->userdata('user_id'), $page_id)){
				$this->result->data = $page_id;
			}else{
				$this->result->isError = true;
				$this->result->errorStr = "There was an error un-subscribing you from that page's comments. Please try again later. If the problem persists call the Helpdesk at extension 4024.";
			}
		}else{
			$this->result->isError = true;
			$this->result->errorStr = "That page id is invalid, if you reached this message by following a link in a notification email please call the Helpdesk at extension 4024 and let them know.";
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($this->result));
	}
}
