<?php
/**
 * @author cravelo
 * @date Sep 22, 2010
 * @time 11:47:59 AM
 */

/**
 * The admin interface controller
 * @package Controllers
 * @author cravelo
 * @property CI_DB_active_record $db
 * @property CI_Loader $load
 * @property CI_Output $output
 * @property Searchindex $searchindex
 * @property CI_Migration $migration
 */
class Admin extends CI_Controller {
	private $page_data;

	public function __construct(){
		parent::__construct();

		$user_name = $this->session->userdata('username');
		if ($user_name === false){
			redirect("/login/index".$this->uri->uri_string());
		}else{
			if (!($this->session->userdata('role') == 'admin')){
				redirect('home');
			}
		}

		$this->page_data = array();
	}

	function index(){
		$page_data['template_name'] = "sys_administration";
		$page_data['page_id'] = 0;
		$page_data['title'] = 'Administration';

		$page_data = array_merge($this->page_data, $page_data);

		$this->load->view('layouts/default', $page_data);
	}

	function rebuildindex(){
		set_time_limit(600); //10 minutes

		$this->load->model('searchindex');
		$ar = $this->searchindex->rebuildIndex();

		$this->page_data['results']  = "The script completed, affected rows: $ar<br><br>\n";
		$this->page_data['results'] .= "if affected rows is 0 something went wrong <br><br>\n";

		$this->index();
	}

	function backup(){
		// Load the DB utility class
		$this->load->dbutil();

		// Backup your entire database and assign it to a variable
		$backup =& $this->dbutil->backup();
		$uploadDir = dirname($_SERVER['SCRIPT_FILENAME'])."/../uploads/";

		// Load the file helper and write the file to your server
		$this->load->helper('file');
		write_file($uploadDir.'backup.gz', $backup);

		// Load the download helper and send the file to your desktop
		$this->load->helper('download');
		force_download('backup.gz', $backup);
	}

	function fixLinks(){
		$revisions = $this->db->from('revisions')
				->get()
				->result_array();

		//echo $this->db->last_query();

		for ($i = 0; $i < count($revisions); $i++){
			$revision_text = $revisions[$i]['revision_text'];

			$revision_text = preg_replace('/http:\\\\\/\\\\\\/effs02\\\\\//i', 'http:\/\/docs.eileenfisher.net\/', $revision_text);
			$revision_text = preg_replace('/effs02.eileenfisher.net/i', 'docs.eileenfisher.net', $revision_text);
			$revision_text = preg_replace('/\\\\\/news\\\\\/(\d+)/i', '\/monthly\/$1', $revision_text);

			//echo $revision_text;

			$this->db
				->where('revision_id', $revisions[$i]['revision_id'])
				->set('revision_text', $revision_text)
				->update('revisions');
		}

		$ar = $this->db->affected_rows();
		$this->page_data['results']  = "The script completed, affected rows: $ar<br><br>\n";

		$this->index();
	}

	function migrate(){
		$this->load->library('migration');
		$this->migration->latest();

		$this->index();
	}

	function fixGalleries(){
		$this->db->trans_start();

		$ar = 0;

		$this->db
			->set('template_name', 'user/old_gallery')
			->set('category', 'system')
			->where('template_id', 21)
			->update('templates');

		$ar += $this->db->affected_rows();

		$this->db
			->set('template_name', 'user/old_gallery_descriptions')
			->set('category', 'system')
			->where('template_id', 28)
			->update('templates');

		$ar += $this->db->affected_rows();

		$this->db
			->set('template_name', 'user/gallery')
			->set('template_title', 'Gallery')
			->where('template_id', 45)
			->update('templates');

		$ar += $this->db->affected_rows();

		$this->db
			->set('template_name', 'user/gallery_descriptions')
			->set('template_title', 'Gallery with Descriptions')
			->where('template_id', 41)
			->update('templates');

		$ar += $this->db->affected_rows();

		$this->page_data['results'] = "The script completed, affected rows: $ar of 4<br>\n";

		$this->db->trans_complete();

		$this->index();
	}

	function fixRedirects(){
		$this->db
			->set('redirect_url', 'MID(`redirect_url`, 2, LENGTH(`redirect_url`) - 1)', false)
			->where('redirect_url IS NOT NULL')
			->update('pages');

		$pr = $this->db->affected_rows();

		$this->db
			->set('redirect_url', 'MID(`redirect_url`, 2, LENGTH(`redirect_url`) - 1)', false)
			->where('redirect_url IS NOT NULL')
			->update('templates');

		$tr = $this->db->affected_rows();

		$this->db
			->set('template_name', "CONCAT('user/', `template_name`)", false)
			->where('template_id', 2)
			->or_where('template_id', 6)
			->or_where('template_id', 10)
			->update('templates');

		$tr2 = $this->db->affected_rows();

		$this->db
			->set('template_name', 'sys_retail_news')
			->where('template_id', 48)
			->update('templates');

		$tr2 += $this->db->affected_rows();

		$this->page_data['results'] = "The script completed, affected rows for pages: $pr<br>\n";
		$this->page_data['results'] .= "Affected rows for templates: $tr<br><br>\n";
		$this->page_data['results'] .= "Affected rows for templates: $tr2<br><br>\n";

		$this->index();
	}

