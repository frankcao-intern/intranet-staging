<?php
/**
 * @author cravelo
 * @date 6/9/11
 * @time 4:25 PM
 */

/**
 * @param string $a form name a
 * @param string $b form name b
 * @return int returns 0 is equal, -1 or 1 in < or > respectively
 * This function is used as a callback for ucmp to alphabetize forms
 */
function alphaForms($a, $b){
	$nameA = strtolower(basename($a['title']));
	$nameB = strtolower(basename($b['title']));
	if ($nameA == $nameB) {
		return 0;
	}
	return ($nameA < $nameB) ? -1 : 1;
}

/**
 * Documents model - Mainly for integration with KnowledgeTree
 * @package Models
 * @author cravelo
 * @property CI_Output $output
 */
class Documents extends CI_Model {
	/**
	 * @param XMLObject $response the response object from the API call to KT
	 * @param array $fileArray the array to fill
	 * @param string $filter filter extensions
	 * @param bool $nodirs whether to create a multi-dimensional array or just a list
	 * @return int file count
	 */
	private function arrayFromKTResults($response, &$fileArray, $filter='', $nodirs){
		$d = $response->items;
		foreach ( $d->item as $f ) {
			$title = (string)$f->title;
			if ( substr($title, 0, 1) == '.' )
				continue;
			elseif ( $f->item_type == 'F' ) {
				if ($nodirs){
					$this->arrayFromKTResults($f, $fileArray, $filter, $nodirs);
				}else{
					$fileArray[$title] = array();
					$this->arrayFromKTResults($f, $fileArray[$title], $filter, $nodirs);
				}
			}
			elseif ( !$filter || preg_match($filter, $f->filename) ) {
				$fileArray[] = array(
					'title' => $title,
					'id' => (int)$f->id,
					'last_mod' => date('Y/m/d', strtotime((string)$f->modified_date)),
				);
			}
		}
	}

	/**
	 * Queries KnowledgeTree's API to get the list of files from a folder
	 * @param $folder_id
	 * @param $nodirs
	 * @return array
	 */
	function fromKT($folder_id, $nodirs){
		if (!is_numeric($folder_id)) {
            return array('forms' => array(), 'folder_id' => $folder_id, 'folder_name' => 'undefined');
        }
        //if its passed on from a model call in a revision it will be a string
        $nodirs = (($nodirs === TRUE) or ($nodirs === 'true'));

		//retrieve the version form the configuration to use in the names of the saved items in the cache
		$ver = FISHNET_VERSION;
		$error = false;
		$fileArray = array();
		$folder_name = "";

		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		if ($this->cache->apc->is_supported()){
			$fileArray = $this->cache->apc->get("docs-$folder_id-$nodirs-$ver");
			$folder_name = $this->cache->apc->get("docsFN-$folder_id-$nodirs-$ver");
		}else{
			//$fileArray = $this->cache->file->get("docs-$folder_id-$nodirs-$ver");
			//$folder_name = $this->cache->file->get("docsFN-$folder_id-$nodirs-$ver");
		}

		if (!$fileArray or !$folder_name){
			//login
			$server_url = 'http://effs02.eileenfisher.net/ktwebservice/ktwebservice.php';
			$url = "$server_url?method=login&password=efisher5130&username=admin";
			$str = @file_get_contents($url);
			if ($str !== false){
				$response = new SimpleXMLElement($str);
				if ((int)$response->status_code == 0){
					$session_id = $response->results;

					//get folder contents
					$url = "$server_url?method=get_folder_contents&session_id=$session_id&folder_id=$folder_id&depth=5";
					$str = file_get_contents($url);
					$str = preg_replace("/&+/", "&amp;", $str);
					$response = new SimpleXMLElement($str);
					//var_dump($response);
					if ($response->status_code == 0){
						$filter = '#\.(png|jpe?g|tiff?|pdf|xlsx?|docx?|pptx?|url|dotx?)$#i';
						//convert raw results to multidimensional array that is more manageable
						$this->arrayFromKTResults($response->results, $fileArray, $filter, $nodirs);
						//print_r($fileArray);

						//sort the list of files
						if ($nodirs){
							asort($fileArray, "alphaForms");
						}else{
							ksort($fileArray);
						}

						//logout
						$url = "$server_url?method=logout&session_id=$session_id";
						file_get_contents($url);

						$folder_name = $response->results->folder_name;

						//save to cache
						$this->cache->save("docs-$folder_id-$nodirs-$ver", $fileArray, 7200);
						$this->cache->save("docsFN-$folder_id-$nodirs-$ver", (string)$folder_name, 7200);
					}else{
						$error = 'The folder ID you specified was not found in KnowledgeTree.';
					}
				}else{
					$error = 'There was a problem login in to KnowledgeTree, please try again later. If the problem
						persists please call the Helpdesk at x4024. Thank you.';
				}
			}else{
				$error = 'There was a problem login in to KnowledgeTree, please try again later. If the problem
						persists please call the Helpdesk at x4024. Thank you.';
			}
		}

		if ($error) {
			return array(
				'isError' => true,
				'errorStr' => $error
			);
		}else{
			return array(
				'forms' => $fileArray,
				'folder_id' => $folder_id,
				'folder_name' => $folder_name,
			);
		}
	}

