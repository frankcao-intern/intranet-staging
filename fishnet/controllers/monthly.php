<?php
/**
 * @author Carlos Ravelo
 */

require_once __DIR__ . '/article.php';

/**
 * News controller
 * @author cravelo
 * @package Controllers
 * @property CI_Loader $load
 */
class Monthly extends Article {
	/**
	 * This function will retrieve the child pages for the news section in monthly date ranges.
	 * Date is expected in mm-yyyy format as a string
	 * @param int $section_id
	 * @param int $date timestamp of the first of the month to load the articles on that month.
	 * @param string $order_by the column to order news by
	 * @return array same as returned by pages->getForSection
	 */
	private function get($section_id, $date, $order_by){
		$this->load->model('pages');

		//get one month of news
		$d_start = date('Y-m-d', $date);
		//this will work even for february leap years
		$d_end = date('Y-m-d', strtotime("+1 month -1 day +23 hours +59 minutes +59 seconds", $date));
        //current todays date
        $d_now = date('Y-m-d');

		return $this->pages->getForSection($section_id, $d_start, $d_end, null, 5, null, null, null, $order_by, 'monthly');
	}

	/*
	 * function to load the news for the news section
	 */
	/**
	 * @param int $section_id
	 * @param string $month month to load news for the month-year.
	 * @param string $year year to load news for the month-year.
	 * @param string $order_by the column to order news by
	 * @return void
	 */
	function load($section_id, $month = null, $year = null, $order_by = null){
		$this->load->model('pages');
		$today = strtotime("01-".date("m-Y", time()));

		if (isset($month)){
			$date = strtotime("01-$month-$year");
		}else{
			$date = $this->input->post('byDate');
			if ($date === false){
				$date = $today;
			}else{
				$dateArr = array();
				preg_match('/(\d+)\/(\d+)/', $date, $dateArr);
				$date = strtotime("{$dateArr[2]}-{$dateArr[1]}-01");
			}
		}

		//navigation
		$this->pageRecord['month'] = date('F', $date);
		$this->pageRecord['year'] = date('Y', $date);

		$last_month = strtotime("last month", $date);
		$this->pageRecord['prev'] = array(date('m', $last_month), date('Y', $last_month));
		$this->pageRecord['prev_text'] = "&#x25c4; Previous Month";

		$next_month = strtotime("next month", $date);
		$this->pageRecord['next'] = array(date('m', $next_month), date('Y', $next_month));
		$this->pageRecord['next_text'] = "Next Month &#x25ba;";
		$this->pageRecord['show_next'] = ($date < $today); //if we are not displaying the current month


		//get current news
		$news = $this->get($section_id, $date, $order_by);
		$this->load->helper('text');
		array_walk($news, 'truncateArticle', array('count' => 45, 'key' => 'article'));
		array_walk($news, 'truncateArticle', array('count' => 45, 'key' => 'article1'));
		$this->pageRecord['news'] = $news;

		//get last month's news
		$news = $this->get($section_id, $last_month, $order_by);
		array_walk($news, 'truncateArticle', array('count' => 45, 'key' => 'article'));
		array_walk($news, 'truncateArticle', array('count' => 45, 'key' => 'article1'));
		$this->pageRecord['last_month'] = $news;

		$this->pageRecord['order_by'] = $order_by;

		$this->index($section_id);
	}
}
