<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('autoloader.php');

if(!Security::checkAccess(FALSE)) {
	header('Location: login.php');
	exit();
}

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
	$assignment->setDocument($formatted_html);
	$allpassed = TRUE;
	$countPassed = 0;
	foreach ($assignment->checks as $check) {
		if (!$check->check()) {
			$allpassed = FALSE;
		} else {
			$countPassed++;
		}
	}
	$assignmentgrade = 100 * $countPassed / sizeof($assignment->checks);
	$grades = $db->saveResults($session->userid, $assignment->id, $assignmentgrade);

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
		<?php require('nav.php'); ?>
		<?php if ($html) { ?>
			<h1>HTML</h1>
			<pre><?php echo(htmlentities($formatted_html)); ?></pre>
			<?php if (sizeof($assignment->checks[0]->errors) > 0) { ?>
				<h1>Parsing errors and warnings</h1>
				<?php foreach ($assignment->checks[0]->errors as $error) { ?>
					<div>
						<?php 
							$severity = "";
							switch ($error->level) { 
								case LIBXML_ERR_WARNING:
									$severity = "Warning";
									break;
								case LIBXML_ERR_ERROR:
									$severity = "Error";
									break;
								case LIBXML_ERR_FATAL:
									$severity = "Fatal";
									break;
							}
						?>
						<?php echo($severity); ?> : <?php echo($error->message); ?> on Line  <?php echo($error->line); ?>.
					</div>
				<?php } ?>
			<?php } ?>
			<h1>Checks for <?php echo htmlentities($assignment->name); ?></h1>
			<?php foreach ($assignment->checks as $check) { ?>
			<div>
				<h2><?php echo htmlentities($check->name); ?></h2>
				<?php if ($check->check()) { echo "Passed"; } else { echo "Failed - " . htmlentities($check->description); } ?>
			</div>
			<?php } ?>
			<hr>
			<?php if ($allpassed) { ?>
				<div>All tests passed.</div>
			<?php } ?>
			<h2>Submissions</h2>
			<table>
			<?php foreach ($grades as $grade) { ?>
				<tr>
					<td><?php echo($grade->submitted); ?></td>
					<td><?php echo($grade->assignment->name); ?></td>
					<td><?php echo($grade->grade); ?>%</td>
				</tr>
			<?php } ?>
			</table>
			<hr>
		<?php } ?>
		<h1>Check assignment</h1>
		<form action="index.php" method="post" name="grade" enctype="multipart/form-data" >
			<label for="submission">Your file:</label>
			<input type="file" name="submission" id="submission" required>
			<br>
			<label for="assignment">Assignment:</label>
			<select name="assignment" id="assignment">
				<?php foreach ($assignments as $assignment) { ?>
					<option value="<?php echo htmlentities($assignment->id); ?>" <?php if (isset($assignmentid) && $assignmentid == $assignment->id) { echo " selected "; } ?>><?php echo htmlentities($assignment->name); ?></option>
				<?php } ?>
			</select>
			<br>
			<input type="submit" name="gradeit" id="gradeit" value="Submit Assignment">
			<input type="hidden" name="thisaction" value="submitassignment">
		</form>
	</body>
</html>


