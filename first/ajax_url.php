<?php
error_reporting(0);
if(isset($_POST["urlvalue"]) && !empty($_POST["urlvalue"])){
    $url = $_POST["urlvalue"];

	if(filter_var($url, FILTER_VALIDATE_URL))
	{
		
		$title = $description = $keywords = 'Not Found';
		
		$html = file_get_contents_curl($url); // Calling the function 

		// Starting of Parsing 
		$doc = new DOMDocument(); //creating New Dom Document
		@$doc->loadHTML($html); // Loading HTML Source in docs
		$nodes = $doc->getElementsByTagName('title'); // getting the attributes of Title Tags 

		//Putting Details into a variable
		$title = $nodes->item(0)->nodeValue; // Getting title 

		$metas = $doc->getElementsByTagName('meta'); //getting Elements with the tags meta
		// Seperating meta description and meta keyword  with each other with running a loop.
		for ($i = 0; $i < $metas->length; $i++)  
		{
			$meta = $metas->item($i);
			if($meta->getAttribute('name') == 'description') //Getting attributes with the Attribute "description"
				$description = $meta->getAttribute('content'); // Loading Content of meta description into a variable
			if($meta->getAttribute('name') == 'keywords') //Getting attributes with the Attribute "keywords" 
				$keywords = $meta->getAttribute('content'); // Loading keyword content into a variable
		}
		
		
		//get ip
		$wrapper = fopen('php://temp', 'r+'); // Opening a wrapper in php 
		$ch = curl_init($url);         // Initilizing Curl
		curl_setopt($ch, CURLOPT_VERBOSE, true);  
		curl_setopt($ch, CURLOPT_STDERR, $wrapper);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch); // Executing Curl & storing Data in result
		curl_close($ch); // Closing Curl
		$ips = get_curl_remote_ips($wrapper); // Getting remote IPS from the wrapper into the IPS Variable
		fclose($wrapper); // Closing Wrapper
		
		
		//get http code
		$htmlcode = http_header($url);
		
		//get load time
		$loadtime = http_loadtime($url);
		
		//get links
		
		$target_url = $url;
		$userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';

		// make the cURL request to $target_url
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		curl_setopt($ch, CURLOPT_URL,$target_url);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$html= curl_exec($ch);
		if (!$html) {
			$curl_data = $curl_data . "URL error number:" .curl_errno($ch);
		}

		// parse the html into a DOMDocument
		$dom = new DOMDocument();
		@$dom->loadHTML($html);

		//grab titles and all the things 

		// grab all the on the page
		$xpath = new DOMXPath($dom);
		$hrefs = $xpath->evaluate("/html/body//a");

		for ($i = 0; $i < $hrefs->length; $i++) {
			$href = $hrefs->item($i);
			$url = $href->getAttribute('href');
			
			if(filter_var($url, FILTER_VALIDATE_URL)){
				$position = strpos($url, $target_url);
							
						  if($position !== FALSE)
						  {
							  $curl_data = $curl_data . 'Internal Link: '.$url.'     ';
						  }
						  else
						  {
							$curl_data = $curl_data . 'External Link: '.$url.'     ';
						  }
				}

			
		}
			
		//sending back data
		
		$data = array(
			'title' =>$title,
			'desc' => $description,
			'keyword' => $keywords,
			'ip' => end($ips),
			'http' => $htmlcode,
			'load' => $loadtime,
			'data' => $curl_data,
		);
		echo json_encode($data);
		
	}
	else {
		$data = array(
				'err' => 'Invalid Url Address',
			);
		echo json_encode($data);
	}
}

//keywords
function file_get_contents_curl($url)
{
    $ch = curl_init(); //Initializing Curl

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $data = curl_exec($ch); //Executing CURL Function for getting the data
    curl_close($ch); // Closing Curl

    return $data; // Returning the HTML Page through data
}

//ip
function get_curl_remote_ips($fp) 
{
    rewind($fp); // Getting IP Adderss into fp variable
    $str = fread($fp, 8192); // Reading IP address
    $regex = '/\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b/'; // Regular Expression for the Valid IP adress matching
    if (preg_match_all($regex, $str, $matches)) {
        return array_unique($matches[0]);
    } else {
        return false;
    }
}

//http code
function http_header($url) {
    $handle = curl_init($url);
    curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

    // Get the HTML Code in the response
    $response = curl_exec($handle);

    // Checking http code through curl Inbuilt Function
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    curl_close($handle);
    
    return $httpCode;
}

//loading time
function http_loadtime($url) {
    $handle = curl_init($url);
    curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

    // gtting the Page Contents into Response
    $response = curl_exec($handle);

    // Calculating Loadtime Using Curl Inbuilt Function
    $loadtime = curl_getinfo($handle, CURLINFO_TOTAL_TIME);
    curl_close($handle);
    return $loadtime;
}


?>