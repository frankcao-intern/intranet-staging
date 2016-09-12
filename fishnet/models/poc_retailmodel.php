<?php
class PoC_Retailmodel extends CI_Model {

	function get_entries() {
		$query = $this->db->get('fn_poc_retail');
        return $query->result();
	}

	function get_entries_count() {
		$num = $this->db->count_all('fn_poc_retail');
		return $num;
	}

	function insert_entry($new_task) {
        return $this->db->insert('fn_poc_retail', $new_task);
	}


	function delete_entry() {
		$this->db->empty_table('fn_poc_retail'); 
	}

}