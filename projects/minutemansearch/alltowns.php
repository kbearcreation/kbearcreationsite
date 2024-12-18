<html>
<head>
  <title> Town List</title>
  <link rel="stylesheet" type="text/css" href="style/style.css" media="all" />
</head>

<body>

<h1> Town List </h1>
	<FORM>
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Return to Search Form" onClick="parent.location='index.php'">
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Return to Previous Screen" onClick="history.go(-1);return true;"><br>
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Display All Companies" onClick="location.href='allcompanies.php'">
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Display All Towns" onClick="location.href='alltowns.php'">
	</FORM>

	<?php
	// Build the Query
		$query1 = "select distinct town from companies";

		//DEBUG echo '<br><br>Run this query: ', $query1;

		// Connect to Database

		$db = new mysqli('localhost', 'baringe1_wbsrch1', 'apr19th', 'baringe1_minuteman');
		//$db = new mysqli('localhost', 'websrch', 'apr19th', 'minuteman');

		// check connection
		if (mysqli_connect_errno()) {
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
		}

		// check if server is alive 
		if ($db->ping()) {
		    //DEBUG echo "<br>(Database Connection is OK!)<br>";
		} else {
		    printf ("<br><u><strong>Database Connection Error</strong></u>: %s\n", $db->error, "<br>");
		}

		// Query the Database

		// Run the Query
		$alltownresult = $db->query($query1);

		$num_results = $alltownresult->num_rows;

		if (!$num_results > 0){
			echo '<br><h3>The Search Returned No Results.</h3><br>';
			exit();
		} else echo '<br><font size=2>Returned ', $num_results, ' results.</font><br>';


		// Build Array from DB Query Results 

		$alltownsarray = array();

		for ($i=0; $i <$num_results; $i++){
			$row = $alltownresult->fetch_assoc();
			array_push($alltownsarray, $row['town']);		
		}

		//DEBUG echo 'Initial Count of alltownsarray is: ', count($alltownsarray), '<br>';
	

		// Add 'Othertown' results to $alltownsarray
		
		$query2 = "select distinct othertown from soldiers";

		$othertownresult = $db->query($query2);

		$num_results2 = $othertownresult->num_rows;

		//DEBUG echo 'Returned ', $num_results2, ' Othertown Results.<br>';

		for ($j=0; $j <$num_results2; $j++){
			$row2 = $othertownresult->fetch_assoc();
			array_push($alltownsarray, $row2['othertown']);		
		}

		//DEBUG echo 'New Count of alltownsarray is: ', count($alltownsarray),'<br>';
	

		// Begin Table for results
		echo '<table border=1>';
		echo '<tr><td><strong><u>Town</u>: </strong></td>';

		// Process results
		for ($k=0; $k < count($alltownsarray); $k++) {

			// Display all towns listed in $alltownsarray
			echo '<tr>';
			echo '<td> <a href=town.php/?town=', $alltownsarray[$k], '>', $alltownsarray[$k], '</a></td>';
			echo '</tr>';

		}

		echo '</table><br>';


	// FOOTNOTES
		if ($othertownresult){
			echo '<br><font size=2>* <u>do</u>: The word "do" was displayed next to the name for a number of soldiers in companies from various town.  Since the names of other towns were listed in this same location for some soldiers, it is thought that "do" is an abbreviation for "Dorchester".  However, there is no evidence to support or refute this.</font>';
		}
	// END FOOTNOTES

		if ($num_results > 0){$alltownresult->free();}


		$db->close();
	?>

</body>
</html>
