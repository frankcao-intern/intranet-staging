<?php
/**
 * @author cravelo
 * @date 4/6/11
 * @time 5:04 PM
 */

/**
 * The articles controller provides access to all pages on the site
 * @package Controllers
 * @author cravelo
 * @property CI_Loader $load
 * @property CI_Pagination $pagination
 * @property Searchindex $searchindex
 * @property Comments_model $comments_model
 * @property Pages $pages
 * @property Revisions $revisions
 * @property Audit $audit
 */
class Article extends MY_Controller {
	/**
	 * The heart of the operation :)
	 *
	 * This function will first use loadPage to get the page record then it will retrieve:
	 *  The latest revision for the page
	 *  The comments if any
	 * And then it will load the main_template view with the template for the page included within.
	 *
	 * @param int $page_id the requested page_id
	 * @param int $d_start (optional) the start date for bucket articles
	 * @param int $d_end (optional) the end date for bucket articles
	 * @param string $order_by the column to order the results.
	 * @return void
	 */
	function index($page_id = null, $d_start = null, $d_end = null, $order_by = null){
		$this->loadPage($page_id);

		//load the latest revision and process the metadata.
		$this->loadRevision($page_id, $d_start, $d_end, $order_by);

		//load comments
		$this->load->model('comments_model');
		$this->pageRecord['comments'] = $this->comments_model->get($page_id);

		//get related tags
		$this->load->model('tags');
		$this->pageRecord['tags'] = $this->tags->getForPage($page_id);
        //pr($this->pageRecord);

		//load the page with the requested template around it
		if (isset($this->pageRecord['params']) and isset($this->pageRecord['params']['layout'])){
			$layout = $this->pageRecord['params']['layout'];
			$this->pageRecord[$layout] = true;
			$this->load->view("layouts/$layout", $this->pageRecord);
		}else{
			$this->setupBreadbrumbs($this->pageRecord);
			$this->load->view('layouts/default', $this->pageRecord);
		}
	}

	/**
	 * Enables edit mode on pages.
	 * @param int $page_id
	 * @return void
	 */
	function edit($page_id){

		$this->pageRecord['edit'] = true;
		$this->load->model("pages");
		$this->pageRecord['user_sections'] = $this->pages->getUserPublish($this->session->userdata('user_id'), $page_id);
		$this->index($page_id);
	}

	/**
	 * This is the handler for the new page form (new page dialog).
	 * @return void
	 */
	function create(){
		//load models
		$this->load->model('pages');
		$this->load->model('permissions');
		$this->load->model('revisions');
		$this->load->model('searchindex');
		$this->load->model('audit');

		//prepare record
		$recordArr = array();
		$recordArr['title'] = "Untitled Page";
		$recordArr['template_id'] = $this->input->post('tid');
		$recordArr['show_until'] = date('Y-m-d');
		$recordArr['created_by'] = $this->session->userdata('user_id');

		$error = true;

		//start a transaction
		$this->db->trans_begin();
		//create the page record
		$new_page_id = $this->pages->newPage($recordArr);
		if ($new_page_id){
			$publish_to = $this->input->post('publish_to');
			if ($publish_to){
				$this->pages->publishPage($new_page_id, array($publish_to));
			}

			//add permissions
			$this->load->model('groups');
			$group_names = array($this->session->userdata('display_name'));

			if ($this->input->post('private') === false){
				$group_config = $this->config->item('groups_config');
				$default_group_names = $group_config['group_names'];
			    $group_names = array_merge($group_names, $default_group_names);
			}

			$group_id = $this->groups->get($group_names);
			function addAccess(&$a, $i, $groups){
				if (in_array($a['group_name'], $groups)){
					$a['access'] = PERM_READ;
				}else{
					$a['access'] = PERM_ALL;
				}
			}
			array_walk($group_id, 'addAccess', $default_group_names);
			$groupsAdded = $this->permissions->add($new_page_id, $group_id, true);

			if ($groupsAdded){
				//add a new revision
				$rev_contents = file_get_contents(realpath(APPPATH)."/views/".$this->input->post('tname').".json");
				$error = !$this->revisions->newRevision($new_page_id, $rev_contents);

				//add it to the search index?
//				$this->searchindex->newEntry(array(
//					"obj_id" => $new_page_id,
//					"obj_type" => 'page',
//					"page_title" =>  $recordArr['title'],
//					"page_content" => $rev_contents
//				));

				//log the creation
				$this->audit->newEntry(array(
					array(
						"page_id" => $new_page_id,
						"what" => 'created',
						"who" => $this->session->userdata('user_id')
					)
				));

				//subscribe the creator to the page
				$this->load->model('subscriptions');
				$this->subscriptions->add($recordArr['created_by'], $new_page_id);
			}
		}

		if (($this->db->trans_status() === FALSE) or ($error === true)){
			$this->db->trans_rollback();
			show_error("There was an error creating the new page. Try again, if the error persists please call the Helpdesk at x4024.");
		}else{
			$this->db->trans_commit();
			redirect("edit/".$new_page_id);
		}
	}

