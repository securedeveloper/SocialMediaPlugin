<?php

session_start();
// added in v4.0.0
require_once 'autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

// init app with app id and secret
FacebookSession::setDefaultApplication('1597089560542115', '85a50c9f9dab0684a6adb78fcd939734');
// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper('fbconfig.php');
try {
    $session = $helper->getSessionFromRedirect();
} catch (FacebookRequestException $ex) {
    // When Facebook returns an error
} catch (Exception $ex) {
    // When validation fails or other local issues
}
// see if we have a session
if (isset($session)) {
    // graph api request for user data
    $request = new FacebookRequest($session, 'GET', '/me');
    $response = $request->execute();
    // get response
    $graphObject = $response->getGraphObject();
    $fbid = $graphObject->getProperty('id');              // To Get Facebook ID
    $fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
    $femail = $graphObject->getProperty('email');    // To Get Facebook email ID
    /* ---- Session Variables ----- */
    $_SESSION['FBID'] = $fbid;
    $_SESSION['FULLNAME'] = $fbfullname;
    $_SESSION['EMAIL'] = $femail;
    //$_SESSION['fbSession'] = $session;
    $prequest = new FacebookRequest(
            $session, 'GET', "/".$fbid."/accounts"
    );
    $presponse = $prequest->execute();
    $pgraphObject = $presponse->getGraphObject();
    $pdata = $pgraphObject->getProperty('data');
    $_SESSION['pages'] = $pdata->asArray();
    /* ---- header location after session ---- */
    header("Location: index.php");
} else {
    $params = array('email,manage_pages,publish_pages');
    $loginUrl = $helper->getLoginUrl($params);
    header("Location: " . $loginUrl);
}
?>