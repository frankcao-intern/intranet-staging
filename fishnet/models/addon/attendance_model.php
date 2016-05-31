<?php
/**
 * @author cravelo
 * @date   2/24/12
 * @time   8:59 AM
 */

/**
 * daysout
 * @package Models
 * @author  cravelo
 * @property CI_DB_active_record $db
 * @property CI_Loader $load
 * @property Users $users
 * @property CI_Config $config
 */
class Attendance_model extends CI_Model {
	/**
	 * get all day types
	 * @return array
	 */
	function getDayTypes(){
		$query = $this->db->order_by('name')->get('day_types');

		return $query->result_array();
	}

	/**
	 * Get all records for a given year
	 * @param int $year
	 * @param int $user_id
	 * @return array|bool
	 */
	public function getYear($year, $user_id){
		if (isset($year) and isset($user_id)){
			$year = preg_replace('/[^\d]+/', '', $year);//clean up
			$user_id = preg_replace('/[^\d]+/', '', $user_id);//clean up

			$query = $this->db
				->select('MONTH(i.date) AS month, DAY(i.date) AS day, d.short_name AS type, d.name, i.status, i.user_id')
				->from('daysout i')
				->join('day_types d', 'i.day_type_id=d.short_name')
				->where('YEAR(i.date)', $year)
				->where('i.user_id', $user_id)
				->get();

			$days = array();
			if ($query){
				$result = $query->result_array();
				foreach($result as $row){
					$days[$row['month']][$row['day']] = array(
						'type' => $row['type'],
						'name' => $row['name'],
						'status' => $row['status']
					);
				}
			}

			//add today
			if (date('Y') == $year){
				$days[date('n')][date('j')] = array('type' => 'Today', 'name' => 'Today', 'status' => 'today');
			}

			$this->load->model('users');
			$user = $this->users->get($user_id);

			$months = array();
			$query = $this->db
				->select('m.month, m.status')
				->from('daysout_months m')
				->where('m.year', $year)
				->where('m.user_id', $user_id)
				->get();

			if ($query){
				$result = $query->result_array();
				foreach($result as $row){
					$months[$row['month']] = $row['status'];
				}
			}

			return array(
				'days' => $days,

				'display_name' => val($user[0]['display_name']),
				'user_id' => val($user_id),
				'months' => $months
			);
		}

		return false;
	}

	/**
	 * @param int $request_id
	 * @return array|bool
	 */
	function getSubmitted($request_id){
		if (isset($request_id)){
			$request_id = preg_replace('/[^\d]+/', '', $request_id);//clean up

			$query = $this->db
				->select('u.display_name, i.date, d.name, d.short_name AS type, i.user_id')
				->from('daysout i')
				->join('day_types d', 'i.day_type_id=d.short_name')
				->join('users u', 'u.user_id=i.user_id')
				->where('i.status', 'pending')
				->where('i.request_id', $request_id)
				->get();

			if ($query->num_rows() > 0){
				return $query->result_array();
			}
		}

		return false;
	}

	/**
	 * @param int $request_id
	 * @return array|bool
	 */
	function getMonth($request_id){
		if (isset($request_id)){
			$query = $this->db
				->select('m.*, u.display_name')
				->from('daysout_months m')
				->join('users u', 'u.user_id=m.user_id')
				->where('m.request_id', $request_id)
				->get();

			if ($query and ($query->num_rows() > 0)){
				$user = $query->row_array();
				$user['month_name'] = date('F', strtotime("{$user['year']}-{$user['month']}-01"));

				$query = $this->db
					->select('i.date, d.name, d.short_name AS type, i.status')
					->from('daysout i')
					->join('day_types d', 'd.short_name=i.day_type_id')
					->where('i.request_id', $request_id)
					->get();

				if ($query){
					$result['days'] = $query->result_array();
					$result['user'] = $user;

					return $result;
				}
			}
		}

		return false;
	}

	/**
	 * @param array $items
	 * @param int $user_id
	 * @return bool
	 */
	function insert($items, $user_id){
		$this->db->trans_start();

		foreach ($items as $item){
			$date = date('Y')."-{$item['month']}-{$item['day']}";

			$query = $this->db
				->select('user_id')
				->from('daysout')
				->where('date', $date)
				->where('user_id', $user_id)
				->limit(1)
				->get();

			if ($query->num_rows() > 0){
				$this->db
					->where('date', $date)
					->where('user_id', $user_id)
					->set('day_type_id', $item['type'])
					->set('request_id', null)
					->set('status', 'planned')
					->update('daysout');
			}else{
				$this->db->insert('daysout', array(
					'user_id' => $user_id,
				    'day_type_id' => $item['type'],
				    'date' => $date
				));
			}
		}

		$this->db->trans_complete();

		return $this->db->trans_status();

	}

