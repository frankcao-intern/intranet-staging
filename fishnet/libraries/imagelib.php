<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author cravelo
 * @date Jun 28, 2010
 * @time: 10:27:19 AM
 * @version 0.2.1
 * Code taken from: http://www.mawhorter.net/web-development/simple-image-manipulation-in-php-rotate-resize-crop-flip-and-mirror-thumbnails-square-and-regular
 * Taken on: 06/28/2010
 * Post signature: December 31st, 2009 by Cory
 * CHANGELOG
 *
 *		0.2.1		-Added output() screen, in addition to save/write
 *					-Added support for PNG transparency
 *					-Made contenttype the PNG by default
 */

/**
 * Image manipulation functions
 * @author Carlos Ravelo
 * @package Libraries
 */
class imagelib {
	/**
	 * @var resource
	 */
	var $res 			= NULL;
	var $source 		= NULL;
	var $contenttype 	= IMAGETYPE_PNG;
	var $is_modified    = false;

	/**
	 * Constructor
	 * @param mixed $src_or_resource src is the path to an image.  If it exists, the image will be automatically opened
	 *		can also be an already created image resource
	 */
	function __construct($src_or_resource = NULL) {
		if( ! is_null($src_or_resource) )
			if ( is_resource($src_or_resource) )
				$this->resource($src_or_resource);
			else
				$this->open($src_or_resource);
	}

	function __destruct(){
		if (isset($this->res) and ($this->res !== FALSE)){ imagedestroy($this->res); }
		$this->source = NULL;
		$this->contenttype = NULL;
	}

	/**
	 * Rotate the active image
	 * @param int $degrees number of degrees to spin the image, if this is something like
	 *		45, it will leave gaps in the frame which will be filled in by $bkg
	 * @param string $bkg If image is rotated in a way that does not fill the frame, this is the color that will be used
	 * @return bool
	 */
	function rotate ($degrees, $bkg='0')
	{
		if ((abs($degrees) == 0) or (abs($degrees) == 360)) return false; //no rotation necessary.

		$im = imagerotate( $this->res, $degrees, $bkg );
		imagedestroy($this->res);
		$this->res = $im;

		return $this->is_modified = true;
	}

	/**
	 * Shortcut functions for rotate
	 */
	function rotate_right() { $this->rotate(270); }
	/**
	 * Shortcut functions for rotate
	 */
	function rotate_180() { $this->rotate(180); }
	/**
	 * Shortcut functions for rotate
	 */
	function rotate_left() { $this->rotate(90); }

	/**
	 * Attempt to automatically rotate the image based on exif data
	 * 	a lot of digital cameras store orientation data of the camera
	 *		this will use that to automatically fix an images orientation
	 *
	 *		Note: This will only work if you have the function exif_read_data
	 *			which is part of PHPs exif data extension
	 * @return mixed
	 */
	function auto_rotate() {
		if( ! function_exists('exif_read_data') or ($this->contenttype !== IMAGETYPE_JPEG)){ return false; }
		$exif = exif_read_data($this->source);

		$ort = isset($exif['Orientation']) ? $exif['Orientation'] : 1;
		 switch($ort) // http://www.impulseadventure.com/photo/exif-orientation.html
		 {
			case 1: // regular, do nothing
				break;
			case 2:
				return $this->flip_h();
			case 3:
				return $this->rotate_180();
			case 4:
				return $this->flip_v();
			case 5:
				return ($this->flip_h() && $this->rotate_right());
			case 6:
				return $this->rotate_right();
			case 7:
				return ($this->flip_h() && $this->rotate_left());
			case 8:
				return $this->rotate_left();
			default:
				return false;
		 }

		return false;
	}