	function changeGroupNames(){
		$this->db->query("UPDATE IGNORE fn_groups g, fn_users u SET g.group_name=u.display_name
			WHERE g.group_type='security'
			AND g.group_name=u.username
		");

		$this->page_data['results'] = "Afftected Rows = {$this->db->affected_rows()}<br>\n";

		$this->index();
	}

	function importSpecialtyStores(){
		/**
		 * @var CI_DB_active_record $import
		 * @var CI_DB_result $query
		 */
		$import = $this->load->database('import', true);
		$query = $import->get('s_stores');
		$stores = array();
		$this->page_data['results']  = "The import table is empty<br><br>";

		if ($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$stores[] = array(
					"number" => $row['Customer'],
					"name" => $row['Cust Name'],
					"street_address1" => $row['Address 1'],
					"street_address2" => $row['Address 2'],
					"city" => $row['City'],
					"state" => $row['U.S. States'],
					"zip" => $row['Zip'],
					"country" => $row['Cntry'],
					"phone" => $row['Primary Contact Access #'],
					'contact_name' => $row['Primary Contact Name'],
					'latitude' => $row['latitude'],
					'longitude' => $row['longitude']
				);
			}

			$this->page_data['results']  = "The script completed successfully<br>\n";
			$columns = array_keys($stores[0]);
			$ar = 0;
			$insert_query = $this->db->insert_batch_string('stores', $stores);

			foreach($insert_query as $q){
				$update_query = " ON DUPLICATE KEY UPDATE ";
				$update_query_arr = array();
				foreach($columns as $column){
					$update_query_arr[] = "$column=VALUES($column)";
				}
				$update_query .= implode(',', $update_query_arr);
				$query = str_replace(';', "$update_query;", $q);
				//echo $query;
				if ($this->db->query($query) === false){
					$this->page_data['results']  = "The update didnt work!<br><br>\n";
					break;
				}

				$ar += $this->db->affected_rows();
			}

			$this->page_data['results'] .= "Afftected Rows = $ar<br>\n";
		}

		$this->index();
	}

	function hrUsersUpdate(){
		/**
		 * @var CI_DB_active_record $import
		 * @var CI_DB_result $query
		 */
		$import = $this->load->database('import', true);
		$query = $import->get('employees');
		$this->page_data['results']  = "The import table is empty<br><br>";

		if ($query->num_rows() > 0){
			$this->page_data['results']  = "The script completed<br>\n";

			$users = array();
			foreach ($query->result_array() as $row){
				if (!empty($row['PERSON_ID'])){
					$users[] = array(
						"person_id" => $row['PERSON_ID'],
						"first_name" => $row['FIRST_NAME'],
						"last_name" => $row['LAST_NAME'],
						"display_name" => utf8_encode($row['FIRST NAME (PREFERRED)'] ." ". $row['LAST NAME (PREFERRED)']),
						"location" => $row['WORK LOCATION'],
						"phonenumber" => $row['DIRECT DIAL'],
						'title' => $row['TITLE'],
						'start_date' => $row['ANNIVERSARY DATE'],
						'payroll_id' => $row['ADP FILE NB'],
						'rate_type' => $row['RATE TYPE'],
						'leader' => $row['LEADER ID']
					);
				}
			}

			if ($this->db->where('hidden', 0)->update_batch('users', $users, 'person_id') === false){
				$this->page_data['results'] .= "Update didnt work!<br><br>\n";
			}else{
				$this->page_data['results'] .= "Afftected Rows: {$this->db->affected_rows()}<br>\n";
				echo $this->db->last_query();
			}

			//echo $this->db->last_query();
		}

		/**
		 * Insert query, use manually for special cases
		 * INSERT INTO `fn_users`(
		`person_id`,
		`first_name`,
		`last_name`,
		`display_name`,
		`location`,
		`phonenumber`,
		`title`,
		`start_date`,
		`payroll_id`,
		`rate_type`,
		`leader`
		)
		SELECT
		PERSON_ID,
		FIRST_NAME,
		LAST_NAME,
		CONCAT_WS(' ', `FIRST NAME (PREFERRED)`, `LAST NAME (PREFERRED)`),
		`WORK LOCATION`,
		`DIRECT DIAL`,
		TITLE,
		`ANNIVERSARY DATE`,
		`ADP FILE NB`,
		`RATE TYPE`,
		`LEADER ID`
		FROM import.employees e
		WHERE e.PERSON_ID IN (PERSON_IDs go here)
		 */

		$this->index();
	}

