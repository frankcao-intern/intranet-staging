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
    function load($page_id){
        $this->load->helper('form');

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

            //load the page
            $this->pageRecord['template_name'] = 'properties';
            //pr($this->pageRecord);
            $this->load->view('layouts/default', $this->pageRecord);
        } else {
            show_error("There was an error retrieving this page. Try reloading/refreshing the page, if it still doesn't
				work please contact the Helpdesk.");
        }
    }

    /**
     * This function updates the page record and logs the actions in the Audit table.
     * @TODO rework this somehow, too long, its doing too many things
     * @return void
     */
    function updatepage(){
        $page_id = $this->input->post('pid');
        $content = json_decode($this->input->post('data'), true);
        //echo "hello content";
        //pr($content);exit;

        //publish page to sections -------------------------------------------------------------------------------------
        if (isset($content['sections'])){
            $this->load->model('pages');
            if (!$this->pages->publishPage($page_id, $content['sections'])){
                $this->result->isError = true;
                $this->result->errorStr = "There was an error publishing this page. Please try again later. If the ";
                $this->result->errorStr .= "problem persists call the Helpdesk at x4024.";
            }
            unset($content['sections']);
        }

        //tags ---------------------------------------------------------------------------------------------------------
        if (isset($content['tags']) and !$this->result->isError){
            $this->load->model('tags');
            if (!$this->tags->tagPage($page_id, $content['tags'])){
                $this->result->isError = true;
                $this->result->errorStr = "There was an error saving the tags. Please try again later. If the ";
                $this->result->errorStr .= "problem persists call the Helpdesk.";
            }
            unset($content['tags']);
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

            $this->load->model('pages');
            if (!$this->pages->updatePage($page_id, $content)){
                $this->result->isError = true;
                $this->result->errorStr = "There was an error saving the page properties. Please try again later. ";
                $this->result->errorStr .= "If the problem persists call the Helpdesk.";
            }
        }

        $this->result->data = "Page properties updated successfully.";
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
    }

    /**
     * This function updates the page record for the general settings in the Page table.
     *
     * @return void
     */

    function updateGeneralSettings(){

	    // the original page_id
	    $page_id = $this->input->post('pageID');
        //pr($this->input->post());

        // Post data from propery edit page
        $post_data = $this->input->post();

        // form validation
        //$this->form_validation->set_rules('allow_comments', 'Allow Comments', 'required');
        $this->form_validation->set_rules('template_id', 'Templates ID', 'required');
        $this->form_validation->set_rules('date_published', 'Date Published', 'required');
        //$this->form_validation->set_rules('show_until', 'Expire date', 'required');

        //checks if validation is true
        if($this->form_validation->run() === true) {
            //echo 'success';
            $this->input->post('allow_comments')? $allow_comments = '0': $allow_comments = '1';
            $template_id = $this->input->post('template_id');
            $date_published = $this->input->post('date_published');
            $show_until = $this->input->post('show_until');

            // contstruct the data array
            $properties_data = array (
                'allow_comments' => $allow_comments,
                'template_id' => $template_id,
                'date_published' => $date_published,
                'show_until' => $show_until,
            );
            $this->pm->updatePage($page_id, $properties_data);

            // set success msg
            set_alert(
                'Page general settings updated successfully',
                'success'
                );
            // redirect user to the same page
            $this->load($page_id);
        } else {
            //usset($this->session->userdata('alerts'));
            pr($this->session->userdata);
            //echo "failed";
            set_alert(
            'Sorry! Page content update was unsuccessful. Please try again or contact administrator.',
            'danger'
            );
        }

        // load the view


    }

    /**
     * This function updates the page record for the general settings in the Page table.
     *
     * @return void
     */

    function updatePagePublishing(){

	    // the original page_id
	    $page_id = $this->input->post('pageID');
        //pr($this->input->post());

        // Post data from propery edit page
        $post_data = $this->input->post();
        pr($post_data);

        /* new test with publish array */
        foreach ( $post_data['publish'] as $key => $value ){
            $section_id = $key;
            $data['date_published'] = ($value['date_published'])? date('Y-m-d', strtotime($value['date_published'])): '';
            $data['show_until'] = ($value['show_until'])? date('Y-m-d', strtotime($value['show_until'])): '';
            //pr($section_id);
            //pr($date_pub);
            //pr($date_exp);


            // update the database
            if($data['date_published'] != null && $data['show_until'] != null){

                // check if data already inerted or not
                $is_available = getPageSectionProperty($page_id, $section_id);
                pr($is_available);

                if(!$is_available){
                    // add new fields in fn_pages_pages
                } else {
                    // update existing data in fn_pages_pages
                    $update = $this->pm->updatePagesSections($page_id, $section_id, $data);
                }
                // set success msg
                set_alert(
                    'Page section publishing settings updated successfully',
                    'success'
                );
                // redirect user to the same page
                $this->load($page_id);

            } else {
                //usset($this->session->userdata('alerts'));
                //pr($this->session->userdata);
                //echo "failed";
                set_alert(
                    'Sorry! Page content update was unsuccessful. Please try again or contact administrator.',
                    'danger'
                );
            }

        }
        exit;
    }
}
