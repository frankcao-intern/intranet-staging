<?php
/**
 * @author cravelo
 */

/**
 * Permissions model
 * Access works with constants in PHP: PERM_READ = 1, PERM_WRITE = 2, etc
 * Access is the sum of all assigned permissions
 *      e.g. access = 3 means read + write
 * Check with: access bit_and(&) PERM_* == PERM_*
 * @package Models
 * @author cravelo
 */
class Permissions extends CI_Model {
	/**
	 * Get permissions table for a page
	 * @param $page_id
	 * @return array
	 */
	function getAccess($page_id){
		$query = $this->db
				->join('groups', 'groups.group_id=permissions.group_id')
				->where("page_id", $page_id)
				->get("permissions");

		if ($query->num_rows() > 0){ return $query->result_array(); }else{ return array(); }
	}

	/**
	 * Get a the permissions a user has to a page
	 * @param int $user_id
	 * @param int $page_id
	 * @return array with the permissions
	 */
	function getUserAccess($user_id, $page_id){
		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db
				->select("BIT_OR(`p`.`access`) as bor")
				->from("permissions p")
				->join("groups", "p.group_id=groups.group_id")
				->join("groups_users rel", "rel.group_id=groups.group_id")
				->where("rel.user_id", $user_id)
				->where("p.page_id", $page_id)
				->get();

		//echo $this->db->last_query()."<br/><br/>";
		$access = $query->row_array();
		$access = $access['bor'];

		return array(
			'canRead' => $access & PERM_READ,
			'canWrite' => $access & PERM_WRITE,
			'canDelete' => $access & PERM_DELETE,
			'canPublish' => $access & PERM_PUBLISH,
			'canPerm' => $access & PERM_PERM,
			'canProp' => $access & PERM_PROPERTIES
		);
	}

	/**
	 * Update permission assignments
	 * @param int $page_id
	 * @param array $data an array of permission assignments
	 * @return bool
	 */
	function updateAccess($page_id, $data){
		//check user has permission to change permissions
		if ($this->session->userdata('role') == 'admin'){
			$perms = array('canPerm' => PERM_PERM);
		}else{
			$perms = $this->getUserAccess($this->session->userdata('user_id'), $page_id);
		}

		if ($perms['canPerm'] == PERM_PERM){
			foreach($data as $perm){
				$this->db
					->where(array(
						'page_id' => $page_id,
						'group_id' => $perm->group_id
					))
					->set("access", $perm->access);

				if ($this->db->update('permissions') === false){
					return false;
				}
			}

			return true;
		}

		return false;
	}

	/**
	 * Add a new group to the permissions table
	 * @param int $page_id
	 * @param array $data an array of permission assignments
	 * @return bool
	 */
	function add($page_id, $data, $override = false){
	    //check user has permission to change permissions
		if (($this->session->userdata('role') == 'admin') or ($override === true)){
			$perms = array('canPerm' => PERM_PERM);
		}else{
			$perms = $this->getUserAccess($this->session->userdata('user_id'), $page_id);
		}

		if ($perms['canPerm'] == PERM_PERM){
			foreach ($data as $perm){
				$tmp = (object)$perm;

				$this->db->insert('permissions', array(
					'page_id' => $page_id,
					'group_id' => $tmp->group_id,
					'access' => $tmp->access
				));

				if ($this->db->affected_rows() == 0){
					return false;
				}
			}

			return true;
		}

		return false;
	}

	/**
	 * Delete a group from the permissions table
	 * @param int $page_id
	 * @param array $data  an array of permission assignments
	 * @return bool
	 */
	function delete($page_id, $data){
		//check user has permission to change permissions
		if ($this->session->userdata('role') == 'admin'){
			$perms = array('canPerm' => PERM_PERM);
		}else{
			$perms = $this->getUserAccess($this->session->userdata('user_id'), $page_id);
		}

		if ($perms['canPerm'] == PERM_PERM){
			foreach($data as $perm){
				$this->db
					->where(array(
						'page_id' => $page_id,
						'group_id' => $perm->group_id
					))
					->delete('permissions');

				if ($this->db->affected_rows() == 0){
					return false;
				}
			}

			return true;
		}

		return false;
	}
}