	function hrUsersDepartmentUpdate(){
		$this->page_data['results'] = "Completed!<br>\n";
		$ar = 0;

		// delete all group relationships
		if ($this->db->query("DELETE FROM fn_groups_users
			WHERE group_id IN  (
				SELECT group_id FROM fn_groups WHERE group_type='department'
			)
			AND user_id IN (
				SELECT user_id FROM fn_users u
				JOIN import.employees e ON e.person_id=u.person_id
			)") === false)
		{
			$this->page_data['results'] .= "Deleting didnt work!<br>\n";
		}else{
			// sub groups a.k.a. real departments
			if ($this->db->query("INSERT IGNORE INTO fn_groups_users(group_id, user_id)
				SELECT g.group_id, u.user_id
				FROM fn_groups g
				JOIN import.employees e ON e.department=g.group_name
				JOIN fn_users u ON e.person_id=u.person_id
				WHERE g.group_type='department'"
			) === false)
			{
				$this->page_data['results'] .= "Updating real departments didnt work!<br><br>\n";
			}else{
				$ar = $this->db->affected_rows();
				// Intranet Groups a.k.a. Umbrella groups i.e. People and culture
				if ($this->db->query("INSERT IGNORE INTO fn_groups_users(group_id, user_id)
					SELECT g.group_id, u.user_id
					FROM fn_groups g
					JOIN import.employees e ON e.`intranet group`=g.group_name
					JOIN fn_users u ON e.person_id=u.person_id
					WHERE g.group_type='department'
					AND u.user_id NOT IN (
						SELECT rel.user_id FROM fn_groups_users rel
					    JOIN fn_groups g ON g.group_id=rel.group_id
					    WHERE g.group_type='department'
					)
				") === false)
				{
					$this->page_data['results'] .= "Updating intranet departments didnt work!<br>\n";
				}else{
					$ar += $this->db->affected_rows();
				}
			}
		}

		$this->page_data['results'] .= "Afftected Rows: $ar<br>\n";

		$this->index();
	}

	function fixPermissions(){
		$perm_publish = PERM_PUBLISH;
		$perm_delete = PERM_DELETE;

		$this->db->query("
			UPDATE fn_permissions perm, fn_pages p, fn_templates t
			SET perm.access = perm.access + $perm_publish - $perm_delete
			WHERE perm.access & $perm_delete = $perm_delete
				AND perm.access & $perm_publish <> $perm_publish
				AND p.page_id=perm.page_id
				AND p.template_id=t.template_id
				AND (t.page_type='section'
				OR  t.page_type='calendar')
		");

		$this->page_data['results'] = "Afftected Rows: {$this->db->affected_rows()}<br>\n";

		$this->index();
	}

	function updateAdsAndPlacements(){
		//Advertising
		$this->db->query("
			UPDATE `fn_pages_pages` pp, fn_tag_matches tm
			SET pp.section_id=19
			WHERE tm.page_id=pp.page_id
				AND pp.section_id=14
				AND tm.tag_id=161
		");

		//Placement
		$this->db->query("
			UPDATE `fn_pages_pages` pp, fn_tag_matches tm
			SET pp.section_id=20
			WHERE tm.page_id=pp.page_id
				AND pp.section_id=14
				AND tm.tag_id=160
		");

		$this->db
			->set('revision_text', '\'{"announcements":1,"color":"#5E759E","buckets":[
					{"name":"news","section_id":2,"tags":null,"limit":12,"featured":null,"wordcount":45,"random":null},
					{"name":"btl","section_id":"6","tags":null,"limit":1,"featured":1,"wordcount":15,"random":null},
					{"name":"video","section_id":13,"tags":null,"limit":1,"featured":1,"wordcount":15,"random":null},
					{"name":"journal","section_id":10,"tags":null,"limit":1,"featured":null,"wordcount":15,"random":null},
					{"name":"ads","section_id":19,"tags":null,"limit":1,"featured":null,"wordcount":null,"random":null},
					{"name":"placements","section_id":20,"tags":null,"limit":1,"featured":null,"wordcount":null,"random":null}
				],
				"model_before_name":"phpbb","model_before_func":"getLatestTopics","model_before_param":null,
				"model_after_name":"users","model_after_func":"getWeekAnniversaries","model_after_param":null,
				"back_link_id":1}\'', false)
			->where('revision_id', 1)
			->update('revisions');

		$this->db
			->set('revision_text', '\'{"back_link_id":1,"buckets":[
					{"name":"ads","section_id":19,"tags":"Advertising","limit":null,"featured":null,"wordcount":null,"random":null},
					{"name":"placements","section_id":20,"tags":"Placement","limit":null,"featured":null,"wordcount":null,"random":null}
				],"color":"#5E759E"}\'', false)
			->where('revision_id', 14)
			->update('revisions');

		$this->page_data['results'] = "Afftected Rows: {$this->db->affected_rows()}<br>\n";

		$this->index();
	}
}
