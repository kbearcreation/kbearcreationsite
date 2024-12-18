<html>
<head>
  <title> Company Details</title>
  <link rel="stylesheet" type="text/css" href="style/style.css" media="all" />
</head>

<body>

<h1>Company Details </h1>
	<FORM>
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Return to Search Form" onClick="parent.location='../index.php'">
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Return to Previous Screen" onClick="history.go(-1);return true;"><br>
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Display All Companies" onClick="location.href='../allcompanies.php'">
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Display All Towns" onClick="location.href='../alltowns.php'">
	</FORM>

	<?php

	//create short variable names
		$compnum = $_GET['compnum'];
		//echo '<br>CompanyNum is: ', $compnum;

	// Build the Database Query

		$querybase = "select * from companies where compnum = ";

		$query1 = $querybase;

		if ($compnum){
			$querycomppnum = "'" .$compnum ."'";
			$query1 = $query1.$querycomppnum;
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

		// check if the Database Server is responding
		if ($db->ping()) {
		    //echo "<br>(Database Connection is OK!)<br>";
		} else {
		    printf ("<br><u><strong>Database Connection Error</strong></u>: %s\n", $db->error, "<br>");
		}

		// Run the Database Query
		$result = $db->query($query1);

		$num_results = $result->num_rows;

		if (!$num_results > 0){
			echo '<br><h3>The Search Returned No Results.</h3><br>';
			exit();
		} else echo '<br><font size=2>Returned ', $num_results, ' company.</font><br>';


		// Begin Table for Company results
		echo '<table border=1>';

		// Process Company results
		for ($i=0; $i <$num_results; $i++) {
			$row = $result->fetch_assoc();

			//CompanyName 
			echo '<tr><td><strong><u>Company</u>: </strong></td>';
			if ( $companyname = stripslashes($row['companyname'])) {
				echo '<td>', $companyname, '</td>';
			} else {echo '<td><i> ** Error Getting CompanyName ** <i></td>'; }
			echo '</tr>';

			//Town
			echo '<tr><td><strong><u>Town</u>: </strong></td>';
			if ( $town = stripslashes($row['town'])) {
				echo '<td> <a href=../town.php/?town=', $town, '>', $town, '</a></td>';
			} else {echo '<td><i> ** Error Getting Town **</i></td>'; }
			echo '</tr>';

			//Entered the Contest
			echo '<tr><td><strong><u>Entered The Contest</u>: </strong></td>';
			if ( $enterednote = stripslashes($row['enterednote'])) {
				echo '<td>',  $enterednote, '</td>';
			} else {echo '<td><i> ** Error Getting EnteredNote **</i></td>'; }
			echo '</tr>';

			//Notes (Textid1 & Textid2) 
			echo '<tr><td><strong><u>Notes</u>: </strong></td>';
			if ( $textid1 = stripslashes($row['textid1'])) {
				$textquery1 = "select text from text where textid = '" .$textid1 ."'";
				$textresult1 = $db->query($textquery1);
				$text1 = $textresult1->fetch_assoc();
				echo '<td>', ($text1['text']);
				if ( $textid2 = stripslashes($row['textid2'])) {
					$textquery2 = "select text from text where textid = '" .$textid2 ."'";
					$textresult2 = $db->query($textquery2);
					$text2 = $textresult2->fetch_assoc();
					echo '<br>', ($text2['text']);
				}
				echo '</td>';
			} else {echo '<td><i> ** Error Getting Notes **</i></td>'; }
			echo '</tr>';

		}
		// Close Table for Company
		echo '</table><br>';

		// Query the Soldiers table to get all soldiers in Company
		$soldierquery = "select * from soldiers where compnum = '" .$compnum ."'";
		$soldierresult = $db->query($soldierquery);
		$num_soldiers = $soldierresult->num_rows;

		if (!$num_soldiers > 0){
			echo '<br><h3>No Results were returned for Soldiers.</h3><br>';
			exit();
		} else echo '<br><font size=2>This Company has ', $num_soldiers, ' soldiers.</font><br>';


		// Begin Table for Soldiers In Company results
		echo '<table border=1>';
		echo '<tr><td><strong><u>Soldier</u></strong></td><td><strong><u>Rank</u></strong></td></tr>';

		// Process Soldiers In Company results
		for ($i=0; $i <$num_soldiers; $i++) {
			$soldiers = $soldierresult->fetch_assoc();

			echo '<tr>';

			//Get Prefix, FirstName, MiddleName, LastName, Suffix; format and display in table
			$personnum = stripslashes($soldiers['personnum']);
			$prefix = stripslashes($soldiers['prefix']);
			$firstname = stripslashes($soldiers['firstname']);
			$middlename = stripslashes($soldiers['middlename']);
			$lastname = stripslashes($soldiers['lastname']);
			$suffix = stripslashes($soldiers['suffix']);
			$fullname = $prefix ." " .$firstname ." " .$middlename ." " .$lastname ." " .$suffix;
			echo '<td><a href=../soldiers.php/?pnum=', $personnum, '>', $fullname, '</a></td>';

			// Get Rank and display in table
			$rank1 = stripslashes($soldiers['rank1']);
			if (!$rank1){
				$rank1 = "<i>No Rank Listed</i>";
			}
			if ($rank2 = stripslashes($soldiers['rank2'])){
				$rank1 = $rank1 .", " .$rank2;
			}
			echo '<td>', $rank1, '</td>';




			echo '</tr>';

		} //end for loop: Process Soldiers In Company results

		// Close Table for Soldiers In Company
		echo '</table><br>';



/*
			//Get PersonNum and display in table
			echo '<tr><td><strong><u>Index #</u>: </strong></td>';
			if ($personnum = stripslashes($row['personnum'])) { 
				echo '<td>', $personnum, '</td>';
			} else {echo '<td> Error Getting PersonNum', '</td>'; }
			echo '</tr>';

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


			//Get OtherTown if present, format and display in table
			$othertown = stripslashes($row['othertown']);
			if ($othertown){
				echo '<tr><td><strong><font size=1><sup>**</sup></font><u>Other Town</u>: </strong></td>'; 
				echo '<td> <a href=../town.php/?town=', $othertown, '>', $othertown, '</td></tr>';
			}

*/

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
