<?php
/**
 * @author cravelo
 */

/**
 * Pages model
 *
 * @package Models
 * @author  cravelo
 * @property CI_DB_active_record $db
 * @property CI_Session $session
 */
class Pages extends CI_Model {
	/**
	 * Get page record and attach the text
	 *
	 * @param int $page_id
	 * @return array
	 */
	function getPage($page_id) {
		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db
				->select('pages.*, templates.template_name, templates.category, templates.page_type,
							templates.redirect_url as default_redirect,
							users.display_name AS creator_name, users.username AS creator')
				->from('pages')
				->join('templates', 'pages.template_id=templates.template_id')
				->join('users', 'pages.created_by=users.user_id')
				->where('pages.page_id', $page_id)
				->where('pages.deleted', 0)
				->limit(1)
				->get();

		if ($query and ($query->num_rows() > 0)) {
			$page = $query->row_array();
			$query = $this->db
					->select('pages.title as section_title, pages_pages.section_id')
					->join('pages', 'pages.page_id=pages_pages.section_id')
					->where('pages_pages.page_id', $page['page_id'])
					->get('pages_pages');
			$page['sections'] = $query->result_array();

			//see if the page provides an override to the default routing
			if (!isset($page['redirect_url'])){ $page['redirect_url'] = $page['default_redirect']; }

			$this->db
					->where('page_id', $page['page_id'])
					->set('page_views', ((int)$page['page_views']) + 1)
					->update('pages');

			return $page;
		} else { //we didn't find that page
			return false;
		}
	}

	/**
	 * Updates the page record
	 * @param int $page_id
	 * @param array $data
	 * @return bool
	 */
	function updatePage($page_id, $data) {
		if (!isset($data)) { return false; }
		if (count($data) == 0) { return true; }

		return $this->db->where('page_id', $page_id)
				->update('pages', $data);
	}

	/**
	 * create a page
	 *
	 * @param array $recordArr
	 * @return int
	 */
	function newPage($recordArr) {
		//print_r($recordArr);
		$this->db->insert('pages', $recordArr);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	/**
	 * @param int $page_id
	 * @return bool
	 */
	function deletePage($page_id) {
		//delete the page record, should cascade to permissions, revisions and comments
		$this->db->where('page_id', $page_id)->delete('pages');

		return ($this->db->affected_rows() > 0);
	}

	/**
	 * this function gets a list of page_id and title for the Who's Who profile page "My Drafts"
	 *
	 * @param $user_id
	 * @return array
	 */
	function drafts($user_id) {
		$perm_write = PERM_WRITE;
		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db
			->select('pages.page_id, pages.title')
			->from('pages')
			->join('permissions p', 'p.page_id=pages.page_id')
			->join('groups_users rel', "rel.group_id=p.group_id")
			->where('rel.user_id', $user_id)
			->where('pages.published', '0')
			->where('pages.deleted', '0')
			->where("p.access & $perm_write=", $perm_write)
			->order_by('pages.date_published', 'DESC')
			->get();

		if ($query and ($query->num_rows() > 0)) {
			return $query->result_array();
		} else {
			return array();
		}
	}

	/**
	 * Get a list of page_id and title that were created by the profile owner and that the visitor has read access to.
	 *
	 * @param int $user_id
	 * @return array
	 */
	function my_pages($user_id) {
		$SID_WHOS_WHO = SID_WHOS_WHO;
		$perm_read    = PERM_READ;
		$visitor      = $this->session->userdata('user_id');
		/**
		 * @var CI_DB_result $query
		 */
		$query        = $this->db->query("
			SELECT fn_pages.page_id, fn_pages.title
			FROM fn_pages
			JOIN fn_permissions p ON p.page_id=fn_pages.page_id
			JOIN fn_groups_users rel ON rel.user_id=$visitor
			JOIN fn_pages_pages prel ON prel.page_id=fn_pages.page_id
			WHERE prel.section_id=$SID_WHOS_WHO
				AND fn_pages.created_by=$user_id
				AND fn_pages.published=1
				AND fn_pages.deleted=0
				AND p.group_id=rel.group_id
				AND p.access & $perm_read=$perm_read
			GROUP BY fn_pages.page_id
			ORDER BY fn_pages.date_published DESC
		");

		if ($query and ($query->num_rows() > 0)) {
			return $query->result_array(); //return everything as array, let the controller take care of handling the data.
		} else {
			return array();
		}
	}

	/**
	 * This function returns a list of all private pages this user has access to read.
	 *
	 * @param int $user_id the user_id to get the private pages
	 * @return array
	 */
	function my_private($user_id) {
		$perm_read    = PERM_READ;
		$SID_PRIVATE = SID_PRIVATE;
		/**
		 * @var CI_DB_result $query
		 */
		$query        = $this->db->query("
			SELECT fn_pages.page_id, fn_pages.title
			FROM fn_pages
			JOIN fn_permissions p ON p.page_id=fn_pages.page_id
			JOIN fn_groups_users rel ON rel.user_id=$user_id
			JOIN fn_pages_pages prel ON prel.page_id=fn_pages.page_id
			WHERE prel.section_id=$SID_PRIVATE
				AND fn_pages.published=1
				AND fn_pages.deleted=0
				AND p.group_id=rel.group_id
				AND p.access & $perm_read=$perm_read
			GROUP BY fn_pages.page_id
			ORDER BY fn_pages.date_published
		");
		//echo $this->db->last_query();

		if ($query and ($query->num_rows() > 0)) {
			return $query->result_array();
		} else {
			return array();
		}
	}

	/**
	 * Search Engine
	 * Get results by section (to show them together), relevance desc and date_created desc
	 *
	 * @param string $query     the query
	 * @param int    $offset    the limit offset for pagination
	 * @param int    $limit      how many results to get
	 * @param int    $section_id if this is set it will only return results for that section
	 * @return array of page objects
	 */
	function getSearchResults($query, $offset = 0, $limit = null, $section_id = null) {
		$section_id     = isset($section_id) ? "WHERE section_id = $section_id" : '';
		$section_id     = ($section_id == '0') ? "WHERE section_id IS NULL" : $section_id;
		$limit = isset($limit) ? "LIMIT $offset, $limit" : '';

		$perm_read    = PERM_READ;
		$visitor      = $this->session->userdata('user_id');

		$search_sel = "
			SELECT obj_id, obj_type, section_id, section_title, page_title, page_content as revision_text,
				page_date_published, tag_name, page_id,
				(IF (title_relevance = 0, content_relevance + tag_relevance, MAX((title_relevance + 0.1) + content_relevance + tag_relevance))) AS relevance
			FROM
				(SELECT *,
					(MATCH(page_title) AGAINST (? IN BOOLEAN MODE)) AS title_relevance,
					(MATCH(page_content) AGAINST (? IN BOOLEAN MODE)) AS content_relevance,
					(MATCH(tag_name) AGAINST (? in BOOLEAN MODE)) AS tag_relevance
				FROM fn_searchindex
				HAVING (title_relevance + content_relevance + content_relevance + tag_relevance) > 0) relevance
				$section_id
			GROUP BY page_id
			ORDER BY relevance DESC, page_date_published DESC
			$limit
		";
		//echo $search_sel;

		/**
		 * @var CI_DB_result $search_query
		 */
		$search_query = $this->db->query($search_sel, array($query, $query, $query));
		//echo $this->db->last_query();

		if ($search_query and ($search_query->num_rows() > 0)) {
			return $search_query->result_array();
		} else {
			return array();
		}
	}

	/**
	 * Search Engine
	 * Counts total results for a query
	 *
	 * @param string $query     the query
	 * @param int    $section_id if this is set it will only return results for that section
	 * @return array of page objects
	 */
	function getSearchResults_count($query, $section_id = null) {
		$section_id     = isset($section_id) ? "WHERE section_id = $section_id" : '';
		$section_id     = ($section_id == '0') ? "WHERE section_id IS NULL" : $section_id;

		$search_sel = "
			SELECT COUNT(*) AS results_count
			FROM
				(SELECT
					(MATCH(page_title) AGAINST (? IN BOOLEAN MODE)) AS title_relevance,
					(MATCH(page_content) AGAINST (? IN BOOLEAN MODE)) AS content_relevance
				FROM fn_searchindex
				HAVING (title_relevance + content_relevance) > 0) relevance
				$section_id
		";
		//echo $search_sel;

		/**
		 * @var CI_DB_result $search_query
		 */
		$search_query = $this->db->query($search_sel, array($query, $query));
		//echo $this->db->last_query();

		if ($search_query and ($search_query->num_rows() > 0)) {
			$results_count = $search_query->row_array();
			return $results_count['results_count'];
		} else {
			return array();
		}
	}

	/**
	 * Get any property off a page given the page_id and the name of the property
	 *
	 * @param int    $page_id the page id
	 * @param string $prop    the name the property
	 * @return mixed The value of the requested property
	 */
	function getPageProperty($page_id, $prop) {
		$query = $this->db->select($prop)->where("page_id", $page_id)->get('pages');

		if ($query->num_rows() > 0) {
			$query = $query->row_array();
			return $query[$prop];
		} else {
			return null;
		}
	}

	/**
	 * get the deleted pages where user_id has PERM_DELETE
	 *
	 * @param int $user_id
	 * @return array with the returned pages
	 */
	function trashcan($user_id) {
		$perm_delete = PERM_DELETE;
		/**
		 * @var CI_DB_result $query
		 */
		$query       = $this->db->query("
			SELECT fn_pages.page_id, fn_pages.title
			FROM fn_pages
			JOIN fn_permissions p ON p.page_id=fn_pages.page_id
			JOIN fn_groups_users rel ON rel.user_id=$user_id
			WHERE fn_pages.deleted=1
				AND p.group_id=rel.group_id
				AND p.access & $perm_delete=$perm_delete
			ORDER BY fn_pages.title
		");

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return null;
		}
	}

	/**
	 * Get pages published to a section that the current user can read
	 *
	 * @param int    $section_id the section id
	 * @param string $d_start    String with mysql formatted date for the start of the range
	 * @param string $d_end      String with mysql formatted date for the end of the range
	 * @param int    $featured   1 or 0
	 * @param int    $limit      how many page records should be returned
	 * @param int    $offset     where to start getting records (for pagination)
	 * @param int    $random     1 or 0 whether to choose pages at random
	 * @param int    $tag_id     a tag id to retrieve only pages that are tagged with it.
	 * @param string $order_by   the column to use to order the pages
	 * @return array of page records matching params
	 */
	function getForSection($section_id = null, $d_start = null, $d_end = null, $featured = null, $limit = null,
	                            $offset = null, $random = null, $tag_id = null, $order_by = null) {
		//$this->output->enable_profiler(TRUE);

		$section      = (isset($section_id)) ? "AND rel.section_id=$section_id" : '';
		$section_join = (isset($section_id)) ? "JOIN fn_pages sections ON sections.page_id=$section_id" : '';
		$section_id   = (isset($section_id)) ? ", sections.title AS section_title, $section_id AS section_id" : '';
		$dates        = (($d_start != null) or ($d_end != null)) ? "AND fn_pages.date_published<='$d_end' AND (fn_pages.show_until IS NULL OR fn_pages.show_until>='$d_start')" : '';
		$limit        = (isset($limit)) ? "LIMIT " . (isset($offset) ? $offset : '0') . ",$limit" : '';
		$random       = (isset($random)) ? "RAND()," : '';
		$featured     = (isset($featured)) ? "AND fn_pages.featured=$featured" : '';

		//tags
		$tags     = (isset($tag_id)) ? "AND fn_tag_matches.tag_id=$tag_id" : '';
		$tag_join = (isset($tag_id)) ? "JOIN fn_tag_matches ON fn_tag_matches.page_id=fn_pages.page_id" : '';

		$order_by  = (isset($order_by)) ? "$order_by DESC, " : '';
		$perm_read = PERM_READ;
		$user_id   = $this->session->userdata('user_id');

		$query_str = "SELECT fn_pages.*, fn_templates.template_name, rev.revision_text $section_id, comm.comments_count
			FROM fn_pages
			$section_join
			JOIN fn_templates ON fn_pages.template_id=fn_templates.template_id
			LEFT JOIN fn_pages_pages rel ON rel.page_id=fn_pages.page_id
			LEFT JOIN (
				SELECT COUNT(comment_id) as comments_count, page_id FROM fn_comments GROUP BY page_id
			) comm ON comm.page_id=fn_pages.page_id
			JOIN (SELECT * FROM (
					SELECT rev.page_id, rev.revision_text, rev.date_created as rev_date_created
					FROM fn_revisions rev
					ORDER BY rev.date_created DESC) revs
				GROUP BY revs.page_id) rev ON rev.page_id=fn_pages.page_id
			JOIN (SELECT BIT_OR(p.access) as bor, p.page_id
				FROM fn_permissions p
				JOIN fn_groups ON p.group_id=fn_groups.group_id
				JOIN fn_groups_users rel ON rel.group_id=fn_groups.group_id
				WHERE rel.user_id=$user_id
				GROUP BY p.page_id) perm ON perm.page_id = fn_pages.page_id
			$tag_join
			WHERE fn_pages.published=1
				AND fn_pages.deleted=0
				AND (perm.bor & $perm_read) = $perm_read
				$section
				$dates
				$featured
				$tags
			GROUP BY fn_pages.page_id
			ORDER BY $random $order_by fn_pages.date_published DESC $limit";

		//echo $query_str;
		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db->query($query_str);

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return array();
		}
	}

	/**
	 * Does the same query as getForSection but selects the count(*) without limit or ordering.
	 *
	 * @param int    $section_id the section id
	 * @param string $d_start    String with mysql formatted date for the start of the range
	 * @param string $d_end      String with mysql formatted date for the end of the range
	 * @param int    $featured   1 or 0
	 * @param int    $limit      how many page records should be returned
	 * @param int    $offset     where to start getting records (for pagination)
	 * @param int    $random     1 or 0 whether to choose pages at random
	 * @param int    $tag_id     a tag id to retrieve only pages that are tagged with it.
	 * @param string $order_by   the column to use to order the pages
	 * @return int the number of rows resulting from the query
	 */
	function getForSection_count($section_id = null, $d_start = null, $d_end = null, $featured = null,
	                             $limit = null, $offset = null, $random = null, $tag_id = null, $order_by = null) {
		$section      = (isset($section_id)) ? "AND rel.section_id=$section_id" : '';
		$section_join = (isset($section_id)) ? "JOIN fn_pages sections ON sections.page_id=$section_id" : '';
		$dates        = (($d_start != null) or ($d_end != null)) ? "AND fn_pages.date_published<='$d_end' AND (fn_pages.show_until IS NULL OR fn_pages.show_until>='$d_start')" : '';
		$featured     = (isset($featured)) ? "AND fn_pages.featured=$featured" : '';
		$tags         = (isset($tag_id)) ? "AND fn_tag_matches.tag_id=$tag_id" : '';
		$tag_join     = (isset($tag_id)) ? "JOIN fn_tag_matches ON fn_tag_matches.page_id=fn_pages.page_id" : '';
		$perm_read = PERM_READ;
		$user_id   = $this->session->userdata('user_id');
		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db->query("
			SELECT count(*) as num_rows FROM fn_pages
			$section_join
			JOIN fn_templates ON fn_pages.template_id=fn_templates.template_id
			LEFT JOIN fn_pages_pages rel ON rel.page_id=fn_pages.page_id
			LEFT JOIN (
				SELECT COUNT(comment_id) as comments_count, page_id FROM fn_comments GROUP BY page_id
			) comm ON comm.page_id=fn_pages.page_id
			JOIN (SELECT * FROM (
					SELECT rev.page_id, rev.revision_text, rev.date_created as rev_date_created
					FROM fn_revisions rev
					ORDER BY rev.date_created DESC) revs
				GROUP BY revs.page_id) rev ON rev.page_id=fn_pages.page_id
			JOIN (SELECT BIT_OR(p.access) as bor, p.page_id
				FROM fn_permissions p
				JOIN fn_groups ON p.group_id=fn_groups.group_id
				JOIN fn_groups_users rel ON rel.group_id=fn_groups.group_id
				WHERE rel.user_id=$user_id
				GROUP BY p.page_id) perm ON perm.page_id = fn_pages.page_id
			$tag_join
			WHERE fn_pages.published=1
				AND fn_pages.deleted=0
				AND (perm.bor & $perm_read) = $perm_read
				$section
				$dates
				$featured
				$tags
		");

		//echo $this->db->last_query();

		if ($query->num_rows() > 0) {
			$result = $query->row_array();
			return $result['num_rows'];
		} else {
			return 0;
		}
	}

	/**
	 * Get the list of sections a user has access to publish a page to and also what sections the page is already published to.
	 *
	 * @param int $user_id the user to check permissions against
	 * @param int $page_id the page id to get the sections that are already published
	 * @return array with section_id, title and information of whether the page is already published and permission.
	 */
	function getUserPublish($user_id, $page_id) {
		$perm_publish = PERM_PUBLISH;

		/**
		 * @var CI_DB_result $query
		 */
		$query = "SELECT fn_pages.page_id, fn_pages.title, (rel.page_id IS NOT NULL) as selected,
					(perms.page_id IS NOT NULL) as permPublish
				FROM fn_pages
				JOIN fn_templates ON fn_pages.template_id=fn_templates.template_id
				LEFT JOIN (
					SELECT * FROM fn_pages_pages WHERE page_id=$page_id
				) rel ON rel.section_id = fn_pages.page_id
				LEFT JOIN (
					SELECT page_id FROM fn_permissions s
					JOIN fn_groups_users grel ON grel.group_id=s.group_id
					WHERE grel.user_id=$user_id
					 AND s.access & $perm_publish=$perm_publish
				) perms ON perms.page_id=fn_pages.page_id
				WHERE fn_templates.page_type='section'
				GROUP BY fn_pages.page_id
				ORDER BY fn_pages.title";

		$query = $this->db->query($query);

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return array();
		}
	}

	/**
	 * Publishes a page to the selected sections.
	 *
	 * @param array $sections section_ids to publish this page to
	 * @param int   $page_id  the page to publish to the sections on $sections
	 * @return bool return whether the publish was successful or not.
	 */
	function publishPage($page_id, $sections) {
		if (is_numeric($page_id) and is_array($sections)) {
			//update section-page-relationships
			$this->db->where('page_id', $page_id)->delete('pages_pages');
			if (count($sections) > 0) {
				$values = array();
				foreach ($sections as $section_id) {
					if (is_numeric($section_id)) {
						$values[] = array(
							'section_id' => $section_id,
							'page_id' => $page_id
						);
					}
				}

				return $this->db->insert_batch('pages_pages', $values);
			}else{
				return true;
			}
		} else {
			return false;
		}
	}

	/**
	 * Get the list of sections a user has access to publish an event to and also what sections
	 * the event is already published to.
	 *
	 * @param int $user_id  the user to check permissions against
	 * @param int $event_id the event id to get the sections that are already published
	 * @return array with section_id, title and information of whether the event is already published and permission.
	 */
	function getPublishForEvent($user_id, $event_id) {
		$perm_publish = PERM_PUBLISH;

		/**
		 * @var CI_DB_result $query
		 */
		$query = "SELECT fn_pages.page_id, fn_pages.title, (rel.event_id IS NOT NULL) as selected, (perms.page_id IS NOT NULL) as permPublish
				FROM fn_pages
				JOIN fn_templates ON fn_pages.template_id=fn_templates.template_id
				LEFT JOIN (
					SELECT * FROM fn_pages_events WHERE event_id=$event_id
				) rel ON rel.page_id = fn_pages.page_id
				LEFT JOIN (
					SELECT page_id FROM fn_permissions s
					JOIN fn_groups_users grel ON grel.group_id=s.group_id
					WHERE grel.user_id=$user_id
					 AND s.access & $perm_publish=$perm_publish
				) perms ON perms.page_id=fn_pages.page_id
				WHERE fn_templates.page_type<>'page'
				ORDER BY fn_pages.title";

		$query = $this->db->query($query);

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return array();
		}
	}

	/**
	 * Publishes an event to the selected sections.
	 *
	 * @param array $sections section_ids to publish this page to
	 * @param int   $event_id the event to publish to the sections on $sections
	 * @return bool return whether the publish was successful or not.
	 * @throws Exception
	 */
	function publishEvent($sections, $event_id) {
		if (is_numeric($event_id)) {
			//update section-page-relationships
			$this->db->where('event_id', $event_id)->delete('pages_events');
			if (count($sections) > 0) {
				$values = array();
				foreach ($sections as $section_id) {
					if (is_numeric($section_id)) {
						$values[] = "($section_id, $event_id)";
					}
				}
				$this->db->query("INSERT INTO fn_pages_events(page_id, event_id) VALUES" . implode(',', $values));

				if ($this->db->affected_rows() == 0) {
					throw new Exception('Error publishing the event. Try again later, if the problem persists contact '.
						'the helpdesk.', E_CAL_PUBLISH);
				}
			}
		} else {
			throw new Exception('The event ID is not in a valid format.', E_CAL_INVALID_EVENT_ID);
		}
	}

	/**
	 * @param string $name part of or complete page title
	 * @param string $type ['calendar', 'section', 'page']
	 * @return array
	 */
	function getByNameAndType($name, $type = null) {
		$this->db
			->select('pages.page_id, pages.title')
			->from('pages')
			->where('title LIKE', "$name%");

		if (isset($type)) {
			$this->db
				->join('templates', 'pages.template_id=templates.template_id')
				->where('templates.page_type', $type);
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return array();
		}
	}

	/**
	 * Gets all page records that are tagged with $tag_id OR $tag_name
	 *
	 * @param      $tag_name
	 * @param null $tag_id
	 * @return array
	 */
	function getForTag($tag_name, $tag_id = null) {
		if (!isset($tag_id)) {
			$this->load->model('tags');
			$tag_id = $this->tags->getID($tag_name);
		}

		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db
				->join('tag_matches', 'tag_matches.page_id=pages.page_id')
				->where("tag_matches.tag_id", $tag_id)
				->get('pages');

		return $query->result_array();
	}
}//class