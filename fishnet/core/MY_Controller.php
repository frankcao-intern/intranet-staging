<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
/**
 * @author cravelo
 * @date 3/1/11
 * @time 5:59 PM
 */

/**
 * This is a class to provide a consistent result JSON object for all AJAX handlers
 * @author cravelo
 * @package Misc
 */
class CResult{
	public $isError;
	public $errorCode;
	public $errorStr;
	public $data;

	function  __construct() {
		$this->isError = false;
		$this->errorCode = 0;
		$this->errorStr = "";
		$this->data = "";
	}

	function  __destruct() {
		unset($this->isError);
		unset($this->errorCode);
		unset($this->errorStr);
		unset($this->data);
	}
}

/**
 * This controller is an extension of the Codeigniter controller class.
 * @author Carlos Ravelo
 * @package Controllers
 * @property Pages $pages
 * @property Users $users
 * @property Permissions $permissions
 * @property Revisions $revisions
 * @property Tags $tags
 * @property Events $events
 */
class MY_Controller extends CI_Controller {
	/**
	 * @var array $pageRecord holds the requested page's record form the DB plus any other additional data to be passed on to the view
	 */
	public $pageRecord;
	/**
	 * @var CResult
	 */
	protected $result;

	/**
	 * the constructor checks if the user is logged in
	 */
	public function __construct(){
		parent::__construct();

		$user_name = $this->session->userdata('username');
		if ($user_name === false){
			if (isAjax()){
				$this->output->set_status_header(401);
				exit();
			}else{
				redirect("/login/index".$_SERVER['REQUEST_URI']);
			}
		}

		$this->result = new CResult();
		$this->pageRecord = array();
	}

	public function __destruct(){
		unset($this->result);
		unset($this->pageRecord);
	}