	/**
	 * @param string $filePath path to the folder to list
	 * @param array $fileArray reference to the array to fill with the dir contents
	 * @param string $filter the filter to apply to file (e.g. to include only certain file extensions)
	 * @param boolean $nodirs whether to create a list or a multi-dimensional array.
	 * @return int file count.
	 *
	 * Creates an array (either list or multi-dimensional) from with the files on a directory
	 */
	private function arrayFromDir($filePath, &$fileArray, $filter='', $nodirs) {
		$leaves = 0;
		$d = new DirectoryIterator(dirname($_SERVER['SCRIPT_FILENAME'])."/$filePath");
		foreach ( $d as $f ) {
			$name = $f->getFilename();
			if ( substr($name, 0, 1) == '.' )
				continue;
			elseif ( $f->isDir() ) {
				if ($nodirs){
					$sub_leaves = $this->arrayFromDir("$filePath/$name", $fileArray, $filter, $nodirs);
				}else{
					$fileArray[$name] = array();
					$sub_leaves = $this->arrayFromDir("$filePath/$name", $fileArray[$name], $filter, $nodirs);
					//$fileArray[$name]['fileCount'] = $sub_leaves;
				}

				$leaves += $sub_leaves;
			}
			elseif ( !$filter || preg_match($filter, $name) ) {

				if (pathinfo("$filePath/$name", PATHINFO_EXTENSION) == 'url'){
					$f = fopen(dirname($_SERVER['SCRIPT_FILENAME'])."/$filePath/$name", 'r');
					fgets($f);//not interested in the first line
					$url = explode('=', fgets($f));
					fclose($f);
					$fileArray[] = $url[1].$name;
				}else{
					$fileArray[] = "$filePath/".ucfirst($name);
				}
				$leaves++;
			}
		}

		return $leaves;
	}

	/**
	 * Uses arrayFromDir and handles caching of the data, the returns an array to be merged with pageData
	 * during page load.
	 * @return array to be merged with pageData
	 * */
	function fromDir(){
		$nodirs = $this->uri->segment(2);
		$nodirs = (($nodirs === '1') OR ($nodirs === 'true') OR ($nodirs === TRUE));

		//retrieve the version form the configuration to use in the names of the saved items in the cache
		$ver = FISHNET_VERSION;
		$group_name = '';
		$filePath = '';
		$show_most_downloaded = false;

		$groups_config = $this->config->item('groups_config');
		$groups = $groups_config['group_names'];
		$this->load->model('groups');
		foreach($groups as $group_name){
			if ($this->groups->is_memberOf($group_name)){
				$filePath = $groups_config[$group_name]['documents'];
		//		$show_most_downloaded = $groups_config[$group_name]['show_most_downloaded'];
				break;
			}
		}

		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		if ($this->cache->apc->is_supported()){
			$fileArray = $this->cache->apc->get("forms-fileArray-$nodirs-$group_name-$ver");
		}else{
			$fileArray = $this->cache->file->get("forms-fileArray-$nodirs-$group_name-$ver");
		}

		if (!$fileArray){
			$fileArray = array();

			if ($filePath){
				$filter = '#\.(png|jpe?g|pdf|xlsx?|docx?|pptx?|url|dotx?)$#i';
				$this->arrayFromDir($filePath, $fileArray, $filter, $nodirs);

				//sort the long list of forms
				if ($nodirs){
					asort($fileArray, "alphaForms");
				}else{
					ksort($fileArray);
				}

				$this->cache->save("forms-fileArray-$nodirs-$group_name-$ver", $fileArray, 43200);
			}else{
				show_error("There is a problem loading Forms Central, please try again later. If the problem persists call the Helpdesk at x4024");
			}
		}

		return array(
			'forms' => $fileArray,
			'nodirs' => $nodirs//,
		//	'show_most_downloaded' => $show_most_downloaded
		);
	}
}
