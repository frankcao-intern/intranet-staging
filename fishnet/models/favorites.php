<?php
/**
 * @author Carlos Ravelo
 */

/**
 * The favorites model
 * @author Carlos Ravelo
 * @package Models
 */
class Favorites extends CI_Model {
	/**
	 * Create a new favorite
	 * @param int $user_id
	 * @param string $title
	 * @param string $url
	 * @param string $type
	 * @return bool
	 */
	function newFavorite($user_id, $title, $url, $type) {
		$this->db->insert("favorites", array(
			'user_id' => $user_id,
			'url' => $url,
			'type' => $type,
			'title' => $title
		));

		return ($this->db->affected_rows() > 0);
	}

	/**
	 * Get all favorites for a user_id
	 * @param int $user_id
	 * @return array
	 */
	function get($user_id) {
		$this->db->where('user_id', $user_id)
				->where('type', 0);//0 are mylinks, 1 are feeds
		$query = $this->db->get('favorites');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		}else{
			return array();
		}
	}

	/**
	 * Replaces all user favorites with the ones in the $data array
	 * @param array $data
	 * @return bool
	 */
	function update($data){
		//delete
		$this->db->where('user_id', $this->session->userdata('user_id'))->delete('favorites');

		if (count($data) > 0){
			//insert new ones
			foreach($data as $fav){
				$this->db->insert('favorites', $fav);
			}

			return ($this->db->affected_rows() > 0);
		}

		return true;
	}
}
