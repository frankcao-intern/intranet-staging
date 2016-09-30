<?php
/**
 * Created by: Mosrur
 * Date: 8/24/2016+
 * Time: 3:32 PM
 */

if ( ! function_exists('pr')){
	/**
	 * @param mixed $array variable
	 * @return mixed print the array vairable
	 */
	function pr(&$arr){
	    echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }
}

if ( ! function_exists('val')){
	/**
	 * @param mixed $a variable to test
	 * @param mixed $default default value to return if variable is not set
	 * @return mixed the passed default value, '' by default
	 */
	function val(&$a, $default = ''){ return isset($a) ? $a : $default; }
}

if ( ! function_exists('e')){
	/**
	 * @param mixed $a variable to test
	 * @param mixed $default default value to return if variable is not set
	 * @return mixed the passed default value, '' by default
	 */
	function e(&$a, $default = ''){ return empty($a) ? $default : $a; }
}

if ( ! function_exists('isAjax')){
	/**
	* Determines if the current page request was done through an AJAX call
	*
	* @return boolean
	*/
	function isAjax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
	}
}

if ( ! function_exists('truncateArticle')){
	/**
	 * @param array $article
	 * @param string $index
	 * @param array $params the count and the key
	 * @return array the modified $article array
	 * This function is a callback to be used with array_walk, is used to truncate article text to the desired
	 * amount of words
	 */
	function truncateArticle(&$article, $index, $params){
		//decode
		if (empty($article['revision_text'])) return $article;

		$key = $params['key'];
		$count = $params['count'];

		if (is_string($article['revision_text'])){
			$article['revision_text'] = json_decode($article['revision_text']);
		}
		//truncate article
		if (isset($count)){
			if (!isset($article['revision_text']->$key)){
				$article['revision_text']->$key = "";
			}else{
				$article['revision_text']->$key = preg_replace("/<\//i", ' <\\',
					$article['revision_text']->$key);
				$text = strip_tags($article['revision_text']->$key);
				$text = trim(preg_replace('/(&nbsp;)+|[\r\n\t]+|[ ]{2,}+/', ' ', $text));
				$words = explode(" ", $text, $count + 1);
				if (count($words) > $count) { unset($words[count($words) - 1]); }
				$article['revision_text']->$key = implode(" ", $words);
			}
		}
		return $article;
	}
}

if ( ! function_exists('get_image')){
	/**
	 * returns the $filename of an image with the corresponding transformations, the transformed image doesn't't exist
	 * yet it will create it.
	 *
	 * @param bool $src
	 * @param bool $width
	 * @param bool $zc
	 * @param bool $flip
	 * @param int $angle
	 * @return string the formed string to use
	 */
	function get_image($src = false, $width = false, $zc = false, $flip = false, $angle = 0){
		include_once APPPATH.'libraries/imagelib.php';

		$imagelib = new imagelib();
		$uploads_dir = config_item('uploads_dir');
		$filename = false;
		$new_name = "";
		$ext = "";
		$path = "";
		$error = false;
		$bHeight = false;

		if ($src){
			$image = explode('.', $src);
			$filename = $image[0];
			$ext = isset($image[1]) ? $image[1] : "";
			$path = $uploads_dir.
				substr($filename, 0, 1).'/'.
				substr($filename, 0, 2).'/'.
				substr($filename, 0, 3).'/'.
				substr($filename, 0, 4).'/';

			$new_name = $fullname = $path.$filename.'.'.$ext;
			if (!file_exists($new_name)){
				$path = $uploads_dir.'profiles/'.$src.'/';
				$filename = 'main';
				$ext = 'jpg';
			}
		}else{
			$error = true;
		}

		if (($filename !== false) and ($error !== true)){
			$new_name = $fullname = $path.$filename.'.'.$ext;
			if ($width or $zc or $flip or $angle){
				if ($width === false){ $width = $zc; $bHeight = true; $zc = false; }
				$filename .= "-".sha1($width.$zc.$flip.$angle);
				$new_name = $path.$filename.'.'.$ext;

				if (!file_exists($new_name)){
					if ($imagelib->open($fullname)){
						$imagelib->rotate($angle);
						$imagelib->resize($width, false, $zc, $bHeight);
						$imagelib->flip($flip);
						if ($imagelib->is_modified){
							$imagelib->write($new_name);
						}
					}else{
						$error = true;
					}
				}
			}
		}else{
			$error = true;
		}

		if (($error === true) or !file_exists($new_name)){
			return false;
		}else{
			return $new_name;
		}
	}
}

