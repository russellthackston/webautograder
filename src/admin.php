<?php

require_once('format.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$html = FALSE;

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$htmlFile = $_FILES['submission'];
	$html = file_get_contents($htmlFile['tmp_name']);

	$format = new Format;
	$formatted_html = $format->HTML($html);

	$doc = new DOMDocument();
	$doc->loadHTML($formatted_html);
	$xpath = new DOMXPath($doc);
	$title = $xpath->query('//html/head/title');
	$desc = $xpath->query('//html/head/meta[@name="description"]');

}

?>
<!doctype html>
<html lang="en">
	<head>
	  <meta charset="utf-8">
	
	  <title>Webpage Autograder</title>
	  <meta name="description" content="An experimental web page autograder based on XPath queries">
	  <meta name="author" content="Russell Thackston">
	
	</head>
	
	<body>
		<form action="index.php" method="post" name="grade" enctype="multipart/form-data" >
			<input type="file" name="submission" id="submission">
			<br>
			<input type="submit" name="gradeit" id="gradeit">
		</form>
	</body>
</html>


