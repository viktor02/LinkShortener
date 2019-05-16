<?php

function generateLink($length = 5)
{
	/* Generate service link
	* 36^5 = 60466176 combination
	*/
	$chars = 'abcdefghijklmnopqrstuvwxyz1234567890';
	$numChars = strlen($chars);
	$string = '';
	for ($i = 0; $i < $length; $i++) {
		$string .= substr($chars, rand(1, $numChars) - 1, 1);
	}
	return $string;
}

function deleteHttp($str)
{
	/* Search in the address  http or https and delete it */
	$posRealUrlHttp = mb_stripos($str, "http://");
	if ($posRealUrlHttp > 0) {
		$str = str_replace("http://", "", $str);
	}
	$posRealUrlHttps = mb_stripos($str, "https://");
	if ($posRealUrlHttps > 0) {
		$str = str_replace("https://", "", $str);
	}
	return str;
}

include("connection.php");

$realUrl = htmlspecialchars($_GET["link"]);

$serviceUrl = generateLink();

$server = $_SERVER['HTTP_HOST'];

// Connect to db
try {
	$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

	// Create database if not exists
	$sql = "CREATE TABLE if not exists `$dbname`.`pair` ( 
		`id` INT NOT NULL AUTO_INCREMENT , 
		`serviceUrl` TEXT NOT NULL , 
		`realUrl` TEXT NOT NULL , 
		PRIMARY KEY (`id`)) ENGINE = InnoDB;";
	$dbh->query($sql);

	// If the combination has already been generated
	if ($dbh->query("SELECT serviceUrl FROM pair where serviceUrl=$serviceUrl") == true) {
		$serviceUrl = generateLink(6);
	}
	/*
	// If this URL has already been added
	if($dbh->query("SELECT realUrl FROM pair where realUrl=$realUrl") == true){
		print("Урл существует");
		$serviceUrl = $row['serviceUrl'];
	}
	*/

	// Search in the address  http or https and delete it
	$realUrl = deleteHttp($realUrl);

	// If an error occurred during the insert
	if ($dbh->query("INSERT INTO `pair` (`serviceUrl`, `realUrl`) VALUES ('$serviceUrl', '$realUrl')") == false) {
		print_r($dbh->errorInfo());
	}

	$dbh = null;
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	exit();
}
include("static/phpqrcode.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Complete</title>
	<link rel="stylesheet" href="/static/bootstrap.min.css">
</head>

<body>
	<div class="container">
		<h1>Your shorter link:</h1>
		<p>
			<?php print("http://$server/?u=$serviceUrl");
			QRcode::png("http://$server/?u=$serviceUrl", "qrcodes/$serviceUrl.png");
			print("<img src='qrcodes/$serviceUrl.png' alt='QRCode' style='width:30%; height:auto;image-rendering: optimizeSpeed;' ");
			?>

		</p>
	</div>


</body>

</html>
<?php exit(); ?>