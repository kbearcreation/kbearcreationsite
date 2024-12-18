<html>
<head>
  <title> Companies List</title>
  <link rel="stylesheet" type="text/css" href="style/style.css" media="all" />
</head>

<body>

<h1> Company List </h1>
	<FORM>
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Return to Search Form" onClick="parent.location='index.php'">
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Return to Previous Screen" onClick="history.go(-1);return true;"><br>
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Display All Companies" onClick="location.href='allcompanies.php'">
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Display All Towns" onClick="location.href='alltowns.php'">
	</FORM>

	<?php
	// Build the Query
		$query1 = "select * from companies";

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
		$allcompresult = $db->query($query1);

		$num_results = $allcompresult->num_rows;

		if (!$num_results > 0){
			echo '<br><h3>The Search Returned No Results.</h3><br>';
			exit();
		} else echo '<br><font size=2>Returned ', $num_results, ' results.</font><br>';


		// Begin Table for results
		echo '<table border=1>';
		echo '<tr><th>Company Name</th><th>Town</th><th>Entered the Contest</th><th>Entered the Contest Notes</th><th>Other Notes</th></tr>';

		// Process results
		for ($i=0; $i <$num_results; $i++) {
			$row = $allcompresult->fetch_assoc();
			echo '<tr>';
			echo '<td> <a href=company.php/?compnum=', stripslashes($row['compnum']), '>', stripslashes($row['companyname']), '</a></td>';
			echo '<td> <a href=town.php/?town=', stripslashes($row['town']), '>', stripslashes($row['town']), '</a></td>';
			echo '<td> ', stripslashes($row['entered']), '</td>';
			echo '<td> ', stripslashes($row['enterednote']), '</td>';
			if ($textid1 = stripslashes($row['textid1'])) {
				$textquery1 = "select text from text where textid = '" .$textid1 ."'";
				$textresult1 = $db->query($textquery1);
				$text1 = $textresult1->fetch_assoc();
				$text = $text1['text'];
			}
			if ($textid2 = stripslashes($row['textid2'])) {
				$textquery2 = "select text from text where textid = '" .$textid2 ."'";
				$textresult2 = $db->query($textquery2);
				$text2 = $textresult2->fetch_assoc();	
				$text = $text."<br>".$text2['text'];
			}
			//$text = $text1['text']."<br>".$text2['text'];
			echo '<td> ', $text, '</td>';
			echo '</tr>';

		} //End For Loop Process Results


		echo '</table>';

		if ($num_results > 0){$allcompresult->free();}


		$db->close();
	?>

</body>
</html>
