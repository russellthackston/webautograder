<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('autoloader.php');

$errors = array();

$db = new DB();
$assignments = $db->getAssignments();

$html = FALSE;

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$assignmentid = $_POST['assignment'];
	$assignment = $db->getAssignment($assignmentid);
	$htmlFile = $_FILES['submission'];
	$html = file_get_contents($htmlFile['tmp_name']);

	$format = new Format();
	$formatted_html = $format->HTML($html);

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
		<?php if (!$html) { ?>
			<form action="index.php" method="post" name="grade" enctype="multipart/form-data" >
				<input type="file" name="submission" id="submission" required>
				<br>
				<select name="assignment">
					<?php foreach ($assignments as $assignment) { ?>
						<option value="<?php echo htmlentities($assignment->id); ?>"><?php echo htmlentities($assignment->name); ?></option>
					<?php } ?>
				</select>
				<br>
				<input type="submit" name="gradeit" id="gradeit">
			</form>
		<?php } else { ?>
			<h1>HTML</h1>
			<pre><?php echo(htmlentities($formatted_html)); ?></pre>
			<h1>Checks for <?php echo htmlentities($assignment->name); ?></h1>
			<?php foreach ($assignment->checks as $check) { 
				$check->setDocument($formatted_html);
			?>
			<div>
				<h2><?php echo $check->name; ?></h2>
				<?php if ($check->check()) { echo "Passed"; } else { echo "Failed - " . $check->description; } ?>
			</div>
			<?php } ?>
		<?php } ?>
	</body>
</html>


