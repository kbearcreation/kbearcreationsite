<html>
<head>
  <title> Town Details</title>
  <link rel="stylesheet" type="text/css" href="style/style.css" media="all" />
</head>

<body>

<h1> Town Details </h1>
	<FORM>
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Return to Search Form" onClick="parent.location='../index.php'">
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Return to Previous Screen" onClick="history.go(-1);return true;"><br>
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Display All Companies" onClick="location.href='../allcompanies.php'">
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Display All Towns" onClick="location.href='../alltowns.php'">
	</FORM>

	<?php
	//create short variable names
		$town = $_GET['town'];
		echo '<h3><u>List of Companies for the town of ', $town, '</u></h3>';

	// Build the Town Query

		$querybase = "select * from companies where town = ";

		$query1 = $querybase;

		if ($town){
			$querytown = "'" .$town ."'";
			$query1 = $query1.$querytown;
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

	// Run the Town Query
		$result = $db->query($query1);

		$num_results = $result->num_rows;

		if ($num_results > 0){
			echo '<br><font size=2>Returned ', $num_results, ' results.</font><br>';

			// Begin Table for results
			echo '<table border=1>';
			echo '<tr><td><strong><u>Town</u></strong></td>';
			echo '<td><strong><u>Company</u></strong></td></tr>';

			// Process Town results
			for ($i=0; $i <$num_results; $i++) {
				$row = $result->fetch_assoc();

				echo '<tr>'; // Begin Row

				//Town
				if ( $town = stripslashes($row['town'])) {
					echo '<td> <a href=../town.php/?town=', $town, '>', $town, '</a></td>';
				} else {echo '<td><i> ** Error Getting Town **</i></td>'; }

				//CompanyName 
				if ( $companyname = stripslashes($row['companyname'])) {
					$compnum =  stripslashes($row['compnum']);
					echo '<td> <a href=../company.php/?compnum=', $compnum, '>', $companyname, '</a></td>';
					//echo '<td>', $companyname, '</td>';
				} else {echo '<td><i> ** Error Getting CompanyName ** <i></td>'; }

				echo '</tr>'; // End Row
	
			} // End For Loop: Process Town Results
			echo '</table><br>';

		} else echo '<h4><i>No Companies From ', $town, ' Are Listed.</i></h4>';



	// Build the OtherTown Query

		$querybase2 = "select * from soldiers where othertown = ";
		$query2 = $querybase2;
		$query2 = $query2.$querytown;

		//DEBUG echo $query2;
	
	// Run the OtherTown Query
		$result2 = $db->query($query2);

		$num_results2 = $result2->num_rows;

		if ($num_results2 > 0){
			echo '<h3><u>Soldiers enlisted in companies from other towns, but showing "', $town, '" on their roster:</u></h3>';
			echo '<font size=2>Returned ', $num_results2, ' results.</font>';


			// Begin Table for OtherTown results
			echo '<table border=1>';
			echo '<tr>'; // Begin Header Row
			echo '<td><strong><u>Other Town</u></strong></td>';
			echo '<td><strong><u>Soldier</u></strong></td>';
			echo '<td><strong><u>Company</u></strong></td>';
			echo '</tr>'; // End Header Row

			// Process OtherTown results
			for ($i=0; $i <$num_results2; $i++) {
				$row2 = $result2->fetch_assoc();
	
				echo '<tr>'; // Begin Row
	
				//Get OtherTown, format and display in table
				if( $othertown = stripslashes($row2['othertown'])){
					echo '<td>', $othertown, '</td>';
				}
	
				//Get Soldier Full Name, format and display in table
				$personnum = stripslashes($row2['personnum']);
				$prefix = stripslashes($row2['prefix']);
				$firstname = stripslashes($row2['firstname']);
				$middlename = stripslashes($row2['middlename']);
				$lastname = stripslashes($row2['lastname']);
				$suffix = stripslashes($row2['suffix']);
				$fullname = $prefix ." " .$firstname ." " .$middlename ." " .$lastname ." " .$suffix;
				echo '<td><a href=../soldiers.php/?pnum=', $personnum, '>', $fullname, '</a></td>';

				//CompanyName 
				if ( $compnum2 = stripslashes($row2['compnum'])) {
					$companyname2query = "select companyname from companies where compnum = '" .$compnum2 ."'";
					$company2result = $db->query($companyname2query);
					$companyname2 = $company2result->fetch_assoc();
					echo '<td> <a href=../company.php/?compnum=', $compnum2, '>', ($companyname2['companyname']), '</a></td>';
				} else {echo '<td><i> ** Error Getting CompanyName ** <i></td>'; }


				echo '</tr>'; // End Row

			} // End For Loop: Process OtherTown Results

			echo '</table><br>';

		} else {
			//DEBUG echo '<font size=2>Returned ', $num_results2, ' results.</font>';
			exit();
		}

	// FOOTNOTES
		$footnotesarray = array();

		if ($altfirstname || $altlastname){
			array_push($footnotesarray, '<font size=2><u>Alternate Name</u>: Indicates that an alternate name for either the first or last name of the person is listed in the database, possibly due to an abbreviation or unclear transcription from the original document.</font>');
		}
		if ($othertown){
			array_push($footnotesarray, '<font size=2><u>Other Town</u>: Indicates that an alternate town is listed next to this soldier\'s name in the original document, which may indicate that the soldier was a resident of a different town than the one associated with the company in which he was listed.</font>');
		}
		if ( $textid = stripslashes($row['textid'])) {
			array_push($footnotesarray, '<font size=2><u>Other Info</u>: Includes additional information that was available either in the original document or as observations made while transferring data from the original document in the database.</font>');
		}
		if ($town == 'do'){
			array_push($footnotesarray, '<font size=2><u>do</u>: The word "do" was displayed next to the name for a number of soldiers in companies from various town.  Since the names of other towns were listed in this same location for some soldiers, it is thought that "do" is an abbreviation for "Dorchester".  However, there is no additional evidence to support or refute this.</font>');
		}
		
		$num_footnotes = count($footnotesarray);
		//DEBUG echo 'Number of footnotes is: ', $num_footnotes, '<br>';

		if (0 < $num_footnotes){
			for ($f=0; $f <$num_footnotes; $f++) {
				echo '<br>';
				for ($s=0; $s <= $f; $s++){ echo '*';} 
				echo ' ', $footnotesarray[$f];
			} //end for loop
		} //end if

	// END FOOTNOTES
 
		if ($num_results > 0){$result->free();}


		$db->close();
	?>

</body>
</html>
