<?php
$images = array_values(array_filter(scandir("."), function($file) {
    return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
}));
echo json_encode($images);
?>
