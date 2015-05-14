<?php
require_once 'conf/config.php';
require_once 'sdk/twitter/autoload.php';
require_once 'class/SimpleJSONToXML.php';

use Abraham\TwitterOAuth\TwitterOAuth;

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $connection = new TwitterOAuth(TT_CONSUMER_KEY, TT_CONSUMER_SECRET, TT_ACCESS_TOKEN, TT_ACCESS_TOKEN_SECRET);
    $response = $connection->get("statuses/user_timeline", array("user_id" => $search));
    $rootElement = new SimpleXMLElement('<tt_tweets></tt_tweets>');
    $xml = new SimpleJSONToXML($response, $rootElement);
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
  <title>Stalker - pobieranie tweetów z Twittera</title>
  <!--[if lt IE 9]>
   <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
 </head>

 <body>
  <form method="post" action="tt_tweets.php">
   <input type="search" name="search" placeholder="ID użytkownika..." required>
   <input type="submit" name="submit" value="Pobierz tweety">
  </form>
 </body>
</html>
<?php 
}
?>
