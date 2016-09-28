<?php
/**
 * @author cravelo
 * @date   4/11/12
 * @time   2:14 PM
 */

/**
 * properties
 * @package
 * @author cravelo
 */
class Properties extends MY_Controller {
	/**
     * This is a Property constractor function
     * loads the pages model and Properties library
	 */
	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('pages', 'pm');
        //$this->load->library('properties');
    }


    /**
	 * This function shows the properties for a given page
	 * @param int $page_id the page id to load the properties for.
	 * @return void it loads the view.
	 */
    function load($page_id, $key = null){

        $this->pageRecord['properties'] = true;
        $this->loadPage($page_id);

        if ($this->pageRecord !== false){
            $this->pageRecord['pageID'] = $page_id;
            //override comments
            $this->pageRecord['p_allow_comments'] = (($this->pageRecord['allow_comments'] === 'true') or
                ($this->pageRecord['allow_comments'] === true));
            $this->pageRecord['allow_comments'] = false;

            //get all permissions
            $this->load->model("permissions");
            $this->pageRecord['permissions'] = $this->permissions->getAccess($page_id);

            //get sections where user has publish permission
            $this->load->model("pages");
            $this->pageRecord['sections'] = $this->pages->getUserPublish($this->session->userdata('user_id'), $page_id);
            //pr($this->pageRecord['sections']);

            //get all revisions without text
            $this->load->model("revisions");
            $this->pageRecord['all_revs'] = $this->revisions->getAll($page_id);
            $this->pageRecord['revision'] = false;//we dont need the latest revision

            //get all non-system templates
            $this->load->model('templates');
            $this->pageRecord['templates'] = $this->templates->getTemplate(null);

            //get related tags
            $this->load->model('tags');
            $this->pageRecord['tags'] = implode(", ", $this->tags->getForPage($page_id));

            $this->load->helper('text');
            //pr($this->pageRecord);
            //load the page
            $this->pageRecord['template_name'] = 'properties';
            //pr($this->pageRecord);

            /* backtracking for page review */
            if($key){
                $this->session->unset_userdata('review_key');
                $this->pageRecord['review_key'] = $key;
                $this->session->set_userdata('review_key', $key);
            }

            $this->load->view('layouts/default', $this->pageRecord);
        } else {
            show_error("There was an error retrieving this page. Try reloading/refreshing the page, if it still doesn't
				work please contact the Helpdesk.");
        }
    }

    /**
     * This function updates the page record for the general settings in the Page table.
     *
     * @return void
     */
    function updateGeneralSettings(){

        $page_id = $this->input->post('pid');
        $content = json_decode($this->input->post('data'), true);

        // load the page model
        $this->load->model('pages');
        // update the page general settings
        if (!$this->pages->updatePage($page_id, $content)){
            $this->result->isError = true;
            $this->result->errorStr = "There was an error saving the page properties. Please try again later. ";
            $this->result->errorStr .= "If the problem persists call the Helpdesk.";
        }

        // if data update successfull insert new entry to audit table
        if (!$this->result->isError){
            $audit = array();
            $audit[] = array(
                "page_id" => $page_id,
                "what" => 'edited',
                "who" => $this->session->userdata('user_id')
            );
            //create audit trail
            if (array_key_exists('published', $content)){
                $audit[] = array(
                    "page_id" => $page_id,
                    "what" => ($content['published'] == 1) ? 'published' : 'unpublished',
                    "who" => $this->session->userdata('user_id')
                );
            }

            $this->load->model('audit');
            $this->audit->newEntry($audit);
        }

        $this->result->data = "Page properties - general settings updated successfully.";

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));

    }

    /**
     * This function updates the page record for the general settings in the Page table.
     *
     * @return void
     */
    function updatePagePublishing(){

        $page_id = $this->input->post('pid');
        $content = json_decode($this->input->post('data'), true);
        if($this->input->post('review_key'))
            $review_key = $this->input->post('review_key');
        //pr($content);
        //publish page to sections -------------------------------------------------------------------------------------
        if (isset($content['date_published']) && isset($content['show_until'])){
            // defining an empty array
            $data = array();

            // itarating through both the content array
            foreach($content['date_published'] as $key => $dp_val){
                if(!is_null($dp_val)){
                    foreach ($content['show_until'] as $index => $su_val){
                        if($key == $index) {
                            if(!is_null($su_val) && !is_null($dp_val)){
                                if(strtotime($su_val) < strtotime($dp_val) ) {
                                    $this->result->isError = true;
                                    $this->result->errorStr = "Show Until cannot be earlier than Date Published, please correct this and try again.";
                                } else {
                                    $data[$index]['page_id'] = $page_id;
                                    $data[$index]['section_id'] = $key;
                                    $data[$index]['date_published'] = $dp_val;
                                    $data[$index]['show_until'] = $su_val;

                                    // sort order data iteration
                                    foreach ($content['sort_order'] as $key => $val){
                                        if($val != null){
                                            $data[$key]['sort_order'] = $val;
                                        }
                                    }
                                }
                            }
                            /*else {
                                $this->result->isError = true;
                                $this->result->errorStr = "Section's dates cannot be blank, please correct this and try again.";
                            }
                            */

                        } else {
                            continue;
                        }
                    }


                }
            }
            //pr($data);
            // passing data array to page model
            if (!$this->pm->publishPage($page_id, $data)){
                $this->result->isError = true;
                $this->result->errorStr = "There was an error publishing this page. Please try again later. If the ";
                $this->result->errorStr .= "problem persists call the Helpdesk at x4024.";
            }

            if(isset($review_key)){

                if($this->session->userdata('review_key') == $review_key){

                    $this->session->unset_userdata('review_backtrack', '');
                    $reviewer_id = $this->session->userdata('user_id');
                    $review_data = array(
                        'status' => 1
                    );

                    // send email to the requester

                    $this->pm->updatePagesReview($page_id, $reviewer_id, $review_data);
                }
            }

        }

        if (!$this->result->isError){
            $audit = array();
            $audit[] = array(
                "page_id" => $page_id,
                "what" => 'edited',
                "who" => $this->session->userdata('user_id')
            );
            //create audit trail
            if (array_key_exists('published', $content)){
                $audit[] = array(
                    "page_id" => $page_id,
                    "what" => ($content['published'] == 1) ? 'published' : 'unpublished',
                    "who" => $this->session->userdata('user_id')
                );
            }

            $this->load->model('audit');
            $this->audit->newEntry($audit);
        }

        $this->result->data = "Page properties - section publishing updated successfully.";

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
    }

    /**
     * This function updates the page record for the tags section in the fn_tags and fn_tags_matches page.
     *
     * @return void
     */
    function updateTags(){

        // page id
        $page_id = $this->input->post('pid');
        $content = json_decode($this->input->post('data'), true);

        // update the page tags settings
        if (isset($content['tags']) and !$this->result->isError){
            // load the tag model
            $this->load->model('tags');
            if (!$this->tags->tagPage($page_id, $content['tags'])){
                $this->result->isError = true;
                $this->result->errorStr = "There was an error saving the tags. Please try again later. If the ";
                $this->result->errorStr .= "problem persists call the Helpdesk.";
            }
        }

        // if data update successfull insert new entry to audit table
        if (!$this->result->isError){
            $audit = array();
            $audit[] = array(
                "page_id" => $page_id,
                "what" => 'edited',
                "who" => $this->session->userdata('user_id')
            );
            //create audit trail
            if (array_key_exists('published', $content)){
                $audit[] = array(
                    "page_id" => $page_id,
                    "what" => ($content['published'] == 1) ? 'published' : 'unpublished',
                    "who" => $this->session->userdata('user_id')
                );
            }

            $this->load->model('audit');
            $this->audit->newEntry($audit);
        }

        $this->result->data = "Page properties - tags updated successfully.";

        // return back to the JSON file
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));

    }
}
