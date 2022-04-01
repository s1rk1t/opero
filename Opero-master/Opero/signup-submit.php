<?php
	


 

	if(isset($_POST['submit']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['password'])) {
		session_start();
		$user_name = 'a';
		$password = 'Csci4300';
		$database = 'users';
		$server = 'localhost';

		$db_handle = mysql_connect($server, $user_name, $password);
		$db_found = mysql_select_db($database, $db_handle);

		if ($db_found) {
			//new memberID is just one higher than the last memberID
			$memberIDQuery = "SELECT MAX(memberID) AS mID FROM login";
		
			$memberResource = mysql_query($memberIDQuery);	
			$result = mysql_fetch_object($memberResource);
			//$mD = 5;
			$mcount = $result->mID;	
			$mcount++;
			$SQL = "INSERT INTO login VALUES ('$_POST[first_name]', '$_POST[last_name]', '$_POST[email]', '$_POST[password]', '$mcount')";
			$query = mysql_query($SQL);
			/*	if($query){
			$_SESSION['login_first_name'] = $_POST['first_name'];
			$_SESSION['login_user'] = $_POST['email'];
			$_SESSION['memberID'] = $mD;}*/
			mysql_close($db_handle);		
		}//close if
		else {
			echo "<p>Error in new member addition process. See admin.</p>";
			mysql_close($db_handle);
		}
	}

?>

<!DOCTYPE html>

<html>

	<head>



		<!--Google fonts link-->



		<link href='http://fonts.googleapis.com/css?family=Raleway|Dosis|Poiret+One|Jura' rel='stylesheet' type='text/css'>



		<!--css stylesheet-->

		<link href="opero_style.css" rel="stylesheet" type="text/css">



		<title>Opero - iWork</title>



	</head>

	<body bgcolor="#073f40">
		<h1 id="heading">Opero</h1>

		<h2 id="tagline">Your source for employment opportunities</h2>

		

		<!--javascript file called "functions.js" is default for all js-->

		<script src="functions.js" type="text/javascript"></script>



		<p id="instructions">Your profile has been created successfully. Welcome <?php echo $_POST['first_name']; ?>!</p>

		<p id="instructions">Begin job search here</p>
		<a href='index.php' id="signuplink"><p id="searchButton">Search</p></a>

	</body>

</html>
