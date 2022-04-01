<!DOCTYPE html>
<html>

<head>
	<title>Opero Account Page</title>
	<link href="opero_style.css" rel="stylesheet" type="text/css">
	<body bgcolor="#005681">

	<script type="text/javascript">

      // The Browser API key obtained from the Google Developers Console.
      var developerKey = $api_key;

      // The Client ID obtained from the Google Developers Console. Replace with your own Client ID.
      var clientId = "592670722460-3j1hrvco4efks1m0e021avi6gtgtm1dt.apps.googleusercontent.com"

      // Scope to use to access user's photos.
      var scope = ['https://www.googleapis.com/auth/drive'];

      var pickerApiLoaded = false;
      var oauthToken;

      // Use the API Loader script to load google.picker and gapi.auth.
      function onApiLoad() {
        gapi.load('auth', {'callback': onAuthApiLoad});
        gapi.load('picker', {'callback': onPickerApiLoad});
      }

      function onAuthApiLoad() {
        window.gapi.auth.authorize(
            {
              'client_id': clientId,
              'scope': scope,
              'immediate': false
            },
            handleAuthResult);
      }

      function onPickerApiLoad() {
        pickerApiLoaded = true;
        createPicker();
      }

      function handleAuthResult(authResult) {
        if (authResult && !authResult.error) {
          oauthToken = authResult.access_token;
          createPicker();
        }
      }

      // Create and render a Picker object for picking user Docs.
      function createPicker() {
        if (pickerApiLoaded && oauthToken) {
          var upload = new google.picker.DocsUploadView();

          var picker = new google.picker.PickerBuilder().
              addView(google.picker.ViewId.DOCS).
              addView(upload).
              setOAuthToken(oauthToken).
              setDeveloperKey(developerKey).
              setCallback(pickerCallback).
              build();
          picker.setVisible(true);
        }
      }

      // A simple callback implementation.
      function pickerCallback(data) {
        var url = 'nothing';
        if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
          var doc = data[google.picker.Response.DOCUMENTS][0];
          url = doc[google.picker.Document.URL];
          sendRequest(url);
        }
      }

      function sendRequest(url) {
   		var xmlhttp = new XMLHttpRequest();
   		xmlhttp.open("GET", "savedocument.php?url=" + url, true);
   		xmlhttp.send();
   		xmlhttp.onreadystatechange=function() {
   	   		//Reloads the page after the database is updated
   			window.location = "http://www.opero.us/profile.php?" + (Math.random() * 10);
   		}	
      }

      function deleteResume() {
		var table = document.getElementById('resumes');
		var checked = new Array();
		var len = table.rows.length;
		for(var i = 1; i < len; i++) {
			if(document.getElementById("resumecheck" + i).checked)
				checked[i] =  document.getElementById("resume" + i);	
		}

		var xmlhttp = new XMLHttpRequest();
		var url = "?";
		var count = 0;
		for(var i = 1; i < len; i++) {
			if(checked[i] !== undefined) {
				count++;
				url += "link" + count + "=" + checked[i] + "&";
			}	
		}	
		url += "count=" + count;
		xmlhttp.open("GET", "deletedocument.php" + url, true);
		xmlhttp.send();
		xmlhttp.onreadystatechange=function() {
   	   		//Reloads the page after the database is updated
   			window.location = "http://www.opero.us/profile.php?" + (Math.random() * 10);
   		}	
      }            
    </script>
<!--css stylesheest-->
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">
	<link href="opero_style_responsive.css" rel="stylesheet" type="text/css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>

<!--css stylesheet-->
   
    
</head>
<body>
<nav role="navigation" class="navbar navbar-default navbar-fixed-top" id="navbar">
	<div class="container-fluid" id="container-fluid">
		<div class="navbar-header">
			<button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
		</div>
		<!-- Collection of nav links and other content for toggling -->
        
        	<div id="navbarCollapse" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
            	<?php
				include('login.php'); // Includes Login Script

				if(isset($_SESSION['login_user'])){
					?>
					<li id="hello">Hello, <?php echo $_SESSION['login_first_name']; ?></li>
					<li><a href="http://www.opero.us/index.php">Home</a></li>
					<li><a href="http://www.opero.us/logout.php">Log Out</a></li>
					<?php
				}
				else {
					?>
					<li class="dropdown" id="menu1">
		             <a class="dropdown-toggle" data-toggle="dropdown" href="#menu1">
		               Login
		                <b class="caret"></b>
		             </a>
		             <div class="dropdown-menu">
		               <form  name="userInfo" method="POST" action="login.php">
		                   <input id="homePageUserLogin" placeholder=" User Name" name="email" type="text">
		                   <input id="homePageUserLogin" placeholder=" Password" name="password" type="password">
		                   <input type="button" id="register" onclick="window.location='signup.php'" value="Register">
		                   <input id="login" type="submit" name="submit" value="Log In">
		                   <input type="button" id="forgotLogin" onclick="window.location='sendReminder.php'" value="Forgot Login Info?">
		               </form>
		             </div>
		           </li>
					<!-- 		           
					<div id="navbarCollapse" class="collapse navbar-collapse">
					<div class="userForm">
					<form  name="userInfo" method="POST" action="login.php">
					<input id="homePageUserLogin" placeholder=" User Name" name="email" type="text"><br>
					<input id="homePageUserLogin" placeholder=" Password" name="password" type="password"><br>
					<input type="button" id="register" onclick="window.location='signup.php'" value="Register">
					<input id="login" type="submit" name="submit" value="Log In">
					<input type="button" id="forgotLogin" onclick="window.location='sendReminder.php'" value="Forgot Login Info?">-->
					
			
					<span><?php echo $error; ?></span>
					</form>
					</div>
					<br>
					<?php
				}
				?>
            </ul>
            </div>
        </div>
	</div>
