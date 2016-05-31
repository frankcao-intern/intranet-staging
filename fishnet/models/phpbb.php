<?php
/**
 * @author cravelo
 * @date 6/10/11
 * @time 1:06 PM
 */

/**
 * Model for integration with phpBB
 * @package Models
 * @author cravelo
 */
class PhpBB extends CI_Model {
	/**
	 * @return array with topic records from phpBB's database
	 */
	function getLatestTopics(){
		define('IN_PHPBB', true);
		//echo dirname(__FILE__);
		// Path to phpbbfolder
		$GLOBALS['phpbb_root_path'] = $phpbb_root_path = dirname(__FILE__).'/../../public/bulletinboard/';
		$GLOBALS['phpEx'] = $phpEx = substr(strrchr(__FILE__, '.'), 1);
		include($phpbb_root_path . 'common.' . $phpEx);
		include($phpbb_root_path . 'includes/functions_display.' . $phpEx);

		// Start session management
		$user->session_begin();
		$auth->acl($user->data);

		// Grab user preferences
		$user->setup();

		/*** phpBB3 - Last Active Topics System ***/
		//Show last x topics
		$topic_limit = 3;

		// Create arrays
		$topics = array();
		$people = array();

		// Get forums that current user has read rights to.
		$forums = array_unique(array_keys($auth->acl_getf('f_read', true)));

		if (count($forums) > 0){
			// Get active topics. with users permissions
			$sql="SELECT * FROM " . TOPICS_TABLE . "
			WHERE topic_approved = '1' AND " . $db->sql_in_set('forum_id', $forums) . "
			ORDER BY topic_last_post_time DESC
			LIMIT 0,' . $topic_limit;";
		}
		// Select the last topics ignoring permissions
		$sql = 'SELECT * FROM ' . TOPICS_TABLE . '
						WHERE topic_approved = 1
						ORDER BY topic_last_post_time DESC
						LIMIT 0,' . $topic_limit;

		$result = $db->sql_query($sql);

		$r = $db->sql_fetchrow($result);
		while($r) {
			$topics[] = $r;
			$people[] = $r['topic_first_poster_name'];
			$r = $db->sql_fetchrow($result);
		}

		$db->sql_freeresult($result);

		return array(
			'topics' => $topics,
			'people' => $people
		);
	}
}
