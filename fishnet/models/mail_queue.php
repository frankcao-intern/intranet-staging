<?php
/**
 * @author cravelo
 * @date 8/19/11
 * @time 1:02 PM
 */

/**
 * Mail queue model
 * @package Models
 * @author cravelo
 */
class Mail_queue extends CI_Model {
	function add($emails){
		if (is_array($emails)){
			$this->db->insert_batch('mail_queue', $emails);

			return ($this->db->affected_rows() > 0);
		}else{
			return false;
		}
	}
}
