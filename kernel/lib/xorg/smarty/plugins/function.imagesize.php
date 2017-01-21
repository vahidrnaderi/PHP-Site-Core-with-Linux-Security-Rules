<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.imagesize.php
 * Type:     function
 * Name:     imagesize
 * Purpose:  resizes and caches an image
 * Version:  1.03
 * Creator:  Eli Van Zoeren - eli@newmediacampaigns.com
 * -------------------------------------------------------------
 *
 * Copyright (c) 2009 New Media Campaigns
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *                
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *                
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.  
 */
 
function smarty_function_imagesize($params, &$smarty)
{
    // Make sure they passed a source file
    if (!$params['src']) return '<!-- Imagesizer Error: You must specify a source image -->';
    
    // Get various paths and urls for the image
    if (!$paths = getImagePath($params['src'], $smarty)) return false;
    
    // Make sure it's a file-type we can deal with
    if (!in_array(strtolower($paths['fileExt']), array('gif','jpeg','jpg','png'))) return '<!-- Imagesizer Error: Invalid file type -->';
    
    // Calculate the final dimensions
    $finalSize = getNewSize($params, $paths);
    
    // Do the actual resizing and cache the result
    if (!$finalImage = resizeAndCache($finalSize, $paths, $params)) return '<!-- Imagesizer Error: Could not resize the image -->';
    
    // Build the options for the img tag
    $opts = 'alt="'.$params['alt'].'"';
    $opts .= ( isset($params['class']) ) ? ' class="'.$params['class'].'"' : '';
    $opts .= ( isset($params['id']) ) ? ' id="'.$params['id'].'"' : '';
    $opts .= ( isset($params['title']) ) ? ' title="'.$params['title'].'"' : '';
    $opts .= ( isset($params['style']) ) ? ' style="'.$params['style'].'"' : '';
    
    // Output the tag
    return '<img src="'.$finalImage.'" '.$opts.' width="'.$finalSize[0].'" height="'.$finalSize[1].'" />';
}


// Figure out various urls and paths that we will need later on
function getImagePath($fileUrl, $smarty)
{
    // Store the image url
    $paths['fileUrl'] = $fileUrl;
    
    // The name of the file: image.jpg
    $paths['fileName'] = basename($fileUrl);
    
    // The paths to the document we are working with
    $scriptName = $smarty->_supers['server']['SCRIPT_NAME'];
    $paths['docPath'] = removeFileFromPath(removeDoubleSlashes(str_replace($smarty->_supers['server']['SCRIPT_NAME'],'',$smarty->_supers['server']['SCRIPT_FILENAME']) . '/' . $smarty->_supers['server']['REQUEST_URI']));
    
    // The relative path to the document
    $docUri = ($smarty->_supers['server']['REDIRECT_URL']) ? $smarty->_supers['server']['REDIRECT_URL'] : dirname($smarty->_supers['server']['SCRIPT_NAME']);
    $docUri = removeDoubleSlashes('/' . $docUri . '/');
    // If we've got an absolute image url...make it relative!
    $fileUri = str_replace('http://'.$smarty->_supers['server']['HTTP_HOST'], '', $fileUrl);
    $fileUri = str_replace('https://'.$smarty->_supers['server']['HTTP_HOST'], '', $fileUri);
    if (substr($fileUri, 0, 1) == DIRECTORY_SEPARATOR)
    {
        $docSegments = explode(DIRECTORY_SEPARATOR, $docUri);
        $fileSegments = explode(DIRECTORY_SEPARATOR, $fileUri);
        
        // Cycle through the url segments, working back from the current url to the image url
        while ( count($docSegments) || count($fileSegments) )
        {
            $docSegment = array_shift($docSegments);
            $fileSegment = array_shift($fileSegments);
            
            if ( ($docSegment != $fileSegment) || $up) {
              $up = ($docSegment) ? '../' : '';
              $relativeUri = $up . $relativeUri . $fileSegment . '/';
            }
        }
        
        $fileUrl = rtrim($relativeUri,'/');
    }

    // Get the actual path to the file on the server
    $filePath = '/' . get_absolute_path(removeDoubleSlashes($paths['docPath'] . '/' . $fileUrl));
    
    // Make sure there is actually an image at that path
    if (!is_readable($filePath))
    {
      echo "<!-- Imagesizer Error: Could not find the file {$filePath} -->";
      return false;
    }

    // Calculate all the other paths we will need
    $pathInfo = pathinfo($filePath);
    $paths['filePath'] = $pathInfo['dirname'];
    $paths['fileExt'] = $pathInfo['extension'];
    $paths['fileBasename'] = $pathInfo['filename'] ? $pathInfo['filename'] : substr($paths['fileName'],0,strrpos($paths['fileName'],'.'));
    $paths['fileSrc'] = removeDoubleSlashes($paths['filePath'] . '/' . $paths['fileName']);
    $paths['cachePath'] = removeDoubleSlashes($paths['filePath'] . '/cache/');

    // Make sure the cache folder exists
    if(!is_dir($paths['cachePath']))
    {
        // Try to create it
		    if (!@mkdir($paths['cachePath'], 0777))
		    {
		      echo "<!-- Imagesizer Error: Could not create cache directory. Please create {$paths['cachePath']} manually and try again. -->";
		      return false;
		    }
		
		    // Make sure it's really writable 
		    if (!is_writable($paths['cachePath']))
		    {
		      echo '<!-- Imagesizer Error: The cache directory is not writeable -->';
		      return false;
		    }
    }
	
    // The url to the cached image
    $paths['cacheUrl'] = removeDoubleSlashes('/' . get_absolute_path($docUri . '/' . dirname($fileUrl)) . '/cache/');
    
    return $paths;
}