	/**
	 * @param array $items
	 * @param int $user_id
	 * @return bool
	 */
	function delete($items, $user_id){
		$date = array();

		foreach ($items as $item){
			$date[] = date('Y')."-{$item['month']}-{$item['day']}";
		}

		return $this->db
			->from('daysout')
			->where("date IN ('".implode("','", $date)."')")
			->where('user_id', $user_id)
			->delete();
	}

	/**
	 * @param array $dates
	 * @param int $user_id
	 * @return bool|int
	 */
	function submit($dates, $user_id){
		$timestamp = time();

		$this->db
			->set('status', 'pending')
			->set('request_id', $timestamp)
			->where("date IN ('".implode("','", $dates)."')")
			->where('user_id', $user_id);

		if ($this->db->update('daysout') === false){
			return false;
		}else{
			return $timestamp;
		}
	}

	/**
	 * @param int $month_ts
	 * @param int $user_id
	 * @return bool|int
	 */
	function submitMonth($month_ts, $user_id){

		if (isset($month_ts) and ($month_ts !== false) and isset($user_id) and is_numeric($user_id)){
			$month = date('n', $month_ts);
			$year = date('Y', $month_ts);

			$dates = array();
			for ($i = 1; $i <= 31; $i++){
				$dates[] = "$year-$month-$i";
			}

			$this->db->trans_start();

			$timestamp = $this->submit($dates, $user_id);

			$this->db->query("INSERT INTO `fn_daysout_months` (`user_id`, `month`, `year`, `request_id`)
					VALUES (?, ?, ?, ?)
					ON DUPLICATE KEY UPDATE `status`='pending', `request_id`=?",
			                 array($user_id, $month, $year, $timestamp, $timestamp));

			if ($this->db->trans_complete() !== false){
				return $timestamp;
			}
		}

		return false;
	}

	/**
	 * @param int $request_id
	 * @param string $status
	 * @return bool
	 */
	function changeStatus($request_id, $status){
		if (isset($request_id)){
			$request_id = preg_replace('/[^\d]+/', '', $request_id);//clean up

			$this->db
				->set('status', $status)
				->set('request_id', null)
				->where('request_id', $request_id)
				->update('daysout');

			return ($this->db->affected_rows() > 0);
		}else{
			return false;
		}
	}

	/**
	 * @param int $request_id
	 * @param string $status
	 * @return bool
	 */
	function changeMonthStatus($request_id, $status){
		if (isset($request_id)){
			$request_id = preg_replace('/[^\d]+/', '', $request_id);//clean up

			$this->db->trans_start();

			$this->db
				->set('status', $status)
				->set('request_id', null)
				->where('request_id', $request_id)
				->update('daysout_months');

			$this->changeStatus($request_id, $status);

			return $this->db->trans_complete();
		}else{
			return false;
		}
	}

	/**
	 * Get TYD report on non-compliant users
	 * @param int $year
	 * @return array|bool
	 */
	public function findNonCompliant($year){ //TODO
		if (!isset($year)){ return false; }

		$year = preg_replace('/[^\d]+/', '', $year);//clean up
		$months = array();

		$query = "SELECT *  FROM (SELECT u.display_name AS `Full Name`, u.person_id AS `HR ID`, u.payroll_id AS `Payroll ID`";
		$where = "WHERE 1=1 ";
		for ($i = 1; $i < date('n'); $i++){
			$month = date('F', mktime(0, 0, 0, $i, 1, $year));
			$months[] = $month;
			$query .= ", (BIT_OR(m.month=$i AND m.status='confirmed')) as $month ";
			$where .= "OR e.$month=0 ";
		}
		$query .= "FROM fn_users u
			JOIN fn_daysout_months m ON u.user_id=m.user_id
			WHERE m.year=$year
			GROUP BY m.user_id) as e
			$where";

		$query = $this->db->query($query);

		//echo $this->db->last_query(); die();

		return $query->result_array();
	}

	/**
	 * Get YTD attendance for everyone
	 * @param int $year
	 * @return array|bool
	 */
	public function getDates($year){
		if (!isset($year)){ return false; }

		$year = preg_replace('/[^\d]+/', '', $year);//clean up

		/**
		 * @var CI_DB_result $day_types
		 */
		$day_types = $this->db->get('day_types');
		if ($day_types and $day_types->num_rows() > 0){
			$day_types = $day_types->result_array();
		}else{
			$day_types = array();
		}

		$this->db->select('u.display_name AS `Full Name`, u.person_id AS `HR ID`, u.payroll_id AS `Payroll ID`');
		foreach ($day_types as $type){
			$this->db->select("COUNT(CASE d.short_name WHEN '{$type['short_name']}' THEN 1 END) AS `{$type['name']}`");
		}

		$query = $this->db
			->from('daysout i')
			->join('day_types d', 'i.day_type_id=d.short_name')
			->join('users u', 'u.user_id=i.user_id')
			->where('i.status', 'confirmed')
			->where('YEAR(i.date)', $year)
			->group_by('i.user_id')
			->get();

		//echo $this->db->last_query(); die();

		return $query->result_array();
	}
}
