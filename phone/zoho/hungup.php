<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../../db.php');
$myfile = fopen("hungup.txt", "w") or die("Unable to open file!");
	fwrite($myfile, "REQUEST" . print_r($_REQUEST));
	//fwrite($myfile, $params);
	$txt = "hello" . json_encode($_REQUEST) . "\n";
	fwrite($myfile, $txt);
	//fwrite($myfile, 'called To    ' . $_REQUEST['callee']);

fclose($myfile);

$setup = json_encode($_REQUEST);
$call_int = json_decode($setup);

$cdr1= $call_int->{'callrefid'};
echo $cdr1;

$query="SELECT * FROM  current_calls WHERE `cid` LIKE '$cdr1';";
$sockets = db::getInstance()->get_result($query);

$user_name = $sockets['integration_user_name'];


	$query = "select * from assigned_integration_short where ai_username = '$user_name' AND i_shortname = 'phone';";
	$sockets2 = db::getInstance()->get_result($query);
	//print_r($sockets2);

	$cur_time = date('Y-m-d H:i:s');
	$refresh_time = strtotime(date($sockets2['ai_tempTokenUpdate']));
	//$refresh_time = strtotime(date($cur_time));
	$future_time = date('Y-m-d H:i:s',strtotime("+55 minutes", $refresh_time));

	//echo $future_time . '======>  ' . $cur_time;


	$time1 = new DateTime($cur_time);
	$time2 = new DateTime($future_time);

	if ($cur_time > $future_time || !isset($sockets2['ai_refreshToken'])){
		//echo "   MADE IT:     ";


		//check Auth Code Netsapiens
		$url = 'https://pbx.1wire.co/ns-api/oauth2/token/';
		$params=['grant_type'=>'refresh_token', 'client_id'=>''. $sockets2['i_token'] . '', 'client_secret'=>''. $sockets2['i_secret'] . '','refresh_token'=>''. $sockets2['ai_refreshToken'] . ''];
		print_r($params);
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

		//curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

		$data = curl_exec($handle);
 
		curl_close($handle);

		//echo $data;

		print_r($data);

		$ai_int = json_decode($data);

		$n_a_token = $ai_int->{'access_token'};
		$n_r_token = $ai_int->{'refresh_token'};

		$query = "UPDATE assigned_integration SET ai_accessToken = '$n_a_token', ai_refreshToken = '$n_r_token', ai_tempTokenUpdate = CURRENT_TIMESTAMP WHERE `ai_id` = " . $sockets2['ai_id'] . ";";
		echo "\n" . $query;
		$update = db::getInstance()->get_result($query);
		//print_r($sockets);

		$query = "select * from assigned_integration_short where ai_username = '$user_name' AND i_shortname = 'phone';";
		$sockets2 = db::getInstance()->get_result($query);
		print_r($sockets2);
	
	}

	$access_cd = $sockets2['ai_accessToken'];


	$r1 = substr($_REQUEST['callrefid'],1);
	$r2 = substr($r1,0,-1);


	$cid = $r2;
	//echo $cid;
	
	$url = 'https://pbx.1wire.co/ns-api/?format=json&object=call&action=disconnect';

	$params=['callid'=>$cid, 'uid'=>$user_name];
	print_r($params);
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

	$query = "UPDATE current_calls SET agent_refused = 1;";
			//echo "\n" . $query;
			$update = db::getInstance()->get_result($query);

	return $data;
?>
