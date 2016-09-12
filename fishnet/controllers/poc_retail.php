<?php 

class PoC_Retail extends CI_Controller {
	public $US_only;
	public $critical;

	public function index() {
		$this->load->helper('form');
		$this->load->view('poc_retailview');
	}

	public function process_input() {
		$o1 = $this->input->post('optionType1');
		$o2 = $this->input->post('optionType2');

		$title = $this->input->post('u_taskname');
		$desc = $this->input->post('u_taskdesc');

		if($o1 == "yes") {
			$US_only = TRUE;
		} else {
			$US_only = FALSE;
		}

		if ($o2 == "yes") {
			$critical = TRUE;
		} else {
			$critical = FALSE;
		}

		$this->load->model('poc_retailmodel');

		$task = array('title' => $title,
			'desc' => $desc, 'US_only' => $US_only, 'critical' => $critical);

		if ($title != "" || $desc != "") {
			$this->poc_retailmodel->insert_entry($task);
		}

		$entry_count = $this->poc_retailmodel->get_entries_count();

		$data = array('o1' => $US_only,
			'o2' => $critical, 'title' => $title, 'desc' => $desc,
				'entry_count' => $entry_count);

		$this->load->view('poc_retailview', $data);
	}

	public function show_tasks() {
		$this->load->model('poc_retailmodel');

		$model_result = $this->poc_retailmodel->get_entries();

		for ($i = 0; $i < count($model_result); $i++) {
			$row = $model_result[$i];

			echo "Task ID: ";
			echo $row->task_id;

			echo nl2br ("	Task Title: ");
			echo $row->title;

			echo nl2br ("	Task Desc: ");
			echo $row->desc;

			echo nl2br ("	US Only: ");
			echo $row->US_only;

			echo nl2br ("	Critical: ");
			echo $row->critical;

			echo nl2br ("\n");
		}
		
		$this->load->view('poc_resultsview');
	}

	public function delete_tasks() {
		$this->load->model('poc_retailmodel');

		$model_result = $this->poc_retailmodel->delete_entry();

		$entry_count = $this->poc_retailmodel->get_entries_count();

		$data = array('entry_count' => $entry_count);

		$this->load->view('poc_retailview', $data);
	}

	public function go_back() {
		$this->load->view('poc_retailview');
	}
}
?>