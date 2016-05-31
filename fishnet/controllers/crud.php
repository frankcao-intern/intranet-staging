<?php
/**
 * @filename crud.php
 * @author   : cravelo
 * @date     : 6/18/12 12:53 PM
 */

require_once __DIR__.'/article.php';

/**
 * General CRUD controller
 * @name Crud
 * @author cravelo
 * @package Controllers
 * @property CI_Loader $load
 * @property CI_Security $security
 */
class Crud extends Article {

	/**
	 * This is the retrieve part it loads the crud template with the contents of the table.
	 * Crud is not a straight view into the DB, there is a JSON strcuture with aliases for tables and which columns
	 * to expose and even a place for a where statement to filter out some records. It will also implement permissions.
	 * @param string $name
	 */
	function load($name = null){
		$this->loadPage(SID_CRUD);

		//load the latest revision and process the metadata.
		$this->loadRevision(SID_CRUD);

		if (isset($name)){
			if (!isset($this->pageRecord['revision']['revision_text']['tables'][$name])){ show_404(); }

			$columns = val($this->pageRecord['revision']['revision_text']['tables'][$name]['columns'], '*');
			$where = val($this->pageRecord['revision']['revision_text']['tables'][$name]['where'], false);
			$table_name = val($this->pageRecord['revision']['revision_text']['tables'][$name]['table_name'], null);

			if (isset($table_name)){
				$this->db
					->select($columns)
					->from($table_name);

				if ($where !== false){ $this->db->where($where[0], $where[1]); }

				$query = $this->db->get();

				$this->pageRecord[$table_name] = ($query) ? $query->result_array() : array();
				$this->pageRecord['table_name'] = $table_name;
			}
		}

		$this->load->view('layouts/default', $this->pageRecord);
	}

	/**
	 * This the Update part of CRUD.
	 */
	function save(){
		$data = json_decode($this->input->post('data', TRUE), TRUE);
		$this->result->isError = true;
		$this->result->data = "Information was successfully saved!";
		$this->result->errorStr = 'There was a problem saving the information on the server. Please try again later. '.
			'If the problem persists call the Helpdesk.';

		if ($data !== false){
			$this->result->isError = !$this->db
				->set($data['key'], base64_decode($data['val']))
				->where($data['table_key'], $data['table_key_val'])
				->update($data['table_name']);
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($this->result));
	}

	/**
	 * This is the Delete part of CRUD
	 */
	function crud_delete(){
		$data = json_decode($this->input->post('data', TRUE), TRUE);
		$this->result->isError = true;
		$this->result->data = "Record was deleted successfully!";
		$this->result->errorStr = 'There was a problem deleting that record. Please try again later.';

		if ($data !== false){
			$this->result->isError = !$this->db
				->where($data['table_key'], $data['table_key_val'])
				->delete($data['table_name']);
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($this->result));
	}

	/**
	 * This is the Create part of CRUD
	 */
	function insert(){
		$data = $this->security->xss_clean($_POST);

		//if the primary key is empty, unset it. EMPTY == Auto Increment, otherwise a value is expected.
		if (empty($data[$data['table_key']])){
			unset($data[$data['table_key']]);
		}

		$table_name = $data['table_name'];
		unset($data['table_name']);
		unset($data['table_key']);

		$this->result->isError = true;
		$this->result->errorStr = 'There was a problem creating the new record. Please try again later.';

		$this->result->isError = !$this->db->insert($table_name, $data);

		if ($this->result->isError){
			$this->output->set_output($this->result->errorStr);
		}else{
			redirect($_SERVER['HTTP_REFERER']);
		}
	}
}
