<?php

session_start();
// User session data availability check 
if (!empty($_SESSION['userdetails'])) {
// Redirecting to home.php
    header('Location: home.php');
}
require 'instagram.class.php';
require 'instagram.config.php';
// Login URL
$loginUrl = $instagram->getLoginUrl(array(
    'basic',
    'likes'
        ));
echo "<a href='$loginUrl'>Sign in with Instagram </a>";
?>