<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../../db.php');


$myfile = fopen("answer.txt", "w") or die("Unable to open file!");
	fwrite($myfile, "REQUEST" . print_r($_REQUEST));
	//fwrite($myfile, $params);
	$txt = "hello" . json_encode($_REQUEST) . "\n";
	fwrite($myfile, $txt);


	$query = "select * from assigned_integration where u_id = 1 AND ai_id = 2;";
	$sockets = db::getInstance()->get_result($query);
	$access_cd = $sockets['ai_accessToken'];



	//$cc_params = json_decode($_REQUEST);
	$r1 = substr($_REQUEST['callrefid'],1);
	$r2 = substr($r1,0,-1);


	$cid = $r2;
	//echo $cid;
	fwrite($myfile, '\n' . $cid);

	
	
	$url = 'https://pbx.1wire.co/ns-api/?format=json&object=call&action=answer';

	$params=['callid'=> $cid , 'uid'=>'9071@1Wire'];

	$headers = ["Authorization: Bearer " . $access_cd];
	$handle = curl_init();
	curl_setopt_array($handle,
  		array(
    		 CURLOPT_URL => $url,
     		// Enable the post response.
    		CURLOPT_POST => true,
		//CURLOPT_CUSTOMREQUEST => 'DELETE',
    		// The data to transfer with the response.
    		CURLOPT_POSTFIELDS => $params,
    		CURLOPT_RETURNTRANSFER     => true,
  		)
	);

	curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

	$data = curl_exec($handle);
 
	curl_close($handle);

	fwrite($myfile, json_encode($data) . "heter");
	fclose($myfile);




?>