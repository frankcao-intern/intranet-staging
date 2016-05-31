<?php
/**
 * @author cravelo
 */

/**
 * Users model
 * @package Models
 * @author cravelo
 * @property CI_Config $config
 * @property CI_Cache $cache
 */
class Users extends CI_Model {
	/**
	 * Try to get user record and if it doesn't exist create it
	 * If it existed first_time = false if it had to create a new record first_time = true
	 * @param array $userRecord
	 * @param array $memberof
	 * @return int the user_id for the logged in user
	 */
	function login($userRecord, $memberof){
		//print_r($userRecord);
		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db
				->where('username', $userRecord['username'])
				->get('users');

		if ($query->num_rows() == 0){
			//create user record and get the user_id
			$this->db->insert('users', $userRecord);
			$user_id = $this->db->insert_id();

			//insert the new group with the same name as the username
			$this->db->insert('groups', array(
				'group_name' => $userRecord['display_name']
			));

			//get groups
			$group_config = $this->config->item('groups_config');
			$default_groups = $group_config['group_names'];
			$groups_to_insert = array();
			$groups_to_insert[] = array(
				'group_id' => $this->db->insert_id(),
				'user_id' => $user_id
			);
			for ($i = 0; $i < count($default_groups); $i++){
				$matches = preg_grep("/CN\=\*?".$default_groups[$i]."\,OU\=.*/i", $memberof);
				if (count($matches) != 0){
					$groups_to_insert[] = array(
						'group_id' => $group_config[$default_groups[$i]]['id'],
						'user_id' => $user_id
					);
				}
			}

			//add the user to the group
			$this->db->insert_batch('groups_users', $groups_to_insert);

			$first_time = true;
		}else{
			$user = $query->result_array();
			$user_id = $user[0]['user_id'];

			$this->db
				->where('user_id', $user_id)
				->set('last_login', date("Y-m-d h:i:s"))
				->set('fax', val($userRecord['fax'], null))
				->set('email', val($userRecord['email'], null))
				->update('users');

			$first_time = false;
		}

		$user = $this->get($user_id, null, true);
		$user[0]['first_time'] = $first_time;
		return $user[0];
	}

	/**
	 * @param int|null $id you can either pass a user_id
	 * @param string|null $user_name or a username
	 * @param bool $login whether we are login in or not
	 * @return mixed user record or false on failure
	 */
	function get($id, $user_name = null, $login = false){
		//if the current logged in user is requesting his/her own profile then its fine to show hidden users
		if (isset($id)){
			if (($id != $this->session->userdata('user_id')) and !$login){
				$this->db->where('hidden', 0);
			}
			$this->db->where('user_id', $id);
		}else{
			if ($user_name != $this->session->userdata('username')){
				$this->db->where('hidden', 0);
			}
			$this->db->where_in('username', $user_name);
		}

		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db->get('users');

		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}

	/**
	 * Update the user record
	 * @param $id
	 * @param $user_name
	 * @param $data
	 * @return bool
	 */
	function update($id, $user_name, $data){
		if (isset($id)){
			$this->db->where("user_id", $id);
		}else{
			$this->db->where("username", $user_name);
		}

		$this->db->update("users", $data);

		return ($this->db->_error_number() == 0);
	}

	/**
	 * query the users table for a $qkey = $q with optional $limit
	 * @param      $qkey
	 * @param      $q
	 * @param null $limit
	 * @return array
	 */
	function query($qkey, $q, $limit = null){
		$q = str_replace('*', '%', $q);
		$this->db
			->select('u.user_id, u.username, u.display_name, u.email, u.title, u.phonenumber, u.fax, u.location,
					u.status')
			->from('fn_users u')
			->where("u.$qkey LIKE", "$q%")
			->where('u.hidden', 0)
			->order_by('u.display_name');
		if (isset($limit)) { $this->db->limit($limit, 0); }

		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db->get();

		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return array();
		}
	}

