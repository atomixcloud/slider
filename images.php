<?php
$dir = "."; // Current directory
$base_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . "/";

$images = array_values(array_filter(scandir($dir), function($file) use ($dir) {
    return is_file("$dir/$file") && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
}));

$images = array_map(function($file) use ($base_url) {
    return $base_url . $file; // Full URL for the images
}, $images);

header('Content-Type: application/json');
echo json_encode($images);
?>
