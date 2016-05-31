<?php
/**
 * @author Carlos Ravelo
 * @date 5/17/11
 * @time 1:08 PM
 */

/**
 * The static pages controller loads the requested static page (e.g. Privacy Policy, TOS, Site Map, etc)
 * @author Carlos Ravelo
 * @package Controllers
 */
class Staticpages extends MY_Controller {
	/**
	 * @param string $page_name the name of the file holding the static page
	 * @param string $not unused param for the word layout
	 * @param string $layout the name of the layout to use.
	 * @return void loads the requested page
	 */
	function index($page_name, $not = '', $layout = 'default'){
		$page_data['template_name'] = 'static/'.$page_name;
		$page_data['page_id'] = 0;
		$page_data['title'] = 'General Content';

		$this->load->view("layouts/$layout", $page_data);
	}

	/**
	 * Override for CI's default 404 handler, this allows me to have a custom 404 template
	 * @return void loads the custom 404 view
	 */
	function show_404(){
		$this->output->set_status_header('404');
		$this->load->view('static/error_404', array('title' => '404 - Page Not Found'));
	}
}
