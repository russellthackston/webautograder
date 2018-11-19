<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('autoloader.php');

if(!Security::checkAccess(TRUE)) {
	header('Location: login.php');
	exit();
}

$db = new DB();

$session = $db->getSessionBySessionID($_COOKIE['wag_sessionid']);
$userid = $session->userid;
$user = $db->getUserByID($userid);
$userid = $user->id;

$assignments = $db->getAssignments();

$html = FALSE;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	if (isset($_GET['id'])) {
		$assignmentid = $_GET['id'];
		$assignment = $db->getAssignment($_GET['id']);

		$format = new Format;
		$formatted_html = $format->HTML($assignment->example);
		
		$assignment->setDocument($formatted_html);
	}
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	if (isset($_POST['createassignment'])) {
		
		$htmlFile = $_FILES['sample'];
		$html = file_get_contents($htmlFile['tmp_name']);
	
		$name = $_POST['name'];
		$description = $_POST['description'];
		$assignment = $db->addAssignment($name, $description, $html, $userid);
		$assignmentid = $assignment->id;

		header("Location: admin.php?id=$assignmentid");
		exit();
	
	}

	if (isset($_POST['addcheck'])) {

		$name = $_POST['name'];
		$description = $_POST['description'];
		$xpath = $_POST['xpath'];
		$checktype = $_POST['checktype'];
		var_dump($_POST);
		$assignmentid = $_POST['assignmentid'];

		$result = $db->addCheck($name, $description, $xpath, $checktype, $assignmentid);
		if ($result) {
			header("Location: admin.php?id=$assignmentid");
			exit();
		} else {
			echo "ERROR!!!!";
		}


	}


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
		<?php if (!isset($assignment)) { ?>
			<h2>Add Assignment</h2>
			<form action="admin.php" method="post" name="createassignmentform" enctype="multipart/form-data" >
				<input type="file" name="sample" id="sample">
				<br>
				<input type="text" name="name" id="name">
				<br>
				<input type="text" name="description" id="description">
				<br>
				<input type="submit" name="createassignment" id="createassignment" value="Create Assignment">
			</form>
		<?php } else { ?>
			<form action="admin.php" method="get" name="chooseassignmentform">
				<label for="assignment">Assignment:</label>
				<select name="id" id="assignment">
					<?php foreach ($assignments as $assignment) { ?>
						<option value="<?php echo htmlentities($assignment->id); ?>" <?php if (isset($assignmentid) && $assignmentid == $assignment->id) { echo " selected "; } ?>><?php echo htmlentities($assignment->name); ?></option>
					<?php } ?>
				</select>
				<input type="submit" name="chooseassignment" value="Load">
			</form>

			<h1>HTML</h1>
			<pre><?php echo(htmlentities($formatted_html)); ?></pre>
			<h1>Checks</h1>
			<table border="1">
			<?php foreach ($assignment->checks as $check) { ?>
				<tr>
					<td><?php echo($check->name); ?></td>
					<td><?php echo($check->description); ?></td>
					<td><?php echo($check->xpath); ?></td>
					<td><?php echo($check->type); ?></td>
					<td><?php if ($check->check()) { echo "Passed"; } else { echo "Failed"; } ?></td>
				</tr>
			<?php } ?>
			</table>
			<h2>Add Check</h2>
			<form action="admin.php" method="post" name="addcheckform">
				Name:
				<input type="text" name="name" id="name">
				<br>
				Description:
				<input type="text" name="description" id="description">
				<br>
				XPath:
				<input type="text" name="xpath" id="xpath">
				<br>
				Check type:
				<input type="text" name="checktype" id="checktype">
				<br>
				<input type="hidden" name="assignmentid" id="assignmentid" value="<?php echo $assignment->id; ?>">
				<input type="submit" name="addcheck" id="addcheck" value="Add Check">
			</form>
		<?php } ?>
	</body>
</html>