	/**
	 * This function takes an array posted to it of object name = object value and it updates that page's \
	 * latest revision with the new content.
	 * @return void
	 */
	function save(){
		//$this->db->query("LOCK TABLES ".$this->db->protect_identifiers("revisions", TRUE)." WRITE");

		$data = json_decode($this->input->post("data"), true);
		$page_id = $this->input->post('pid');

		//I assume is an element in the JSON structure of the page text and I create a new revision with this new content
		$this->load->model('revisions');
		$revision = $this->revisions->getLatestRevision($page_id);
		if (!$revision){
			$this->result->isError = true;
			$this->result->errorStr = "There was an error saving the new content.";
		}else{
			if (is_array($data)){
				$changed = false;
				foreach($data as $element_id => $content){
					$content = utf8_decode(base64_decode(str_replace(" ", "+", $content)));
					//disallow script tag
					$content = preg_replace('/< *script/i', "&lt;script", $content);
					$content = preg_replace( "/< *\/ *script *>/i", "&lt;/script&gt;", $content);
					//echo $content;
					//only if something changed
					$content = json_decode(utf8_encode($content));
					if (!isset($revision['revision_text'][$element_id]) or ($revision['revision_text'][$element_id] != $content)){
						$revision['revision_text'][$element_id] = $content;
						$changed = true;
					}
				}

				if ($changed){
					$revision['revision_text'] = json_encode($revision['revision_text']);
					//print_r($revision);
					if (!$this->revisions->newRevision($page_id, $revision['revision_text'])){
						$this->result->isError = true;
						$this->result->errorStr = "There was an error saving the new content.";
					}
				}
			}
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));

