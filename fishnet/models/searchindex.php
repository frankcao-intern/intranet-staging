<?php

# CURRENTLY WIP

/**
 * @author cravelo
 * @date Sep 22, 2010
 * @time 10:48:35 AM
 */

/**
 * Searchindex model
 * @package Models
 * @author cravelo
 * @property CI_DB_active_record $db
 */
class Searchindex extends CI_Model {
	/**
	 * @param array $data
	 * @return int the newly inserted record's ID
	 */
	function newEntry($data){
		$this->db->insert("searchindex", $data);

		if ($this->db->affected_rows() > 0){
			return $this->db->insert_id();
		} else{
			return false;
		}
	}

	/**
	 * Deletes an entry
	 * @param $page_id
	 * @return object
	 */
	function delete($page_id){
		$this->db->where('obj_id', $page_id)->delete('searchindex');

		return ($this->db->affected_rows() > 0);
	}

	/**
	 * @return int number of entries on the search index
	 */
	function rebuildIndex(){
		$this->db->trans_start();

		$this->db->truncate("searchindex");


		//this query might take a while

		// @date 6/9 - refactored sql to take in tag names!
		$query = $this->db->query("
			SELECT rev.*, fn_pages.title, fn_pages.date_published, rel.section_id, sections.title as section_title, fn_tags.tag_name, fn_tags.tag_id, fn_permissions.access
			FROM (SELECT * FROM (
					SELECT r.page_id, r.revision_text
					FROM fn_revisions r
					ORDER BY r.date_created DESC) rev1
				GROUP BY rev1.page_id) rev
			JOIN fn_pages ON rev.page_id=fn_pages.page_id
			JOIN fn_templates ON fn_pages.template_id=fn_templates.template_id
			JOIN fn_tag_matches ON rev.page_id=fn_tag_matches.page_id # added by Frank!
			JOIN fn_tags ON fn_tag_matches.tag_id=fn_tags.tag_id # added by Frank!
			JOIN fn_permissions ON fn_permissions.page_id=rev.page_id # added by Frank!
			LEFT JOIN fn_pages_pages rel ON rel.page_id=fn_pages.page_id
			LEFT JOIN fn_pages sections ON rel.section_id=sections.page_id
			WHERE fn_pages.published=1 AND fn_pages.deleted=0 AND fn_templates.page_type<>'system'
		");
		$queryArr = $query->result_array();

		$len = count($queryArr);
		$pages = 0;
		if ($len > 0){
			$insertValues = array();

			for ($i = 0; $i < $len; $i++){
				$row = $queryArr[$i];
				if ($row['revision_text'] != ""){
					//$row['revision_text'] = json_decode($row['revision_text']);
					$row['revision_text'] = strip_tags($row['revision_text']);
					//print_r($row);
					$value = "(";
					$value .= $row['page_id'].",";
					$value .= "'page',";
					$value .= $this->db->escape($row['title']).",";
					$value .= $this->db->escape($row['revision_text']).",";
					$value .= $this->db->escape($row['date_published']).",";
					$value .= $this->db->escape($row['section_id']).",";
					$value .= $this->db->escape($row['section_title']).",";
					$value .= $this->db->escape($row['tag_name']).","; // Added by Frank!
					$value .= $this->db->escape($row['tag_id']).","; // Added by Frank!
					$value .= $this->db->escape($row['access']); // Added by Frank!
					$value .= ")";

					$insertValues[] = $value;

					if (($i != 0) and (($i % 50) == 0) or ($i == ($len - 1))){
						$strInsertQuery = "INSERT INTO fn_searchindex(obj_id, obj_type, page_title, page_content,
								page_date_published, section_id, section_title, tag_name, tag_id, access) VALUES ";
						$strInsertQuery .= implode(",", $insertValues);
						$strInsertQuery .= " ON DUPLICATE KEY UPDATE obj_id=VALUES(obj_id), obj_type=VALUES(obj_type),
								page_title=VALUES(page_title), page_content=VALUES(page_content),
								page_date_published=VALUES(page_date_published), section_id=VALUES(section_id),
								section_title=VALUES(section_title), tag_name=VALUES(tag_name), tag_id=VALUES(tag_id), access=VALUES(access)";

						//echo "$i of ".($len - 1)."<br><br>";
						$this->db->query($strInsertQuery);

						if ($this->db->affected_rows() == 0){
							return 0;
						}else{
							$pages += $this->db->affected_rows();
							$insertValues = array();
						}
					}
				}
			}

			$this->db->trans_complete();
		}

		return $pages;
	}
}