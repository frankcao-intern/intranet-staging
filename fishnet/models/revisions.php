<?php
/**
 * @author cravelo
 */

/**
 * Revisions model
 * @package Models
 * @author cravelo
 */
class Revisions extends CI_Model {
	/**
	 * @param int $page_id
	 * @return array the revision record
	 */
	function getLatestRevision($page_id){
		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db->select('page_id as rev_page_id, revision_id, date_created as rev_date_created,
					revision_text')
				->from('revisions')
				->where('revisions.page_id', $page_id)
				->order_by('rev_date_created', 'desc')
				->limit(1)
				->get();

		if ($query->num_rows() > 0){
			$resultArr = $query->row_array();//return first result, there should be only one result
			if (isset($resultArr['revision_text'])){
				/*TODO: //replace any instances of :id with the page_id in the revision_text
				$revision_text = str_replace(':id', $page_id, $resultArr['revision_text']);
				$revision_text = json_decode($revision_text, true); //decode the template data

				$resultArr['revision_text'] = $revision_text;*/

				$resultArr['revision_text'] = json_decode($resultArr['revision_text'], true); //decode the template data
			}

			return $resultArr;
		}else{ //that page doesn't have any revisions
			return false;
		}
	}

	/**
	 * Get a revision by id
	 * @param int $rev_id
	 * @return array the revision record
	 */
	function get($rev_id){
		$this->db->select('revisions.date_created, revisions.revision_text')
				->from('revisions')
				->where('revisions.revision_id', $rev_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0){
			$resultArr = $query->row_array();//return first result, there should be only one result
			if (isset ($resultArr['revision_text'])){
				$resultArr['revision_text'] = json_decode($resultArr['revision_text'], true); //decode the template data
			}

			return $resultArr;
		}else{ //we didnt find that revision :(
			return false;
		}
	}

	/**
	 * Get a list of all revisions for a $page_id
	 * @param int $page_id
	 * @return bool
	 */
	function getAll($page_id){
		$this->db->select('revisions.date_created, revisions.revision_id, users.display_name')
				->from('revisions')
				->order_by('date_created', 'desc')
				->join('users', 'revisions.created_by=users.user_id')
				->where('revisions.page_id', $page_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}

	/**
	 * Creates a new revision
	 * @param $page_id
	 * @param null $content
	 * @return bool whether it was created successfully or not
	 */
	function newRevision($page_id, $content = null){
		//default value for content
		$content = ($content == null) ? '' : $content;

		$this->db->insert('revisions', array(
			'revision_text' => $content,
			'page_id' => $page_id,
			'created_by' => $this->session->userdata('user_id')
		));

		$this->load->model('audit');
		$this->audit->newEntry(array(array(
			"page_id" => $page_id,
			"what" => 'edited',
			"who" => $this->session->userdata('user_id')
		)));

		return ($this->db->_error_number() == 0);
	}

	/**
	 * Updates a revision
	 * @param array $revision the new revision object
	 * @return bool whether it failed or not
	 */
	function updateRevision($revision){
		//print_r($revision);
		$result = $this->db->where('revision_id', $revision['revision_id'])
				->set('revision_text', json_encode($revision['revision_text']))
				->set('date_created', date("Y-m-d H:i:s")) //bump the date, this will also trigger the searchindex update
				->update('revisions');

		$this->load->model('audit');
		$this->audit->newEntry(array(array(
			"page_id" => $revision['rev_page_id'],
			"what" => 'edited',
			"who" => $this->session->userdata('user_id')
		)));

		return $result;
	}

	/**
	 * Deletes a revision
	 * @param $page_id
	 * @param $rev_id
	 * @return bool
	 */
	function delete($page_id, $rev_id){
		$this->db->from('revisions')->where("revision_id", $rev_id)->delete();

		return ($this->db->_error_number() == 0);
	}

	function clonerev($rev_id){
		$this->db->query("INSERT INTO fn_revisions (revision_text, page_id, created_by)
			SELECT revision_text, page_id, created_by
			FROM fn_revisions
			WHERE revision_id=$rev_id");

		return ($this->db->affected_rows() > 0);
	}
}
