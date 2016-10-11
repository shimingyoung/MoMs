<?

/* MySQL Information */
$dbHost = '';// add host:port
$dbName = '';// add name
$dbUser = '';// add user
$dbPass = '';// add password
date_default_timezone_set('America/New_York');

/* Guess any script which include dbConfig.inc.php would like to connect to the database */
$dbLinkBM3 = @mysql_pconnect($dbHost, $dbUser, $dbPass) or die("Can't connect to Database Server. Please try restarting the Server.");
$dbSelectedBM3 = mysql_select_db($dbName, $dbLinkBM3);

?>
