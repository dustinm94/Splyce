<?php

$crawl_here = $_POST["urlbox"]; //takes form input of which website to crawl.
$holding = array(); // holds all of the pages that it crawls.

function get_links($url) {
	global $holding; // lets us use this variable out side the function.
	$input = @file_get_contents($url); //this function is getting the links and using a RegEx to get the url.
	$regex = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
	preg_match_all("/$regex/siU", $input, $matches); //matches the links
	$p_url = parse_url($url, PHP_URL_HOST);
	$m = $matches[2];

	foreach ($m as $links) {

		if (strpos($links, "#")) {        // stops multiple indexing of same web page.
			$links = substr($links, 0, strpos($links, "#"));
		}
		if (substr($links,0, 1) == ".") {        // formating purposes, removes the "." before each link
			$links = substr($links, 1);
		}
		if (substr($links, 0, 7) == "http://") { // checks for sites that start with http://
			$links = $links;
		}
		else if (substr($links, 0, 8) == "https://") { // checks for sites that start with https://
			$links = $links;
		}
		else if (substr($links, 0, 2) == "//") { // checks for pages that could start with // ex. style sheets
			$links = $substr($links, 2);
		}
		else if (substr($links, 0, 1) == "#") {  // if we see a "#", it is stripped and gives you everything before.
			$links = $url;
		}
		else if (substr($links, 0, 7) == "mailto:") { // looks for emails on sites and places brackets around them. easier reading.
			$links = "[".$links."]";
		}
		else
		{
			if (substr($links, 0, 1) != "/") { // checks urls that do not start with / or anything above then uses parse_url function.
				$links = $p_url."/".$links; // appending
			}
			else {
				$links = $p_url.$link;
			}
		}
		if (substr($links, 0, 7) != "http://" && substr($links, 0, 8) != "https://" && substr($links, 0, 1) != "[") {
			if (substr($url, 0, 8) == "https://") {
				$links = "https://".$links;
			}
			else {
				$links = "http://".$links;
			}
		}
		if (!in_array($links, $holding)) {  // uses the boolean factor of the function "in_array"
			array_push($holding, $links); // if the link is not in the array, it is pushed into the array / onto the link
		}
	}
}

get_links($crawl_here);

foreach ($holding as $page) {
	get_links($page); // crawls each page once.
}

foreach ($holding as $page) { // displays the links crawled.
	echo $page."<br />";
}
$count_links = count($holding);
echo "<br />"


$con=mysqli_connect("localhost","your","info","here");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }


mysqli_query($con,"INSERT INTO Links (SITE_URL)
VALUES ('$page')");

mysqli_close($con);


echo "<br><h2>Links Crawled</h2>";

$result = mysqli_query($con,"SELECT * FROM Links");

echo "<table border='1'>
<tr>
<th>Links</th>
</tr>";

while($row = mysqli_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['SITE_URL'] . "</td>";
  echo "</tr>";
  }
echo "</table>";

mysqli_close($con);
?>