// Figure out what the final size of the image should be,
// based on the inputs from the user
function getNewSize($params, $paths)
{
    // Get the size and ratio of the original image
    list($oldWidth, $oldHeight) = getimagesize($paths['fileSrc']);
    
    // Store the target dimensions for convenience
    $width = $params['width'];
    $height = $params['height'];
    
    // Figure out what to do based on the new and old sizes
    if (($oldWidth <= $width) && ($oldHeight <= $height) || (!$width && !$height))
    {
        // The image is already smaller than the requested sizes
        $newWidth = $oldWidth;
        $newHeight = $oldHeight;
    }
    elseif ($width && !$height)
    {
        // Resize based on the width
        if ($width > $oldWidth) $width = $oldWidth;
        $newWidth = $width;
        $newHeight = floor( $oldHeight * ($width / $oldWidth) );
    }
    elseif ($height && !$width)
    {
        // Resize based on the height
        if ($height > $oldHeight) $height = $oldHeight;
        $newHeight = $height;
        $newWidth = floor( $oldWidth * ($height / $oldHeight) );
    }
    else
    {
        // If they specified both, we need to decide whether to crop it
        if ($params['crop'])
        {
            // Crop it to the exact dimensions
            $newWidth = ($width > $oldWidth) ? $oldWidth : $width;
            $newHeight = ($height > $oldHeight) ? $oldHeight : $height;
            $crop = true;
        }
        else
        {
            // Resize to fit within the box

            // First based on the width
            if ($width > $oldWidth) $width = $oldWidth;
            $newWidth = $width;
            $newHeight = floor( $oldHeight * ($width / $oldWidth) );
            
            // Then based on height            
            if ($newHeight > $height)
            {
                $newWidth = floor( $newWidth * ($height / $newHeight) );
                $newHeight = $height;
            }
        }
    }       
    
    return array($newWidth, $newHeight, $crop);
}


