<?php
// Establishing Connection with Server by passing server_name, login_id and password as a parameter
$connection = mysql_connect("localhost", "a", $login_id);
// Selecting Database
$db = mysql_select_db("users", $connection);
session_start();// Starting Session
// Storing Session
$user_check=$_SESSION['login_user'];
// SQL Query To Fetch Complete Information Of User
$ses_sql=mysql_query("select email from login where email='$user_check'", $connection);

//get member ID


$row = mysql_fetch_assoc($ses_sql);
$login_session =$row['email'];
$login_first_name = $row['first_name'];
$memberID = $row['memberID'];
//TODO: test this and see if we can make member ID's $ID = $row['memberID'];
if(!isset($login_session)){
mysql_close($connection); // Closing Connection
header('Location: index.php'); // Redirecting To Home Page
}
?>
