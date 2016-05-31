<?php
/**
 * Created by: cravelo
 * Date: 3/7/12
 * Time: 2:14 PM
 */

require_once dirname(__FILE__).'/../articles.php';

/**
 *
 * @package Controllers
 * @author  cravelo
 * @property CI_Loader $load
 * @property CI_Security $security
 * @property Daysout_model $daysout_model
 */
class Statements extends MY_Controller {
	function who_tab(){
		$this->load->model('addon/daysout_model');

		$page_data = $this->daysout_model->get_year(date('Y'));
		//var_dump($page_data);
		$this->load->view('addon/statements_daysout', $page_data);

	}
}
