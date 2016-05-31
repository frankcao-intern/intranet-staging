<?php
/**
 * @author cravelo
 * @date Jun 25, 2010
 * @time 4:50:33 PM
 */

require_once dirname(__FILE__).'/article.php';

/**
 * Calendar controller
 * @author cravelo
 * @package Controllers
 * @property Events $events
 */
class Calendar extends Article {
	/**
	 * @param array $event the event array as a reference
	 * @param string $key  the key within the array that contains the article text
	 * @param int $count the required word count
	 * @return the modified $event array
	 * This function is a callback to be used with array_walk, is used to truncate event text to the desired amount of words
	 */
	static function truncateEvent(&$event, $key, $count){
		//print_r($event);
		//truncate description
		if (!isset($event->event_desc)){
			$event->event_desc = "...";
		}else{
			$words = explode(" ", strip_tags($event->event_desc), $count + 1);
			if (count($words) > $count)
				unset($words[count($words) - 1]);
			$event->event_desc = implode(" ", $words)." ...";
		}

		return $event;
	}

	/**
	 * JSON Feed for the homepage calendar
	 * @todo Add error checking here and in the model.
	 * @param int $cal_id
	 * @param string $date 00-0000 month and year
	 * @return void echoes the JSON structure with the events
	 * */
	function json($cal_id, $date){
		$this->load->model('events');
		$today = ($date == 'null') ? getdate() : getdate(strtotime("01-".$date));
		$start = mktime(0, 0, 0, $today['mon'], 1, $today['year']);

		//get this month's events
		$d_start = date('Y-m-d H:i:s', $start);
		$d_end = date('Y-m-d H:i:s', strtotime("31 days", $start));
		$events = $this->events->get($cal_id, $d_start, $d_end);
		$events = array_reverse($events);//its sorted by date for the web, for rss we need the inverse order.

		//walk thru the results to form the news array.
		$jsonArr = array();
		foreach ($events as $event){
			//var_dump($event);
			$jsonArr[] = array(
				'title' => $event->event_title,
				'link' => site_url("/event/".$event->event_id),
				'start_date' => $event->start_date,
				'end_date' => $event->start_date,
				'start_time' => $event->start_time,
				'end_time' => $event->end_time,
				'where' => $event->where,
				'description' => $event->event_desc,
				'allDay' => (boolean) $event->all_day
			);
		}

		$this->result->isError = false;
		$this->result->data = $jsonArr;

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	/**
	 * JSON Feed for the big calendars calendar
	 * @todo Add error checking here and in the model.
	 * @param int $cal_id
	 * @param int $start timestamp
	 * @param int $end timestamp
	 * @return void
	 */
	function fullcal_json($cal_id, $start, $end){
		$d_start = date('Y-m-d 00:00:00', $start);
		$d_end = date('Y-m-d 11:59:59', $end);
		//echo $d_start;

		$this->load->model('events');
		$events = $this->events->get($cal_id, $d_start, $d_end);

		$this->load->model('revisions');
		$rev = $this->revisions->getLatestRevision($cal_id);
		$color = val($rev['revision_text']['color'], '#000');

		$this->load->model('pages');
		$cal_name = $this->pages->getPageProperty($cal_id, 'title');

		//walk thru the results to form the result object.
		$jsonArr = array(
			'events' => array(),
			'cal_id' => $cal_id,
			'cal_name' => $cal_name,
			'color' => $color
		);
		foreach ($events as $event)
		{
			//var_dump($event);
			$jsonArr['events'][] = array(
				'id' => $event->event_id,
				'title' => $event->event_title,
				'url' => site_url("/event/".$event->event_id),
				'start' => date("Y-m-d\TH:i:s\Z", strtotime($event->start_date.' '.$event->start_time)),
				'end' =>   date("Y-m-d\TH:i:s\Z", strtotime($event->end_date.' '.$event->end_time)),
				'allDay' => (boolean) $event->all_day,
				'color' => $color
			);
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($jsonArr));
	}

	/**
	 * This function will output an ATOM feed of the child pages to be able to subscribe to the news
	 * @param int $cal_id
	 * @return void echoes the RSS feed content
	 */
	function rss($cal_id){
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<rss version=\"2.0\">\n<channel>\n";
		$xml .= "<title>Company Events</title>\n";
		$xml .= "<link>".site_url("/calendar/rss/".$cal_id)."</link>\n";
		$xml .= "<description>Two months woth of events from EF</description>\n";
		$xml .= "<language>en-us</language>\n";
		$xml .= "<copyright>Copyright EILEEN FISHER Inc. ".date('Y').". All Rights Reserved.</copyright>\n";
		$xml .= "<pubDate>".date("D, d M Y H:i:s T", time())."</pubDate>\n";
		$xml .= "<lastBuildDate>".date("D, d M Y H:i:s T", time())."</lastBuildDate>\n";
		$xml .= "<generator>FishNET</generator>\n";
		$xml .= "<webMaster>cravelo@eileenfisher.com (Carlos Ravelo)</webMaster>\n";

		$this->load->model('events');
		$today = getdate();
		$start = mktime(0, 0, 0, $today['mon'], 1, $today['year']);

		//get this month's events
		$d_start = date('Y-m-d H:i:s', strtotime("-1 month +1 day", $start));//not sure how good this will work but it doesnt matter for the feed
		$d_end = date('Y-m-d H:i:s', strtotime("+1 month -1 day", $start)); //this will work even for leap years
		$events = $this->events->get($cal_id, $d_start, $d_end);
		$events = array_reverse($events);//its sorted by date for the web, for rss we need the inverse order.

		//walk thru the results to form the news array.
		for ($i = 0; $i < count($events); $i++)
		{
			$xml .= "<item>\n";
			$xml .= "<title>".htmlspecialchars($events[$i]->event_title, 2, 'UTF-8')."</title>\n";
			$xml .= "<link>".site_url("event/".$events[$i]->event_id)."</link>\n";
			$xml .= "<description>".htmlspecialchars($events[$i]->event_desc, 2, 'UTF-8')."</description>\n";
			$xml .= "<pubDate>".date("D, d M Y H:i:s T", strtotime($events[$i]->start_date." ".$events[$i]->start_time))."</pubDate>\n";
			$xml .= "<guid>".site_url("event/".$events[$i]->event_id."/".rand())."</guid>\n";
			$xml .= "</item>\n";
		}

		$xml .= "</channel>\n</rss>\n";

		$this->output
            ->set_content_type('application/rss+xml')
            ->set_output($xml);
	}

	/**
	 * Full calendar Ajax handler to update an event
	 */
	function moveEvent(){
		$fc_event = json_decode(base64_decode($this->input->post('event')), true);
		//print_r($fc_event);

		$this->load->model('events');
		if (!$this->events->move($fc_event)){
			$this->result->isError = true;
			$this->result->errorStr = "There was an error updating the event. Try again later.";
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($this->result));
	}

	/**
	 * Full calendar Ajax handler to update an event
	 */
	function resizeEvent(){
		$fc_event = json_decode(base64_decode($this->input->post('event')), true);
		//print_r($fc_event);

		$this->load->model('events');
		if (!$this->events->resize($fc_event)){
			$this->result->isError = true;
			$this->result->errorStr = "There was an error updating the event. Try again later.";
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($this->result));
	}

	/**
	 * Loads the event details page
	 * @param int $event_id
	 * @param string $layout
	 *
	 * @return void load event view or show 404 error
	 */
	function loadevent($event_id, $layout = 'default'){
		$this->load->model('events');
		$event = $this->events->getByID($event_id);

		if ($event != null){
			$page_data = array();
			$page_data['template_name'] = "sys_event";

//			if (($event->creator == $this->session->userdata('user_id'))){
			if (($event->creator == $this->session->userdata('user_id')) or
					(strtolower($this->session->userdata('role')) == 'admin')){
				$page_data['canWrite'] = true;
				$page_data['canDelete'] = true;
			}

			$this->setupBreadbrumbs($page_data);

			$page_data['canProp'] = true; //allow all users to publish the event to their own calendars
			$page_data['revision'] = false;
			$page_data['page_type'] = 'event';
			$page_data['category'] = 'events';
			$page_data['page_id'] = "event/".$event->event_id;
			$page_data['title'] = 'Calendar Event - '.$event->event_title;
			$page_data['event'] = $event;
			$page_data[$layout] = true;

			$this->load->view("layouts/$layout", $page_data);
		}else{
			show_404();
		}
	}

	/**
	 * Same as article/properties but for events
	 * @param int $event_id the event id to load the properties for.
	 * @return void it loads the view.
	 */
	function eventprops($event_id){
		$this->load->helper('form');

		$this->pageRecord['properties'] = true;

		if ($this->pageRecord !== false){
			$this->pageRecord['page_id'] = $event_id;
			$this->pageRecord['title'] = "Event Properties";

			//get sections where user has publish permission
			$this->load->model("pages");
			$this->pageRecord['sections'] = $this->pages->getPublishForEvent($this->session->userdata('user_id'), $event_id);

			//load the page
			$this->pageRecord['template_name'] = 'properties_event';
			$this->load->view('layouts/default', $this->pageRecord);
		}else{
			show_error("There was an error retrieving this event. Reload the page, if the problem persists call the Helpdesk at x4024.");
		}
	}

	/**
	 * Load the new event form
	 * @param array $page_data to allow this function to be called from edit mode with extra data
	 *
	 * @return void
	 */
	function neweventform($page_data = array()){
		$this->load->helper('form');

		$page_data['template_name'] = "sys_new_event";
		$page_data['revision'] = false;
		$page_data['page_type'] = 'event';
		$page_data['page_id'] = isset($page_data['page_id']) ? $page_data['page_id'] : 0;
		$page_data['title'] = isset($page_data['title']) ? $page_data['title'] : 'Calendar - New event';

		$this->setupBreadbrumbs($page_data);

		//load the list of calendars this user has access to
		$this->load->model("pages");
		$page_data['sections'] = $this->pages->getPublishForEvent($this->session->userdata('user_id'),$page_data['page_id']);

		$this->load->view('layouts/default', $page_data);
	}

	/**
	 * Load an event in edit mode
	 * @param int $event_id
	 * @return void
	 */
	function editevent($event_id){
		$this->load->helper('form');

		$page_data = array();

		$this->load->model('events');
		$event = (array)$this->events->getByID($event_id);

		if (!empty($event['rec_serial'])){
			$recurrence = array();
			parse_str(base64_decode($event['rec_serial']), $recurrence);
			$event = array_merge((array)$event, $recurrence);
		}

		//if (($event->creator == $this->session->userdata('user_id'))){
		if (($event['creator'] == $this->session->userdata('user_id')) or
			(strtolower($this->session->userdata('role')) == 'admin')){
			$page_data['canWrite'] = true;
			$page_data['canDelete'] = true;
			$page_data['category'] = 'event';
		}

		$page_data['event'] = $event;
		$page_data['page_id'] = $event_id;
		$page_data['title'] = 'Editing event: '.$event['event_title'];

		$this->neweventform($page_data);
	}

	/**
	 * Returns a list of all calendars formatted for jQuery-UI autocomplete
	 * @param string $name part of or complete calendar name (page_title)
	 * @return void
	 */
	function getall($name){
		$this->load->model('pages');
		$pages = $this->pages->getByNameAndType(base64_decode($name), 'calendar');

		$result = array();
		foreach ($pages as $page){
			$result[] = array(
				'id' => $page['page_id'],
				'value' => $page['title']
			);
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
	}

	//EVENTS ------------------------------------------------------------------------------------------
	/**
	 * AJAX handler for creating a new event
	 * @return void
	 */
	function newevent(){
		$events = json_decode(base64_decode(str_replace(" ", "+", $this->input->post('events'))));
		$this->result->data = false;

		$this->load->model("events");
		foreach ($events as $event){
			if ($event != null){
				$event->creator = $this->session->userdata('user_id');

				if (!isset($event->organizer)){
					$event->organizer = $this->session->userdata('user_id');
					$event->organizer_name = $this->session->userdata('display_name');
				}

				$sections = $event->sections;
				unset($event->sections);

				$newEvent = $this->events->newEvent($event);

				if ($newEvent){
					$this->result->data = "event/$newEvent";

					if (isset($sections) and (count($sections) > 0)){
						$this->load->model('pages');
						try{
							$this->pages->publishEvent($sections, $newEvent);
							$this->result->data = "article/{$sections[0]}";
						} catch(Exception $e){
							$this->result->isError = true;
							$this->result->errorStr = $e->getMessage();
						}
					}
				}else{
					$this->result->isError = true;
					$this->result->errorStr = "There was an error creating the event. Please try again later. If the problem persists call the Helpdesk at x4024.";
				}
			}
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	/**
	 * AJAX handler for updating events
	 * @return void
	 */
	function saveevent(){
		$events = json_decode(base64_decode(str_replace(" ", "+", $this->input->post('events'))), true);
		$event = $events[0]; //for saving there is always just one event

		$this->load->model("events");
		if ($event != null){
			if (!isset($event['rec_factor'])){
				$event['rec_factor'] = null;
				$event['rec_rule'] = null;
			}

			//for events that require the start date to be on the first occurrence
			$event['start_date'] = date("Y-m-d", strtotime($event['start_date']));

			$sections = $event['sections'];
			unset($event['sections']);

			$this->result->isError = !$this->events->update($event);
			if ($this->result->isError){
				$this->result->errorStr = "There was an error updating the event. Try again later.";
			}else{
				$this->result->data = "event/{$event['event_id']}";

				if (isset($sections) and is_array($sections)){
					$this->load->model('pages');
					try{
						$this->pages->publishEvent($sections, $event['event_id']);
						if (count($sections) > 0){ $this->result->data = "article/{$sections[0]}"; }
					} catch(Exception $e){
						$this->result->isError = true;
						$this->result->errorStr = $e->getMessage();
					}
				}
			}
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	/**
	 * AJAX handler to delete an event
	 * @return void
	 */
	function deleteevent(){
		$event_id = $this->input->post('event_id');

		$this->load->model("events");

		if ($this->events->delete($event_id)){
			$this->result->data = $event_id;
		}else{
			$this->result->isError = true;
			$this->result->errorStr = "There was an error deleting the event. Please try again later. If the problem persists call the Helpdesk at x4024.";
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	/**
	 * AJAX handler to publish events to sections
	 * @return void
	 */
	function publishevent(){
		$event_id = $this->input->post('pid');
		$sections = json_decode($this->input->post('data'));

		if (isset($sections)){
			$this->load->model('pages');
			try{
				$this->pages->publishEvent($sections, $event_id);
			} catch(Exception $e){
				$this->result->isError = true;
				$this->result->errorStr = $e->getMessage();
			}
		}

		$this->result->data = "This event was published successfully!";
		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	/**
	 * function that sends out an email with the event data attached in VCALENDAR format.
	 * @return void
	 */
	function sendevent(){
		//setup mail
		$to = $this->session->userdata('email');
		$from = "\"Eileen Fisher Intranet\" <noreply@eileenfisher.com>";
		$subject = "Calendar Event from the Intranet";
		$host = "efexc01.eileenfisher.net";//this is using a local smtp server (IIS or other)
		$port = "25";
		ini_set('SMTP', $host);
		ini_set('smtp_port', $port);
		ini_set('sendmail_from', 'noreply@eileenfisher.com');

		//generate boundary string
		$semi_rand = md5(time());
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

		//headers
		$headers  = "Content-Type:  text/plain";
		$headers .= "From: $from\n";
		$headers .= "To: $to\n";
		$headers .= "Subject: $subject\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: multipart/mixed; boundary=\"{$mime_boundary}\"\n";

		// Add a multipart boundary above the plain message
        $body  = "This is a multi-part message in MIME format.\n\n";
        $body .= "--{$mime_boundary}\n";
		$body .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
		$body .= "Content-Transfer-Encoding: 7bit\n\n";
		$body .= "Hello ".$this->session->userdata("first_name").",\n\n\tThis is the event you requested from the Intranet. Double click on the attachment and save it to your calendar.";
		$body .= "\n\nThank you\n\n";

		//get user's info to fill the vCard:
		$this->load->model('events');
		$event_id = $this->input->post("event_id");
		$event_id = substr($event_id, strpos($event_id, "/") + 1, strlen($event_id));
		$event = $this->events->getByID($event_id);

		// Add file attachment to the message
		$body .= "--{$mime_boundary}\n";
		$body .= "Content-Type: text/plain;\n";
		$body .= " name=\"{$event->event_title}.ics\"\n";
		$body .= "Content-Disposition: attachment;\n";
		$body .= " filename=\"{$event->event_title}.ics\"\n";
		$body .= "Content-Transfer-Encoding: 7bit\n\n";

		$body .= "BEGIN:VCALENDAR\n";
		$body .= "VERSION:2.0\n";
		//$body .= "PRODID:-//hacksw/handcal//NONSGML v1.0//EN\n";
		$body .= "BEGIN:VEVENT\n";
		$body .= "UID:{$event->event_id}\n";
		$body .= "DTSTAMP:".date("Ymd\THis\ZT\n", strtotime($event->start_date." ".$event->start_time))."\n";
		$body .= "ORGANIZER;CN=\n";
		$body .= "DTSTART:19970714T170000Z\n";
		$body .= "DTEND:19970715T035959Z\n";
		$body .= "SUMMARY:THIS IS NOT FINISHED.\n";
		$body .= "END:VEVENT\n";
		$body .= "END:VCALENDAR\n";

		$body .= "--{$mime_boundary}--\n";

		//send it
		if (mail($to, $subject, $body, $headers) === FALSE){
			$this->result->isError = true;
			$this->result->errorStr = "There was an error sending the email with your event, please try again later. If it still doesnt work please report this problem. Thank you";
		}

		$this->result->data = "Email sent successfully";

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}
}
