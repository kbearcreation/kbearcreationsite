<html>
<head>
  <title>Test to see if PHP is working</title>
  <link rel="stylesheet" type="text/css" href="style/style.css" media="all" />
</head>

<body>

<?php
// prints e.g. 'Current PHP version: 4.1.1'
echo 'Current PHP version: ' . phpversion();

// prints e.g. '2.0' or nothing if the extension isn't enabled
echo phpversion('tidy');
?>

<br> <br>

<u>Database connection test:</u> 

<?php
	//$db = new mysqli('localhost', 'baringe1_wbsrch1', 'apr19th', 'baringe1_minuteman');
	@ $db = mysql_connect('localhost', 'websrch', 'apr19th', 'baringe1_minuteman');

	/* check connection */
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}


	/* check if server is alive */
	if ($db->ping()) {
	    printf ("Our connection is ok!\n");
	} else {
	    printf ("Error: %s\n", $db->error);
	}

	/* close connection */
	$db->close();

?>

</body>
</html>
