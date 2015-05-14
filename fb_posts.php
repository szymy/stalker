<?php
session_start();
require_once 'conf/config.php';
require_once 'sdk/facebook/autoload.php';
require_once 'class/SimpleJSONToXML.php';

use Facebook\FacebookSession;
use Facebook\FacebookRequest;

FacebookSession::setDefaultApplication(FB_APP_ID, FB_APP_SECRET);
$session = FacebookSession::newAppSession();

if (isset($_POST['search']) && $session) {
    $search = $_POST['search'];
    $userPosts = (new FacebookRequest($session, 'GET', '/'.$search.'/posts?fields=id,from,message,story,full_picture,link,name,place,type,created_time&limit=5'))->execute()->getGraphObject()->asArray();
	$rootElement = new SimpleXMLElement('<fb_user_posts></fb_user_posts>');
    $xml = new SimpleJSONToXML($userPosts, $rootElement);
    $xml->objToStr();
    $xml->strToArray();
    $xml->arrayToXML();
    echo header('Content-Type: text/xml');
    echo $xml->getXML();
} else {
?>
<!DOCTYPE html>
<html lang="pl" dir="ltr">
 <head>
  <meta charset="utf-8">
  <title>Stalker - pobieranie postów z Facebooka</title>
  <!--[if lt IE 9]>
   <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
 </head>

 <body>
  <form method="post" action="fb_posts.php">
   <input type="search" name="search" placeholder="ID użytkownika..." required>
   <input type="submit" name="submit" value="Pobierz posty">
  </form>
 </body>
</html>
<?php 
}
?>
