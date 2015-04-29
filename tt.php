<?php
require_once 'conf/config.php';
require_once 'sdk/twitter/autoload.php';
require_once 'class/SimpleJSONToXML.php';

use Abraham\TwitterOAuth\TwitterOAuth;

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $connection = new TwitterOAuth(TT_CONSUMER_KEY, TT_CONSUMER_SECRET, TT_ACCESS_TOKEN, TT_ACCESS_TOKEN_SECRET);
    $response = $connection->get("users/search", array("q" => $search));
    $rootElement = new SimpleXMLElement('<twitter></twitter>');
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
  <title>Stalker - wyszukiwarka użytkowników na twitterze</title>
  <!--[if lt IE 9]>
   <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
 </head>

 <body>
  <form method="post" action="tt.php">
   <input type="search" name="search" placeholder="Szukaj użytkownika..." required>
   <input type="submit" name="submit" value="Szukaj">
  </form>
 </body>
</html>
<?php 
}
?>
