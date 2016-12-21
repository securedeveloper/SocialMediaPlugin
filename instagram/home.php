<?php

session_start();
if ($_GET['id'] == 'logout') {
    unset($_SESSION['userdetails']);
    session_destroy();
}

require 'instagram.class.php';
require 'instagram.config.php';
if (!empty($_SESSION['userdetails'])) {
    $data = $_SESSION['userdetails'];

    echo '<br><img src=' . $data->user->profile_picture . ' >';
    echo '<br>Name:' . $data->user->full_name;
    echo '<br>Username:' . $data->user->username;
    echo '<br>User ID:' . $data->user->id;
    echo '<br>Bio:' . $data->user->bio;
    echo '<br>Website:' . $data->user->website;
    echo '<br>Profile Pic:' . $data->user->profile_picture;
    echo '<br>Access Token: ' . $data->access_token;
    echo '<br>';
// Store user access token
    $instagram->setAccessToken($data);
// Your uploaded images
    $popular = $instagram->getUserMedia($data->user->id);
    foreach ($popular->data as $data) {
        echo '<img src=' . $data->images->thumbnail->url . '>';
    }
    echo '<br>';
    // Instagram Data Array
    $likes = $data->likes;
    echo '<h1>LIKES </h1><br>';
    echo '<ul>';
    foreach ($likes->data as $key => $value) {
        echo '<li>';
        echo '<strong>UserName : ' . $value->username . '</strong><br>';
        echo '<strong>ID : ' . $value->id . '</strong><br>';
        echo '<strong>FullName : ' . $value->full_name . '</strong><br>';
        echo '<img src=' . $value->profile_picture . '>';
        echo '<br>';
        echo '</li>';
    }
    echo '</ul>';
} else {
    header('Location: index.php');
}
?>