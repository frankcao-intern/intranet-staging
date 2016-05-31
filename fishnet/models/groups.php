<?php
/**
 * @author cravelo
 */

/**
 * Groups model
 * @package Models
 * @author cravelo
 */
class Groups extends CI_Model{
	/**
	 * @param string|array $q if its numeric is the group_id if its a name then its part of or the complete group_name
	 * @param string $type group type
	 * @return array results from the query
	 */
	function get($q, $type = null){
		$this->db->select("group_id, group_name");
		if (isset($type)){ $this->db->where('group_type', $type); }

		if (isset($q)){
			if (is_numeric($q)){
				$this->db->where("group_id", $q);
			}else{
				if (is_array($q)){
					foreach ($q as $group_name){
						$this->db->or_where('group_name LIKE', "$group_name%");
					}
				}else{
					$this->db->where('group_name LIKE', "$q%");
				}
			}
		}

		$query = $this->db->get('groups');

		return $query->result_array();
	}

	/**
	 * Returns a multidimensional array with all departments in the groups table
	 * @return array to be merged with pagedata
	 */
	function getDepartments(){
		//retrieve the version form the configuration to use in the names of the saved items in the cache
		$ver = FISHNET_VERSION;

		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		if ($this->cache->apc->is_supported()){
			$departments = $this->cache->apc->get('who-departments'.$ver);
		}else{
			$departments = $this->cache->file->get('who-departments'.$ver);
		}

		if(!$departments){
			$query = $this->db
				->select('groups.group_name, groups.group_id, g.group_name AS parent, g.group_id AS parent_id')
				->from('groups')
				->join('groups g', 'groups.group_parent = g.group_id', 'left')
				->where('groups.group_type', 'department')
				->order_by('parent, group_name')
				->get();

			$results = $query->result_array();

			//print_r($results);
			$departments = array();
			foreach($results as $dep){
				if (isset($dep['parent'])){
					$departments[$dep['parent']]['children'][] = array(
						'name' => $dep['group_name'],
						'id' => $dep['group_id']
					);
				}else{
					$departments[$dep['group_name']]['name'] = $dep['group_name'];
					$departments[$dep['group_name']]['id'] = $dep['group_id'];
				}
			}
			ksort($departments);

			$this->cache->save('who-departments'.$ver, $departments, 86400);
		}

		return array('departments' => $departments);
	}

	/**
	 * returns the user's department and sub-department
	 * @param $user_id
	 * @return mixed array or false on error
	 */
	function getUserDepartments($user_id){
		$query = $this->db
			->select('groups.group_name AS department, groups.group_id AS department_id, g.group_name AS parent_dep,
				g.group_id AS parent_dep_id')
			->from('groups')
			->join('groups g', 'groups.group_parent = g.group_id', 'left')
			->join('groups_users rel', 'rel.group_id=groups.group_id')
			->where('groups.group_type', 'department')
			->where('rel.user_id', $user_id)
			->get();

		if ($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return false;
		}
	}

	/**
	 * Get the members for a group
	 * @param int $gid
	 * @param null $name
	 * @return mixed returns the members array for a group or false on error
	 */
	function members($gid, $name = null){
		if (isset($name)){
			$query = $this->db
					->select('group_id')
					->where('group_name LIKE', "%$name%")
					->where('group_type', 'department')
					->get('groups');
			if ($query->num_rows() > 0){
				$tmp = $query->result_array();
				$gid = array();
				foreach($tmp as $id){ $gid[] = $id['group_id']; }
				$gid = implode(",", $gid);
			}else{
				return false;
			}
		}

		$query = $this->db->select('u.user_id, u.username, u.display_name, u.email, u.title, u.phonenumber, u.fax, u.status,
				groups.group_name AS department, groups.group_id AS department_id,
				g.group_name AS parent_dep, g.group_id AS parent_dep_id')
			->from('users u')
			->join('groups_users rel', 'u.user_id=rel.user_id')
			->join('groups', 'groups.group_id=rel.group_id')
			->join('groups g', 'g.group_id=groups.group_parent', 'left')
			->where("(rel.group_id IN ($gid)")
			->or_where("rel.group_id IN (SELECT group_id FROM fn_groups WHERE group_parent IN ($gid)))")
			->where('u.hidden', '0')
			->order_by('u.display_name')
			->get();

		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}

	/**
	 * Return true if the logged in user belongs to $group_name
	 * @param $group_name
	 * @return boolean
	 */
	function is_memberOf($group_name){
		$user_id = $this->session->userdata('user_id');

		$q = $this->db
			->select('rel.group_id')
			->from('groups_users rel')
			->join('groups', 'rel.group_id=groups.group_id')
			->where('rel.user_id', $user_id)
			->like('groups.group_name', $group_name, 'after')
			->get();

		//echo $this->db->last_query();

		return ($q->num_rows() > 0);
	}
}

