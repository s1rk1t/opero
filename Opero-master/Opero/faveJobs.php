
<?php



echo "<p>start of php</p>";
//import the user's session

session_start();

//talk to database
$connection = mysql_connect("localhost", "a", $member_id);

//get data base variable from server
$db = mysql_select_db("jobs", $connection);

//given that we are already connected to jobs db...next query 
//gets data for html table elements. Format is intended to be dymanic.

$faveJobQuery = mysql_query("SELECT * FROM jobData INNER JOIN favorites ON (favorites.jobID = jobData.jobID) AND ($_SESSION['memberID'] = favorites.memberID)");

echo "<p>before table build</p>";
// Create the beginning of HTML table, and of the first row
$html_table = '<table id=\"faveJobsTable\"><tr><th><Employer></th><th>Salary</th><th>Location</th><th><Job Title</th><th>Full Details</th><th>Match Strength</th>';
if($_SESSION['faveJobCount']) $numberOfJobsDisplayed = $_SESSION['faveJobCount'];//to do: userPrefs sets $_session['numJobs']     
else $numberOfJobsDisplayed = 10;//default to ten jobs displayed in favorties


$i = 0;
while($job = mysql_fetch_assoc($faveJobQuery)){
     $jobs[$i] = $job;
	$i++;
}
echo "<p>after fetch assoc i = ".$i." as no of jobs</p>";

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


echo"<p>before main table build</p>";

$html_table .= '<tr><td id = \"employer\">'.$employer.'</td>';
$html_table .= '<td id = \"salary\">'.$salary.'</td>';
$html_table .= '<td id = \"location\">'.$location.'</td>';
$html_table .= '<td id = \"title\">'.$title.'</td>';
$html_table .= '<td id = \"url\">'.'<a href ='.$url.'>Link to Job Listing</a></td>';
$html_table .= '<td id = \"strength\">'.$matchStrength.'</td></tr>';
$numberOfjobsDisplayed--;
}
}//end foreach
}//end if $jobSize


//close html table tag 
$html_table .= '</table>';
echo"<p>after main table build</p>";
 
/*
// Traverse the array with FOREACH
foreach($jobs AS $job) {
  $html_table .= '<td>' .$key. ' - '. $val. '</td>';       // adds key-value in columns in table
  $i++;

  // If the number of columns is completed for a row (rest of division of $i to $nr_col is 0)
  // Closes the current row, and begins another row
  $col_to_add = $i % $nr_col;
  if($col_to_add == 0) { $html_table .= '</tr><tr>'; }
}

// Adds empty column if the current row is not completed
if($col_to_add != 0) $html_table .= '<td colspan="'. ($nr_col - $col_to_add). '">&nbsp;</td>';

$html_table .= '</tr></table>';         // ends the last row, and the table



$aray = array('http://coursesweb.net', 'www.marplo.net', 'Courses', 'Web Programming', 'PHP-MySQL');
$nr_elm = count($aray);        // gets number of elements in $aray

// Create the beginning of HTML table, and of the first row
$html_table = '<table border="1 cellspacing="0" cellpadding="2""><tr>';
$nr_col = 2;       // Sets the number of columns

// If the array has elements
if ($nr_elm > 0) {
  // Traverse the array with FOR
  for($i=0; $i<$nr_elm; $i++) {
    $html_table .= '<td>' .$aray[$i]. '</td>';       // adds the value in column in table

    // If the number of columns is completed for a row (rest of division of ($i + 1) to $nr_col is 0)
    // Closes the current row, and begins another row
    $col_to_add = ($i+1) % $nr_col;
    if($col_to_add == 0) { $html_table .= '</tr><tr>'; }
  }

  // Adds empty column if the current row is not completed
  if($col_to_add != 0) $html_table .= '<td colspan="'. ($nr_col - $col_to_add). '">&nbsp;</td>';
}

$html_table .= '</tr></table>';         // ends the last row, and the table

// Delete posible empty row (<tr></tr>) which cand be created after last column
$html_table = str_replace('<tr></tr>', '', $html_table);
*/
echo "$html_table"; 
echo "<p>test 1</p>";       // display the HTML table
?>