if ( ! function_exists('get_image_html')){
	/**
	 * outputs src and width and height ready to use on <img>
	 *
	 * @param bool $src
	 * @param bool $width
	 * @param bool $zc
	 * @param bool $flip
	 * @param int $angle
	 * @return string the formed string to use
	 */
	function get_image_html($src = false, $width = false, $zc = false, $flip = false, $angle = 0, $lazy = false){
		$filename = get_image($src, $width, $zc, $flip, $angle);

		if ($filename === false){
			$zc = $zc ? $zc : 407;
			$width = $width ? $width : 760;
			$result = 'src="%s" width="%s" height="%s" data-flip="false" data-angle="0"';

			return sprintf($result, site_url("/images/error/$width/$zc"), $width, $zc);
		}else{
			$info = getimagesize($filename);
			$filename = basename($filename);
			if ($lazy){
				$result = 'src="'.STATIC_URL.'images/grey.gif" data-original="%s" %s data-flip="%s" data-angle="%s"';
			}else{
				$result = 'src="%s" %s data-flip="%s" data-angle="%s"';
			}
			$flip = (($flip === 'true') or ($flip === true)) ? 'true' : 'false';

			return sprintf($result, site_url("/images/src/$filename"), $info[3], $flip, $angle);
		}
	}
}

if ( ! function_exists('get_image_html')){
	/**
	 * outputs src and width and height ready to use on <img>
	 *
	 * @param bool $src
	 * @param bool $width
	 * @param bool $zc
	 * @param bool $flip
	 * @param int $angle
	 * @return string the formed string to use
	 */
	function get_image_html($src = false, $width = false, $zc = false, $flip = false, $angle = 0, $lazy = false){
		$filename = get_image($src, $width, $zc, $flip, $angle);

		if ($filename === false){
			$zc = $zc ? $zc : 407;
			$width = $width ? $width : 760;
			$result = 'src="%s" width="%s" height="%s" data-flip="false" data-angle="0"';

			return sprintf($result, site_url("/images/error/$width/$zc"), $width, $zc);
		}else{
			$info = getimagesize($filename);
			$filename = basename($filename);
			if ($lazy){
				$result = 'src="'.STATIC_URL.'images/grey.gif" data-original="%s" %s data-flip="%s" data-angle="%s"';
			}else{
				$result = 'src="%s" %s data-flip="%s" data-angle="%s"';
			}
			$flip = (($flip === 'true') or ($flip === true)) ? 'true' : 'false';

			return sprintf($result, site_url("/images/src/$filename"), $info[3], $flip, $angle);
		}
	}
}

if ( ! function_exists('set_alert')) {
    function set_alert($msg, $type = 'info')
    {
        $ci =& get_instance();
        $alerts = $ci->session->userdata('alerts');
        $alerts[$type][] = $msg;
        $ci->session->set_userdata('alerts', $alerts);
    }
}

if ( ! function_exists('show_alert')) {
    function show_alert()
    {
        $ci =& get_instance();
        $alerts = $ci->session->userdata('alerts');
        foreach ($alerts as $type => $msgs) {
            echo "<div class='alert'>";
            foreach ($msgs as $msg) {
                echo "<h5>" . $msg . "</h5>";
            }
            echo "</div>";
        }
        $ci->session->unset_userdata('alerts');
    }
}

if ( ! function_exists('has_alert')) {
    function has_alert()
    {
        $ci =& get_instance();
        $alerts = $ci->session->userdata('alerts');
        return !empty($alerts);
    }
}


if ( ! function_exists('get_page_section_details')){
    /**
     * outputs page propertise section details
     *
     * @param bool $page_id
     * @param bool $section_id
     * @param bool $field
     * @return string from page model getPageSectionProperty
     */
    function get_page_section_details($page_id = false, $section_id = false, $field = false){
        $ci =& get_instance();
        $data = $ci->pages->getPageSectionProperty($page_id, $section_id, $field);
        //pr($data);//exit;

        return $data;
    }
}

if ( ! function_exists('get_page_review_details')){
    /**
     * outputs page propertise section details
     *
     * @param bool $page_id
     * @param bool $sender_id
     * @param bool $field
     * @return string from page model getPageSectionProperty
     */
    function get_page_review_details($page_id, $sender_id, $field = false){
        $ci =& get_instance();
        $data = $ci->pages->checkPagesReview($page_id, $sender_id, $field);
        //pr($data);//exit;

        return $data;
    }
}

if ( ! function_exists('cmp')){
    function cmp($item1, $item2) {
        if ($item1['sort_order'] == $item2['sort_order']) return 0;
        return $item1['sort_order'] < $item2['sort_order'] ? -1 : 1;
    }

}

