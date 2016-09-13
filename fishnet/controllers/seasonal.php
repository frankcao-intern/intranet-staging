<?php
/**
 * @author cravelo
 * @date Oct 14, 2010
 * @time 11:24:42 AM
 */

require_once dirname(__FILE__).'/article.php';

/**
 * The seasonal aggregator controller
 * @package Controllers
 * @author cravelo
 */
class Seasonal extends Article {
	/**
	 * This function loads a page that requires season/year information
	 * @param int $section_id the section to load
	 * @param string $season the name of the season passed from the url by the user to load the articles form that season/year
	 * @param string $year the year in the season/year combination
	 * @param string $order_by the column to order the results
	 * @return void loads the selected section
	 */
	function load($section_id, $season = null, $year = null, $order_by = null){
		$seasons = $this->setupSeason($season, $year);
		$current_season = $seasons['current_season'];
		$last_season = $seasons['last_season'];

		$this->load->model('pages');
		$thisSeason = $this->pages->getForSection($section_id, $current_season['first_day'],
			$current_season['last_day'], null, null, null, null, null, $order_by, 'seasonal');
		array_walk($thisSeason, 'truncateArticle', array('count' => 45, 'key' => 'article'));
		$this->pageRecord['articles'] = $thisSeason;

		//get last season
		$lastSeason = $this->pages->getForSection($section_id, $last_season['first_day'], $last_season['last_day'],
			null, null, null, null, null, $order_by);
		array_walk($lastSeason, 'truncateArticle', array('count' => 45, 'key' => 'article'));
		$this->pageRecord['past_articles'] = $lastSeason;

		$this->pageRecord['order_by'] = $order_by;

	    $this->index($section_id);
	}

	/**
	 * same parameters as load, except this one doesn't actually load articles, just calculates the dates for the
	 * season and loads the page.
	 * @param $section_id
	 * @param null $season
	 * @param null $year
	 * @param null $order_by
	 */
	function adsandplacements($section_id, $season = null, $year = null, $order_by = null){
		$seasons = $this->setupSeason($season, $year);
		$current_season = $seasons['current_season'];

		$this->index($section_id, $current_season['first_day'], $current_season['last_day'], $order_by);
	}

	/**
	 * Get the current season, past and next, add all the data to the pageRecord and return the current season in
	 * order to load the section
	 * @param string $season
	 * @param string $year
	 * @return array
	 */
	private function setupSeason($season = null, $year = null){
		// season/year will come through parameters when user types them in the search box
		// otherwise system gets the current season/year
		if (isset($season) and isset($year)) {
			$current_season = array(
				'season' => $season,
				'year' => $year,
				'first_day' => $this->getSeasonFirstDay($season, $year),
				'last_day' => $this->getSeasonLastDay($season, $year)
			);
		}else{
			if ($this->input->post('season') === false){
				$current_season = $this->getSeason();
			}else{
				$season = $this->input->post('season');
				$year = $this->input->post('year');
				$current_season = array(
					'season' => $season,
					'year' => $year,
					'first_day' => $this->getSeasonFirstDay($season, $year),
					'last_day' => $this->getSeasonLastDay($season, $year)
				);
			}
		}

		// Navigation ----------------------------------------------------------------------------------
		$this->pageRecord['season'] = $current_season['season'];
		$this->pageRecord['year'] = $current_season['year'];

		$last_season = $this->getSeason(strtotime('-4 months', strtotime($current_season['last_day'])));
		$this->pageRecord['prev'] = array($last_season['season'], $last_season['year']);
		$this->pageRecord['prev_text'] = "&#x25c4; Previous Season";

		$next_season = $this->getSeason(strtotime('+4 months', strtotime($current_season['first_day'])));
		$this->pageRecord['next'] = array($next_season['season'], $next_season['year']);
		$this->pageRecord['next_text'] = "Next Season &#x25ba;";

		//if we are not displaying the current season then show the next season link
		$actual_current_season = $this->getSeason();//CURRENT ACCORDING TO DATE
		$this->pageRecord['show_next'] = (strtotime($current_season['last_day']) < strtotime($actual_current_season['first_day']));

		return array(
			'current_season' => $current_season,
			'last_season' => $last_season
		);
	}


	/**
	 * returns the season and year, either current (date = null) or related to the date param
	 * @param string $date optional, if passed the returned season/year are for this date
	 * @return array season, year
	 */
	private function getSeason($date = null) {
		if (!isset($date)){ $date = time(); }

		$year = date('Y', $date);

	    $firstdayspring = strtotime($year . "-02-10");
	    $firstdayfall   = strtotime($year . "-06-10");
	    $firstdayresort = strtotime($year . "-10-10");

	    if ($date < $firstdayspring){
		    $season = 'Resort';
		    $year--;
	    }elseif ($date < $firstdayfall){
		    $season = 'Spring';
	    }elseif ($date < $firstdayresort){
		    $season = 'Fall';
	    }else{
		    $season = 'Resort';
	    }

	    return array(
			'season' => $season,
			'year' => $year,
			'first_day' => $this->getSeasonFirstDay($season, $year),
			'last_day' => $this->getSeasonLastDay($season, $year)
		);
	}


	/**
	 * @param string $season name of the season
	 * @param string $year the year
	 * @return string return mySQL formatted date for the first day if the given season/year
	 */
	private function getSeasonFirstDay($season, $year) {
	    if (strtolower($season) == "spring") {
	        $sfd = $year."-02-10";
	    } elseif (strtolower($season) == "fall") {
	        $sfd = $year."-06-10";
	    } elseif (strtolower($season) == "resort") {
			$sfd = $year."-10-10";
	    } else{
		    $sfd = "";
			show_404();
	    }

	    return $sfd;
	}

	/**
	 * @param string $season name of the season
	 * @param string $year the year
	 * @return string return mySQL formatted date for the last day if the given season/year
	 */
	private function getSeasonLastDay($season,$year) {
	    if (strtolower($season) == "spring") {
	        $sld = $year."-06-09";
	    } elseif (strtolower($season) == "fall") {
	        $sld = $year."-10-09";
	    } elseif (strtolower($season) == "resort") {
	        $sld = ($year + 1)."-02-09";
	    }else{
		    $sld = "";
			show_404();
	    }

	    return $sld;
	}
}
