<?php

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @param string $path where to save the uploaded file
     * @param string $ext the file extension
     * @return boolean TRUE on success
     */
    function save($path, $ext) {
		//get the file from input
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

		//if the sizes don't match then something went wrong.
        if ($realSize != $this->getSize()){
            return false;
        }

		//get a unique name
		fseek($temp, 0, SEEK_SET);
		$filename = sha1(stream_get_contents($temp)).$ext;

		//copy the stream to the file
		fseek($temp, 0, SEEK_SET);
        $target = fopen($path.$filename, "w");
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return $filename;
    }

	/**
	 * @return string filename
	 */
    function getName() {
        return $_GET['qqfile'];
    }

	/**
	 * @throws Exception when content length is not supported
	 * @return int the file size
	 */
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];
        } else {
            throw new Exception('Getting content length is not supported.');
        }
    }
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path, $ext) {
	    $tmp_filename = rand(0, 1000000); //better way?
	    if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path.$tmp_filename)){
		    return false;
	    }

	    $filename = sha1(file_get_contents($path.$tmp_filename));
	    rename($path.$tmp_filename, $path.$filename.$ext);

        return $filename.$ext;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){
        $allowedExtensions = array_map("strtolower", $allowedExtensions);

        $this->allowedExtensions = $allowedExtensions;
        $this->sizeLimit = $sizeLimit;

        $this->checkServerSettings();

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false;
        }
    }

    private function checkServerSettings(){
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
        }
    }

    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory){
        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.");
        }

        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }

        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => 'File is empty');
        }

        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }

        $pathinfo = pathinfo($this->file->getName());
        $ext = strtolower($pathinfo['extension']);

        if($this->allowedExtensions && !in_array($ext, $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }

	    $filename = $this->file->save($uploadDirectory, '.'.$ext);
        if ($filename === false){
            return array('error'=> 'Could not save uploaded file. The upload was cancelled, or server error encountered');
        } else {
            return array('success' => true, 'filename'=>$filename, 'orig_filename'=>$this->file->getName());
        }

    }
}

// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array('jpg', 'jpeg', 'bmp', 'gif', 'png', 'pdf');
// max file size in bytes
$sizeLimit = 8 * 1024 * 1024;

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$result = $uploader->handleUpload('../uploads/');

// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
