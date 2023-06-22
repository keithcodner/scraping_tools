<?php

//define('MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL);
set_time_limit(1000000); 
$domain = "https://book-kings.com/";
$host_db = "localhost";
$db_core = "book-kings";
$usr_db = "root";
$pwd_db = "root";

$agent = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36";

/*
define("DB_SERVER", "localhost");
define("DB_USERNAME", "remote");
define("DB_PASSWORD", "41TOj3Ibka86HUc@y2UgJAgJL%44oE");
define("DB_DATABASE", "book-kings");
*/

define("DB_SERVER", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "root");
define("DB_DATABASE", "book-kings");


//FUNCTIONS
function fetchQuery($query)
{

	

	//get the show ID to connect the show and the episodes
	$db = mysqli_connect(DB_SERVER,  DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	//$db = mysqli_connect(DB_SERVER, DB_DATABASE, DB_USERNAME, DB_PASSWORD);
	
	$result = mysqli_query($db, $query);

	//$theResult = mysqli_fetch_object($result);

	if(!$result){
		return 'error';
	 }else{
		return $result;
	 }


	 
}

function execQuery($query)
{
	//get the show ID to connect the show and the episodes 
	$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	
	
	$result = mysqli_query($db, $query);
}
  
function returnQuery($query)
{
   //get the show ID to connect the show and the episodes
  $link = mysql_connect(DB_SERVER, DB_DATABASE, DB_USERNAME, DB_PASSWORD);
  if (!$link) {
	  die('Could not connect: ' . mysql_error());
  }
  if (!mysql_select_db($db_core)) {
	  die('Could not select database: ' . mysql_error());
  }
  $result = mysql_query($query);
  if (!$result) {
	  die('Could not query:' . mysql_error());
  }
  //echo mysql_result($result, 0); // outputs third employee's name
  //$theResult = mysql_result(, 0);

  mysql_close($link); 

	return $result;
}
  
 
//CURL FUNCTIONSfunction 

/*

$xpath = new DOMXPath($dom);	
	$values = $xpath->query($xPath);	
	return $values->item(0)->nodeValue;	
	curl_close($ch);*/
function searchPageDOM($dom, $xpathz){

	$values = $dom->query($xpathz);	
	return $values->item(0)->nodeValue;	
}

function hopToNext($hopLink, $xPathz, $returnPage=false){	

	$ch = curl_init();	
	curl_setopt($ch, CURLOPT_URL, $hopLink);	// Added a standard User Agent. If problem persists, try setting up and using a proxy.	

	// Search CURLOPT_PROXYAUTH on http://us1.php.net/manual/en/function.curl-setopt.php	
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	

	$ws = curl_exec($ch);	
	usleep(3500);
	curl_close($ch);
	
	$dom = new DOMDocument();	
	@$dom->loadHTML($ws);
	$xpath = new DOMXPath($dom);	

	if($returnPage == true){
		return $xpath;
	}else{
		$values = $xpath->query($xPathz);	
		return $values->item(0)->nodeValue;	
	}
}

function hopToNextAll($hopLink, $xPathz){	

	$ch = curl_init();	
	curl_setopt($ch, CURLOPT_URL, $hopLink);	// Added a standard User Agent. If problem persists, try setting up and using a proxy.	

	// Search CURLOPT_PROXYAUTH on http://us1.php.net/manual/en/function.curl-setopt.php	
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	

	$ws = curl_exec($ch);	
	usleep(3500);
	curl_close($ch);
	
	$dom = new DOMDocument();	
	@$dom->loadHTML($ws);
	$xpath = new DOMXPath($dom);	

	$values = $xpath->query($xPathz);	
	return $values;	
	
}

function returnedPage($hopLink, $returnDomDoc = "true"){	

	$ch = curl_init();	
	curl_setopt($ch, CURLOPT_URL, $hopLink);	// Added a standard User Agent. If problem persists, try setting up and using a proxy.	

	// Search CURLOPT_PROXYAUTH on http://us1.php.net/manual/en/function.curl-setopt.php	
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	

	$ws = curl_exec($ch);	
	usleep(3500);
	curl_close($ch);
	
	$dom = new DOMDocument();	
	@$dom->loadHTML($ws);
	$xpath = new DOMXPath($dom);	

	//$values = $xpath->query($xPathz);	
	if($returnDomDoc == 'true'){
		return $dom;	
	}else if($returnDomDoc == 'false'){
		return $xpath;	
	}
	
	
}

function sendAIRequest($request){	

	$link = 'https://api.openai.com/v1/engines/text-davinci-002/completions';
	$data = array(
		'prompt' => $request,
		'temperature' => 0.5,
		'max_tokens' => 5,
	  );

	$ch = curl_init();	
	curl_setopt($ch, CURLOPT_URL, $link);	
	//curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	

	curl_setopt($ch, CURLOPT_ENCODING, '');	
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10);	
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);	
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);	
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');	
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));	
	curl_setopt($ch, CURLOPT_HTTPHEADER , array(
		'Content-Type: application/json',
		'Authorization: Bearer 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'
	  ));	
	
	$response = curl_exec($ch);
	$err = curl_error($ch);
	
	curl_close($ch);
	
	if ($err) {
		echo 'cURL Error #:' . $err;
	  } else {
		echo $response;
	  }
	
}
 
function dateTime(){
	//get the date and time
	$a = date("y-m-d", time());  
	$b = date("G:i:s", time());   
	return $timeDate = $a." ".$b; 
}


