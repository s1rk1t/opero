<!DOCTYPE HTML>

<html>


<head>
<?php include"session.php"?>
<script>
	function makeFavorite(memberID, jobID){

	if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
       
        xmlhttp.open("POST","insertFave.php",true);
     
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send("mID="+memberID+"&jID="+jobID);	

	var ele = document.activeElement;
	ele.value = "Job Saved";
	ele.style.borderColor = "#2e343c";
	ele.style.color = "01ffac";
	ele.style.outline = "none";	

	//document.getElementById("faveButton").innerHTML="Job Saved";	
}//end makeFavorite
</script>



</head>
<body>


<!--stylesheet-->

<!--fonts-->

<link href='http://fonts.googleapis.com/css?family=Dosis|Josefin+Sans|Maven+Pro|Quicksand|Exo+2|Pontano+Sans|EB+Garamond|Jura|Comfortaa|Sintony|Antic+Slab|Quattrocento|Marvel|Poiret+One' rel='stylesheet' type='text/css'>

<!--styles-->

<link href="results.css" rel="stylesheet" type="text/css">


<?php
function get_url_contents($url) {
    $crl = curl_init();

    curl_setopt($crl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
    curl_setopt($crl, CURLOPT_URL, $url);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, 5);

    $ret = curl_exec($crl);
    curl_close($crl);
    return $ret;
}
$url = 'http://ajax.googleapis.com/ajax/services/search/images?v=1.0&q='.$_POST['city'];
$json = get_url_contents($url);

$data = json_decode($json);
$goodChoice;
foreach($data->responseData->results as $result){
	if($result->height > 1000 && $result->width > 1000)
	$goodChoice = $result;
	break;


}


//echo "background=\"$goodChoice->url\"";

require_once "Careerjet_API.php";

function verifyInput(){

	$goodData = TRUE;
	$job = $_POST["job"];
	$checkJob = gettype($job);
		
	$state = $_POST["state"];
	$checkState = gettype($state);

	$city = $_POST["city"];
	$checkCity = gettype($city);
	
	//check to make sure at least one of the city state or zip code are present for location
	if(($checkCity != "string") && ($checkState != "string")) $goodData = FALSE;
	else if($checkJob != "string") $goodData = FALSE;

		
	return $goodData;

}//end verifyData


