<?php
/**
 * @author cravelo
 * @package Controllers
 */

/**
 * Image object is a simple class mainly to ease the construction of the JSON structure for the revisions.
 * @package Misc
 * @author  cravelo
 */
class image_object{
	public $src;
	public $alt;
	public $credit;
	public $date;
	public $desc;

	/**
	 * @param   string  $src    the URL for the image
	 * @param   string  $alt    the alt text
	 * @param   string  $credit who created the image
	 * @param   int     $date   timestamp
	 * @param   string  $desc   a description of the image
	 */
	public function __construct($src, $alt = "", $date = 500270040, $credit = "", $desc = ""){
		$this->alt = $alt;
		$this->src = $src;
		$this->credit = $credit;
		$this->date = $date;
		$this->desc = $desc;
	}
}

/**
 * The images controller is the proxy for all user uploaded images on the site.
 * All images are accessed using the same URL:
 * [path to intranet]/images/src/[image_id].[extension]
 * @package Controllers
 * @author  cravelo
 * @property CI_Loader $load
 * @property CI_Security $security
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Output $output
 * @property CI_Uri $uri
 * @property Revisions $revisions
 * @property Imagelib $imagelib
 */
class Images extends MY_Controller {
	/**
	 * @var string the base folder
	 */
	private $uploadsDir;

	/**
	 * Constuctor check if the user has a session going. and if the call is correct.
	 */
	public function __construct(){
		parent::__construct();

		$user_name = $this->session->userdata('username');
		if ($user_name === false){
			if ($this->input->is_ajax_request()){
				$this->result->isError = true;
				$this->result->errorCode = 401;
				$this->result->errorStr = 'Your session expired, please reload/refresh the page.';
			}else{
				if (($this->uri->segment(2) == 'src') or ($this->uri->segment(2) == 'profile')){
					$this->error();
				}else{
					redirect("/login/index".$this->uri->uri_string());
				}
			}
		}

		$this->load->library('imagelib');
		$this->load->model('audit');

		$this->uploadsDir = realpath(APPPATH."../").'/uploads/';
	}

	/**
	 * @param $page_id
	 * @param string $gallery_id the id of the image stack on the page to send back to callback function
	 * @param $new_filename
	 * @param boolean $pdf whether I'm dealing with a pdf
	 */
	private function updatePageRevision($page_id, $gallery_id, $new_filename, $pdf){
		//load the latest revision and add the image to the corresponding gallery
		$this->db->query("LOCK TABLES ".$this->db->protect_identifiers("revisions", TRUE)." WRITE, ".
							$this->db->protect_identifiers("audit", TRUE)." WRITE");

		$this->load->model('revisions');
		$revision = $this->revisions->getLatestRevision($page_id);

		if (!$revision){
			$this->result->isError = true;
			$this->result->errorStr = "There was an error retrieving the page.";
		}else{
			if ($pdf){
				$alt = '<a href="'.site_url('images/src/'.$pdf).'" target="_blank">Download Attached PDF</a>';
			}else{
				$alt = "";
			}
			$newImage = new image_object(basename($new_filename), $alt, time());
			$revision['revision_text'][$gallery_id][] = $newImage;
			$this->result->data['image_data'] = $newImage;
			$this->result->data['image_id'] = count($revision['revision_text'][$gallery_id]) - 1;
			//print_r($revision);
			if (!$this->revisions->updateRevision($revision)){
				$this->result->isError = true;
				$this->result->errorStr = "There was an error updating the page.";
			}
		}

		$this->db->query("UNLOCK TABLES");
	}