	/**
	 * This function will load the page_id information into the private variable $pageRecord
	 * only if the page has been published and if the current session user has access to see it
	 * @param int $page_id the paged_id to load
	 * @return void
	 */
	function loadPage($page_id){
		$load_page = false;
		//load the models
		$this->load->model('pages');

		//get the page
		$pageRecord = $this->pages->getPage($page_id);
		//var_dump($pageRecord);
		if ($pageRecord){
			//Check if the page has a special URL redirect
			if (isset($pageRecord['redirect_url']) and (!isset($this->pageRecord['edit'])) and
			   (!isset($this->pageRecord['properties'])))
			{
				$url = str_replace(':id', $page_id, $pageRecord['redirect_url']);

				if (strstr($this->uri->uri_string(), $url) === false){
					redirect($url);
				}
			}

			$pageRecord['allow_comments'] = ($pageRecord['allow_comments'] === "1");

			//parse parameters and pass them on to the page
			$this->pageRecord['params'] = $this->uri->uri_to_assoc(3);

			//merge the page record from the DB with any existing settings loaded before we got here.
			$this->pageRecord = array_merge($pageRecord, $this->pageRecord);

			//check permissions
			if ($this->session->userdata('role') == 'admin'){
				$perms = array(
					'canRead' => PERM_READ,
					'canWrite' => PERM_WRITE,
					'canDelete' => PERM_DELETE,
					'canPublish' => PERM_PUBLISH,
					'canPerm' => PERM_PERM,
					'canProp' => PERM_PROPERTIES
				);
			}else{
				$this->load->model('permissions');
				$perms = $this->permissions->getUserAccess($this->session->userdata("user_id"), $page_id);
			}
			$this->pageRecord = array_merge($this->pageRecord, $perms);

			//check if the user had read access
			if ($this->pageRecord['canRead']){
				//user can read it lets load the page
				$load_page = true;
			}else{
				$this->load->model('users');
				$creator = $this->users->get($this->pageRecord['created_by']);
				if ($creator === false){
					show_error("This page has restricted access.", 200);
				}else{
					show_error("This page has restricted access. If you think this is an error please
						contact ".anchor("/profiles/{$creator[0]['user_id']}", $creator[0]['display_name']), 200);
				}
			}
		}else{
			show_404();
		}

		if (!$load_page){
			show_error("There was an error retrieving this page. Reload the page, if the problem persists please call
				the Helpdesk at x4024. Thank you.");
		}

	}

	/**
	 * This function will load the page_id information into the private variable $pageRecord
	 * only if the page has been published and if the current session user has access to see it
	 * @param int $page_id the paged_id to load
	 * @return void
	 */
	function loadPageProperties($page_id, $key = null){
		$load_page = false;
		//load the models
		$this->load->model('pages');

		//get the page
		$pageRecord = $this->pages->getPage($page_id);
		//var_dump($pageRecord);
		if ($pageRecord){
			//Check if the page has a special URL redirect
			if (isset($pageRecord['redirect_url']) and (!isset($this->pageRecord['edit'])) and
			   (!isset($this->pageRecord['properties'])))
			{
				$url = str_replace(':id', $page_id, $pageRecord['redirect_url']);

				if (strstr($this->uri->uri_string(), $url) === false){
					redirect($url);
				}
			}

			$pageRecord['allow_comments'] = ($pageRecord['allow_comments'] === "1");

			//parse parameters and pass them on to the page
			$this->pageRecord['params'] = $this->uri->uri_to_assoc(3);

			//merge the page record from the DB with any existing settings loaded before we got here.
			$this->pageRecord = array_merge($pageRecord, $this->pageRecord);

			//check permissions
			if ($this->session->userdata('role') == 'admin'){
				$perms = array(
					'canRead' => PERM_READ,
					'canWrite' => PERM_WRITE,
					'canDelete' => PERM_DELETE,
					'canPublish' => PERM_PUBLISH,
					'canPerm' => PERM_PERM,
					'canProp' => PERM_PROPERTIES
				);
			}else{
				$this->load->model('permissions');
				$perms = $this->permissions->getUserAccess($this->session->userdata("user_id"), $page_id);
			}
			$this->pageRecord = array_merge($this->pageRecord, $perms);

			//check if the user had read access
			if ($this->pageRecord['canRead']){
				//user can read it lets load the page
				$load_page = true;
			}else{
				$this->load->model('users');
				$creator = $this->users->get($this->pageRecord['created_by']);
				if ($creator === false){
					show_error("This page has restricted access.", 200);
				}else{
					show_error("This page has restricted access. If you think this is an error please
						contact ".anchor("/profiles/{$creator[0]['user_id']}", $creator[0]['display_name']), 200);
				}
			}
		}else{
			show_404();
		}

		if (!$load_page){
			show_error("There was an error retrieving this page. Reload the page, if the problem persists please call
				the Helpdesk at x4024. Thank you.");
		}
	}

	/**
	 * @param int $page_id the page id to load the latest revision
	 * @param string $d_start a mySQL style date
	 * @param string $d_end a mySQL style date
	 * @param string $order_by column to order the results
	 * @return void loads the revision onto the $pageRecord array
	 */
	function loadRevision($page_id, $d_start = null, $d_end = null, $order_by = null){
		//load the latest revision and its text
		$this->load->model('revisions');
		if (isset($this->pageRecord['params']['revision'])){//if we are asked for a specific revision
			$this->pageRecord['revision'] = $this->revisions->get($this->pageRecord['params']['revision']);
		}else{//otherwise load the latest one.
			$this->pageRecord['revision'] = $this->revisions->getLatestRevision($page_id);
		}

		if ($this->pageRecord['revision']){ //if we got a revision
			//check if the user defined a function to call
			if (isset($this->pageRecord['revision']['revision_text']['model_before_name'])) {
				$name = $this->pageRecord['revision']['revision_text']['model_before_name'];
				$this->load->model($name);
				$modelRef = &$this->$name;

				$call = array(
					$modelRef,
					$this->pageRecord['revision']['revision_text']['model_before_func']
				);

				$before_param = $this->pageRecord['revision']['revision_text']['model_before_param'];
				if (!is_array($before_param)){
					$before_param = array(str_replace(':id', $page_id, $before_param));
				}

				$this->pageRecord = array_merge(call_user_func_array($call, $before_param), $this->pageRecord);
			}

			//back link
			if (isset($this->pageRecord['revision']['revision_text']['back_link_id'])){
				$back_link_id = $this->pageRecord['revision']['revision_text']['back_link_id'];
				$back_link_title = $this->pages->getPageProperty($back_link_id, 'title');
				$this->pageRecord['back_link'] = anchor("article/$back_link_id", $back_link_title);
			}elseif (count($this->pageRecord['sections']) > 0){
				$back_link_id = $this->pageRecord['sections'][0]['section_id'];
				$back_link_title = $this->pageRecord['sections'][0]['section_title'];
				$this->pageRecord['back_link'] = anchor("article/$back_link_id", $back_link_title);
			}

			//get people related to this template if any
			$people = isset($this->pageRecord['revision']['revision_text']['people']) ?
					  $this->pageRecord['revision']['revision_text']['people'] :
						  isset($this->pageRecord['people']) ?
						  $this->pageRecord['people'] :
						  null;
			if (isset($people)){
				$this->pageRecord['revision']['revision_text']['people'] = array();
				$this->load->model('users');
				$people = $this->users->get(null, $people);
				for($i = 0; $i < count($people); $i++){
					$people[$i]['user_picture'] = $this->users->getUserPicture($people[$i]['username']);
					$this->pageRecord['revision']['revision_text']['people'][$people[$i]['username']] = $people[$i];
				}
			}

			//Load buckets
			if (isset($this->pageRecord['revision']['revision_text']['buckets'])){
				$this->load->model('pages');
				$this->load->model('tags');

				foreach($this->pageRecord['revision']['revision_text']['buckets'] as $bucket){
					$name = $bucket['name'];
					$tag_id = (isset($bucket['tags'])) ? $this->tags->getID($bucket['tags']) : null;
					$section_id = str_replace(':id', $page_id, $bucket['section_id']);

                    $tmp = $this->pages->getForSection($section_id, $d_start, $d_end, $bucket['featured'], $bucket['limit'], null, $bucket['random'], $tag_id, $order_by, null);
					array_walk($tmp, 'truncateArticle', array('count' => $bucket['wordcount'], 'key' => 'article'));
					$this->pageRecord[$name] = $tmp;
				}

			}

			//Load announcements
			if (isset($this->pageRecord['revision']['revision_text']['announcements'])){
				//get one week's announcements from today
				$start = time();
				$d_start = date('Y-m-d 00:00:00', $start);
				$d_end = date('Y-m-d 00:00:00', strtotime("+1 week", $start));

				$this->load->model('events');
				$this->pageRecord['announcements'] = $this->events->get($page_id, $d_start, $d_end);
			}

			//call the OnAfter user func
			if (isset($this->pageRecord['revision']['revision_text']['model_after_name'])) {
				$name = $this->pageRecord['revision']['revision_text']['model_after_name'];
				$this->load->model($name);
				$modelRef = &$this->$name;

				$call = array(
					$modelRef,
					$this->pageRecord['revision']['revision_text']['model_after_func']
				);

				$after_param = $this->pageRecord['revision']['revision_text']['model_after_param'];
				if (!is_array($after_param)){
					$after_param = array(str_replace(':id', $page_id, $after_param));
				}

				$this->pageRecord = array_merge(call_user_func_array($call, $after_param), $this->pageRecord);
			}
		}else{
			show_error("There was an error retrieving the latest version for this page. Reload the page, if the problem persists please call the helpdesk at x4024. Thank you.");
		}
	}

	/**
	 * Helper function to setup breadcrumbs on pages that request it
	 * @param $page_data
	 */
	function setupBreadbrumbs(&$page_data){
		if (isset($page_data['page_type']) and (($page_data['page_type'] == 'section') or ($page_data['page_type'] == 'calendar') )){
			$this->session->set_userdata('breadcrumbs', json_encode(array(
				"url" => anchor($this->uri->uri_string(), '&#x25c4;&nbsp;'.$page_data['title']),
				"section_id" => $page_data['page_id']
			)));
		}else{
			$breadcrumbs = json_decode($this->session->userdata('breadcrumbs'), true);
			if (isset($breadcrumbs)){
				$page_data['breadcrumbs'] = $breadcrumbs;
			}
		}
	}
}