		//$this->db->query("UNLOCK TABLES");

	}

	/**
	 * removes an element from the page's revision
	 */
	function removeelement(){
		$key = $this->input->post("key");
		$page_id = $this->input->post('pid');

		//I assume is an element in the JSON structure of the page text and I create a new revision with this new content
		$this->load->model('revisions');
		$revision = $this->revisions->getLatestRevision($page_id);
		if (!$revision){
			$this->result->isError = true;
			$this->result->errorStr = "There was an error removing the element from the page.";
		}else{
			if ($key){
				$changed = false;

				if (isset($revision['revision_text'][$key])){
					unset($revision['revision_text'][$key]);
					$changed = true;
				}

				if ($changed){
					$revision['revision_text'] = json_encode($revision['revision_text']);
					//print_r($revision);
					if (!$this->revisions->newRevision($page_id, $revision['revision_text'])){
						$this->result->isError = true;
						$this->result->errorStr = "There was an error removing the element from the page.";
					}
				}
			}
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	/**
	 * Marks a page for deletion or un-deletes it, logs this action and
	 * if deleting it removes the page form the search index.
	 */
	function delete(){
		$page_id = $this->input->post('pid');
		$content['deleted'] = $this->input->post('delete');

		if ($content['deleted'] == 1){
			$what = 'deleted';
			$content['published'] = 0;
			$this->load->model('searchindex');
			$this->searchindex->delete($page_id);
		}else{
			$what = 'undeleted';
		}

		$audit = array();
		$audit[] = array(
			"page_id" => $page_id,
			"what" => $what,
			"who" => $this->session->userdata('user_id')
		);
		$this->load->model('audit');
		$this->audit->newEntry($audit);

		$this->load->model('pages');
		if (!$this->pages->updatePage($page_id, $content)){
			$this->result->isError = true;
			$this->result->errorStr = "There was an error deleting this page. Please try again later.";
			$this->result->errorStr .= "If the problem persists call the Helpdesk.";
		}

		$this->result->data = "The page was $what successfully!";
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($this->result));
	}

	/**
	 * This function permanently deletes a page and is used from the purge function in who's who
	 */
	function purge(){
		$this->load->model('pages');
		$page_id = $this->result->data = $this->input->post('pid');

		if ($this->pages->deletePage($page_id) === false){
			$this->result->isError = true;
			$this->result->errorStr = "There was an error deleting this page. Please try again later.";
			$this->result->errorStr .= "If the problem persists call the Helpdesk.";
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	/**
	 * Send an email to the specified people with a link back to an article
	 */
	function share(){
		$email_addresses = json_decode($this->input->post('emails'));
		$msg = $this->input->post('msg');
		$page_id = $this->input->post('pid');
		$firstname = $this->session->userdata('first_name');

		$message = "Hello,\r\n\r\n";
		$message .= "You are receiving this notification because %s has shared an article with you on fishNET. ";
		if (!empty($msg)){//if the message is empty don't bother
			$message .= "\r\n\r\n%s said: %s\r\n\r\n";
		}
		$message .= "You can see the article by using the following link:";
		$message .= "\r\n\r\n%s\r\n\r\n";

		if (!empty($msg)){
			$msg = sprintf($message, $firstname, $firstname, $msg,
				       $this->config->item('site_url')."article/$page_id");
		}else{
			$msg .= sprintf($message, $firstname, 
					$this->config->item('site_url')."article/$page_id");
		}

		$emails = array();
		foreach ($email_addresses as $address){
			$emails[] = array(
				'subject' => "$firstname has shared an article with you",
				'from' => "$firstname <{$this->session->userdata('email')}>",
				'to' => $address,
				'message' => $msg
			);
		}

		$this->load->model('mail_queue');
		if ($this->mail_queue->add($emails)){
			$this->result->data = "Emails were queued successfully and will be sent shortly.";
		}else{
			$this->result->isError = true;
			$this->result->errorStr = "There was an error queuing up the emails, please try again later.";
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	/**
	 * Send an email to the specified people with a link back to an article
	 */
	function review(){
	    $this->load->model('pages');
	    $this->load->model('users');
        $this->load->model('mail_queue');


		$email_addresses = json_decode($this->input->post('emails'));
		$msg = $this->input->post('msg');
		$page_id = $this->input->post('pid');
		$firstname = $this->session->userdata('first_name');
        $article_url = $this->config->item('site_url')."properties/$page_id";

        pr($page_id);
        pr($firstname);
        pr($email_addresses);
        pr($msg);

        $message = "Hello,\r\n\r\n";
        $message .= "You are being requested to review and approve this article. \r\n\r\n";
        $message .= "Article link: ".$article_url ."\r\n\r\n";
        $message .= "Notes to reviewer: \r\n\r\n";
        $message .= $msg."\r\n\r\n";
        $message .= "Thank you,\r\n\r\n";
        $message .= $firstname;
        //pr($message);

        $emails = array();
        foreach ($email_addresses as $address){
            $sender_id = $this->session->userdata('user_id');
            $reviewer = $this->users->query('email', $address);
            $reviewer_id = $reviewer[0]['user_id'];
            $reviewData = array (
                'page_id' => $page_id,
                'sender_id' => $sender_id,
                'reviewer_id' => $reviewer_id,
                'status' => 0
            );

            //pr($reviewData);
            if($this->pages->addPagesReview($reviewData)){

                $emails[] = array(
                    'subject' => "$firstname has shared an article with you for review",
                    'from' => "$firstname <{$this->session->userdata('email')}>",
                    'to' => $address,
                    'message' => $message
                );

                if ($this->mail_queue->add($emails)){
                    $this->result->data = "Emails were queued successfully and will be sent shortly. Look you article's page properties for approval updates.";
                }else{
                    $this->result->isError = true;
                    $this->result->errorStr = "There was an error queuing up the emails, please try again later.";
                }
            } else {
                $this->result->isError = true;
                $this->result->errorStr = "There was an error queuing up the emails, please try again later.";
            }
        }

        pr($emails);
        exit;


        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	/**
	 * Loads all pages that are tagged with the specified tagname
	 * @return void
	 */
	function tag() {
		//$this->output->enable_profiler(TRUE);

		$query = $this->uri->uri_to_assoc(2);
		$section = (isset($query['section']) and is_numeric($query['section'])) ? $query['section'] : null;

		$tag_name = isset($query['tag']) ? urldecode($query['tag']) : null;

		//check the tag name is valid and exists
		if (!isset($tag_name)){ show_404(); }
		$tag_name = str_replace("_", " ", $tag_name);
		$this->load->model('tags');
		$tag_id = $this->tags->getID($tag_name);
		if (!isset($tag_id)) { show_404(); }

		$this->load->model('pages');

		if (isset($section)){
			$config['uri_segment'] = 6;    //  1      2      3        4       5        6
			$config['base_url'] = site_url("article/tag/$tag_name/section/$section/");
			$current_offset = $this->uri->segment(6);
		}else{
			$config['uri_segment'] = 4;   //   1      2      3        4
			$config['base_url'] = site_url("article/tag/$tag_name/");
			$current_offset = $this->uri->segment(4);
		}
		$current_offset = ($current_offset and is_numeric($current_offset) and ($current_offset > 0)) ? $current_offset : 0;

		$config['per_page'] = 12;
		$config['total_rows'] = $this->pages->getForSection_count($section, null, null, null, $config['per_page'], $current_offset, null, $tag_id);
		$config['num_links'] = 1;
		$config['display_pages'] = false;
		$config['full_tag_open'] = '<span class="right">
			<li class="current nav-link">
				<em class="min">'.($current_offset / $config['per_page'] + 1).'</em>
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

		//load the articles
		$articles = $this->pages->getForSection($section, null, null, null, $config['per_page'], $current_offset, null, $tag_id);

		// get first 90 words from each article
		$this->load->helper('text');
		array_walk($articles, 'truncateArticle', array('count' => 90, 'key' => 'article'));

		$this->load->library('pagination');
		$this->pagination->initialize($config);

		//view variables
		$this->pageRecord['template_name'] = "sys_tag_list";
		$this->pageRecord['page_id'] = 0;
		$this->pageRecord['title'] = $tag_name;
		$this->pageRecord['articles'] = $articles;
		if (isset($section)){
			$this->pageRecord['page_id'] = $section;
			$this->pageRecord['revision']['revision_text']['back_link_id'] = $section;
			$this->pageRecord['revision']['revision_text']['back_link_title'] =
					$this->pages->getPageProperty($section, 'title');
			$this->pageRecord = array_merge($this->pageRecord, $this->tags->getPopularity($section));
		}

		$this->load->view('layouts/default', $this->pageRecord);
	}

	/**
	 * This function will retrieve the latest picture from sys_pictures
	 */
	function getpicture(){
		//get the latest pic from the revision
		$this->load->model('revisions');
		$revision = $this->revisions->getLatestRevision(SID_PHOTOS);
		$image1 = val($revision['revision_text']['main_image'][0], array());
		$image1['page_id'] = null;

		//get the latest pic from albums published to the section
		$this->load->model('pages');
		$articles = $this->pages->getForSection(SID_PHOTOS, null, null, null, 1);
		$revision_text = json_decode($articles[0]['revision_text'], true);
		$image2 = val($revision_text['main_image'][0], array());
		$image2['page_id'] = $articles[0]['page_id'];

		//find the most recent
		$image1['date'] = (int)val($image1['date'], 0);
		$image2['date'] = (int)val($image2['date'], 0);
		if ($image1['date'] > $image2['date']){
			$this->result->data = $image1;
		}else{
			$this->result->data = $image2;
		}

		$this->result->data['date'] = date('m-d-Y', $this->result->data['date']);
		$this->result->data['src'] = site_url('images/preview/'.$this->result->data['src']);

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	/**
	 * This function will output an RSS 2.0 feed of a section
	 * @param int $section_id
	 */
	function rss($section_id){
		$this->load->model('pages');
		$articles = $this->pages->getForSection($section_id);
		//var_dump($articles);

		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$xml .= "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
		$xml .= "<channel>\n";
		$xml .= "<atom:link href=\"".site_url("/article/rss/".$section_id)."\" rel=\"self\" ";
		$xml .= "type=\"application/rss+xml\" />\n";
		$xml .= "<title>".htmlspecialchars($articles[0]['section_title'], 2, 'UTF-8')."</title>\n";
		$xml .= "<link>".site_url("/article/rss/".$section_id)."</link>\n";
		$section_title = htmlspecialchars($articles[0]['section_title'], 2, 'UTF-8');
		$xml .= "<description>Latest articles from $section_title</description>\n";
		$xml .= "<language>en-US</language>\n";
		$xml .= "<copyright>Copyright EILEEN FISHER Inc. 2010-".date('Y').". All Rights Reserved.</copyright>\n";
		$xml .= "<pubDate>".date("D, d M Y H:i:s T", time())."</pubDate>\n";
		$xml .= "<lastBuildDate>".date("D, d M Y H:i:s T", time())."</lastBuildDate>\n";
		$xml .= "<generator>fishNET</generator>\n";
		$xml .= "<webMaster>cravelo@eileenfisher.com (Carlos Ravelo)</webMaster>\n";
		$xml .= "<managingEditor>nashton@eileenfisher.com (Norlisa Ashton)</managingEditor>\n";

		array_walk($articles, 'truncateArticle', array('count' => 45, 'key' => 'article'));
		foreach ($articles as $article){
			$xml .= "<item>\n";
			$xml .= "<title>".htmlspecialchars($article['title'], 2, 'UTF-8')."</title>\n";
			$xml .= "<link>".site_url("/article/".$article['page_id'])."</link>\n";
			$desc = htmlspecialchars(val($article['revision_text']->article), 2, 'UTF-8');
			//$desc = str_replace("\n", '&lt;br&gt;', $desc);
			//$desc = str_replace('&', '&amp;', $desc);
			$xml .= "<description>$desc</description>\n";
			$xml .= "<pubDate>".date("D, d M Y H:i:s T", strtotime($article['date_published']))."</pubDate>\n";
			$xml .= "<guid>".site_url("/article/".$article['page_id'])."</guid>\n";
			$xml .= "</item>\n";
		}

		$xml .= "</channel>\n</rss>\n";

		$this->output
			->set_content_type('application/rss+xml')
			->set_output($xml);
	}

	/**
	 * Changes the page's title
	 */
	function change_page_title(){
		$page_id = $this->input->post('pid');
		$content['title'] = base64_decode($this->input->post('title'));

		$this->load->model('pages');
		if (!$this->pages->updatePage($page_id, $content)){
			$this->result->isError = true;
			$this->result->errorStr = "There was an error saving the page title. Please try again later. ";
			$this->result->errorStr .= "If the problem persists call the Helpdesk.";
		}

		$this->result->data = "The page title was updated successfully.";
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($this->result));
	}

	/**
	 * Updates a page's author and subscribes the author to the page
	 */
	function update_author(){
		$this->save();

		if (!$this->result->isError){
			$page_id = $this->input->post('pid');
			$data = json_decode($this->input->post("data"), true);
			$user_id = json_decode(base64_decode($data['author']));

			$this->load->model('subscriptions');
			$this->result->isError = !$this->subscriptions->add($user_id, $page_id);
			$this->result->errorStr = "The author was saved, but there was an error subscribing him/her to the page.";

			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($this->result));
		}
	}
    /**
     * Updates sort order for the listing
     */
    function sortOrder() {
        $this->load->model('pages', 'pm');
        $section_id = $this->input->post('sid');
        $content = json_decode($this->input->post('data'), true);

        foreach($content as $key => $value){
            /*$data[] = array(
                'section_id' => $page_id,
                'page_id' => $value,
                'sort_order' => $key+1,
            );*/
            $data['sort_order'] = $key+1;
            $page_id = $value;

            if(!$this->pm->updatePublishPage($page_id, $section_id, $data)){
                $this->result->isError = true;
                $this->result->errorStr = "There was an error saving the new content.";
            }

        }

        if (!$this->result->isError){
            $this->result->data = "Sorting updated successfully.";
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));

    }

    /**
     * This function checks if page awaiting page review/approval of the users
     */
    function checkPageReview($page_id, $reviewer_id){
        $this->load->model('pages');
        $review = $this->pages->checkPagesReview($page_id, $this->session->userdata('user_id'));
        //print_r($user);
        if ($review and (($review[0]['status'] == 0))){
            $this->result->isError = true;
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
    }
};
