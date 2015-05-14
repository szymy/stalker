<?php
session_start();
require_once 'conf/config.php';
require_once 'sdk/facebook/autoload.php';
require_once 'class/SimpleJSONToXML.php';

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;

FacebookSession::setDefaultApplication(FB_APP_ID, FB_APP_SECRET);
$helper = new FacebookRedirectLoginHelper(FB_REDIRECT_URL);

try {
    $session = $helper->getSessionFromRedirect();
} catch (FacebookRequestException $ex) {
    die("Błąd: " . $ex->getMessage());
} catch (\Exception $ex) {
    die("Błąd: " . $ex->getMessage());
}

if ($session) {
    $userProfile = (new FacebookRequest($session, 'GET', '/me?fields=id,name,email'))->execute()->getGraphObject()->asArray();
    $userLikes = (new FacebookRequest($session, 'GET', '/me/likes?fields=id,username,name,category,is_verified'))->execute()->getGraphObject()->asArray();
	$rootElement = new SimpleXMLElement('<fb_user_likes></fb_user_likes>');
    $xml = new SimpleJSONToXML($userLikes, $rootElement);
    $xml->objToStr();
    $xml->strToArray();
    $xml->arrayToXML();
    echo header('Content-Type: text/xml');
    echo $xml->getXML();
} else {
    $loginUrl = $helper->getLoginUrl(array('scope'=>FB_PERMISSIONS));
    echo '<a href="'.$loginUrl.'">Zarejestruj się przez Facebooka</a>';
}
?>