// Actually resize the image and save it to the
// cache folder. Return the new filename.
function resizeAndCache($finalSize, $paths, $params)
{
    // Target dimensions
    $width = $finalSize[0];
    $height = $finalSize[1];
    
    // Original dimensions
    list($oldWidth, $oldHeight, $imgType) = getimagesize($paths['fileSrc']);

    // Don't resize if we don't need to
    if ( ($width >= $oldWidth) && ($height >= $oldHeight) && !$params['bw'] && !$params['radius'] ) return $paths['fileUrl'];
   
    // Create a name and path for the cached file
    $cachedName = $paths['fileBasename'] . '-' . $width . 'x' . $height;
    $cachedName .= ($params['bw']) ? '-bw' : '';
    $cachedName .= ($params['colorize']) ? '-c'.trim($params['colorize'],'#') : '';
    $cachedName .= ($params['radius']) ? '-r'.$params['radius'].trim($params['background'],'#') : '';
    $cachedName .= ($params['forcepng']) ? '.png' : '.' . $paths['fileExt'];
    $cachedPath = $paths['cachePath'] . $cachedName;
    $cachedUrl = $paths['cacheUrl'] . $cachedName;

    // Unless there's already a cached version, create one
    $imageTime = @filemtime($paths['fileSrc']);
    $cacheTime = @filemtime($cachedPath);
    if (!is_file($cachedPath) || $imageTime > $cacheTime)
    {		    
        // Read the image into memory
        switch ($imgType) 
        {
        	case IMAGETYPE_GIF:
              $image = imagecreatefromgif($paths['fileSrc']);
              break;
        	case IMAGETYPE_JPEG:
              $image = imagecreatefromjpeg($paths['fileSrc']);
              break;
        	case IMAGETYPE_PNG:
              $image = imagecreatefrompng($paths['fileSrc']);
              break;
        	default:
              return false;
    	  }
      
        // Create a new blank image to hold the resized image
        $newImage = imagecreatetruecolor($width, $height);
        
        if (!$finalSize[2])
        {
            // Resize it - no cropping needed
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $oldWidth, $oldHeight);
        }
        else
        {
            // Resize and crop
            $oldRatio = $oldWidth / $oldHeight;
            $newRatio = $width / $height;
            
            if ($oldRatio > $newRatio)
            {
                // Crop the width
                $adjustedWidth = floor( $oldWidth * ($height / $oldHeight) );
                $adjustedHeight = $height;
                $widthMargin = ($adjustedWidth - $width) / 2;
            }
            elseif ($oldRatio < $newRatio)
            {
                // Crop the height
                $adjustedHeight = floor( $oldHeight * ($width / $oldWidth) );
                $adjustedWidth = $width;
                $heightMargin = ($adjustedHeight - $height) / 2;
            }
                        
            imagecopyresampled($newImage, $image, -$widthMargin, -$heightMargin, 0, 0, $adjustedWidth, $adjustedHeight, $oldWidth, $oldHeight);
        }

        // Do the greyscale conversion
        if ($params['bw'])
        {
            imagefilter($image, IMG_FILTER_GRAYSCALE);
        }
        elseif ($params['colorize'])
        {
            $rgb = rgb_from_hex($params['colorize']);
            
            imagefilter($newImage, IMG_FILTER_GRAYSCALE);
            imagefilter($newImage, IMG_FILTER_COLORIZE, $rgb['r'], $rgb['g'], $rgb['b']);
        }
        
        // Add rounded corners 
        if ($params['radius'])
        {
            // Create a new image to hold the alpha mask
            $mask = imagecreatetruecolor($width, $height);
            imagealphablending ($mask, true);
            $trans = imagecolorallocatealpha($mask, 0, 0, 0, 127);
            $black = imagecolorallocatealpha($mask, 0, 0, 0, 0);
            
            // Convert to true color, if needed
            if (!imageistruecolor($newImage))
            {
                $tmpImage = imagecreatetruecolor($width, $height);
                imagecopy($tmpImage,$newImage,0,0,0,0,$width, $height);
                $newImage = $tmpImage;
            }
            
            // Draw the mask with rounded corners
            $radius = $params['radius'];
            $diameter = $radius * 2;
            imagefill($mask, 0, 0, $trans);
            imagefilledrectangle($mask, $radius, 0, ($width - $radius), $height, $black);
            imagefilledrectangle($mask, 0, $radius, $width, ($height - $radius), $black);
            $fillcolor = array(0, 0, 0, 0);
            imageSmoothArc ($mask, $radius, ($radius + 1), $diameter, $diameter, $fillcolor, 0, 12); //TL
            imageSmoothArc ($mask, ($width - $radius - 2), ($radius + 1), $diameter, $diameter, $fillcolor, 0, 12); //TR
            imageSmoothArc ($mask, $radius, ($height - $radius - 1), $diameter, $diameter, $fillcolor, 0, 12); //BL
            imageSmoothArc ($mask, ($width - $radius - 2), ($height- $radius - 1), $diameter, $diameter, $fillcolor, 0, 12); //BR
            
            // Create an image object to hold the final image
            $rounded = imagecreatetruecolor($width, $height);
            imagealphablending ($rounded, true);
            
            // Force to save as a PNG for transparency
            if ($params['forcepng'])
            {
                $imgType = IMAGETYPE_PNG;
            }
            
            // Create a background color
            if ( $params['background'] || ($imgType != IMAGETYPE_PNG) )
            {
                $bg = $params['background'] ? $params['background'] : 'ffffff';            
                $rgb = rgb_from_hex($bg);
                imagefill($rounded, 0, 0, imagecolorallocate($rounded, $rgb['r'], $rgb['g'], $rgb['b']));
            }
            else
            {
                imagefill($rounded, 0, 0, imagecolorallocatealpha($rounded, 255, 255, 255, 127));
                imagesavealpha($rounded, true);
            }
                
            // Cycle through the pixels and apply new color using mask
            for($x = 0; $x < $width; $x++)
            {
                for($y = 0; $y < $height; $y++)
                {
                    $rgb = imagecolorat($newImage, $x, $y);
                    $r = ($rgb >> 16) & 0xFF;
                    $g = ($rgb >> 8) & 0xFF;
                    $b = $rgb & 0xFF;
                    $a = imagecolorsforindex($mask, imagecolorat($mask, $x, $y));
                    $a = $a['alpha'];
                    $color = imagecolorallocatealpha($rounded, $r, $g, $b, $a);
                    imagesetpixel($rounded, $x, $y, $color);
                }
            }
                            
            $newImage = $rounded;
        }
        
        // Save the file
        switch ($imgType)
        {
    		case IMAGETYPE_GIF:
    			imagegif($newImage, $cachedPath);
          break;
    		case IMAGETYPE_JPEG:
    			imagejpeg($newImage, $cachedPath);
    			break;
    		case IMAGETYPE_PNG:
    			imagepng($newImage, $cachedPath);
    			break;
    		default:
    			return false;
    	}
    }
	
	return $cachedUrl;
}