	/**
	 * Performs a search on the users table for $q
	 * on the fields: display_name, first_name, last_name, location, job_overview, email, phone, fax, title
	 * @param      $q
	 * @param null $limit
	 * @return array
	 */
	function search($q, $limit = null){
		$q = str_replace('*', '%', $q);

//		$query = $this->db->select('u.user_id, u.username, u.display_name, u.email, u.title, u.phonenumber, u.fax, u.status,
//				groups.group_name AS department, groups.group_id AS department_id,
//				g.group_name AS parent_dep, g.group_id AS parent_dep_id')
//			->from('users u')
//
//			->where("(rel.group_id IN ($gid)")
//			->or_where("rel.group_id IN (SELECT group_id FROM fn_groups WHERE group_parent IN ($gid)))")
//			->where('u.hidden', '0')
//			->order_by('u.display_name')
//			->get();

		$this->db
			->select('u.user_id, u.username, u.display_name, u.email, u.title, u.phonenumber, u.fax, u.location,
					u.status, g.group_name AS department, g.group_id AS department_id,
					parent.group_name AS parent_dep, parent.group_id AS parent_dep_id')
			->from('fn_users u')
			->join('groups_users rel', 'u.user_id=rel.user_id', 'left')
			->join('groups g', 'g.group_id=rel.group_id', 'left')
			->join('groups parent', 'parent.group_id=g.group_parent', 'left')
			->where("u.first_name LIKE", "$q%")
			/**->or_where("u.email LIKE", "$q%")
			->or_where("u.title LIKE", "%$q%")
			->or_where("u.phonenumber LIKE", "%$q%")
			->or_where("u.fax LIKE", "%$q%")
			->or_where("u.cellphone LIKE", "%$q%")
			->or_where("u.location LIKE", "%$q%")
			->or_where("u.joboverview LIKE", "%$q%")
			->or_where("u.extra_contact_info LIKE", "%$q%")
			->or_where("u.status LIKE", "%$q%")
			->or_where('g.group_name LIKE', "%$q%")*/
			//->or_where('parent.group_name LIKE', $this->db->escape("%$q%").")", FALSE)
			->where('u.hidden', 0)
			->where('u.display_name !=', '')
//			->where('g.group_type', 'department')
			->group_by('u.user_id')
			->order_by('u.display_name');

		if (isset($limit)) { $this->db->limit($limit, 0); }

		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db->get();
//		echo $this->db->last_query();

		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return array();
		}
	}

	/**
	 *
	 * @param string $qkey the DB field to list
	 * @param string $q part of the value to filter
	 * @return array
	 */
	function getlist($qkey, $q){
		$q = str_replace('*', '%', $q);
		$this->db
			->select("u.$qkey")
			->distinct()
			->from('users u')
			->where("u.$qkey LIKE", "$q%")
			->where('u.hidden', 0)
			->order_by("u.$qkey")
			->limit(15);

		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db->get();

		if ($query->num_rows() > 0){ return $query->result_array(); }else{ return array(); }
	}

	/**
	 * @param string $user_name the username
	 * @return string full path to the user's profile picture
	 */
	function getUserPicture($user_name){
		$user_name = strtolower((isset($user_name) and !empty($user_name)) ? $user_name : 'default');

		return site_url('images/profile/'.$user_name);
	}

	/**
	 * get anniversaries for the current week.
	 * @return array to be merged with page_date
	 */
	function getWeekAnniversaries(){
		$week_start = strtotime("last sunday", time());
		$week_end = strtotime("next saturday", time());

		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		if ($this->cache->apc->is_supported()){
			$anniversaries = $this->cache->apc->get('anniversaries-'.FISHNET_VERSION);
		}else{
			$anniversaries = $this->cache->file->get('anniversaries-'.FISHNET_VERSION);
		}

		if(!$anniversaries){
			/**
			 * @var CI_DB_result $query
			 */
			$query = $this->db->query("
				SELECT * FROM(
					SELECT user_id,	username, display_name, start_date, hidden,
						YEAR(NOW()) - YEAR(start_date) AS years_served,
						STR_TO_DATE(CONCAT_WS('-', YEAR(NOW()), MONTH(start_date), DAY(start_date)), '%Y-%m-%d') AS anniv
					FROM `fn_users`
					ORDER BY start_date ASC
				) as u WHERE anniv >= '".date("Y-m-d", $week_start)."' AND anniv <= '".date("Y-m-d", $week_end)."'
				AND years_served > 0 AND hidden = 0
			");

			//echo $this->db->last_query();

			$anniversaries = $query->result_array();

			$this->cache->save('anniversaries-'.FISHNET_VERSION, $anniversaries, 10080);
		}

		return array('anniversaries' => $anniversaries);
	}


	/**
	 * Function to retrieve the user record for a user's leader
	 * @param $user_id
	 * @return array|bool the leader info or false on failure
	 */
	function getLeader($user_id){
		if (isset($user_id) and is_numeric($user_id)){
			/**
			 * @var CI_DB_result $query
			 */
			$query = $this->db
				->select('ldr.*')
				->from('users u')
				->join('users ldr', 'u.leader=ldr.person_id')
				->where('u.leader IS NOT NULL')
				->where('ldr.person_id IS NOT NULL')
				->where('u.user_id', $user_id)
				->get();

			if ($query and ($query->num_rows() > 0)){
				return $query->row_array();
			}
		}

		return false;
	}

	/**
	 * Returns a list of user_ids of the person's direct reports
	 * @param $person_id
	 * @return array
	 */
	function getDirectReports($person_id){
		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db->select('user_id, display_name')->where('leader', $person_id)->get('users');

		if ($query and ($query->num_rows() > 0)){
			return $query->result_array();
		}

		return array();
	}
}
