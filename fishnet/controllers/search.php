<?php
/**
 * @author Carlos Ravelo
 * @date Apr 4, 2010
 * @time 10:12:42 AM
 */

require_once dirname(__FILE__).'/article.php';

/**
 * The search controller handles the search engine
 * @author cravelo
 * @package Controllers
 * @property Pages $pages
 */
class Search extends MY_Controller {
	/**
	 * Search engine
	 * @param int $section_id the section id to get results from
	 * @param string $search search query encoded base64
	 * @return void
	 */
//	function index($section_id, $search){
//		$section_id = ($section_id == 'null') ? null : $section_id;
//		$search = ($search == 'null') ? null : $search;
//		$search_results = array();
//		$whosearch = array();
//		$viewall = 0;
//
//		$this->load->model('pages');
//
//		// getSearchResult parameters
//		// ($search, $current_page = null, $first_result, $filter = null, $limit = null) {
//		if (isset($section_id)) { // from search_results
//			$q = base64_decode(urldecode($search));
//			// get search results for the current page (all results)
//			$search_results = $this->pages->getSearchResults($q, null, $section_id);
//		} else { // from search box in main_template
//			$q = $this->input->post('query');
//			if ($q === false){
//				$q = urldecode($search);
//			}
//			$section_id = $this->input->post('section_id');
//			if (($q != "") and (($section_id != "") and is_numeric($section_id))){
//				//perform the search
//				$search_results = $this->pages->getSearchResults($q, 3);
//				//search who's who
//				$this->load->model('users');
//				$this->load->model('groups');
//				$whosearch = $this->users->query('display_name', $q, 4);
//				for($i = 0; $i < count($whosearch); $i++){
//					$dep = $this->groups->getUserDepartments($whosearch[$i]['user_id']);
//					if ($dep){
//						$whosearch[$i] = array_merge($whosearch[$i], $dep);
//					}
//				}
//			}
//		}
//
//		// create an array for the titles and main_parent ids
//		$search_result_titles = array();
//		for($i = 0; $i < count($search_results); $i++){
//			$sid = $search_results[$i]['section_id'];
//			$search_result_titles[$i]['section_id'] = $sid;
//			$search_result_titles[$i]['title'] = $this->pages->getPageProperty($sid, 'title');
//		}
//		// get unique values
//		$search_result_titles = array_values(array_unique($search_result_titles, SORT_REGULAR));
//
//		// send titles and contents to the view
//		$this->pageRecord['search'] = $q;
//		$this->pageRecord['first_page'] = $section_id;
//		$this->pageRecord['search_result_titles'] = $search_result_titles;
//		$this->pageRecord['search_results'] = $search_results;
//		$this->pageRecord['who_search'] = $whosearch;
//		$this->pageRecord['template_name'] = 'sys_search_result';
//
//		$this->load->view('layouts/default', $this->pageRecord);
//	}

	function index(){
		$q = $this->input->post('query');
		$limit = 10;
		if ($q === false){
			$query = $this->uri->uri_to_assoc(2);
			$q = trim(urldecode(val($query['q'])));
			$offset = trim(val($query['offset']));
		}

		$offset = (!empty($offset) and is_numeric($offset)) ? $offset : 0;
		$this->pageRecord['total_rows'] = 0;

		if (empty($q)) {
			$this->pageRecord['search_results'] = array();
			$this->pageRecord['who_search'] = array();
		}else{
			$config['per_page'] = $this->pageRecord['per_page'] = $limit;

			//Pages search
			$this->load->model('pages');
			$search_results = $this->pages->getSearchResults($q, $offset, $limit);
			array_walk($search_results, 'truncateArticle', array('count' => 45, 'key' => 'article'));
			$this->pageRecord['search_results'] = $search_results;

			//Who's Who search
			$this->load->model('users');
			$this->pageRecord['who_search'] = $this->users->search($q);

			$config['base_url'] = site_url("/search/q/$q/offset/");
			$config['total_rows'] = $this->pageRecord['total_rows'] = $this->pages->getSearchResults_count($q);
			$config['num_links'] = 1;
			$config['display_pages'] = false;
			$config['uri_segment'] = 5;
			$config['full_tag_open'] = '<span class="right">
				<li class="current nav-link">
					Page <em class="min">'.($offset / $config['per_page'] + 1).'</em>
					<span> of </span>
					<em class="max">'.ceil($config['total_rows'] / $config['per_page']).'</em>
				</li>';
			$config['full_tag_close'] = '</span>';
			$config['first_link'] = '&#x25c4; First';
			$config['prev_link'] = '&#x25c4;';
			$config['next_link'] = '&#x25ba;';
			$config['last_link'] = 'Last &#x25ba;';
			$config['next_tag_open'] =
			$config['prev_tag_open'] =
			$config['first_tag_open'] =
			$config['last_tag_open'] = '<li class="nav-link">';
			$config['next_tag_close'] =
			$config['prev_tag_close'] =
			$config['first_tag_close'] =
			$config['last_tag_close'] = '</li>';

			$this->load->library('pagination');
			$this->pagination->initialize($config);
		}

		$this->pageRecord['search'] = $q;
		$this->pageRecord['template_name'] = 'sys_search_result';
		$this->pageRecord['title'] = "Search results for $q";
		$this->load->view('layouts/default', $this->pageRecord);
	}
}