	/**
	 * Will flip an image (mirror it)
	 *
	 * @param boolean $bFlipH: flip horizontal  (true/false)
	 * @param boolean $bFlipV: flip vertical (true/false)
	 * @return bool
	 */
	function flip($bFlipH, $bFlipV = false){
		if (!$bFlipH and !$bFlipV) { return false; }; //if both are false there's nothing to do

		$imgsrc = $this->res;
		$width = imagesx($imgsrc);
		$height = imagesy($imgsrc);
		$imgdest = imagecreatetruecolor($width, $height);
		$this->prepare($imgdest);

		for ($x = 0; $x < $width; $x++)
		{
			for ($y = 0; $y < $height; $y++)
			{
				if ($bFlipH && $bFlipV) imagecopy($imgdest, $imgsrc, $width - $x - 1, $height - $y - 1, $x, $y, 1, 1);
				else if ($bFlipH) imagecopy($imgdest, $imgsrc, $width - $x - 1, $y, $x, $y, 1, 1);
				else if ($bFlipV) imagecopy($imgdest, $imgsrc, $x, $height - $y - 1, $x, $y, 1, 1);
			}
		}

		imagedestroy($imgsrc);

		$this->res = $imgdest;
		$this->is_modified = true;

		return true;
	}

	/**
	 * Shortcut functions to flip
	 * @return bool
	 */
	function flip_h() { return $this->flip(true, false); }
	/**
	 * Shortcut functions to flip
	 * @return bool
	 */
	function flip_v() { return $this->flip(false, true); }
	/**
	 * Shortcut functions to flip
	 * @return bool
	 */
	function flip_both() { return $this->flip(true, true); }

