<?php
 /**
 * @author cravelo
 * @date 8/9/11
 * @time 10:31 AM
 */

/**
 * Comments subscriptions model
 * @package Models
 * @author cravelo
 * @property CI_DB_active_record $db
 */
class Subscriptions extends CI_Model {
	/**
	 * retrieve all subscriptions for a page or a user
	 * @param int $user_id the user
	 * @param int $page_id the page
	 * @return array
	 */
	function get($user_id = null, $page_id = null){
		if (($user_id === null) and ($page_id === null)){ return array(); }

		if (is_numeric($user_id)){
			$this->db->where('user_id', $user_id);
		}
		if (is_numeric($page_id)){
			$this->db->where('page_id', $page_id);
		}

		$q = $this->db
			->select('u.*, pages.title')
			->from('pages_users u')
			->join('pages', 'u.page_id=pages.page_id')
			->order_by('pages.date_published DESC')
			->get();

		return $q->result_array();
	}

	/**
	 * Add a new subscription
	 * @param int $user_id the user to subscribe
	 * @param int $page_id the page that this user is subscribing to
	 * @return int
	 */
	function add($user_id, $page_id){
		return $this->db->query('INSERT IGNORE INTO fn_pages_users(page_id, user_id) VALUES('.
				$this->db->escape($page_id).', '.$this->db->escape($user_id).')');
	}

	/**
	 * remove a subscription
	 * @param int $user_id the user
	 * @param int $page_id the page
	 * @return int
	 */
	function remove($user_id, $page_id){
		$this->db
			->where('page_id', $page_id)
			->where('user_id', $user_id)
			->delete('pages_users');

		return ($this->db->affected_rows() > 0);
	}

	/**
	 * @param int $page_id the page id
	 * @param int $comm_id the new comment ID
	 * @return boolean
	 */
	function send($page_id, $comm_id){
		$q = $this->db
			->select('p.page_id, p.title, u.email, u.first_name')
			->from('pages p')
			->join('pages_users s', 'p.page_id=s.page_id')
			->join('users u', 's.user_id=u.user_id')
			->where('p.page_id', $page_id)
			->get();

		$email = array('subject' => 'New comment notification');
		$message = "Hello %s,\r\n\r\n";
		$message .= "You are receiving this notification because you are subscribed to the page \"%s\" on FISHNET. ";
		$message .= "This page has received a new comment. \r\n\r\n";
		$message .= "INSIDE the EF Network you can view the comment using the following link:";
		$message .= "\r\n\r\n%s\r\n\r\n";
		$message .= "OUTSIDE the EF Network you can see the comment using the following link:";
		$message .= "\r\n\r\n%s\r\n\r\n\r\n\r\n";
		$message .= "If you no longer wish to receive notifications from this page either manage your subscriptions on ";
		$message .= "your profile on FISHNET or click the following link: \r\n\r\n";
		$message .= "INSIDE the EF Network you can use the following link to unsubscribe:";	
		$message .= "\r\n\r\n%s\r\n\r\n";
		$message .= "OUTSIDE the EF Network you can use the following link to unsubscribe:";
		$message .= "\r\n\r\n%s\r\n\r\nThanks,\r\nFISHNET";
		$emails = array();

		if ($q->num_rows() > 0){
		$result = $q->result();

			foreach ($result as $row){
				$emails[] = $email;
				$last_idx = count($emails)-1 ;
				$emails[$last_idx]['to'] = $row->email;
				$emails[$last_idx]['message'] = sprintf($message, $row->first_name, $row->title,
					$this->config->item('site_url')."article/$page_id#comment-$comm_id",
					$this->config->item('site_url')."cvpn/https/fishnet.eileenfisher.com/article/$page_id#comment-$comm_id",
					$this->config->item('site_url')."comments/unsubscribe/$page_id",
					$this->config->item('site_url')."cvpn/https/fishnet.eileenfisher.com/comments/unsubscribe/$page_id");
			}

			$this->load->model('mail_queue');
			return $this->mail_queue->add($emails);
		}

		return false;
	}
}
