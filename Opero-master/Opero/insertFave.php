<?php
$jobID = $_POST['jID'];
$memberID = $_POST['mID'];

makeFavorite($memberID, $jobID);


function makeFavorite($memberID, $jobID){

$connect = mysql_connect('localhost', 'a', $login_id);

$i = 0;
        $db = mysql_select_db('jobs', $connect);
/*	
	$faveIDQuery = "SELECT MAX(faveID) FROM favorites";
	$fqResult = mysql_query($faveIDQuery);
	$max = mysql_fetch_object($fqResult);
	//increment faveID every time it goes in
	$faveID = $max->faveID;
	$faveID++;*/
        //nested insert and select command ensures no duplicate favorites 
        $faveJobInsert = "INSERT INTO favorites VALUES ('$memberID','$jobID')";

        $result = mysql_query($faveJobInsert);

        

        if(!$result) echo "<p>problem inserting job into favorites</p>";


        mysql_close($connect);


}

?>
