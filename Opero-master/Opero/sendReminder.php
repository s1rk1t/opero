<html>
	<head>
		<title>Forgot Info</title>
	</head>
	
	<body bgcolor="#073f40">
	<form>
		Please enter your email:
		<input type="text" name="email" action="" method="GET">
		<input type="submit" id="forgot" name="checkMail" value="Submit">
	</form>
		
		<?php 
			if($_GET){
				if(isset($_GET['email'])){
					check();
				}
			}
			
			function check(){
				$email = $_GET['email'];
				//establish conection with server
				$connection = mysql_connect("localhost", "a", $login_id);
				//security for mysql
				$email = stripslashes($email);
				$email = mysql_real_escape_string($email);
				//select database
				$db = mysql_select_db("members", $connection);
				$query = mysql_query("select * from memberData where email='$email'", $connection);
				$rows = mysql_num_rows($query);
				if($rows != 1){
					echo "invalid email";
				}
				else{
					echo "we are sending a confirmation email";
					$array = mysql_fetch_assoc($query);
					$address = $array["email"];
					$msg = "<html>
					<head>
					  <title>Test Mail</title>
					</head>
					<body>
						<p><a href='http://opero.us'>Change Password</a></p>
					</body>
					</html>
					";
					$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					mail($address, 'Reset Password', $msg, $headers);
				}
			}
			?>
	</body>
</html>
