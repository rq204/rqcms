<?php
//添加水印
function coreaddwatermark($sourcefile = '', $watermarkfile = '', $position = 9, $quality = 80) {
	!isset($setting_attachimagequality) && $setting_attachimagequality = 80;
	$sourceinfo = getimagesize($sourcefile);
	$watermarkinfo = getimagesize($watermarkfile);
	list($s_w, $s_h) = $sourceinfo;
	list($w_w, $w_h) = $watermarkinfo;
	switch($sourceinfo['mime']) {
		case 'image/jpeg':
			$source = imageCreateFromJPEG($sourcefile);
			break;
		case 'image/gif':
			$gifdata = readfromfile($sourcefile);
			if(strpos($gifdata, 'NETSCAPE2.0') !== false) return false;
			$source = imageCreateFromGIF($sourcefile);
			break;
		case 'image/png':
			$source = imageCreateFromPNG($sourcefile);
			break;
		default:
			return false;
	}
	switch($position) {
		case 1:
			$x = +5;
			$y = +5;
			break;
		case 2:
			$x = ($s_w - $w_w) / 2;
			$y = +5;
			break;
		case 3:
			$x = $s_w - $w_w - 5;
			$y = +5;
			break;
		case 4:
			$x = +5;
			$y = ($s_h - $w_h) / 2;
			break;
		case 5:
			$x = ($s_w - $w_w) / 2;
			$y = ($s_h - $w_h) / 2;
			break;
		case 6:
			$x = $s_w - $w_w - 5;
			$y = ($s_h - $w_h) / 2;
			break;
		case 7:
			$x = +5;
			$y = $s_h - $w_h - 5;
			break;
		case 8:
			$x = ($s_w - $w_w) / 2;
			$y = $s_h - $w_h - 5;
			break;
		case 9:
			$x = $s_w - $w_w - 5;
			$y = $s_h - $w_h - 5;
			break;
	}
	if(substr($watermarkfile, -4) == '.png') {
		$watermark = imageCreateFrompng($watermarkfile);
	} else {
		$watermark = imageCreateFromGIF($watermarkfile);
	}
	imagecopy($source, $watermark, $x, $y, 0, 0, $w_w, $w_h);
	imagejpeg($source, $sourcefile, $quality);
}

//缩略图处理

function generate_thumbnail($sourcefile,$thumbswidth,$thumbsheight,$thumbfilename,$extension) {
	$image  = '';
	$return = array();
	$remap  = array( 1 => 'gif', 2 => 'jpg', 3 => 'png' );
	if ($thumbswidth && $thumbsheight ) {
		$filesize = @GetImageSize($sourcefile);
		if ($filesize[0] > $thumbswidth || $filesize[1] > $thumbsheight ) { 
			$im = scale_image( array(
				'max_width'  => $thumbswidth,
				'max_height' => $thumbsheight,
				'cur_width'  => $filesize[0],
				'cur_height' => $filesize[1]
			));
			$return['thumbwidth']   = $im['img_width'];
			$return['thumbheight']  = $im['img_height'];
			if ($remap[$filesize[2]] == 'gif' ) {
				if (function_exists('imagecreatefromgif')) {
					if(!($image = @imagecreatefromgif($sourcefile))){
						redirect($filename.'文件生成缩略图失败');
					}
					$type = 'gif';
				}
			} else if ($remap[$filesize[2]] == 'png') {
				if (function_exists('imagecreatefrompng')) {
					if(!($image = @imagecreatefrompng($sourcefile))){
						redirect($filename.'文件生成缩略图失败');
					}
					$type = 'png';
				}
			} else if ($remap[$filesize[2]] == 'jpg') {
				if (function_exists('imagecreatefromjpeg')) {
					if(!($image = @imagecreatefromjpeg($sourcefile))){
						redirect($filename.'文件生成缩略图失败');
					}
					$type = 'jpg';
				}
			}
			if ( $image ) {
				if (function_exists('imagecreatetruecolor')) {
					$thumb = @imagecreatetruecolor($im['img_width'], $im['img_height']);
					@imagecopyresampled($thumb, $image, 0, 0, 0, 0, $im['img_width'], $im['img_height'], $filesize[0], $filesize[1]);
				} else {
					$thumb = @imagecreate($im['img_width'], $im['img_height']);
					@imagecopyresized($thumb, $image, 0, 0, 0, 0, $im['img_width'], $im['img_height'], $filesize[0], $filesize[1]);
				}
				if (PHP_VERSION != '4.3.2') {
					UnsharpMask($thumb);
				}

				if ($extension == 'jpg' && function_exists('imagejpeg')) {
					@imagejpeg( $thumb, $thumbfilename );
					@imagedestroy( $thumb );
				} else if ($extension == 'png' && function_exists('imagepng'))	{
					@imagepng( $thumb,$thumbfilename);
					@imagedestroy( $thumb );
				} else if ($extension == 'gif' && function_exists('imagegif'))	{
					@imagegif($thumb,$thumbfilename);
					@imagedestroy( $thumb );
				} 
				return $return;
			} else {
				$return['thumbwidth']    = $im['img_width'];
				$return['thumbheight']   = $im['img_height'];
				return $return;
			}
		} else { 
			$return['thumbwidth']    = $filesize[0];
			$return['thumbheight']   = $filesize[1];
			return $return;
		}
	}
}


