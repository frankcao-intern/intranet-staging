<?php
/**
 * @author cravelo
 * @date   4/10/12
 * @time   1:28 PM
 */

require_once dirname(__FILE__).'/article.php';

/**
 * Journal provides a blog style digest where articles are just an endless list with no clear division
 * like monthly or seasonal digests
 * @package Controllers
 * @author cravelo
 * @property Pages $pages
 * @property Tags $tags
 */
class Journal extends Article {
	/**
	 * @var int The number of words (as in strings separated by spaces) to show on the digest before user has to
	 * click read more >
	 */
	private $word_count_per_article = 180;
	/**
	 * @param int $page_id
	 * @param int $offset
	 * @param string $sort_by
	 */
	function load($page_id, $offset = null, $sort_by = null){
		$this->load->model('pages');

		$offset = ($offset and is_numeric($offset) and ($offset > 0)) ? $offset : 0;

		$config['base_url'] = site_url($this->uri->segment(1)."/$page_id");
		$config['per_page'] = 5;

		//load the articles
		$articles = $this->pages->getForSection($page_id, null, null, null, null, $config['per_page'], $offset, null,
				null, $sort_by);

		$config['total_rows'] = $this->pages->getForSection_count($page_id, null, null, null, $config['per_page'],
				$offset);
		$config['num_links'] = 1;
		$config['display_pages'] = false;
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<span class="right">
			<li class="current nav-link">
				<em class="min">'.($offset / $config['per_page'] + 1).'</em>
				<span> of </span>
				<em class="max">'.ceil($config['total_rows'] / $config['per_page']).'</em>
			</li>';
		$config['full_tag_close'] = '<li class="nav-link">';
		$config['full_tag_close'] .= '<a class="icon" href="'.site_url("article/rss/$page_id").'">';
		$config['full_tag_close'] .= '<img src="'.STATIC_URL.'images/feed-icon-14x14.png" alt="Journal RSS	Feed" ';
		$config['full_tag_close'] .= 'height="14" width="14" /></a></li></span>';
		$config['first_link'] = '&#x25c4; First';
		$config['prev_link'] = '&#x25c4;';
		$config['next_link'] = '&#x25ba;';
		$config['last_link'] = 'Last &#x25ba;';
		$config['next_tag_open'] =
		$config['prev_tag_open'] =
		$config['first_tag_open'] =
		$config['last_tag_open'] = '<li class="nav-link">';
		$config['next_tag_close'] =
		$config['prev_tag_close'] =
		$config['first_tag_close'] =
		$config['last_tag_close'] = '</li>';

		// get first X words from each article
		array_walk($articles, 'truncateArticle', array('count' => $this->word_count_per_article, 'key' => 'article'));

		$this->load->library('pagination');
		$this->pagination->initialize($config);

		$this->pageRecord['order_by'] = $sort_by;

		$this->pageRecord['articles'] = $articles;
		$this->load->model('tags');
		$this->pageRecord = array_merge($this->pageRecord, $this->tags->getPopularity($page_id));

		$this->index($page_id);
	}
}
