<?php

session_start();

$consumerKey = 'YOUR_TWITTER_APP_CONSUMER_KEY';
$consumerSecret = 'YOUR_TWITTER_APP_CONSUMER_SECRET';
$oAuthToken = 'YOUR_TWITTER_APP_oAuthToken';
$oAuthSecret = 'YOUR_TWITTER_APP_oAuthSecret';

# API OAuth
require_once('twitteroauth.php');
$twitter = new TwitterOAuth($consumerKey, $consumerSecret,$oAuthToken,$oAuthSecret);
if (!isset($_SESSION['access_token'])) {
    if (!isset($_SESSION['oauth_token']) && !isset($_REQUEST['oauth_token'])) {
        $request_token = $twitter->getRequestToken('/SocialMediaPlugin/twitter/');
        $_SESSION['oauth_token'] = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
        $url = $twitter->getAuthorizeURL($request_token['oauth_token'], TRUE);
        echo "<a href='$url'>Click to login</a>";
        //$url = $twitter->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
    } else {
        $request_token = [];
        $request_token['oauth_token'] = $_SESSION['oauth_token'];
        $request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
        if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
            print_r($request_token);
            print_r($_REQUEST);
            echo 'Abort! Something is wrong.';
            exit;
        }
        //$access_token = $twitter->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
        $access_token = $twitter->getAccessToken($_REQUEST['oauth_verifier']);
        $_SESSION['access_token'] = $access_token;
        echo '<a href="#" onclick="return window.location.reaload();">Refresh</a>';
    }
}
if (isset($_SESSION['access_token'])) {

    $access_token = $_SESSION['access_token'];
    $res = $twitter->get('followers/ids', array('screen_name' => $access_token['screen_name']));

    $followers = json_decode($res);

    $idsArr = $followers->{'ids'};
    $ids = array();
    while (true) {
        $arr = array_slice($idsArr, 0, 15);
        $ids[] = implode(",", $arr);
        $idsArr = array_slice($idsArr, 15);
        if (!isset($idsArr) || empty($idsArr) || count($idsArr) <= 0)
            break;
    }
    $users = array();
    foreach ($ids as $key => $value) {
        $users[] = $twitter->get('users/lookup', array('user_id' => "$value"));
    }
    print_r($users);
}