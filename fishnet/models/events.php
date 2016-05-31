<?php
/**
 * @author cravelo
 * @date Jun 25, 2010
 * @time 4:51:57 PM
 */

/**
 * The events model
 * @author Carlos Ravelo
 * @package Models
 */
class Events extends CI_Model {
	/**
	 * Handles monthly recurrence taking into consideration the special case of february
	 * @param string $format strtotime compatible format string
	 * @param timestamp $timestamp
	 * @param timestamp $start_date start date for relative formats (last week)
	 * @return int
	 */
	static function myStrToTime($format, $timestamp, $start_date){
		if (preg_match('/\+.*month$/i', trim($format))){
			$month = array();
			preg_match('/\d+/', trim($format), $month);
			$month = $month[0];
			if ((((date("n", $timestamp) + $month) % 12) == 2) and (date("j", $timestamp) > 28)){//if month is before feb and day is over 28 then pick the last day of february
				$year = date("Y", $timestamp);
				$year = (date("n", $timestamp) > 2) ? $year + 1 : $year;
				return strtotime("last day of February-$year");
			}elseif (date("n", $timestamp) == 2){//if the month is february then we have to adjust for the correct day again for march
				return strtotime(date("Y-".((2 + $month) % 12)."-".date("j", strtotime($start_date)), $timestamp));
			}else{
				return strtotime($format, $timestamp);//otherwise just run it by the default PHP function
			}
		}
		else{
			return strtotime($format, $timestamp);
		}
	}

	/**
	 * Get all events published to a calendar ($cal_id) that fall within the date range ($d_start to $d_end)
	 * @param int $cal_id
	 * @param string $d_start
	 * @param string $d_end
	 * @return array
	 */
	function get($cal_id, $d_start, $d_end){
		$events = array();
		$start = strtotime($d_start);
		$end = strtotime($d_end);

		$query = $this->db->select('events.*')
				->from('events')
				->join('pages_events rel', 'rel.event_id=events.event_id')
				->where('rel.page_id', $cal_id)
				->where("events.start_date <= '$d_end'")
				->where('(fn_events.end_date >=', $d_start)
				->or_where('events.end_date IS NULL)')
				->get();
		//echo $this->db->last_query();

		$result = $query->result();
		foreach ($result as $event){
			//if the event didnt end already
			if (($event->end_date == null) or (strtotime($event->end_date) >= $start)){
				$e_start = strtotime($event->start_date);
				$e_end = strtotime($event->end_date);

				//simple events
				if ($event->rec_factor == null){
					//if the event falls inside my range.
					//if (($e_start >= $start) and ($e_start <= $end) and  ){
						$events[] = $event;
					//}
				}else{//if is recurrent
					$current_date = $e_start;
					while(($current_date <= $end) and (!$e_end or ($current_date <= $e_end))){//while the recurrence date is within range and occurs before the event's end_date.
						if ($current_date >= $start){
							if (empty($event->rec_rule)){
								$event->start_date = date("Y-m-d", $current_date);
							}else{
								$event->start_date = date("Y-m-d", strtotime(strftime($event->rec_rule, $current_date)));
							}

							$event->end_date = $event->start_date;
							$events[] = clone($event);
						}

						if (!empty($event->rec_rule)){
							$event->start_date = date("Y-m-d", strtotime(strftime($event->rec_rule, $current_date)));
						}

						$current_date = Events::myStrToTime($event->rec_factor, $current_date, $event->start_date);
					}
				}
			}
		}

		/**
		 * sort events by start_date
		 *
		 * @param $a
		 * @param $b
		 * @return int
		 */
		function event_cmp($a, $b){
			$A = strtotime($a->start_date);
			$B = strtotime($b->start_date);
			if ($A == $B){
				return 0;
			}
			return ($A < $B) ? -1 : 1;
		}
		usort($events, "event_cmp");

		return $events;
	}