	/**
	 * After the image was uploaded by the generic uploader this will connect it with the page or user profile
	 * @param string $upload_type page or user profile
	 */
	function upload($upload_type){
		$page_id  = $this->input->post('page_id');
		$filename = $this->input->post('filename');
//		$orig_filename = $this->input->post('orig_filename');

		if ($upload_type == "page"){
			//this will create a directory structure of only 16 dirs wthin each other, more maneageable than
			$folders = substr($filename, 0, 1).'/'.substr($filename, 0, 2).'/'.substr($filename, 0, 3).'/'.substr($filename, 0, 4).'/';

			//mkdir mode is ignored in windows
			if (is_dir($this->uploadsDir.$folders) or mkdir($this->uploadsDir.$folders,	0755, true)){

				$src = $this->uploadsDir.$folders.$filename;
				rename($this->uploadsDir.$filename, $src);

			    if (pathinfo($src, PATHINFO_EXTENSION) == 'pdf'){
				    $jpg = str_replace('.pdf', '.jpg', $filename);
					exec("gs -sDEVICE=jpeg -o \"".
							$this->uploadsDir.$folders.$jpg.
							"\" -dJPEGQ=80 -dFirstPage=1 -dLastPage=1 -r200x200 $src");
				    $this->updatePageRevision($page_id, $this->input->post('gallery_id'), $jpg, $filename);
				}else{
					$this->imagelib->open($src);
				    $this->imagelib->auto_rotate();
					$this->imagelib->write($src);
					$this->updatePageRevision($page_id, $this->input->post('gallery_id'), $filename, false);
			    }
			}else{
				unlink($this->uploadsDir.$filename);
				$this->result->isError = true;
				$this->result->errorStr = "There was an error creating the file on the server.";
			}

			if (!$this->result->isError){
				$this->result->data['gallery_id'] = $this->input->post('gallery_id');
				$this->result->data['image_data']->date = date('Y-m-d', $this->result->data['image_data']->date);
				$this->result->data['image_data']->src = site_url('images/src/'.$this->result->data['image_data']->src);
				$this->result->errorStr = "Image #".($this->result->data['image_id'] + 1).
						" was uploaded successfully!";
			}
		}else{
			$profileDir = $this->uploadsDir."profiles/$page_id/";
			if (is_dir($profileDir) or mkdir($profileDir, 0755, true)){

				$this->imagelib->open($this->uploadsDir.$filename);
				array_map('unlink', glob($profileDir.'*.jpg'));
				$this->imagelib->write($profileDir.'main.jpg');

				unlink($this->uploadsDir.$filename);

				//the time stamp is added so the filename will always be different and updated to the user
				$this->result->data = site_url("images/profile/$page_id/".time());
			}else{
				unlink($this->uploadsDir.$filename);
				$this->result->isError = true;
				$this->result->errorStr = "There was an error creating the file on the server.";
			}
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	/**
	 * image retriever /src/img_name/f/[true|false]/a/[0|90|180|270]
	 * @param mixed $src
	 */
	function src($src = false){
		$error = false;

		if ($src === false){
			$error = true;
		}else{
			$path = $this->uploadsDir;
			$image = explode(".", strtolower($src));
			$filename = $image[0];
			$ext = isset($image[1]) ? $image[1] : "";
			$path .= substr($filename, 0, 1).'/'.
					 substr($filename, 0, 2).'/'.
					 substr($filename, 0, 3).'/'.
					 substr($filename, 0, 4).'/';

			if (file_exists($path.$src)){
				$this->output->set_header('Set-Cookie: ', true);
				$lastModified = filemtime($path.$src);

				$ifModified = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModified;
				if ($ifModified) {
					$this->output->set_status_header(304);
					exit;
				}
				$this->output
					->set_header("Last-Modified: ".gmdate("D, d M Y H:i:s \G\M\T", $lastModified))
					->set_header("Cache-Control: max-age=2592000")//a month
					->set_header("Content-Disposition: inline; filename=\"$src\"")
					->set_content_type($ext)
					->set_output(file_get_contents($path.$src));
			}else{
				$error = true;
			}
		}

		if ($error){
			$this->error();
		}
	}

	/**
	 * previews image changes like flip and rotate /src/img_name/f/[true|false]/a/[0|90|180|270]
	 */
	function preview(){
		$src = false;
		$paramArr = $this->uri->uri_to_assoc(2);
		if (isset($paramArr['preview'])){
			$src = $paramArr['preview'];
		}elseif (isset($paramArr['profile'])){
			$src = $paramArr['profile'];
		}
		$error = false;
		$width = null;
		$zc = null;

		if ($src === false){
			$error = true;
		}else{
			$flip = val($paramArr['f'], false);
			$flip = (($flip === "true") or ($flip === true));
			$angle = val($paramArr['a'], 0);
			$width = val($paramArr['w'], null);
			$zc = val($paramArr['zc'], null);
			$filename = get_image($src, $width, $zc, $flip, $angle);

			if (file_exists($filename)){
				$info = pathinfo($filename);
				//print_r($info);
				$this->output
					->set_header('Last-Modified: '.gmdate("D, d M Y H:i:s \G\M\T", filemtime($filename)))
					->set_header("Content-Disposition: inline; filename=\"{$info['basename']}\"")
					->set_content_type($info['extension'])
					->set_output(file_get_contents($filename));
			}else{
				$error = true;
			}
		}

		if ($error){
			$this->error($width, $zc);
		}
	}

	/**
	 * Outputs an error image
	 * @param int|bool $width
	 * @param int|bool $zc
	 */
	function error($width = false, $zc = false){
		if (!$width) { $width = 760; }
		if (!$zc) { $zc = 471; }

		$my_img = imagecreate($width, $zc);
		$background = imagecolorallocate($my_img, 233, 229, 218);
		ob_start();
		imagejpeg($my_img);
		$image = ob_get_clean();
		imagecolordeallocate($my_img, $background);
		imagedestroy($my_img);

		$this->output
			->set_header("Cache-Control: no-cache, must-revalidate")
			->set_header('Expires: '.gmdate("D, d M Y H:i:s \G\M\T", strtotime("-1 years")))
			->set_header("Content-Disposition: inline; filename=\"error.jpg\"")
			->set_content_type('jpeg')
			->set_output($image);
	}

	/**
	 * Crop an image and return the new url for it
	 * @return void
	 */
	function crop(){
		$gallery_id = $this->input->post('gallery_id');
		$data = json_decode($this->input->post('data'));
		$x = ceil($data->x);
		$y = ceil($data->y);
		$w = ceil($data->w);
		$h = ceil($data->h);
		$img_id = $data->img_id;

		$path = $this->uploadsDir.substr($img_id, 0, 1).'/'.
				substr($img_id, 0, 2).'/'.
				substr($img_id, 0, 3).'/'.
				substr($img_id, 0, 4).'/';
		$old_path = $path.$img_id;
		$new_name = pathinfo($img_id, PATHINFO_FILENAME)."x{$x}x{$y}x{$w}x{$h}.".pathinfo($img_id, PATHINFO_EXTENSION);
		$new_path = $path.$new_name;

		if (!file_exists($new_path)){
			if ($this->imagelib->open($old_path)){
				$this->imagelib->rotate($data->angle);
				$this->imagelib->flip($data->flip);
				$this->imagelib->crop($x, $y, $h, $w);//x, y, h, w
				if ($this->imagelib->is_modified){
					$this->imagelib->write($new_path);
				}
			}else{
				$this->result->isError = true;
			}
		}

		if ($this->result->isError or !file_exists($new_path)){
			$this->result->errorStr = 'Error: Cropping failed, please try again. If the problem persists, call the
				helpdesk at x4024.';
		}else{
			$this->result->data['gallery_id'] = $gallery_id;
			$this->result->data['index'] = $data->index;
			$this->result->data['img_id'] = $new_name;
			$this->result->data['src'] = site_url("images/src/$new_name");
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	//this will delete an image form the revision gallery, but not from the disk
	function delete(){
		$page_id = $this->input->post('page_id');
		$gallery_id = $this->input->post('gallery_id');
		$images = json_decode($this->input->post('images'), true);
		//print_r($images);

		//load the latest revision and delete the image from the corresponding gallery
		$this->load->model('revisions');
		$revision = $this->revisions->getLatestRevision($page_id);
		if (!$revision){
			$this->result->isError = true;
			$this->result->errorStr = "ERROR: Retrieving the revision";
		}else{
			$gallery_array = $revision['revision_text'][$gallery_id];
			//delete all the images from the array
			foreach ($images as $image){
				unset($gallery_array[$image]);
			}
			$revision['revision_text'][$gallery_id] = array_values($gallery_array);
			//update the revision record
			if (!$this->revisions->updateRevision($revision)){
				$this->result->isError = true;
				$this->result->errorStr = "There was an error updating the revision";
			}
		}

		if (!$this->result->isError){
			$this->result->data['gallery_id'] = $gallery_id;
			$this->result->data['images'] = $images;
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	function addtitle(){
		$caption = $this->security->xss_clean(base64_decode(str_replace(' ', '+', $this->input->post('caption'))));
		$byline = $this->security->xss_clean(base64_decode(str_replace(' ', '+', $this->input->post('byline'))));
		$date = strtotime($this->input->post('date', true));
		$page_id = $this->input->post('page_id');
		$gallery_id = $this->input->post('gallery_id');
		$image_id = $this->input->post('image_id');
		$desc = utf8_decode(base64_decode(str_replace(" ", "+", $this->input->post('desc'))));
		//disallow script tag
		$desc = preg_replace('/< *script/i', "&lt;script", $desc);
		$desc = preg_replace( "/< *\/ *script *>/i", "&lt;/script&gt;", $desc);

		//load the latest revision and delete the image from the corresponding gallery
		$this->load->model('revisions');
		$revision = $this->revisions->getLatestRevision($page_id);
		if (!$revision){
			$this->result->isError = true;
			$this->result->errorStr = "ERROR: Retrieving the revision";
		}else{
			$revision['revision_text'][$gallery_id][$image_id]["alt"] = $caption;
			$revision['revision_text'][$gallery_id][$image_id]["credit"] = $byline;
			$revision['revision_text'][$gallery_id][$image_id]["date"] = $date;
			$revision['revision_text'][$gallery_id][$image_id]["desc"] = $desc;
			//update the revision record
			if (!$this->revisions->updateRevision($revision)){
				$this->result->isError = true;
				$this->result->errorStr = "There was an error updating the revision";
			}
		}

		if (!$this->result->isError){
			$this->result->data = $revision['revision_text'][$gallery_id][$image_id];
			$this->result->data['image_id'] = $image_id;
			$this->result->data['gallery_id'] = $gallery_id;
			$this->result->errorStr = "The caption was saved successfully!";
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}

	function save(){
		$page_id = $this->input->post('page_id');
		$gallery_id = $this->input->post('gallery_id');
		$data = json_decode($this->input->post('data'));

		$this->_save($page_id, $gallery_id, $data);
	}

	/**
	 * saves the changes in the manage gallery dialog to the revision
	 * @param $page_id
	 * @param $gallery_id
	 * @param $data
	 */
	private function _save($page_id, $gallery_id, $data){
		//load the latest revision and delete the image from the corresponding gallery
		$this->load->model('revisions');
		$revision = $this->revisions->getLatestRevision($page_id);
		$new_revision = $revision; //make a copy to set the new order.
		if (!$revision){
			$this->result->isError = true;
			$this->result->errorStr = "ERROR: Retrieving the revision record to update. Please try again. If the problem persists please call the Helpdesk at x4024.";
		}else{
			if (count($data) != 0){//I don't want to give out an error either if its empty
				for ($i = 0; $i < count($data); $i++){
					//var_dump($data[$i]);
					if (isset($revision['revision_text'][$gallery_id][$data[$i]->old_index]['src'])){

						$revision['revision_text'][$gallery_id][$data[$i]->old_index]["src"] = $data[$i]->src;
						$revision['revision_text'][$gallery_id][$data[$i]->old_index]["flip"] = $data[$i]->flip;
						$revision['revision_text'][$gallery_id][$data[$i]->old_index]["angle"] = $data[$i]->angle;

						$new_revision['revision_text'][$gallery_id][$data[$i]->new_index] = $revision['revision_text'][$gallery_id][$data[$i]->old_index];
					}else{
						$this->result->isError = true;
						$this->result->errorStr = "Image ".($i + 1)." appears to be corrupted, delete it and re-upload it. NOTE: The other images have been saved.";
					}
				}

				if (!$this->result->isError){
					//var_dump($new_revision['revision_text'][$gallery_id]);
					$this->result->data['images'] = $new_revision['revision_text'][$gallery_id];

					//update the revision record
					if (!$this->revisions->updateRevision($new_revision)){
						$this->result->isError = true;
						$this->result->errorStr = "There was an error updating the revision with the image changes. Try again later. If the problem persists please call the Helpdesk at x4024";
					}else{
						$this->result->data['gallery_id'] = $gallery_id;
					}
				}
			}
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->result));
	}
}
