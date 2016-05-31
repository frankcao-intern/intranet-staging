<?php
/**
 * @author		cravelo
 */

/**
 * Comments model
 *
 * @package		Models
 * @author		cravelo
 * @property	CI_DB_active_record	$db
 * @property	CI_Loader			$load
 * @property	Users				$users
 */
class Comments_model extends CI_Model {
	/**
	 * Get comments for a page
	 * @param int $page_id
	 * @return array query results
	 */
	function get($page_id){
		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db->select("
				comments.response_to, comments.timestamp, comments.comment_id, comments.comment_text,
				users.display_name, users.username, users.user_id, users.email, users.role
			")
			->from('comments')
			->join('users', 'comments.user_id=users.user_id')
			->where('comments.page_id', $page_id)
			->order_by('comments.response_to desc, comments.comment_id')
			->get();

		if ($query->num_rows() > 0) {//if we found comments
			$result = $query->result_array();
			$this->load->model('users');
			for($i = 0; $i < count($result); $i++){
				$result[$i]['user_picture'] = $this->users->getUserPicture($result[$i]['username']);
			}

			return $result;
		}else{
			return null;
		}
	}

	/**
	 * Insert a new commet
	 * @param int $page_id
	 * @param string $content the text of the comment
	 * @param int $response_to if this comment is a response to another one this is the reference to it.
	 * @return bool whether the insert was successful or not
	 */
	function newComment($page_id, $content, $response_to){
		//insert comment
		$record = array(
			'user_id' => $this->session->userdata('user_id'),
			'page_id' => $page_id,
			'comment_text' => $content
		);
		$update = false;
		if ($response_to){
			$record['response_to'] = $response_to;
		}else{
			$update = true;
		}

		$this->db->insert('comments', $record);
		$comment_id = $this->db->insert_id();
		//update response to
		if ($update){
			$this->db->set('response_to', $comment_id)
					->where('comment_id', $comment_id)
					->update('comments');
		}
		//var_dump($this->db);
		if ($this->db->affected_rows() > 0)
			return $comment_id;
		else
			return false;
	}

	/**
	 * Delete a comment
	 *
	 * @param int $cid comment id
	 * @throws Exception
	 * @return void
	 */
	function delete($cid){
		$affected = $this->db->where('comment_id', $cid)->limit(1)->delete('comments');

		if ($affected and ($this->db->affected_rows() == 0)){
			throw(new Exception('ERROR: Deleting the comment.'));
		}
	}
}
