<?php

/*
 *     Smarty plugin
 * -------------------------------------------------------------
 * File:        function.iimg.php
 * Type:        function
 * Name:        iimg
 * Description: This TAG creates a "intalligence img" like img.
 *
 * -------------------------------------------------------------
 * Created by Ali Hosseini
 * @license GNU Public License (GPL)
 *
 * -------------------------------------------------------------
 * Parameter:
 * - src         = source of media (required)
 * -------------------------------------------------------------
 * Example usage:
 *
 * <div>{iimg src="/img/icon.png"}</div>
 */

function fetchImgInfo($src, $width=null, $height=null, $flag=null){
    global $system;
    
	switch ($flag){
		case 'max':
		    return mysqli_fetch_array(mysqli_query($system->dbm->db->dbhandler, "SELECT * FROM `cache` WHERE `src` = '$src' AND `width` = (SELECT max(`width`) FROM `cache`)"));
			break;
		default:
		    return mysqli_fetch_array(mysqli_query($system->dbm->db->dbhandler, "SELECT * FROM `cache` WHERE `src` = '$src' AND `width` = $width AND `height` = $height"));
			break;
	}
	
}

function smarty_function_iimg($params, &$smarty) {
	global $system, $settings;
	
	$src = urldecode($params['src']);
	$id = !empty($params['id']) ? 'id="' . $params['id'] . '"' : null;
	$class = !empty($params['class']) ? 'class="' . $params['class'] . '"' : null;
	$alt = !empty($params['alt']) ? 'alt="' . $params['alt'] . '"' : null;
	$title = !empty($params['title']) ? 'title="' . $params['title'] . '"' : null;
	$figCaption = !empty($params['caption']) ? '<figcaption>' . $params['caption'] . '</figcaption>' : null;
	$jpeg_quality = 70;
	
	if(!empty($params['width']) && !empty($params['height'])){
		$widthString = 'width="' . $params['width'] . '"';
		$heightString = 'height="' . $params['height'] . '"';
		$width = $params['width'];
		$height = $params['height'];
		$imgInfo = fetchImgInfo($src, $width, $height);
//		echo '1 <br>';
//		print_r($imgInfo);
	}else{
		$imgInfo = fetchImgInfo($src, null, null, 'max');
		
		$size = getimagesize($src);
		
		$widthString = ($size[0] > $imgInfo['width']) ? 'width="' . $size[0] . '"': 'width="' . $imgInfo['width'] . '"';
		$heightString = ($size[1] > $imgInfo['height']) ? 'height="' . $size[0] . '"': 'height="' . $imgInfo['height'] . '"';
		
		$width = ($size[0] > $imgInfo['width']) ? $size[0] : $imgInfo['width'];
		$height = ($size[1] > $imgInfo['height']) ? $size[1] : $imgInfo['height'];
//		echo 2;
	}
	
	if($imgInfo['name'] && $imgInfo['extension']){
		$fileName = $imgInfo['name'];
		$fileExtension = $imgInfo['extension'];
//		echo 3;
	}else{
		$imgInfo = @pathinfo($src);
		$fileName = $imgInfo['filename'];
		$fileExtension = $imgInfo['extension'];
//		echo 4;
	}
	
	$cacheImage = 'tmp/cache/img/' . $fileName . '-' . $width . '-' . $height . '.' . $fileExtension;
	if(mysqli_num_rows(mysqli_query($system->dbm->db->dbhandler, "SELECT `id` FROM `cache` WHERE `name` = '$fileName' AND `extension` = '$fileExtension' AND `width` = $width AND `height` = $height")) > 0){
//		echo 5;
		return '<div style="margin:0 auto; width:' . $width . 'px;"><figure><img ' . $id . ' src="' . $cacheImage . '" ' . $widthString . ' ' . $heightString . ' ' . $class . ' ' . $alt . ' ' . $title . '>' . $figCaption . '</figure></div>';
	}else{
//		echo '6 <br>';
//		print_r($imgInfo);
//		echo '<br> '.$imgInfo.'<br> '.$src.'<br> ********* <br>';
		require_once 'kernel/lib/xorg/phpThumb/ThumbLib.inc.php';

		try {
			$thumb = @PhpThumbFactory::create($src, array('jpegQuality' => $jpeg_quality));
		}catch(Exception $e){
			$thumb = @PhpThumbFactory::create('theme/' . $settings['theme'] . '/img/default.jpg', array('jpegQuality' => $jpeg_quality));
		}

		if(isset($width) || isset($height)) $thumb->resize($width, $height);
		if(isset($params['cropSize'])) $thumb->cropFromCenter($params['cropSize']);
		if(isset($params['rotateDegrees'])) $thumb->rotateImageNDegrees($params['rotateDegrees']);
		
		if($thumb->save($cacheImage)){
			$time = time();
			mysqli_query($system->dbm->db->dbhandler, "INSERT INTO `cache` (`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `src`, `type`, `name`, `extension`, `width`, `height`) VALUES (1, $time, 1, 1, 1, 1, 1, '$src', 'image', '$fileName', '$fileExtension', $width, $height)");
		}		

//		$thumb->show();
		return '<div style="margin:0 auto; width:' . $width . 'px;"><figure><img ' . $id . ' src="' . $cacheImage . '" ' . $widthString . ' ' . $heightString . ' ' . $class . ' ' . $alt . ' ' . $title . '>' . $figCaption . '</figure></div>';
	}

}

?>