//计算缩略图的大小

function scale_image($arg) {

	$ret = array('img_width' => $arg['cur_width'], 'img_height' => $arg['cur_height']);

	if ( $arg['cur_width'] > $arg['max_width'] ) {

		$ret['img_width']  = $arg['max_width'];

		$ret['img_height'] = ceil( ( $arg['cur_height'] * ( ( $arg['max_width'] * 100 ) / $arg['cur_width'] ) ) / 100 );

		$arg['cur_height'] = $ret['img_height'];

		$arg['cur_width']  = $ret['img_width'];

	}

	if ( $arg['cur_height'] > $arg['max_height'] ) {

		$ret['img_height']  = $arg['max_height'];

		$ret['img_width']   = ceil( ( $arg['cur_width'] * ( ( $arg['max_height'] * 100 ) / $arg['cur_height'] ) ) / 100 );

	}

	return $ret;

}



function UnsharpMask($img, $amount = 100, $radius = .5, $threshold = 3) {

	$amount = min($amount, 500);

	$amount = $amount * 0.016;

	if ($amount == 0) return true;

	$radius = min($radius, 50);

	$radius = $radius * 2;

	$threshold = min($threshold, 255);

	$radius = abs(round($radius));

	if ($radius == 0) return true;

	$w = ImageSX($img);

	$h = ImageSY($img);

	$imgCanvas  = ImageCreateTrueColor($w, $h);

	$imgCanvas2 = ImageCreateTrueColor($w, $h);

	$imgBlur    = ImageCreateTrueColor($w, $h);

	$imgBlur2   = ImageCreateTrueColor($w, $h);

	ImageCopy($imgCanvas,  $img, 0, 0, 0, 0, $w, $h);

	ImageCopy($imgCanvas2, $img, 0, 0, 0, 0, $w, $h);

	for ($i = 0; $i < $radius; $i++) {

		ImageCopy($imgBlur, $imgCanvas, 0, 0, 1, 1, $w - 1, $h - 1);

		ImageCopyMerge($imgBlur, $imgCanvas, 1, 1, 0, 0, $w, $h, 50);

		ImageCopyMerge($imgBlur, $imgCanvas, 0, 1, 1, 0, $w - 1, $h, 33.33333);

		ImageCopyMerge($imgBlur, $imgCanvas, 1, 0, 0, 1, $w, $h - 1, 25);

		ImageCopyMerge($imgBlur, $imgCanvas, 0, 0, 1, 0, $w - 1, $h, 33.33333);

		ImageCopyMerge($imgBlur, $imgCanvas, 1, 0, 0, 0, $w, $h, 25);

		ImageCopyMerge($imgBlur, $imgCanvas, 0, 0, 0, 1, $w, $h - 1, 20 );

		ImageCopyMerge($imgBlur, $imgCanvas, 0, 1, 0, 0, $w, $h, 16.666667); // dow

		ImageCopyMerge($imgBlur, $imgCanvas, 0, 0, 0, 0, $w, $h, 50);

		ImageCopy($imgCanvas, $imgBlur, 0, 0, 0, 0, $w, $h);

		ImageCopy($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h);

		ImageCopyMerge($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 50);

		ImageCopyMerge($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 33.33333);

		ImageCopyMerge($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 25);

		ImageCopyMerge($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 33.33333);

		ImageCopyMerge($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 25);

		ImageCopyMerge($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 20 );

		ImageCopyMerge($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 16.666667);

		ImageCopyMerge($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 50);

		ImageCopy($imgCanvas2, $imgBlur2, 0, 0, 0, 0, $w, $h);

	}

	for ($x = 0; $x < $w; $x++)	{

		for ($y = 0; $y < $h; $y++)	{

			$rgbOrig = ImageColorAt($imgCanvas2, $x, $y);

			$rOrig = (($rgbOrig >> 16) & 0xFF);

			$gOrig = (($rgbOrig >>  8) & 0xFF);

			$bOrig =  ($rgbOrig        & 0xFF);

			$rgbBlur = ImageColorAt($imgCanvas, $x, $y);

			$rBlur = (($rgbBlur >> 16) & 0xFF);

			$gBlur = (($rgbBlur >>  8) & 0xFF);

			$bBlur =  ($rgbBlur        & 0xFF);

			$rNew = (abs($rOrig - $rBlur) >= $threshold) ? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig)) : $rOrig;

			$gNew = (abs($gOrig - $gBlur) >= $threshold) ? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig)) : $gOrig;

			$bNew = (abs($bOrig - $bBlur) >= $threshold) ? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig)) : $bOrig;

			if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew)) {

				$pixCol = ImageColorAllocate($img, $rNew, $gNew, $bNew);

				ImageSetPixel($img, $x, $y, $pixCol);

			}

		}

	}

	ImageDestroy($imgCanvas);

	ImageDestroy($imgCanvas2);

	ImageDestroy($imgBlur);

	ImageDestroy($imgBlur2);

	return true;

}
