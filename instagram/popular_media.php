<?php

require 'instagram.class.php';

// Initialize class for public requests
$instagram = new Instagram('Client_ID');

// Get popular instagram media
$popular = $instagram->getPopularMedia();

// Display results
foreach ($popular->data as $data) {
    echo "<img src='{$data->images->thumbnail->url}'>";
}
?>