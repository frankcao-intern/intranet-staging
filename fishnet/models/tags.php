<?php
/**
 * @author cravelo
 * @date Feb 7, 2011
 * @time: 1:44:53 PM
 */

/**
 * Tags model
 * @package Models
 * @author cravelo
 * @property CI_DB_active_record $db
 */
class Tags extends CI_Model {
	/**
	 * @param int $id
	 * @return array
	 */
	function get($id = null){
		$this->db->select('tag_name');
		if (isset($id)){
			$this->db->where('tag_id', $id);
		}

		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->get("tags");

		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return array();
		}
	}

	/**
	 * Get all tags that start with $tag_name
	 * @param string $tag_name
	 * @return array
	 */
	function getList($tag_name){
		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db->select('tag_name')->where('tag_name LIKE', "$tag_name%")->get("tags");

		$tags = array();
		if ($query->num_rows() > 0){
			$result = $query->result();
			foreach($result as $row){
				$tags[] = $row->tag_name;
			}
		}

		return $tags;
	}

	/**
	 * Get the tag_id for a tag_name
	 * @param string $tag_name
	 * @return null
	 */
	function getID($tag_name){
		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db->select('tag_id')->where('tag_name LIKE', "$tag_name%")->get("tags");

		$tag_id = null;
		if ($query->num_rows() > 0){
			$tag_id = $query->row_array();
			$tag_id = $tag_id['tag_id'];
		}

		return $tag_id;
	}

	/**
	 * Get the list of tags associated with a page
	 * @param int $page_id
	 * @return array
	 */
	function getForPage($page_id){
		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db->select('tags.tag_name')
				->join('tag_matches t', 't.tag_id=tags.tag_id')
				->where('page_id', $page_id)
				->get("tags");

		$tags = array();
		if ($query->num_rows() > 0){
			$result = $query->result();
			foreach($result as $row){
				$tags[] = $row->tag_name;
			}
		}

		return $tags;
	}

	/**
	 * Get all tags the are used in pages published to section_id along with how many times is used as a percentage
	 * of total pages published to that section
	 * @param int $section_id
	 * @return array
	 */
	function getPopularity($section_id = null){
		$sections = isset($section_id) ?
				"JOIN fn_pages_pages rel ON fn_tag_matches.page_id=rel.page_id WHERE rel.section_id=$section_id" : "";

		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db->query("SELECT fn_tags.tag_name, fn_tags.tag_id,
			(100*COUNT(fn_tag_matches.page_id)/(
					SELECT COUNT(fn_pages.page_id) FROM fn_pages
					JOIN fn_pages_pages rel ON rel.page_id=fn_pages.page_id
					WHERE rel.section_id=$section_id
				)
			) as popularity
			FROM fn_tags
			JOIN fn_tag_matches ON fn_tags.tag_id=fn_tag_matches.tag_id
			$sections
			GROUP BY fn_tag_matches.tag_id
			ORDER BY popularity DESC
		");

		if ($query and ($query->num_rows() > 0)){
			return array('all_tags' => $query->result_array());
		}else{
			return array('all_tags' => array());
		}
	}

	/**
	 * Assign tags to a page, insert new tags if they don't exist
	 * @param $page_id
	 * @param $tags
	 * @return bool
	 */
	function tagPage($page_id, $tags){
		$this->db->where('page_id', $page_id)->delete('tag_matches');
		if (count($tags) > 0){
			$tags = array_map(function($tag){
				return mysql_real_escape_string($tag);
			}, $tags);

			$this->db->query("INSERT IGNORE INTO fn_tags(tag_name) VALUES ('".implode("'),('", $tags)."')");
			$this->db->query("INSERT IGNORE INTO fn_tag_matches(page_id, tag_id)
				SELECT $page_id, tag_id FROM fn_tags WHERE tag_name IN ('".implode("','", $tags)."')");
		}

		return ($this->db->_error_number() === 0);
	}
}
