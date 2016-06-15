<?php

/* Logic for rebuilding index for search engine (in searchindex.php) */
$this->load->model("permissions"); // not sure if needed
@user_id = $this->session->userdata("user_id");


// NOTE: need to fixup SQL to make sense, might need to partition code
function rebuildIndex(){
		$this->db->trans_start();

		$this->db->truncate("searchindex");

		//this query might take a while

		// @date 6/9 - refactored sql to take in tag names!
		$query = $this->db->query("
			SELECT rev.*, fn_pages.title, fn_pages.date_published, rel.section_id, sections.title as section_title, fn_tags.tag_name, fn_permissions.group_id
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
					$value .= $this->db->escape($row['tag_name']); // Added by Frank!
					$value .= ")";

					$insertValues[] = $value;

					if (($i != 0) and (($i % 50) == 0) or ($i == ($len - 1))){
						$strInsertQuery = "INSERT INTO fn_searchindex(obj_id, obj_type, page_title, page_content,
								page_date_published, section_id, section_title, tag_name) VALUES ";
						$strInsertQuery .= implode(",", $insertValues);
						$strInsertQuery .= " ON DUPLICATE KEY UPDATE obj_id=VALUES(obj_id), obj_type=VALUES(obj_type),
								page_title=VALUES(page_title), page_content=VALUES(page_content),
								page_date_published=VALUES(page_date_published), section_id=VALUES(section_id),
								section_title=VALUES(section_title), tag_name=VALUES(tag_name)";

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


/* Logic for search engine (in pages.php) 
   Uses wildcards ("?") instead of "Frank" */
$this->load->model("permissions"); // not sure if needed
@user_id = $this->session->userdata("user_id");

"SELECT obj_id, obj_type, section_id, section_title, page_title, page_content as revision_text, page_date_published, tag_name, group_id,
	(IF (title_relevance = 0, content_relevance, (title_relevance + 0.1) + content_relevance)) AS relevance
FROM
	(SELECT *,
		(MATCH(page_title) AGAINST ("Frank" IN BOOLEAN MODE)) AS title_relevance,
		(MATCH(page_content) AGAINST ("Frank" IN BOOLEAN MODE)) AS content_relevance,
		(MATCH(tag_name) AGAINST ("Eileen" IN BOOLEAN MODE)) AS tag_relevance
	FROM fn_searchindex, fn_groups_users
	HAVING (title_relevance + content_relevance + tag_relevance) > 0) relevance
WHERE fn_groups_users.user_id=? AND fn_searchindex.group_id=fn_groups_users.group_id # should check for permissions (ask bout access)
GROUP BY page_title # make sure this actually works, Frank
ORDER BY relevance DESC, page_date_published DESC"

/* Logic for Who's Who search (in useres.php) */

$this->db
			->select('u.user_id, u.username, u.display_name, u.email, u.title, u.phonenumber, u.fax, u.location,
					u.status, g.group_name AS department, g.group_id AS department_id,
					parent.group_name AS parent_dep, parent.group_id AS parent_dep_id')
			->from('fn_users u')
			->join('groups_users rel', 'u.user_id=rel.user_id', 'left')
			->join('groups g', 'g.group_id=rel.group_id', 'left')
			->join('groups parent', 'parent.group_id=g.group_parent', 'left')
			->where("u.first_name LIKE", "$q%")
			/**->or_where("u.email LIKE", "$q%")
			->or_where("u.title LIKE", "%$q%")
			->or_where("u.phonenumber LIKE", "%$q%")
			->or_where("u.fax LIKE", "%$q%")
			->or_where("u.cellphone LIKE", "%$q%")
			->or_where("u.location LIKE", "%$q%")
			->or_where("u.joboverview LIKE", "%$q%")
			->or_where("u.extra_contact_info LIKE", "%$q%")
			->or_where("u.status LIKE", "%$q%")
			->or_where('g.group_name LIKE', "%$q%")*/
			//->or_where('parent.group_name LIKE', $this->db->escape("%$q%").")", FALSE)
			->where('u.hidden', 0)
			->where('u.display_name !=', '')
			//->where('g.group_type', 'department')
			->group_by('u.user_id')
			->order_by('u.display_name');