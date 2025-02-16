<?php
$default_dir = "."; // Default directory if no path is given
$requested_dir = isset($_GET['path']) ? realpath($_GET['path']) : $default_dir;

// Security: Prevent access outside allowed directories
if (!$requested_dir || !is_dir($requested_dir)) {
    echo json_encode(["error" => "Invalid directory"]);
    exit;
}

$base_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . "/";

$images = array_values(array_filter(scandir($requested_dir), function($file) use ($requested_dir) {
    return is_file("$requested_dir/$file") && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
}));

$images = array_map(function($file) use ($requested_dir, $base_url) {
    return $base_url . rawurlencode(basename($requested_dir)) . "/" . rawurlencode($file);
}, $images);

header('Content-Type: application/json');
echo json_encode($images);
?>
