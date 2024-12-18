<html>
<head>
  <title> Soldier Details</title>
  <link rel="stylesheet" type="text/css" href="style/style.css" media="all" />
</head>

<body>

<h1>Soldier Details </h1>
	<FORM>
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Return to Search Form" onClick="parent.location='../index.php'">
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Return to Previous Screen" onClick="history.go(-1);return true;"><br>
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Display All Companies" onClick="location.href='../allcompanies.php'">
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Display All Towns" onClick="location.href='../alltowns.php'">
	</FORM>

	<?php

	//create short variable names
		$pnum = $_GET['pnum'];
		//DEBUG echo '<br>Person Number Index is: ', $pnum;

	// Build the Query

		$querybase = "select * from soldiers where personnum = ";

		$query1 = $querybase;

		if ($pnum){
			$querypnum = "'" .$pnum ."'";
			$query1 = $query1.$querypnum;
		}
		else {
			echo "<br><strong>No search parameters have been provided.</strong>";
			exit;
		}

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
		$result = $db->query($query1);

		$num_results = $result->num_rows;

		if (!$num_results > 0){
			echo '<br><h3>The Search Returned No Results.</h3><br>';
			exit();
		} else echo '<br><font size=2>Returned ', $num_results, ' results.</font><br>';


		// Begin Table for results
		echo '<table border=1>';

		// Process results
		for ($i=0; $i <$num_results; $i++) {
			$row = $result->fetch_assoc();

			/*//Get PersonNum and display in table
			echo '<tr><td><strong><u>Index #</u>: </strong></td>';
			if ($personnum = stripslashes($row['personnum'])) { 
				echo '<td>', $personnum, '</td>';
			} else {echo '<td> Error Getting PersonNum', '</td>'; }
			echo '</tr>';
			*/

			//Get Prefix, FirstName, MiddleName, LastName, Suffix; format and display in table
			$prefix = stripslashes($row['prefix']);
			$firstname = stripslashes($row['firstname']);
			$middlename = stripslashes($row['middlename']);
			$lastname = stripslashes($row['lastname']);
			$suffix = stripslashes($row['suffix']);
			$fullname = $prefix ." " .$firstname ." " .$middlename ." " .$lastname ." " .$suffix;
			echo '<tr><td><strong><u>Name</u>: </strong></td>', '<td>', $fullname, '</td></tr>';

			//Get Alt Names if present, format and display in table
			$altfirstname = stripslashes($row['altfirstname']);
			$altlastname = stripslashes($row['altlastname']);
			$altnames = $prefix ." ";
			if ($altfirstname || $altlastname){
				if ($altfirstname) {$altnames = $altnames.$altfirstname." ";}
				else {$altnames = $altnames.$firstname." ";}
				if ($altlastname) {$altnames = $altnames.$altlastname." ";}
				else {$altnames = $altnames.$lastname." ";}
				$altnames = $altnames.$suffix." ";
				echo '<tr><td><strong><font size=1><sup>*</sup></font><u>Alternate Name</u>: </strong></td>', '<td>', $altnames, '</td></tr>';
			}
				
			//Get CompNum, Query DB for CompanyName and display in table
			echo '<tr><td><strong><u>Company</u>: </strong></td>';
			if ( $compnum = stripslashes($row['compnum'])) {
				$companyquery = "select companyname from companies where compnum = '" .$compnum ."'";
				$companyresult = $db->query($companyquery);
				$companyname = $companyresult->fetch_assoc();
				echo '<td> <a href=../company.php/?compnum=', $compnum, '>', ($companyname['companyname']), '</a></td>';
			} else {echo '<td> ** Error Getting CompNum **', '</td>'; }
			echo '</tr>';

			//Get Rank(s) if present, format and display in table
			echo '<tr><td><strong><u>Rank</u>: </strong></td>';
			$rank1 = stripslashes($row['rank1']);
			$rank2 = stripslashes($row['rank2']);
			if ($rank1 || $rank2) {
				$allranks = $rank1;
				if ($rank2) {$allranks = $allranks.", ".$rank2;}
				echo '<td>', $allranks, '</td>';
			}
			else {echo '<td><i>No Rank Listed</i></td>';}

			//Get CompNum, Query DB for Town and display in table
			echo '<tr><td><strong><u>Town</u>: </strong></td>';
			if ( $compnum = stripslashes($row['compnum'])) {
				$townquery = "select town from companies where compnum = '" .$compnum ."'";
				$townresult = $db->query($townquery);
				$town = $townresult->fetch_assoc();
				echo '<td> <a href=../town.php/?town=', ($town['town']), '>', ($town['town']), '</a></td>';
			} else {echo '<td> ** Error Getting Town **', '</td>'; }
			echo '</tr>';


			//Get OtherTown if present, format and display in table
			$othertown = stripslashes($row['othertown']);
			if ($othertown){
				echo '<tr><td><strong><font size=1><sup>**</sup></font><u>Other Town</u>: </strong></td>'; 
				echo '<td> <a href=../town.php/?town=', $othertown, '>', $othertown, '</td></tr>';
			}


			//Get Textid if present, Query DB for Text and display in table
			if ( $textid = stripslashes($row['textid'])) {
				$textquery = "select text from text where textid = '" .$textid ."'";
				$textresult = $db->query($textquery);
				$text = $textresult->fetch_assoc();
				echo '<tr><td><font size=1><sup>***</sup></font><strong><u>Other Info</u>: </strong></td><td>', ($text['text']), '</a></td></tr>';
			}


		}
		echo '</table><br>';


	// Footnotes
		if ($altfirstname || $altlastname){
			echo '<br><font size=2><sup>* </sup> Alternate Name: Indicates that an alternate name for either the first or last name of the person is listed in the database, possibly due to an abbreviation or unclear transcription from the original document.</font>';
		}
		if ($othertown){
			echo '<br><font size=2><sup>** </sup> Other Town: Indicates that an alternate town is listed next to this soldier\'s name in the original document, which may indicate that the soldier was a resident of a different town than the one associated with the company in which he was listed.</font>';
		}
		if ( $textid = stripslashes($row['textid'])) {
			echo '<br><font size=2><sup>*** </sup> Other Info: Includes additional information that was available either in the original document or as observations made while transferring data from the original document in the database.</font>';
		}


		if ($num_results > 0){$result->free();}


		$db->close();
	?>

</body>
</html>
