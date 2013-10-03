<?php 
    session_start();
    
    // config
    $length         = 5;    // amount of characters
    $size           = 24;   // font size
    $img_width      = 180;  // output image width in pixels
    $img_height     = 50;   // output image height in pixels 

    // list of fonts 
    $fonts[] = 'font/Roboto-Black.ttf'; 

    // characters are randomly chosen out of this alphabet
    $abc = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 
                 'H', 'Q', 'J', 'K', 'L', 'M', 'N', 
                 'P', 'R', 'S', 'T', 'U', 'V', 'Y', 
                 'W', '2', '3', '4', '5', '6', '7'); 

 
    header('Content-Type: image/jpeg', true); 
    $img = imagecreatetruecolor($img_width, $img_height); 

    $black = imagecolorallocate($img, 0, 0, 0);

    // random background-color
    $col = imagecolorallocate($img, rand(230, 255), rand(230, 255), rand(230, 255));
    imagefill($img, 0, 0, $col); 

    // optional background noise: points
    for( $i=0; $i<($img_width*$img_height)/8; $i++ ) { 
        imagefilledellipse($img, mt_rand(0,$img_width), mt_rand(0,$img_height), 1, 1, $black);
    } 

    // optional background noise: lines
    /*for( $i=0; $i<($img_width*$img_height)/150; $i++ ) { 
        imageline($img, mt_rand(0,$img_width), mt_rand(0,$img_height), mt_rand(0,$img_width), mt_rand(0,$img_height), $black);
    }*/ 

    $captcha = '';

    // x-coordinate of the first character in pixels
    $x = 10;

    // choose '$length' random characters
    for($i = 0; $i < $length; $i++) { 

        $chr = $abc[rand(0, count($abc) - 1)];
        $captcha .= $chr;

        // random font color
        $col = imagecolorallocate($img, rand(0, 199), rand(0, 199), rand(0, 199));

        // random font
        $font = $fonts[rand(0, count($fonts) - 1)];

        // vertical character positioning
        $y = ($img_height-20) + rand(0, 20);

        // random angle for this character
        $angle = rand(0, 30);

        // print character on new image
        imagettftext($img, $size, $angle, $x, $y, $col, $font, $chr); 

        // calculate consumed space
        $dim = imagettfbbox($size, $angle, $font, $chr);

        // calculate appropriate horizontal buffer
        $x += $dim[4] + abs($dim[6]) + 10;
    } 

    // save in session
    $_SESSION["captcha"] = $captcha;

    // output
    imagejpeg($img); 
    imagedestroy($img); 
?>