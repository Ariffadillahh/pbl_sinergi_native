<?php
session_start();


$captcha = $_SESSION['captcha'] ?? 'ERROR';
$width = 130;
$height = 40;

$image = imagecreate($width, $height);

$bg = imagecolorallocate($image, 240, 240, 240);
$textColor = imagecolorallocate($image, 20, 40, 100);

for ($i = 0; $i < 50; $i++) {
    $noiseColor = imagecolorallocate($image, rand(150, 255), rand(150, 255), rand(150, 255));
    imagesetpixel($image, rand(0, $width), rand(0, $height), $noiseColor);
}

$fontSize = 5;
$x = (imagesx($image) - imagefontwidth($fontSize) * strlen($captcha)) / 2;
$y = (imagesy($image) - imagefontheight($fontSize)) / 2;
imagestring($image, $fontSize, $x, $y, $captcha, $textColor);

header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
