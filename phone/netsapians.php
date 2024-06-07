<?php

echo "hello";

$url = 'https://sebeta18.netsapiens.com/ns-api/';

$headers = ["Authorization: Bearer e147acca94b7c36446db34d1b51be43a"];
$params = 'dobject=uiconfig&daction=read&duser=9071z&ddomain=1wire&drole=Basic User&dreseller=NetSapiens&dconfig_name=WS_SERVERS';
$handle = curl_init();
curl_setopt_array($handle,
  array(
     CURLOPT_URL => $url,
     // Enable the post response.
    CURLOPT_POST       => true,
    // The data to transfer with the response.
    CURLOPT_POSTFIELDS => $params,
    CURLOPT_RETURNTRANSFER     => true,
  )
);

curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

$data = curl_exec($handle);
 
curl_close($handle);

print_r($data);
echo "bye";
?>