	/**
	 * Resizes an image to fit in a certain size
	 *
	 * @param int $newdim:  This is the largest dimension the image can contain in
	 *		pixels.  If either of the measurements are larger than this size,
	 *		the image will be scaled
	 *
	 * @param bool $square make the new image square instead of keeping the dimensions
	 * @param bool $zc zoom crop
	 * @param bool $bHeight
	 * @param bool $resample: high quality resize?  it is good to turn this off if you are doing
	 *		lots of conversions and quality isn't a huge issue
	 *		resample on/off is a noticeable difference in time
	 *
	 * @return void
	 */
	function resize($newdim, $square=false, $zc=false, $bHeight=false, $resample=true) {
		if (!isset($newdim) or ($newdim === false)){ return; }

		$src_width 	= imagesx( $this->res );
		$src_height = imagesy( $this->res );
		$src_w 		= $src_width;
		$src_h 		= $src_height;
		$src_x 		= 0;
		$src_y 		= 0;

		$percent = false;
		if ( $newdim < 1 ){
			$percent = $newdim;
		}elseif ( substr($newdim, -1) == '%' ){
			$percent = substr($newdim, 0, -1)/100;
		}

		if ( false !== $percent ){
			$newdim = round( ($bHeight ? ($src_height*$percent) : ($src_width*$percent) ) );
		}

		if($zc and ($src_height > $src_width)){ $bHeight = true; $newdim = $zc; }

		if ($square){
			$dst_w = $newdim;
			$dst_h = $newdim;
			if ( ! $bHeight ){
				$src_x = ceil( ( $src_width - $src_height ) / 2 );
				$src_w = $src_height;
				$src_h = $src_height;
			}else{
				$src_y = ceil( ( $src_height - $src_width ) / 2 );
				$src_w = $src_width;
				$src_h = $src_width;
			}
		}else{
			if ( ! $bHeight ){
				$dst_w = $newdim;
				$dst_h = floor( $src_height * ($dst_w / $src_width) );
			}else{
				$dst_h = $newdim;
				$dst_w = floor( $src_width * ($dst_h / $src_height) );
			}
		}

		if($zc and ($src_width >= $src_height)){
			$src_w = $src_width;
			$src_h = $src_height;

			$cmp_x = $src_width  / $dst_w;
			$cmp_y = $src_height / $zc;
			$dst_h = $zc;

			// calculate x or y coordinate and width or height of source
			if ($cmp_x > $cmp_y) {

				$src_w = round (($src_width / $cmp_x * $cmp_y));
				$src_x = round (($src_width - ($src_width / $cmp_x * $cmp_y)) / 2);

			} elseif ($cmp_y > $cmp_x) {
				$src_h = round (($src_height / $cmp_y * $cmp_x));
				$src_y = round (($src_height - ($src_height / $cmp_y * $cmp_x)) / 2);
				//$src_y = 0;//always cut from the top;
			}

			// positional cropping!
			/*switch ($align) {
				case 't':
				case 'tl':
				case 'lr':
				case 'tr':
				case 'rt':
					$src_y = 0;
					break;

				case 'b':
				case 'bl':
				case 'lb':
				case 'br':
				case 'rb':
					$src_y = $height - $src_h;
					break;

				case 'l':
				case 'tl':
				case 'lt':
				case 'bl':
				case 'lb':
					$src_x = 0;
					break;

				case 'r':
				case 'tr':
				case 'rt':
				case 'br':
				case 'rb':
					$src_x = $width - $new_width;
					$src_x = $width - $src_w;

					break;

				default:

					break;
			}*/
		}

		$dst_im = imagecreatetruecolor($dst_w,$dst_h);
		$this->prepare($dst_im);
		if ($resample){
			imagecopyresampled($dst_im, $this->res, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
		}else{
			imagecopyresized($dst_im, $this->res, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
		}

		imagedestroy($this->res);
		$this->res = $dst_im;
		$this->is_modified = true;
	}

	/**
	 * Shortcut functions to resize an image to certain, standard sizes
	 * @param int $newdim
	 * @param bool $square
	 * @param bool $resample
	 * @return void
	 */
	function width($newdim, $square=false, $resample=true) { $this->resize($newdim, $square, false, $resample); }
	/**
	 * Shortcut functions to resize an image to certain, standard sizes
	 * @param int $newdim
	 * @param bool $square
	 * @param bool $resample
	 * @return void
	 */
	function height($newdim, $square=false, $resample=true) { $this->resize($newdim, $square, true, $resample); }
	/**
	 * Shortcut functions to resize an image to certain, standard sizes
	 * @param bool $square
	 * @return void
	 */
	function resize_1600($square=false) { $this->resize(1600, $square); }
	/**
	 * Shortcut functions to resize an image to certain, standard sizes
	 * @param bool $square
	 * @return void
	 */
	function resize_1200($square=false) { $this->resize(1200, $square); }
	/**
	 * Shortcut functions to resize an image to certain, standard sizes
	 * @param bool $square
	 * @return void
	 */
	function resize_1024($square=false) { $this->resize(1024, $square); }
	/**
	 * Shortcut functions to resize an image to certain, standard sizes
	 * @param bool $square
	 * @return void
	 */
	function resize_800($square=false) { $this->resize(800, $square); }
	/**
	 * Shortcut functions to resize an image to certain, standard sizes
	 * @param bool $square
	 * @return void
	 */
	function resize_640($square=false) { $this->resize(640, $square); }

	/**
	 * Generate a thumbnail from the loaded image
	 *
	 * @param string $dest:  The destination file on disk to save new thumbnail
	 * @param int $newdim:  This is the largest dimension the image can contain in
	 *		pixels.  If either of the measurements are larger than this size,
	 *		the image will be scaled
	 * @param bool $square: create a square thumbnail
	 * @param bool $bHeight: create a square thumbnail
	 * @param int $out: The type of image to create (uses PHPs standard image constants for PNG, JPG, GIF)
	 * @param bool $resample: high quality resize?  it is good to turn this off if you are doing
	 *		lots of conversions and quality isn't a huge issue
	 * @return void
	 */
	function thumbnail($dest, $newdim, $square=false, $bHeight=false, $out=NULL, $resample=true) {
		$src_width 	= imagesx($this->res);
		$src_height = imagesy($this->res);
		$src_w 		= $src_width;
		$src_h 		= $src_height;
		$src_x 		= 0;
		$src_y 		= 0;

		$percent = false;
		if ( $newdim < 1 )
			$percent = $newdim;
		elseif ( substr($newdim, -1) == '%' )
			$percent = substr($newdim, 0, -1)/100;

		if ( false !== $percent )
			$newdim = round( ($bHeight ? ($src_height*$percent) : ($src_width*$percent) ) );

		if ($square)
		{
			$dst_w = $largest_dim;
			$dst_h = $largest_dim;
			if ( ! $bHeight )
			{
				$src_x = ceil( ($src_width - $src_height) / 2 );
				$src_w = $src_height;
				$src_h = $src_height;
			}
			else
			{
				$src_y = ceil( ($src_height - $src_width) / 2 );
				$src_w = $src_width;
				$src_h = $src_width;
			}
		}
		else
		{
			if ( ! $bHeight )
			{
				$dst_w = $largest_dim;
				$dst_h = floor( $src_height * ($dst_w / $src_width) );
			}
			else
			{
				$dst_h = $largest_dim;
				$dst_w = floor( $src_width * ($dst_h / $src_height) );
			}
		}
		$dst_im = imagecreatetruecolor($dst_w,$dst_h);
		$this->prepare($dst_im);
		if ($resample)
			imagecopyresampled($dst_im, $this->res, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
		else
			imagecopyresized($dst_im, $this->res, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

		if ( is_null($out) )
			$out = $this->contenttype;

		switch($out)
		{
			case IMAGETYPE_PNG:
			  imagepng($dst_im, $dest);
			break;
			case IMAGETYPE_GIF:
			  imagegif($dst_im, $dest);
			break;
			case IMAGETYPE_JPEG:
			  imagejpeg($dst_im, $dest);
			break;
		}
		imagedestroy($dst_im);
		$this->is_modified = true;
	}

	/**
	 * Shortcut functions to some standard thumbnail sizes
	 * @param string $dest
	 * @param int $out
	 * @param bool $square
	 * @return void
	 */
	function thumbnail_xsmall($dest, $out, $square=false) { $this->thumbnail($dest, $out, 60, $square); }
	/**
	 * Shortcut functions to some standard thumbnail sizes
	 * @param string $dest
	 * @param int $out
	 * @param bool $square
	 * @return void
	 */
	function thumbnail_small($dest, $out, $square=false) { $this->thumbnail($dest, $out, 80, $square); }
	/**
	 * Shortcut functions to some standard thumbnail sizes
	 * @param string $dest
	 * @param int $out
	 * @param bool $square
	 * @return void
	 */
	function thumbnail_medium($dest, $out, $square=false) { $this->thumbnail($dest, $out, 160, $square); }
	/**
	 * Shortcut functions to some standard thumbnail sizes
	 * @param string $dest
	 * @param int $out
	 * @param bool $square
	 * @return void
	 */
	function thumbnail_large($dest, $out, $square=false) { $this->thumbnail($dest, $out, 300, $square); }
	/**
	 * Shortcut functions to some standard thumbnail sizes
	 * @param string $dest
	 * @param int $out
	 * @param bool $square
	 * @return void
	 */
	function thumbnail_xlarge($dest, $out, $square=false) { $this->thumbnail($dest, $out, 512, $square); }

	/**
	 * Crop an image by N pixels
	 *
	 * Same order as CSS property
	 *
	 * This will crop an image by the number of pixels you specify.
	 *		For example, to crop 10 pixels off the bottom and left sides
	 *		you would do crop(10, 0, 10)
	 *
	 * @param int $top
	 * @param int $right
	 * @param int $bottom
	 * @param int $left
	 * @return void
	 */
	private function _crop ($top, $right=0, $bottom=0, $left=0){
		$w  = imagesx($this->res);
		$h  = imagesy($this->res);
		$nw = $w - ($left+$right);
		$nh = $h - ($top+$bottom);
		$im = imagecreatetruecolor( $nw, $nh );
		$this->prepare($im);

		imagecopy($im, $this->res, 0, 0, $left, $top, $nw, $nh );

		imagedestroy($this->res);
		$this->res = $im;
		$this->is_modified = true;
	}

	/**
	 * Shortcut functions to _crop
	 * @param int $px
	 * @return void
	 */
	function crop_top($px) { $this->_crop($px); }
	/**
	 * Shortcut functions to _crop
	 * @param int $px
	 * @return void
	 */
	function crop_right($px) { $this->_crop(0, $px, 0, 0); }
	/**
	 * Shortcut functions to _crop
	 * @param int $px
	 * @return void
	 */
	function crop_bottom($px) { $this->_crop(0, 0, $px, 0); }
	/**
	 * Shortcut functions to _crop
	 * @param int $px
	 * @return void
	 */
	function crop_left($px) { $this->_crop(0, 0, 0, $px); }
	/**
	 * Shortcut functions to _crop
	 * @param int $px
	 * @return void
	 */
	function crop_h($px) { $this->_crop($px, 0, $px, 0); }
	/**
	 * Shortcut functions to _crop
	 * @param int $px
	 * @return void
	 */
	function crop_v($px) { $this->_crop(0, $px, 0, $px); }
	/**
	 * Shortcut functions to _crop
	 * @param int $px
	 * @return void
	 */
	function crop_all($px) { $this->_crop($px, $px, $px, $px); }

	/**
	 * Crop image by coordinates, $x, $y of starting point plus a height and width of selection.
	 * Should be used along with resize.
	 * @author Carlos Ravelo
	 * @date 06/28/2010
	 * @param int $x
	 * @param int $y
	 * @param int $h
	 * @param int $w
	 * @return bool
	 */
	function crop ($x, $y, $h, $w){
		if (($h <= 0) or ($w <= 0)) return false;
		$dst_r = ImageCreateTrueColor($w, $h);

		imagecopyresampled($dst_r, $this->res, 0, 0, $x, $y, $w, $h, $w, $h);

		imagedestroy($this->res);
		$this->res = $dst_r;
		$this->is_modified = true;

		return true;
	}

	/**
	 * Open an image from a file on disk
	 * @param string $src
	 * @return bool
	 */
	function open($src)	{
		$this->source = $src;
		if (file_exists($src)){
			switch( ( $this->contenttype = exif_imagetype($src) ) )
			{
				case IMAGETYPE_PNG:
				  $this->res = @imagecreatefrompng($src);
				break;
				case IMAGETYPE_GIF:
				  $this->res = @imagecreatefromgif($src);
				break;
				case IMAGETYPE_JPEG:
				  $this->res = @imagecreatefromjpeg($src);
				break;
			}
		}

		if (isset($this->res) and $this->res !== false){
			$this->prepare($this->res);
			$this->is_modified = false;
			return true;
		}else{
			return false;
		}
	}

	/**
	 * @return array
	 */
	function get_info(){
		return getimagesize($this->source);
	}

	/**
	 * Takes an image resource and a content type and prepares the resource
	 * @param resource $res
	 * @param int $contenttype
	 * @return void
	 */
	function prepare($res, $contenttype=NULL){
		if ( is_null($contenttype) ) $contenttype = $this->contenttype;

		if ( $contenttype == IMAGETYPE_PNG ){
			imagesavealpha($res, true);
			imagealphablending($res, false);
		}
	}

	/**
	 * Get/set image resource
	 * @param resource $res
	 * @return resource
	 */

	function resource ($res=NULL) {
		if ( is_null($res) ){
			return $this->res;
		}else{
			$this->res = $res;
			$this->is_modified = false;

			return null;
		}
	}

	/**
	 * Save the image back to the original file
	 * @param null $out
	 * @return void
	 */
	function save($out=NULL){
		if ( !is_null($this->source) and $this->is_modified ){
			$this->write($this->source, $out);
		}
	}

	/**
	 * Save the image to a different location
	 * @param string $dest
	 * @param int $out
	 * @return void
	 */
	function write($dest, $out=NULL){
		if(is_null($out)){
			$out = $this->contenttype;
		}

		switch($out){
			case IMAGETYPE_PNG:
			  imagepng($this->res, $dest, 2, PNG_FILTER_AVG);
			break;
			case IMAGETYPE_GIF:
			  imagegif($this->res, $dest);
			break;
			case IMAGETYPE_JPEG:
			  imagejpeg($this->res, $dest, 90);
			break;
		}
	}

	/**
	 * Output the image to the stream (browser)
	 * @param int $out
	 * @return void
	 */
	function output($out=NULL)
	{
		switch($out) {
			default:
			case IMAGETYPE_PNG:
			  $contenttype = 'png';
			break;
			case IMAGETYPE_GIF:
			  $contenttype = 'gif';
			break;
			case IMAGETYPE_JPEG:
			  $contenttype = 'jpeg';
			break;
		}

		header('Content-type: image/'.$contenttype);
		$this->write(NULL, $out);
	}
}

if( ! function_exists('exif_imagetype') )
{
	function exif_imagetype ( $f )
	{
		if ( false !== ( list(,,$type,) = getimagesize( $f ) ) )
			return $type;
		return IMAGETYPE_PNG; // meh same thing
	}
}
