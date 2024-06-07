<?php
$myfile = fopen("control.txt", "w") or die("Unable to open file!");
	fwrite($myfile, "REQUEST" . print_r($_REQUEST));
	fwrite($myfile, $params);
	$txt = "hello" . json_encode($_REQUEST) . "\n";
	fwrite($myfile, $txt);
fclose($myfile);



$zoho_params = "[{name:pbxuserid,value:768724185},{name:emailaddress,value:brandon@at1ts.com},{name:callee,value:'{to}'}]";

$url = 'https://www.zohoapis.com/phonebridge/v3/callcontrol';

$headers = ["Authorization: Zoho-oauthtoken " . $access_cd];
$params = 'clicktodialuri=https://phone.1cxpro.com/phone/zoho/clicktodial.php&zohouser=768724185&clicktodialparam='.$zoho_params;
$handle = curl_init();
curl_setopt_array($handle,
  array(
     CURLOPT_URL => $url,
     // Enable the post response.
    CURLOPT_POST       => true,
	//CURLOPT_CUSTOMREQUEST => 'DELETE',
    // The data to transfer with the response.
    CURLOPT_POSTFIELDS => $params,
    CURLOPT_RETURNTRANSFER     => true,
  )
);

curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

$data = curl_exec($handle);
 
curl_close($handle);
?>