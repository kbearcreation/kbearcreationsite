<html>
<head>
  <title>Search The Rosters of the Battle of April 19th, 1775</title>
  <link rel="stylesheet" type="text/css" href="style/style.css" media="all" />
</head>

<body>

<h1>Search Results</h1>
	<FORM>
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Return to Search Form" onClick="parent.location='index.php'">
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Return to Previous Screen" onClick="history.go(-1);return true;"><br>
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Display All Companies" onClick="location.href='allcompanies.php'">
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Display All Towns" onClick="location.href='alltowns.php'">
	</FORM>

	<?php
	//create short variable names
		$lastname=trim($_POST['lastname']);
		$firstname=trim($_POST['firstname']);
		$town=trim($_POST['town']);
		$compname=trim($_POST['compname']);

		if (!$lastname && !$firstname && !$town && !$compname) {
			echo "<br><strong>No search terms have been provided.  Please enter a value in one or more fields.</strong>";
			exit;
		}

		if (!get_magic_quotes_gpc()){
			$lastname = addslashes($lastname);
			$firstname = addslashes($firstname);
			$town = addslashes($town);
			$compname = addslashes($compname);
		}

		// Display the Search values & Build the Query

		$querybase = "select a.firstname, a.middlename, a.lastname, a.personnum, a.othertown, b.companyname, b.town from soldiers a, companies b
			where a.compnum = b.compnum";

		$query1 = $querybase;

		echo '<br><table border="0" bgcolor=lightblue>';
		echo '<tr><td colspan="2"><u>Searching for the following values</u>:</td></tr>';
		if ($lastname){
			echo '<tr><td>Last Name:</td><td>', $lastname, '</td></tr>';
			$querylastname = " and (a.lastname like '%" .$lastname. "%' or a.altlastname like '%" .$lastname. "%')";
			$query1 = $query1.$querylastname;
		}
		if ($firstname){
			echo '<tr><td>First Name:</td><td>', $firstname, '</td></tr>';
			$queryfirstname = " and (a.firstname like '%" .$firstname. "%' or a.altfirstname like '%" .$firstname. "%')";
			$query1 = $query1.$queryfirstname;
		}
		if ($compname){
			echo '<tr><td>Company Name:</td><td>', $compname, '</td></tr>';
			$querycompname = " and (b.companyname like '%" .$compname. "%')";
			$query1 = $query1.$querycompname;
		}
		if ($town){
			echo '<tr><td>Town:</td><td>', $town, '</td></tr>';
			$querytown = " and ((b.town like '%" .$town. "%')";
			$queryothertown = " or (a.othertown like  '%" .$town. "%'))";
			$query1 = $query1.$querytown.$queryothertown;
		}
		echo '</table>';

		//DEBUG echo '<br><br>Run this query: ', $query1;

		// Connect to Database

		$db = new mysqli('localhost', 'baringe1_wbsrch1', 'apr19th', 'baringe1_minuteman');
		//$db = new mysqli('localhost', 'websrch', 'apr19th', 'minuteman');

		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		/* check if server is alive */
		if ($db->ping()) {
			//DEBUG echo "<br>(Database Connection is OK!)<br>";
		} else {
			printf ("<br><u><strong>Database Connection Error</strong></u>: %s\n", $db->error, "<br>");
		}

		// Query the Database

		/*Run the Query */
		$result = $db->query($query1);

		$num_results = $result->num_rows;

		if (!$num_results > 0){
			echo '<br><h3>The Search Returned No Results.</h3><br>';
			exit();
		} else echo '<br><font size=2>Returned ', $num_results, ' results.</font><br>';


		// Begin Table for results
		echo '<table border=1>';
		echo '<tr><th>First Name</th><th>MI</th><th>Last Name</th><th>Company Name</th><th>Town<sup><font size=1>*</font></sup></th></tr>';

		// Process results
		for ($i=0; $i <$num_results; $i++) {
			$row = $result->fetch_assoc();
			echo '<tr>';
			//echo '<td>', stripslashes($row['firstname']), '</td>';
			echo '<td> <a href=soldiers.php/?pnum=', stripslashes($row['personnum']), '>', stripslashes($row['firstname']), '</a></td>';
			echo '<td> <a href=soldiers.php/?pnum=', stripslashes($row['personnum']), '>', stripslashes($row['middlename']), '</a></td>';
			echo '<td> <a href=soldiers.php/?pnum=', stripslashes($row['personnum']), '>', stripslashes($row['lastname']), '</a></td>';
			echo '<td>', stripslashes($row['companyname']), '</td>';
			echo '<td>', stripslashes($row['town']), '</td>';
			echo '</tr>';
		}
		echo '</table>';

		// FOOTNOTES
		echo '<br> * <font size=2><u>Other Town</u>: In some cases an alternate town is listed next to a soldier\'s name in the original document, which may indicate that the soldier was a resident of a different town than the one associated with the company in which he was listed. Searches for this "other" town will display soldiers that have the town listed next to their name in the database, but the results table will only show the town of the company in which they were enlisted. Clicking on the soldier\'s name will display the Soldier Details page, which shows "Other Town" values.</font>';

		if ($num_results > 0){$result->free();}


		$db->close();
 
	?>

</body>
</html>
