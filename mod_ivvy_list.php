<?php
defined('_JEXEC') or die('Restricted access');

// Get Access Keys from your iVvy Dashboard
$api_key    = "XXXXXXXXXXXXXXXXXXXXXXXXXXXX";
$api_secret = "XXXXXXXXXXXXXXXXXXXXXXXXXXXX";

// Add filters etc to the POST data
$data = "{}";

$host = "https://api.ivvy.com";

// string to sign 
$method = "post";
$contentmd5 = md5($data);
$content_type = "application/json";
$request_string = "/api/1.0/event?action=getEventList";
$api_version = "1.0";
$ivvy_date = date("Y-m-d H:i:s");

$string_to_sign  = $method;
$string_to_sign .= $contentmd5;
$string_to_sign .= $content_type;
$string_to_sign .= $request_string;
$string_to_sign .= $api_version;
$string_to_sign .= "ivvydate=" . $ivvy_date;

$hmac_signature = hash_hmac("sha1", strtolower($string_to_sign), $api_secret);

$curl_headers = [
    'Host: api.ivvy.com',
    'Content-MD5: ' . $contentmd5,
    'Content-Type: ' . $content_type,
    'Content-Length: ' . strlen($data),
    'X-Api-Version: ' . $api_version,
    'X-Api-Authorization: IWS ' . $api_key . ':' . $hmac_signature,
    'IVVY-Date: ' . $ivvy_date,
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_RETURNTRANSFER => 1,
    //CURLOPT_HEADER => 1,
    CURLOPT_URL => $host.$request_string,
    CURLOPT_HTTPHEADER => $curl_headers,
    //CURLINFO_HEADER_OUT => 1,
]);

$results = curl_exec($curl);


// DEBUGGING ONLY -------------------------------------------------

//$header_sent = curl_getinfo($curl, CURLINFO_HEADER_OUT ); 
/*
echo "header sent";
echo "<pre>";
print_r($header_sent);
echo "</pre>";

echo "endpoint: ";
echo $host.$request_string;
echo "<br>";

echo "string to sign: ";
echo strtolower($string_to_sign);
echo "<br>";

echo "secret key used: ";
echo $api_secret;
echo "<br>";

echo "curl headers";
echo "<pre>";
print_r($curl_headers);
echo "</pre>";
*/
// LAYOUT --------------------------------------------------

$events = json_decode($results,1);
?>
<h2>iVvy Events API Test</h2>
<p>Below is the raw data return from the iVvy API using an unfiltered 'getEventList' request.</p>

<pre>
<?php print_r($events); ?>
</pre>