// Just like it says on the box
function removeDoubleSlashes($str)
{
    return str_replace(array('//', '\\', '\\\\'), '/', $str);
}


// Removes any .php file from the end of a path
function removeFileFromPath($path)
{
    $segments = explode(DIRECTORY_SEPARATOR, $path);
    $lastSegment = end($segments);
    
    if (strpos($lastSegment, '.php'))
    {
        array_pop($segments);
    }
    
    return implode('/', $segments);
}


// Resolves /../ in paths. From the PHP docs.
function get_absolute_path($path) {
    $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
    $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
    $absolutes = array();
    foreach ($parts as $part) {
        if ('.' == $part) continue;
        if ('..' == $part) {
            array_pop($absolutes);
        } else {
            $absolutes[] = $part;
        }
    }
    return implode('/', $absolutes);
}


// Finds a url based on a relative path. From the PHP docs.
function mapURL($relPath)
{
    $filePathName = realpath($relPath);
    $filePath = realpath(dirname($relPath));
    $basePath = realpath($_SERVER['DOCUMENT_ROOT']);
    
    // can not create URL for directory lower than DOCUMENT_ROOT
    if (strlen($basePath) > strlen($filePath)) {
        return '';
    }
    
    return 'http://' . $_SERVER['HTTP_HOST'] . substr($filePathName, strlen($basePath));
}