</nav>

	<script type="text/javascript" src="https://apis.google.com/js/api.js"></script>
	<div id="profileHeader">
	<b id="welcome">
	
	<div id = "linkbox">
	<form id="userPrefs" action="profile.php" method="POST">

	<a href="index.php">Home</a>
	<a href="setUserPrefs.php">Preferences</a>
	<a href="changePassword.php">Change Password</a><br>
	<a href="logout.php">Log Out</a><br>
	<p id="faveJobMessage">Number of Favorite Jobs to Display: </p><input type="text"  width="10em" placeholder=" 0-100" name="faveJobCount"> 	
	<input type="submit" value="Save Preferences">
	<!--TODO:add preferences to display from selected favorite-->

	</form>
	
	</div><!--end linkbox-->
	
	</div><!--end profile header-->
	
	<!--php script to output favorite jobs to an html table-->
	<!--settings for output can be adjusted in user preferences php page-->
	
	<br>
	<div id="resumetable">

<!--start output of favorite jobs table-->
	<?php 
	echo "<p>beginning of php for resume table jobs table code</p>\n";
	session_start();
	
	if($_POST['faveJobCount']) $_SESSION['faveJobCount'] = $_POST['faveJobCount'];
	else $_SESSION['faveJobCount'] = 1;
	$user_name = "a";
	$password = "Csci4300";
	$hostname = "localhost";
	$currentuser = $_SESSION['login_user'];
	
	try {
		$db = new PDO("mysql:host=$hostname;dbname=Documents", "a", $login_id);
		$sql = "SELECT * FROM resumes WHERE username = '$currentuser'";
		
		$stmt = $db->query($sql);
		
		$count = 1;
		if($stmt != NULL) {
			echo "<table id='resumes'>";
			echo "<tr><th>Your Resumes</th></tr>";
			while($row = $stmt->fetchObject()) {
	      		echo "<tr>";
				$resumeURL = $row->url;
				echo "<td><input type='checkbox' id='resumecheck"  .$count . "'>";
				echo "<td><a href='" . $resumeURL . "' id='resume" . $count . "'>Resume" . $count . "</td>";
				echo "</tr>";
				$count++;
			}
			echo "</table>";
		}
		/* foreach($db->query(sql) as $row) {
			echo $row['username'].' '.$row['url']; //etc...
		} */
		
		$db = null;
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
	?>
	
	<button onclick="onApiLoad()">Upload New Resume</button>
	<button onclick="deleteResume()">Delete Selected Resume</button>
	</div>

	<p>before favejobs include</p>	
	<!--
<?php



echo '<p>start of php for fave jobs</p>\n';
//import the user's session


//talk to database
$connection = mysql_connect('localhost', 'a', 'Csci4300');

//get data base variable from server
$db = mysql_select_db('jobs', $connection);

//given that we are already connected to jobs db...next query 
//gets data for html table elements. Format is intended to be dymanic.

$faveJobQuery = mysql_query('SELECT * FROM jobData OUTER JOIN favorites ON (favorites.jobID = jobData.jobID) AND ($_SESSION['memberID'] = favorites.memberID)");

echo "<p>before table build</p>';
// Create the beginning of HTML table, and of the first row
$html_table = '<table id=\"faveJobsTable\"><tr><th><Employer></th><th>Salary</th><th>Location</th><th><Job Title</th><th>Full Details</th><th>Match Strength</th>';
if($_SESSION['faveJobCount']) $numberOfJobsDisplayed = $_SESSION['faveJobCount'];//to do: userPrefs sets $_session['numJobs']     
else $numberOfJobsDisplayed = 10;//default to ten jobs displayed in favorties


$i = 0;
while($job = mysql_fetch_assoc($faveJobQuery)){
     $jobs[$i] = $job;
        $i++;
}
echo '<p>after fetch assoc i = '.$i.' as no of jobs</p>';

//verify that assoc call worked

if($i > 0){
// Associative array with data that will be displayed in HTML table

foreach($jobs as $job){

while($numberOfJobsDisplayed > 0){

$employer = $job['employer'];
$salary = $job['salary'];
$location = $job['location'];
$keywordMatchSnippets = $job['keywordMatchSnippets'];
$title = $job['title'];
$url = $job['url'];
$jobID = $job['jobID'];
$ogKeyWords = $job['originalKeyWords'];

//$matchStrength is a metric on how relevant the favorite might be relative
//to how much matching there was in  the full description and the inital search
//may skew toards longer ads without real results, but is at least some kind of 
//intelligence, which could be later honed with a parser

$matchStrength = strlen($keywordMatchSnippets) / strlen($ogKeyWords);


echo'<p>before main table build</p>';

$html_table .= '<tr><td id = \"employer\">'.$employer.'</td>';
$html_table .= '<td id = \"salary\">'.$salary.'</td>';
$html_table .= '<td id = \"location\">'.$location.'</td>';
$html_table .= '<td id = \"title\">'.$title.'</td>';
$html_table .= '<td id = \"url\">'.'<a href ='.$url.'>Link to Job Listing</a></td>';
$html_table .= '<td id = \"strength\">'.$matchStrength.'</td></tr>';
$numberOfJobsDisplayed--;
}
}//end foreach
}//end if $jobSize


//close html table tag 
$html_table .= '</table>';
echo'<p>after table build</p>';
//echo "$html_table";
echo '<p>test 1</p>';       // display the HTML table
?>
-->
	<p>after table</p>
	
	<!--terminado-->
	
	</body>
</html>
