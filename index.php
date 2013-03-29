<?php
require 'src/facebook.php';
require_once 'db.php';
/**
 * Init db
 */
$db = new tDB();
$db_host = "localhost";
$db_user = "machnhov_fb";
$db_pass = "R+NRuc?Kb&Q%";
$db_name = "machnhov_fb";

$db = new DB();  
$db->init($db_host, $db_user, $db_pass, $db_name);




$facebook = new Facebook(array(
            'appId' => '240745192719172',
            'secret' => '512637b8ca31c1764b6dcf4956a563f0',
        ));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
    try {
        // Proceed knowing you have a logged in user who's authenticated.
        $user_profile = $facebook->api('/me');
    } catch (FacebookApiException $e) {
        error_log($e);
        $user = null;
    }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
    $logoutUrl = $facebook->getLogoutUrl();
} else {
    $url = "https://graph.facebook.com/oauth/authorize?"
            . "client_id=240745192719172&"
            . "redirect_uri=http://apps.facebook.com/isticky/&"
            . "scope=user_photos,publish_stream";
    echo "<script language=javascript>window.open('$url', '_parent', '')</script>";


    //$loginUrl = $facebook->getLoginUrl(array('scope'=>'publish_stream,user_photos','redirect_uri' => 'http://apps.facebook.com/isticky/'));
}

if ($user) {
    $user_profile = $facebook->api('/me');
    $dataInsert = array(
        "fb_id" => $db->quote($user_profile["id"]),
        "full_name" => $db->quote($user_profile["name"])
    );

    $user_exit = $db->checkRecodeExist("full_name", "fb_user", " fb_id = " . $user_profile["id"], true, true, $dataInsert);

    //var_dump($user_exit);die;
    ?> 
    <?php
}
?> 