// Takes a hex color code and returns an array of rgb
function rgb_from_hex($hex) 
{
    if (!$hex) return;
    $hex = trim($hex, '#');
    
    $rgb['r'] = hexdec(substr($hex, 0, 2));
    $rgb['g'] = hexdec(substr($hex, 2, 2));
    $rgb['b'] = hexdec(substr($hex, 4, 2));
    
    return $rgb;
}


/*
    
    Copyright (c) 2006-2008 Ulrich Mierendorff

    Permission is hereby granted, free of charge, to any person obtaining a
    copy of this software and associated documentation files (the "Software"),
    to deal in the Software without restriction, including without limitation
    the rights to use, copy, modify, merge, publish, distribute, sublicense,
    and/or sell copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
    THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
    DEALINGS IN THE SOFTWARE.

*/

function imageSmoothArcDrawSegment (&$img, $cx, $cy, $a, $b, $color, $start, $stop, $seg)
{   
    $fillColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], $color[3] );
    switch ($seg)
    {
        case 0: $xp = +1; $yp = -1; $xa = 1; $ya = -1; break;
        case 1: $xp = -1; $yp = -1; $xa = 0; $ya = -1; break;
        case 2: $xp = -1; $yp = +1; $xa = 0; $ya = 0; break;
        case 3: $xp = +1; $yp = +1; $xa = 1; $ya = 0; break;
    }
    for ( $x = 0; $x <= $a; $x += 1 ) {
        $y = $b * sqrt( 1 - ($x*$x)/($a*$a) );
        $error = $y - (int)($y);
        $y = (int)($y);
        $diffColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error );
        imageSetPixel($img, $cx+$xp*$x+$xa, $cy+$yp*($y+1)+$ya, $diffColor);
        imageLine($img, $cx+$xp*$x+$xa, $cy+$yp*$y+$ya , $cx+$xp*$x+$xa, $cy+$ya, $fillColor);
    }
    for ( $y = 0; $y < $b; $y += 1 ) {
        $x = $a * sqrt( 1 - ($y*$y)/($b*$b) );
        $error = $x - (int)($x);
        $x = (int)($x);
        $diffColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error );
        imageSetPixel($img, $cx+$xp*($x+1)+$xa, $cy+$yp*$y+$ya, $diffColor);
    }
}


function imageSmoothArc ( &$img, $cx, $cy, $w, $h, $color, $start, $stop)
{   
    while ($start < 0)
        $start += 2*M_PI;
    while ($stop < 0)
        $stop += 2*M_PI;
    
    while ($start > 2*M_PI)
        $start -= 2*M_PI;
    
    while ($stop > 2*M_PI)
        $stop -= 2*M_PI;
    
    
    if ($start > $stop)
    {
        imageSmoothArc ( &$img, $cx, $cy, $w, $h, $color, $start, 2*M_PI);
        imageSmoothArc ( &$img, $cx, $cy, $w, $h, $color, 0, $stop);
        return;
    }
    
    $a = 1.0*round ($w/2);
    $b = 1.0*round ($h/2);
    $cx = 1.0*round ($cx);
    $cy = 1.0*round ($cy);
    
    for ($i=0; $i<4;$i++)
    {
        if ($start < ($i+1)*M_PI/2)
        {
            if ($start > $i*M_PI/2)
            {
                if ($stop > ($i+1)*M_PI/2)
                {
                    imageSmoothArcDrawSegment($img, $cx, $cy, $a, $b, $color, $start, ($i+1)*M_PI/2, $i);
                }
                else
                {
                    imageSmoothArcDrawSegment($img, $cx, $cy, $a, $b, $color, $start, $stop, $i);
                    break;
                }
            }
            else
            {
                if ($stop > ($i+1)*M_PI/2)
                {
                    imageSmoothArcDrawSegment($img, $cx, $cy, $a, $b, $color, $i*M_PI/2, ($i+1)*M_PI/2, $i);
                }
                else
                {
                    imageSmoothArcDrawSegment($img, $cx, $cy, $a, $b, $color, $i*M_PI/2, $stop, $i);
                    break;
                }
            }
        }
    }
}

?>