<!DOCTYPE HTML>

<html>
<head>

</head>

<body>




<?php
session_start(); // Starting Session
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
if (empty($_POST['email']) || empty($_POST['password'])) {
$error = "Email or Password is invalid";
}
else
{
// Define $username and $password
$email=$_POST['email'];
$password=$_POST['password'];
// Establishing Connection with Server by passing server_name, member_id and password as a parameter
$connection = mysql_connect("localhost", "a", $member_id);
// To protect MySQL injection for Security purpose
$email = stripslashes($email);
$password = stripslashes($password);
$email = mysql_real_escape_string($email);
$password = mysql_real_escape_string($password);
// Selecting Database
$db = mysql_select_db("users", $connection);
// SQL query to fetch information of registerd users and finds user match.
$query = mysql_query("select * from login where password='$password' AND email='$email'", $connection);
/*
$getFirstName= mysql_query("select first_name from login where password='$password' AND email='$email'", $connection);
$getLastName= mysql_query("select last_name from login where password='$password' AND email='$email'", $connection);
$getEmail= mysql_query("select email from login where password='$password' AND email='$email'", $connection);
$getPassword= mysql_query("select password from login where password='$password' AND email='$email'", $connection);
//$getMemberID = mysql_query("select memberID from login where password='$password' AND email='$email'", $connection);
//$getMemberIDRow = mysql_fetch_assoc($getMemberID);
$getFirstNameRow = mysql_fetch_assoc($getFirstName);
$getLastNameRow = mysql_fetch_assoc($getLastName);
$getEmailRow = mysql_fetch_assoc($getEmail);
$getPasswordRow = mysql_fetch_assoc($getPassword);
//$_SESSION['memberID'] = $getMemberIDRow['memberID'];
$_SESSION['login_first_name'] = $getFirstNameRow['first_name'];
$_SESSION['login_last_name'] = $getLastNameRow['last_name'];
$_SESSION['login_email'] = $getEmailRow['email'];
$_SESSION['login_password'] = $getPasswordRow['password'];
//$_SESSION['pageNum'] = 1;
*/
$member = mysql_fetch_assoc($query);
printf (%s %s %i, $member["email"], $member["password"], $member["memberID"];


$rows = mysql_num_rows($query);
if ($rows == 1) {
$_SESSION['login_user']=$email; // Initializing Session
header("location: www.opero.us/index.php"); // Redirecting To Other Page
} else {
$error = "Email or Password is invalid";
}
mysql_close($connection); // Closing Connection
}
}
?>
</body>

</html>
