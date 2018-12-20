<?php
require('../../../wp-config.php');

// Load the stamp and the photo to apply the watermark to



// image resize and add padding------------------------------------------------------------------

function thumbnail_box($img, $box_w, $box_h) {
    //create the image, of the required size
    $new = imagecreatetruecolor($box_w, $box_h);
    if($new === false) {
        //creation failed -- probably not enough memory
        return null;
    }


    //Fill the image with a light grey color
    //(this will be visible in the padding around the image,
    //if the aspect ratios of the image and the thumbnail do not match)
    //Replace this with any color you want, or comment it out for black.
    //I used grey for testing =)
    $fill = imagecolorallocate($new, 255, 255, 255);
    imagefill($new, 0, 0, $fill);

    //compute resize ratio
    $hratio = $box_h / imagesy($img);
    $wratio = $box_w / imagesx($img);
    $ratio = min($hratio, $wratio);

    //if the source is smaller than the thumbnail size, 
    //don't resize -- add a margin instead
    //(that is, dont magnify images)
    if($ratio > 1.0)
        $ratio = 1.0;

    //compute sizes
    $sy = floor(imagesy($img) * $ratio);
    $sx = floor(imagesx($img) * $ratio);

    //compute margins
    //Using these margins centers the image in the thumbnail.
    //If you always want the image to the top left, 
    //set both of these to 0
    $m_y = floor(($box_h - $sy) / 2);
    $m_x = floor(($box_w - $sx) / 2);

    //Copy the image data, and resample
    //
    //If you want a fast and ugly thumbnail,
    //replace imagecopyresampled with imagecopyresized
    if(!imagecopyresampled($new, $img,
        $m_x, $m_y, //dest x, y (margins)
        0, 0, //src x, y (0,0 means top left)
        $sx, $sy,//dest w, h (resample to this size (computed above)
        imagesx($img), imagesy($img)) //src w, h (the full size of the original)
    ) {
        //copy failed
        imagedestroy($new);
        return null;
    }
    //copy successful
    return $new;
}

$newimage = str_replace(' ','%20', $_GET['image']); 
$path_info = pathinfo($newimage);

if($path_info['extension'] == 'jpg' || $path_info['extension'] == 'JPG' || $path_info['extension'] == 'jpeg'){
    $im = imagecreatefromjpeg($newimage);
}elseif($path_info['extension'] == 'png' || $path_info['extension'] == 'PNG'){
    $im = imagecreatefrompng($newimage);
}elseif($path_info['extension'] == 'gif'){
    $im = imagecreatefromgif($newimage);
}else{
    //do nothing
}

$stamp = thumbnail_box($im, 177, 97);

imagedestroy($im);

if(is_null($stamp)) {
    /* image creation or copying failed */
    header('HTTP/1.1 500 Internal Server Error');
    exit();
}
header('Content-Type: image/png');
imagejpeg($stamp);
imagedestroy($stamp);
?>
