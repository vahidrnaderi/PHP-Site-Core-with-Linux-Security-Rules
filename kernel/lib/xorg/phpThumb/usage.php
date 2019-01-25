<?php
require_once 'ThumbLib.inc.php';

$fileName = (isset($_GET['url'])) ? urldecode($_GET['url']) : '../../../../theme/mobdesign/img/default.jpg';
//echo '1 '.$fileName;
$pathParts = @pathinfo($_GET['url']);
//echo '2 '.$pathParts;
try {
	$thumb = @PhpThumbFactory::create($fileName);
//	echo '3 '.$thumb;
}catch(Exception $e){
	$thumb = @PhpThumbFactory::create('../../../../theme/mobdesign/img/default.jpg');
//	echo '4 '.$thumb;
}

if(isset($_GET['width']) || isset($_GET['height'])) $thumb->resize(urldecode($_GET['width']), urldecode($_GET['height']));
if(isset($_GET['cropSize'])) $thumb->cropFromCenter(urldecode($_GET['cropSize']));
if(isset($_GET['rotateDegrees'])) $thumb->rotateImageNDegrees(urldecode($_GET['rotateDegrees']));
$thumb->save("../../../../tmp/cache/img/$pathParts[filename]-$_GET[width]-$_GET[height].$pathParts[extension]", urldecode($pathParts[extension]));

//$thumb->show();

?>