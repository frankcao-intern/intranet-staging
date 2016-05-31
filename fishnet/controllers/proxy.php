<?php
/**
 * @author Carlos Ravelo
 */

/**
 * Serves as proxy to get XML feeds from external websites, something that JS can't do on its own.
 * @author cravelo
 * @package Controllers
 * @property phpVimeo $phpvimeo
 * @property MY_Session $session
 */
class Proxy extends CI_Controller {
	/**
	 * Retrieve the buzz section on the homepage, echoes JSON structure
	 * @return void
	 */
	function buzz(){
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		$json = false;
		if ($this->cache->apc->is_supported()){
			//$json = $this->cache->apc->get('home-buzz');
		}else{
			//$json = $this->cache->file->get('home-buzz');
		}

		if(!$json){
			$sites = array(
				'New York Times' => 'http://topics.nytimes.com/topics/reference/timestopics/subjects/f/fashion_and_apparel/index.html?rss=1',
				//'Women\'s Wear Daily' => 'https://www.wwd.com/rss/2/news/recentstories',
				'Wall Street Journal' => 'http://online.wsj.com/xml/rss/3_7336.xml',
				'Google News' => 'https://news.google.com/news?pz=1&cf=all&ned=us&hl=en&q=%22eileen+fisher%22+designer&cf=all&output=rss'
			);

			$json = array();

			foreach ($sites as $title => $url){
				$str = file_get_contents($url);
				if ($str !== false){
					$str = preg_replace("/[\n\r\t]*/", "", $str);
					//echo $str;
					$xml = new SimpleXMLElement($str);
					$xml = $xml->channel->item[0];
					//print_r($xml);
					$xml->myTitle = $title;
					$json[] = $xml;
				}
			}

			$json = json_encode($json);
			$this->cache->save('home-buzz', $json, 43200);
		}

		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Fri, 8 Nov 1985 03:54:00 EST"); // Date in the past
		$this->output
            ->set_content_type('application/json')
            ->set_output($json);
	}

	function piwikTopDocs(){
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		if ($this->cache->apc->is_supported()){
			$json = $this->cache->apc->get('piwik-top-docs');
		}else{
			$json = $this->cache->file->get('piwik-top-docs');
		}

		if(!$json){
			$json = file_get_contents('https://fishnet.eileenfisher.com/piwik/index.php?module=API'.
					'&method=Actions.getDownloads'.
					'&idSite=1&period=month&date=today&format=JSON&token_auth=18617452e9755a574d294a7841a11e84'.
					'&expanded=1');

			if ($json !== false){
				$this->cache->save('piwik-top-docs', $json, 43200);
			}
		}

		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Fri, 8 Nov 1985 03:54:00 EST"); // Date in the past
		$this->output
            ->set_content_type('application/json')
            ->set_output($json);
	}

	/**
	 * @param int $videoID the video id from vimeo.com
	 */
	function getvimeovideo($videoID){
		$this->load->library('phpvimeo', array(
			'consumer_key' => 'e666c822f21b08c769405b520504c4bd',
			'consumer_secret' => '8bb01e7389573b62',
			'token' => '426132a1d9a53984fe7d81e4b544c2cc',
			'token_secret' => 'eaacac9450c0ec27727e13a4edda2c471be18028'
		));

		/*AUTHORIZATION SCHEME if (!isset($auth)){
			$videoData = $this->phpvimeo->getRequestToken();
			$this->session->set_userdata('secret', $videoData['oauth_token_secret']);
			$this->phpvimeo->setToken($videoData['oauth_token'], $videoData['oauth_token_secret']);
			$url = $this->phpvimeo->getAuthorizeUrl($videoData['oauth_token'], 'delete');
			header("Location: $url");
		}else{
			$this->phpvimeo->setToken($_GET['oauth_token'], $this->session->userdata('secret'));
			$videoData = $this->phpvimeo->getAccessToken($_GET['oauth_verifier']);
			var_dump($videoData);
			$this->phpvimeo->setToken($videoData['oauth_token'], $videoData['oauth_token_secret']);
		}*/

		$videoData = $this->phpvimeo->call('vimeo.videos.getInfo', array('video_id' => $videoID));

		if ($videoData->stat == 'ok'){
			$videoData = $videoData->video[0];

			$thumb = $videoData->thumbnails->thumbnail;
			$thumb = $thumb[count($thumb) - 1]->_content;
			$filecont = file_get_contents($thumb);
			$filename = sha1($filecont).'.'.pathinfo($thumb, PATHINFO_EXTENSION);
			$path = realpath(APPPATH."../").'/uploads/';
			$file = fopen($path.$filename, 'w+');
			fwrite($file, $filecont);
			fclose($file);
			$videoData->thumb = $filename;
		}

		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Fri, 8 Nov 1985 03:54:00 EST"); // Date in the past
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($videoData));
	}

	/**
	 * Helper function to download Doc Spot documents
	 * @param string $doc_name base64_encoded path to the document
	 */
	function downloadDoc($doc_name){
		$doc_name = base64_decode($doc_name);
		if ($doc_name !== false){
			$doc_name = str_replace(array("\r", "\n", "\t"), '', $doc_name);

			if(substr($doc_name, 0, 4) === 'http'){
				redirect($doc_name);
				echo var_dump($doc_name);
			}else{
				$this->load->helper('download');
				$data = file_get_contents($doc_name);
				//print_r($data);
				$name = basename($doc_name);
				//print_r($name);
	
				force_download($name, $data);
			}
		}
	}
	

}
