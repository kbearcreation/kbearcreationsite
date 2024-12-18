<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Search The Rosters of the Battle of April 19th, 1775</title>
  <link rel="stylesheet" type="text/css" href="style/style.css" media="all" />
</head>

<body>

	<h1> Search The Rosters of the Battle of April 19th, 1775</h1>
	<br>

	<FORM>
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Return to Previous Screen" onClick="history.go(-1);return true;">
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Display All Companies" onClick="location.href='allcompanies.php'">
		<INPUT TYPE="button" STYLE="height:25px; width:200px;" VALUE="Display All Towns" onClick="location.href='alltowns.php'">
	</FORM>

	<h2> Search Soldiers by:</h2>
	<form action="searchdb.php" method="post">
	<table border="0" bgcolor=lightblue> 
		<tr><td>Last Name: </td><td> <input name="lastname" type="text" size="40"/></td></tr>
		<tr><td>First Name: </td><td> <input name="firstname" type="text" size="40"/></td></tr>
		<tr><td>Company Name: </td><td> <input name="compname" type="text" size="40"/></td></tr>
		<tr><td>Town: </td><td> <input name="town" type="text" size="40"/></td></tr>
		<tr><td colspan="2"><center> 
			<input type="submit" name="submit" value="Search">
			<input type="reset" value="Reset">
		</center></td></tr>
	</table>
	</form>

	<br>
</body>
</html>