if($_SERVER["REQUEST_METHOD"] == "POST"){
	session_start();
	$goodData = verifyInput();

	$keywords = $_POST["job"];	
	$_SESSION['job'] = $keywords;	
	$city = $_POST["city"];
	$_SESSION['city'] = $city;	
	$state = $_POST["state"];
	$_SESSION['state'] = $state;
	//$zipcode = $_POST["zipCode"];

	//$dol = $_POST["dolInfo"];

	$time = $_POST["time"];
	$_SESSION['time'] = $time;
	$contractType = $_POST["contract"];
	$_SESSION['contractType'] = $contractType;
	$sort = $_POST["sortBy"];
	$_SESSION['sort'] = $sort;
	$results = $_POST["#OfResults"];
	$_SESSION['results'] = $results;




//the commented out code below is supposed to select an imgae of the queried city
//and use that as the background of the search page


/*	
//curl request returns json output via json_decode php function
function curl($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

//parse the json output
function getResults($json){

	$results = array();

	$json_array = json_decode($json, true);

	foreach($json_array['query']['pages'] as $page){
		if(count($page['images']) > 0){
		    foreach($page['images'] as $image){

		    	$title = str_replace(" ", "_", $image["title"]);
		    	$imageinfourl = "http://en.wikipedia.org/w/api.php?action=query&titles=".$title."&prop=imageinfo&iiprop=url&format=json";
		    	$imageinfo = curl($imageinfourl);
		    	$iamge_array = json_decode($imageinfo, true);
		    	$image_pages = $iamge_array["query"]["pages"];

				foreach($image_pages as $a){
					$results[] = $a["imageinfo"][0]["url"];
				}
			}
		}
	}

	return $results;

}


if (empty($city)) {
    //term param not passed in url
    exit;
} else {
    //create url to use in curl call
    $term = str_replace(" ", "_", $search);
    $url = "http://en.wikipedia.org/w/api.php?action=query&titles=".$term."&prop=images&format=json&imlimit=1";

    $json = curl($url);
    $results = getResults($json);

	//print the results using an unordered list
	
	foreach($results as $a){
		  $backgroundURL = $a;
			break;
	}
	    
}
*/




	if($goodData == TRUE){
		
		//set default search region to US. Can be expanded at will
		$api = new Careerjet_API('en_US');
		$page = 1;
	//	if(isset($_GET['pageUp']))$page = $_GET['pageUp'];
	//	else if(isset($_GET['pageDown'])) $page = $_GET['pageDown'];
	
		//set finite bounds on page numbers
		//TODO: list page numbers
		if($page < 1) $page = 1;
		if($page > $_SESSION['maxPages']) $page = $_SESSION['maxPages'];

		//get data from database given user's parameters
		$result = $api->search(array( 
		
		'keywords' => $_SESSION['job'],
		'location' => $_SESSION['city']. ' ' . $_SESSION['state'], 
		'pagesize' => $_SESSION['results'],
		'sort' => $_SESSION['sort'], 
		'contractype' => $_SESSION['contractType'], 
		'contractperiod' => $_SESSION['time'], 
		'page' => $page,));
	
		
		if($result->hits == 0){
		echo "<div id=\"no_of_results_message\">No results found for '".$_SESSION['job']."' in ".$_SESSION['city'].", ".$_SESSION['state']."</div>";
		echo "<a class=\"ax\" href=\"index.html\"><p id=\"homelink\">Click to go back to homepage</p></a>";
		}
		if ($result->type == 'JOBS' ) {	
	
		if($result->pages > 1){
		$_SESSION['maxPages'] = $result->pages;
				
		echo "<div id=\"no_of_results_message\">Opero found ".$result->hits." jobs";
		echo " in ".$_SESSION['city'].", ".$_SESSION['state']."\n</div>";

		}

		elseif($result->pages == 1){

	
		echo "<div id=\"no_of_results_message\">Opero found " .$result->hits." jobs";
		echo " in ".$_SESSION['city'].", ".$_SESSION['state']. "\n</div>";

		
		}

		//echo "<form action=\"temp_search_results.php\" method=\"GET\">";
		//echo "<input id=\"pageUpButton\" type=\"submit\" name=\"pageUp\" value='".$_SESSION['pageNum']++."'";
		//echo "<input id=\"pageDownButton\" type=\"submit\" name=\"pageDown\" value='".$_SESSION['pageNum']--."'></form>";
		$jobs = $result->jobs;

		foreach($jobs as $job){

		//establish link with jobs db
	$jobConn = mysql_connect('localhost', 'a', 'Csci4300');
        
        $db = mysql_select_db('jobs', $jobConn);		

        $jobIDQuery = "SELECT MAX(jobID) AS jID FROM jobData";


        $result = mysql_query($jobIDQuery);

	$affected = mysql_affected_rows();

	$value = mysql_fetch_object($result);

	$jcount = $value->jID; 

	$jcount++;

	  if(!$result) echo "<p>job not inserted</p>"; 

        $insertJob = "INSERT INTO jobData VALUES ('$jcount', '$job->title','$job->locations', '$job->salary','$job->url', '$job->company', '$job->description', '$keywords')";

 	$insert = mysql_query($insertJob);

	$t = mysql_affected_rows();

	mysql_close($jobConn);

	



		
                
		
	//echo "<div id=\"background\" style=\"background-image: url(".$backgroundURL.")\">";
	
		//job division
		echo "<div id=\"job\">";
	
		if(($_SESSION['memberID'])){
                $memberID = $_SESSION['memberID'];	
		//it finally works!!!
		echo "<input type=\"submit\" id=\"faveButton\" onclick=\"makeFavorite('$memberID', '$jcount');\" value=\"Save Job\">";}
	
			
		
			echo "<a id=\"url_link\" href=\"$job->url\"><img src=\"https://pbs.twimg.com/profile_images/1319539968/mobile_icon_eng.png\" height=\"100\" width=\"100\" alt=\"career jet logo\"></a>";
			echo "<div id=\"click_message\">Click Logo<br>For Full Details</div>";
		
			echo "<div id=\"title\">".$job->title."</div>";
			//echo "<div id=\"loc\">Location: ".$job->locations."</div>\n";
		
			echo "<div id=\"salary\">".$job->salary."</div>";	
		
		
			echo "<div id=\"company\">".$job->company."</div>";

			echo "<div id=\"desc\">Key word matches: ".$job->description."</div>";
			echo "</div>"; //end job div
	
		
			
			echo "\n";
        
	



		}//endforeach
	//echo "</div>";//end background div
		}//end if
			
		//if location is ambiguous...


			if ( $result->type == 'LOCATIONS' ){

  			$locations = $result->solveLocations ;

  			foreach ( $locations as $loc ){

    			echo $loc->name."\n" ; # For end user display
    		## Use $loc->location_id when making next search call
    		## as 'location_id' parameter

		////////////////////IMPORTANT!!/////////////////

		//add database deposit of information here

		///////////////////IMORTANT!!///////////////////
  }
}
}



}

/*
function makeFavorite($userID, $jobID){ 
 
        $connect = mysql_connect('localhost', "a", "Csci4300"); 
 
 
        $db = mysql_select_db("jobs", $connect); 
 
 
        //nested insert and select command ensures no duplicate favorites 
        $faveJobInsert = "INSERT INTO favorites VALUES ('$userID','$jobID')"; 
 
        $result = mysql_query($faveJobInsert); 
 
        if($result) continue; 
	
        else echo "<p>problem inserting job into favorites</p>"; 
 

	mysql_close($connect);
}*/
function makeFavorite($memberID, $jobID){

$connect = mysql_connect('localhost', 'a', 'Csci4300');


        $db = mysql_select_db("jobs", $connect);


        //nested insert and select command ensures no duplicate favorites 
        $faveJobInsert = "INSERT INTO favorites VALUES ('$memberID','$jobID')";

        $result = mysql_query($faveJobInsert);

        if($result) continue;

        else echo "<p>problem inserting job into favorites</p>";


        mysql_close($connect);
	header('Location: ' . $_SERVER['HTTP_REFERER']);

}





//end php
?>


</body>
</html>			
