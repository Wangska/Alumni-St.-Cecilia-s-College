<?php
// Create a simple default avatar
$width = 120;
$height = 120;

// Create image
$image = imagecreatetruecolor($width, $height);

// Define colors
$background = imagecolorallocate($image, 243, 244, 246); // Light gray
$avatar = imagecolorallocate($image, 156, 163, 175); // Gray

// Fill background
imagefill($image, 0, 0, $background);

// Draw head (circle)
imagefilledellipse($image, 60, 45, 40, 40, $avatar);

// Draw body (larger circle)
imagefilledellipse($image, 60, 90, 60, 60, $avatar);

// Output as PNG
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>