	/**
	 * @param $event_id
	 * @return null
	 */
	function getByID($event_id){
		$query = $this->db
				->select("events.*")
				->from("events")
				->where('events.event_id', $event_id)
				->get();

		if ($query->num_rows() > 0){
			$event = $query->row();
			$event->next_occurrence = null;
			$today = strtotime(date("Y-m-d"));//Im only interested in today's date not the time
			$e_end = strtotime($event->end_date);
			$current_date = strtotime($event->start_date);

			if ($event->rec_factor != null){
				if ($current_date >= $today){
					if (empty($event->rec_rule)){
						$event->next_occurrence = date("Y-m-d", $current_date);
					}else{
						$event->next_occurrence = date("Y-m-d", Events::myStrToTime(strftime($event->rec_rule, $current_date), $current_date, $event->start_date));
					}
				}else{
					while(($current_date <= $today) and (!$e_end or ($current_date < $e_end))){
						if (isset($event->rec_rule)){
							$current_date = Events::myStrToTime(strftime($event->rec_rule, $current_date), $current_date, $event->start_date);
						}

						if ($current_date <= $today){
							$current_date = Events::myStrToTime($event->rec_factor, $current_date, $event->start_date);
						}
					}

					if (isset($event->rec_rule)){
						$current_date = Events::myStrToTime(strftime($event->rec_rule, $current_date), $current_date, $event->start_date);
					}

					$event->next_occurrence = date("Y-m-d", $current_date);
				}
			}

			// Sections
			$event->sections = array();
			$query = $this->db
				->select("rel.page_id, pages.title as calendar_title")
				->from("pages_events rel")
				->join("pages", "pages.page_id=rel.page_id")
				->where('rel.event_id', $event_id)
				->get();

			$sections = $query->result_array();
			for ($i = 0; $i < count($sections); $i++){
				$event->sections[$sections[$i]['calendar_title']] = $sections[$i]['page_id'];
			}

			return $event;
		}
		else { return null; }
	}

	/**
	 * @param $event
	 * @return bool
	 */
	function newEvent($event){
		$this->db->insert("events", $event);

		if ($this->db->affected_rows() > 0){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}

	/**
	 * @param $event
	 * @return bool
	 */
	function update($event){
		$event_id = $event['event_id'];
		unset($event['event_id']);

		return $this->db->where('event_id', $event_id)->update("events", $event);
	}

	/**
	 * @param $id
	 * @return bool
	 */
	function delete($id){
		$this->db->where('event_id', $id)->delete('events');

		return ($this->db->affected_rows() > 0);
	}

	/**
	 * Adds delta days or delta minutes to an event
	 * @param $e
	 *
	 * @return bool
	 */
	function move($e){
		if ($e['dayDelta'] !== 0){
			$this->db
				->set("start_date", "start_date + INTERVAL {$e['dayDelta']} DAY", false)
				->set("end_date", "end_date + INTERVAL {$e['dayDelta']} DAY", false);
		}
		if ($e['minuteDelta'] !== 0){
			$this->db
				->set("start_time", "TIME(DATE_ADD(CONCAT('1985-11-08 ', start_time),
						INTERVAL {$e['minuteDelta']} MINUTE))", false)
				->set("end_time", "TIME(DATE_ADD(CONCAT('1985-11-08 ', end_time),
						INTERVAL {$e['minuteDelta']} MINUTE))", false);
		}

		return $this->db
			->set('event_id', $e['event_id'])//in case none of the above gets executed
			->where('event_id', $e['event_id'])
			->update('events');
	}

	/**
	 * Adds delta days or delta minutes to an event
	 * @param $e
	 *
	 * @return bool
	 */
	function resize($e){
		if ($e['dayDelta'] !== 0){
			$this->db
				->set("end_date", "end_date + INTERVAL {$e['dayDelta']} DAY", false);
		}
		if ($e['minuteDelta'] !== 0){
			$this->db
				->set("end_time", "TIME(DATE_ADD(CONCAT('1985-11-08 ', end_time),
						INTERVAL {$e['minuteDelta']} MINUTE))", false);
		}

		return $this->db
			->set('event_id', $e['event_id'])//in case none of the above gets executed
			->where('event_id', $e['event_id'])
			->update('events');
	}

	/**
	 * Gets events that are not published to any calendar where the creator is the $user_id passed in
	 * @param $user_id
	 * @return array
	 */
	function orphaned($user_id){
		$query = $this->db
				->select('events.*')
				->join('pages_events rel', 'events.event_id=rel.event_id', 'left')
				->where('creator', $user_id)
				->where('rel.page_id IS NULL')
				->get('events');

		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return array();
		}
	}
}
