<?php
require('../db.php');
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
require __DIR__ .'/vendor/autoload.php';
//print_r($_SESSION['1wire']);
	$user_pbx_name = $_GET['u_id'];

	$zoho_updated = 0;
	$phone_updated = 0;

	///CHECH Zoho Token
	$query = "select * from assigned_integration_short where `u_wpUser` = '$user_pbx_name' AND i_shortname = 'zoho';";

	$sockets = db::getInstance()->get_result($query);
	$u_email = $sockets['u_email'];
	//print_r($sockets);

	$zoho_api_url = $sockets['ai_tokenURL'];

	$cur_time = date('Y-m-d H:i:s');
	$refresh_time = strtotime(date($sockets['ai_tempTokenUpdate']));
	//$refresh_time = strtotime(date($cur_time));
	$future_time = date('Y-m-d H:i:s',strtotime("+55 minutes", $refresh_time));

	//echo $future_time . '======>  ' . $cur_time;


	$time1 = new DateTime($cur_time);
	$time2 = new DateTime($future_time);
	
	if ($cur_time > $future_time || !isset($sockets['ai_refreshToken'])){
		//echo "   MADE IT:     ";


		//check Auth Code Zoho
		$url = $zoho_api_url . '/oauth/v2/token';

		$params=['refresh_token'=>''. $sockets['ai_refreshToken']  .'', 'client_id'=>''. $sockets['i_token'] . '', 'client_secret'=>''. $sockets['i_secret'] . '','grant_type'=>'refresh_token'];

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

		//print_r($data);

		$ai_int = json_decode($data);
		//print_r($ai_int);

		$n_a_token = $ai_int->{'access_token'};

		$query = "UPDATE assigned_integration SET ai_accessToken = '$n_a_token', ai_tempTokenUpdate = CURRENT_TIMESTAMP WHERE `ai_id` = " . $sockets['ai_id'] . ";";
		//echo "\n" . $query;
		$update = db::getInstance()->get_result($query);
		//print_r($sockets);

		$query = "select * from assigned_integration_short where u_wpUser = '$user_pbx_name' AND i_shortname = 'zoho';";
		$sockets = db::getInstance()->get_result($query);

		$zoho_updated = 1;
	}
		////////////////////////END Zoho Token Check /////////////////////////

		////Start Netsapiens Token Check ////////////////////////////

	$query = "select * from assigned_integration_short where u_email = '$u_email' AND i_shortname = 'phone';";
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

		//print_r($data);

		$ai_int = json_decode($data);

		$n_a_token = $ai_int->{'access_token'};
		$n_r_token = $ai_int->{'refresh_token'};

		$query = "UPDATE assigned_integration SET ai_accessToken = '$n_a_token', ai_refreshToken = '$n_r_token', ai_tempTokenUpdate = CURRENT_TIMESTAMP WHERE `ai_id` = " . $sockets2['ai_id'] . ";";
		//echo "\n" . $query;
		$update = db::getInstance()->get_result($query);
		//print_r($sockets);

		$query = "select * from assigned_integration_short where u_email = '$u_email' AND i_shortname = 'phone';";
		$sockets2 = db::getInstance()->get_result($query);

		$phone_updated = 1;
	
	}

		if ($phone_updated == 0){
		$query = "select * from assigned_integration_short where u_email = '$u_email' AND i_shortname = 'phone';";
		$sockets2 = db::getInstance()->get_result($query);
		//print_r($sockets2);
	}

	if ($zoho_updated == 0){
		$query = "select * from assigned_integration_short where u_email = '$u_email' AND i_shortname = 'zoho';";
		$sockets = db::getInstance()->get_result($query);
		//print_r($sockets);

	}

	global $phone_access_token;
	$phone_access_token = $sockets2['ai_accessToken'];
	$phone_domain = $sockets2['i_phone_domain'];
	$pbx_user_name = $sockets['u_wpUser'];
	$cdr1 = $_GET['cid'];
	$date_starter = date("Y-m-d H:i:s");
	$zoho_access_token = $sockets['ai_accessToken'];
	$from = $_GET['from'];
	$to = $_GET['to'];
	$ext = $sockets['u_extension'];
	$international_num = 0;
	$duration = $_GET['duration'];
	$user_id = $sockets['u_id'];
	$pbx_user = $sockets['ai_username'];
	//$zoho_access_token = $sockets['ai_accessToken'];
	$access_cd = $zoho_access_token;
	$event_type=$_GET['event'];
	$z_api_url = $sockets['ai_apiURL'];
	

	if($event_type == 'out_disconnected' || $event_type == 'in_disconnected'){

		$query="SELECT * FROM  current_calls WHERE `cid` LIKE '$cdr1';";
		$sockets = db::getInstance()->get_result($query);
		//	print_r($sockets);
		if(is_null($sockets['cid'])){
			return 0;
		}

		if ($sockets['call_answered'] == 0){
			if ($event_type == 'out_disconnected' ){
				//$event_type='wrong_num';
				//$event_type='agent_disconnect';
				$cdr2 = $sockets['cid'];
				$from = $sockets['u_ext'];
				$to = $sockets['callee'];
				$st_time = $sockets['call_start'];
				$duration = 0;
				if(substr($to,0,1)=='+'){
					$to = substr($to,1);
				}
			}elseif ($event_type == 'in_disconnected') {
				//$event_type='wrong_num';
				//$event_type='agent_disconnect';
				$cdr2 = $sockets['cid'];
				$from = $sockets['caller'];
				$to = $sockets['u_ext'];
				$st_time = $sockets['call_start'];
				$duration = 0;
				if(substr($from,0,1)=='+'){
					$from = substr($from,1);
				}
			}
		
		}
	}


$myfile = fopen("zoho.txt", "w") or die("Unable to open file!");


switch($event_type){
	case 'out_setup':
				fwrite($myfile, "REQUEST  " . print_r($_REQUEST));
				$txt = "out_setup" . json_encode($_REQUEST) . "\n";
				fwrite($myfile, $txt);
				$txt = "Test\n" . $event_type;
				fwrite($myfile, $txt);
		break;
		
    case 'out_ringing':
			$query="SELECT * FROM  current_calls WHERE `cid` LIKE '$cdr1';";
			$sockets = db::getInstance()->get_result($query);
			//$row_num = mysql_num_rows($sockets);

			$date_starter = date('Y-m-d H:i:s');
			
			if (is_null($sockets)){
				if(substr($to,0,1)=='+'){
					if(substr($to,0,2)=='+1'){
						$international_num = 0;
					}else{
						$international_num = 1;
					}
				}

				if($international_num == 0){
					if(substr($to,0,2)!='+1'){
						$to = '+1' . $to;
					}
				}elseif($international_num == 1){
					if(substr($to,0,3)=='011'){
						$to = '+' . substr($to,3);
					}elseif(substr($to,0,1)=='+'){
						$to = '+' . substr($to,1);
					}
				}
 
				//echo 'null';
				$query = "INSERT INTO `dblnef5hfuazae`.`current_calls` (`cid`, `callee`, `call_start`, `caller`, `direction`, `integration_user_name`, `call_answered`, `u_ext`) VALUES ('$cdr1','$to', '$date_starter', '$from',1, '$pbx_user_name', 0, $ext);";
				$update = db::getInstance()->get_result($query);

				$query="SELECT * FROM  current_calls WHERE `cid` LIKE '$cdr1';";
				$sockets = db::getInstance()->get_result($query);
				//print_r($sockets);
			}

			$from = $sockets['u_ext'];
			$to = $sockets['callee'];

			if(substr($to,0,3)=='011'){
				$to = '+' . substr($to,3);
			}

			$st_time = $sockets['call_start'];

			$url = $z_api_url . '/phonebridge/v3/callnotify';

			$headers = ["Authorization: Zoho-oauthtoken " . $access_cd, "Content: application/x-www-form-urlencoded"];
			$params = 'type=dialed&state=ringing&id=' . urlencode($cdr1) . '&from=' . urlencode($from).'&to=' . urlencode($to);
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

			//print_r($data);

			//fwrite($myfile, "REQUEST  " . print_r($_REQUEST));
			$txt = "out_ringing" . json_encode($_REQUEST) . "\n";
			fwrite($myfile, $txt);
			$txt = "Test\n" . $event_type;
			fwrite($myfile, $txt);
		break;
    case 'out_connected':

			$cdr1=$_GET['cid'];
			
			$query="SELECT * FROM  current_calls WHERE `cid` LIKE '$cdr1';";
			$sockets = db::getInstance()->get_result($query);

			$cdr2 = $sockets['cid'];
			$from = $sockets['caller'];
			$to = $sockets['callee'];
			$st_time = $sockets['call_start'];

				if(substr($to,0,1)=='+'){
					if(substr($to,0,2)=='+1'){
						$international_num = 0;
					}else{
						$international_num = 1;
					}
				}

				if($international_num == 0){
					if(substr($to,0,2)!='+1'){
						$to = '+1' . $to;
					}
				}elseif($international_num == 1){
					if(substr($to,0,3)=='011'){
						$to = '+' . substr($to,3);
					}elseif(substr($to,0,1)=='+'){
						$to = '+' . substr($to,1);
					}
				}



			$url = $z_api_url . '/phonebridge/v3/callnotify';

			$headers = ["Authorization: Zoho-oauthtoken " . $zoho_access_token, "Content: application/x-www-form-urlencoded"];

			//return print_r($headers);
			$params = 'type=dialed&state=answered&id='. urlencode($cdr2) .'&from=' . urlencode($from).'&to=' . urlencode($to);
	
			$handle = curl_init();
 
			//$url = "https://accounts.zoho.com/oauth/v2/token";
 
			// Array with the fields names and values.
			// The field names should match the field names in the form.
 
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

			$query = "UPDATE current_calls SET call_answered = 1;";
			//echo "\n" . $query;
			$update = db::getInstance()->get_result($query);

			$txt = "out_connected" . json_encode($_REQUEST) . "\n";
			fwrite($myfile, $txt);
			$txt = "Test\n" . $event_type;
			fwrite($myfile, $txt);


		break;

		
    case 'out_disconnected':
			$cdr1=$_GET['cid'];

			$query="SELECT * FROM  current_calls WHERE `cid` LIKE '$cdr1';";
			$sockets = db::getInstance()->get_result($query);

			print_r($sockets);

			$cdr2 = $sockets['cid'];
			$from = $sockets['caller'];
			$to = $sockets['callee'];
			$st_time = $sockets['call_start'];
			$answered = $sockets['call_answered'];
			$duration = $_GET['duration'];
			$ext = $sockets['u_ext'];
			$international_num = 0;
			$agent_refused = $sockets['agent_refused'];



			$seconds_dur = $duration;

			$time = time() -$seconds_dur;//time stamp is in seconds, so now -1 would be the current date minus 1
	
			$start_time = $st_time;
			
			if ($answered == 0){
				/*if(substr($to,0,1)=='+'){
					if(substr($to,0,2)=='+1'){
						$international_num = 0;
					}else{
						$international_num = 1;
					}
				}

				if($international_num == 0){
					if(substr($to,0,2)=='+1'){
						$to = substr($to,2);
					}
				}elseif($international_num == 1){
					if(substr($to,0,1)=='+'){
						$to = substr($to,1);
					}
				}*/

				//$duration = 0;
				if(substr($cdr2,1,4)=='zoho'){
					if($_REQUEST['duration']==0 || $agent_refused == 1){
						$url = $z_api_url . '/phonebridge/v3/clicktodialerror';
						///REGEX for invalid numbers

						$headers = ["Authorization: Zoho-oauthtoken " . $zoho_access_token];
						$params=['code'=>'invalid', 'from'=>'' . $ext . '', 'to'=>'' . $to .'', 'message' => 'Caller busy, no answer, agent aborted call', 'zohouser' => '' . $pbx_user . ''];
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

						curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

						$data = curl_exec($handle);
						print_r($data);

						curl_close($handle);

					} elseif ($_REQUEST['duration'] > 0 && $agent_refused == 0) {
							$url = $z_api_url . '/phonebridge/v3/callnotify';

							$headers = ["Authorization: Zoho-oauthtoken " . $zoho_access_token, "Content: application/x-www-form-urlencoded"];

							//return print_r($headers);
							$params = 'type=dailed&state=busy&id=' . urlencode($cdr2) .'&from=' . urlencode($ext) .'&to=' . urlencode($to) . '&start_time=' . $st_time; // .'&duration='. $duration;
							$handle = curl_init();
 
							//$url = "https://accounts.zoho.com/oauth/v2/token";
 
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
							print_r($data);
 
							curl_close($handle);

					}else{
							$url = $z_api_url . '/phonebridge/v3/callnotify';

							$headers = ["Authorization: Zoho-oauthtoken " . $zoho_access_token, "Content: application/x-www-form-urlencoded"];

							//return print_r($headers);
							$params = 'type=dailed&state=rejected&id=' . urlencode($cdr2) .'&from=' . urlencode($ext) .'&to=' . urlencode($to) . '&start_time=' . $st_time; // .'&duration='. $duration;
							$handle = curl_init();
 
							//$url = "https://accounts.zoho.com/oauth/v2/token";
 
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
							print_r($data);
 
							curl_close($handle);

					}
				/*
					$url = $z_api_url . '/phonebridge/v3/callnotify';

					$headers = ["Authorization: Zoho-oauthtoken " . $zoho_access_token, "Content: application/x-www-form-urlencoded"];

					//return print_r($headers);
					$params = 'type=dailed&state=ended&id=' . urlencode($cdr2) .'&from=' . urlencode($ext) .'&to=' . urlencode($to) . '&start_time=' . $st_time .'&duration='. $duration;
					$handle = curl_init();
 
					//$url = "https://accounts.zoho.com/oauth/v2/token";
 
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

					//$data = curl_exec($handle);
					//print_r($data);
 
					curl_close($handle);*/
				}else{					
					$url = $z_api_url . '/phonebridge/v3/callnotify';

					$headers = ["Authorization: Zoho-oauthtoken " . $zoho_access_token, "Content: application/x-www-form-urlencoded"];

					//return print_r($headers);
					$params = 'type=dailed&state=rejected&id=' . urlencode($cdr2) .'&from=' . urlencode($ext) .'&to=' . urlencode($to) . '&start_time=' . $st_time; // .'&duration='. $duration;
					$handle = curl_init();
 
					//$url = "https://accounts.zoho.com/oauth/v2/token";
 
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
					//print_r($data);
 
					curl_close($handle);
				}
 
			}else{

				$url = $z_api_url . '/phonebridge/v3/callnotify';

				$headers = ["Authorization: Zoho-oauthtoken " . $zoho_access_token, "Content: application/x-www-form-urlencoded"];

				//return print_r($headers);
				$params = 'type=dailed&state=ended&id=' . urlencode($cdr2) .'&from=' . urlencode($ext) .'&to=' . urlencode($to) . '&start_time=' . $st_time .'&duration='. $duration;
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
			}

			$txt = "out_disconnected" . json_encode($_REQUEST) . "\n";
			fwrite($myfile, $txt);
			$txt = "Test\n" . $event_type;
			fwrite($myfile, $txt);	
			fwrite($myfile, $params);
			
			$query="DELETE FROM current_calls WHERE `cid` LIKE '$cdr2';";
			$sockets = db::getInstance()->get_result($query);

			//return json_encode($data);

		break;
		
	case 'in_setup':
				fwrite($myfile, "REQUEST  " . print_r($_REQUEST));
				$txt = "in_setup" . json_encode($_REQUEST) . "\n";
				fwrite($myfile, $txt);
				$txt = "Test\n" . $event_type;
				fwrite($myfile, $txt);
		break;

    case 'in_ringing':	
		global $call_ph;
		$call_ph = $_REQUEST['from'];

		$call_ph = preg_replace("/[^0-9]/", "", $call_ph );
		//echo $call_ph;

			//check if Agent to be called is online
		$url = 'https://pbx.1wire.co/ns-api/?format=json&object=presence&action=list';

		$params=['domain'=>$phone_domain];

		//$params=['callid'=>'nothing', 'uid'=>'9071@1Wire', 'destination'=> '8017839004'];

		$headers = ["Authorization: Bearer " . $phone_access_token];

		//$headers = ["Authorization: Bearer c0476e1db4a160634a457811d44e0b88"];

	
		$handle = curl_init();
		curl_setopt_array($handle,
  			array(
    			 CURLOPT_URL => $url,
     			// Enable the post response.
    			CURLOPT_POST => true,
				//CURLOPT_CUSTOMREQUEST => 'DELETE',
    			// The data to transfer with the response.
    			CURLOPT_POSTFIELDS => $params,
    			CURLOPT_RETURNTRANSFER => true,
  			)
		);

		curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

		$data = curl_exec($handle);
 
		curl_close($handle);

		$arr = json_decode($data,true);

			//print_r($arr);


		$pbx_ext = '';

		$usr_results = array_filter($arr, function($users) {
		$session_user = $GLOBALS['call_ph'];
	;
			 //echo '\nEMAIOL0' . $session_user;
			return $users['user'] == $session_user;
		});


		//print_r($usr_results);

		$user_key = array_shift(array_keys($usr_results));
		$is_ext = 0;

		//if ($usr_results[$user_key]['user'] > ''){
		if (!is_null($usr_results[$user_key]['user'])){
		//$result_num = count($user_key);

		//echo $user_key;

			$phone_presence = $usr_results[$user_key]['presence'];
			$phone_message = $usr_results[$user_key]['message'];
			$pbx_ext = $usr_results[$user_key]['user'];
			$is_ext = 1;
		}
	
		if($is_ext == 1){
			$from = $call_ph;
		}else{
			$swissNumberStr = $_REQUEST['from'];
			$phoneUtil = libphonenumber\PhoneNumberUtil::getInstance();
			try {
				$swissNumberProto = $phoneUtil->parse($swissNumberStr, 'US');
				//print_r($swissNumberProto);
				if($phoneUtil->isValidNumberForRegion($swissNumberProto, 'US')){
					$from = $phoneUtil->format($swissNumberProto, \libphonenumber\PhoneNumberFormat::E164);
				}else{
					$from = '+' . substr($swissNumberStr,0);
					//echo $from;

				}

			} catch (libphonenumber\NumberParseException $e) {
				var_dump($e);
			}
		}

		$date_starter = date('Y-m-d H:i:s');


			$query = "INSERT INTO `dblnef5hfuazae`.`current_calls` (`cid`, `callee`, `call_start`, `caller`, `direction`, `ai_auth_token`, `integration_user_name`, `call_answered`, `u_ext`) VALUES ('$cdr1','$to', '$date_starter', '$from',2, '$access_cd', '$pbx_user_name', 0, $ext );";
			$update = db::getInstance()->get_result($query);
			//echo $query;
			///var_dump($update);

			$url = $z_api_url . '/phonebridge/v3/callnotify';

			$headers = ["Authorization: Zoho-oauthtoken " . $access_cd, "Content: application/x-www-form-urlencoded"];

			//return print_r($headers);
			$params = 'type=received&state=ringing&id=' . urlencode($cdr1) .'&from=' . urlencode($from) .'&to=' . $ext;
			$handle = curl_init();
 
			//$url = "https://accounts.zoho.com/oauth/v2/token";
 
			// Array with the fields names and values.
			// The field names should match the field names in the form.
 
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
			//print_r($data);
 
			curl_close($handle);

			$txt = "in_ringing" . json_encode($_REQUEST) . "\n";
			fwrite($myfile, $txt);
			$txt = "Test\n" . $event_type;
			fwrite($myfile, $txt);

		break;

    case 'in_connected':    
				
			$cdr1=$_GET['cid'];

			
			$query="SELECT * FROM  current_calls WHERE `cid` LIKE '$cdr1';";
			$sockets = db::getInstance()->get_result($query);

			$cdr2 = $sockets['cid'];
			$from = $sockets['caller'];
			$to = $sockets['callee'];
			$st_time = $sockets['call_start'];

			/*	if(substr($from,0,1)=='+'){
					if(substr($from,0,2)=='+1'){
						$international_num = 0;
					}else{
						$international_num = 1;
					}
				}

				if($international_num == 0){
					if(substr($from,0,2)!='+1'){
						$from = '+1' . $from;
					}
				}elseif($international_num == 1){
					if(substr($from,0,3)=='011'){
						$from = '+' . substr($from,3);
					}elseif(substr($from,0,1)=='+'){
						$from = '+' . substr($from,1);
					}
				}*/
			$url = $z_api_url . '/phonebridge/v3/callnotify';

			$headers = ["Authorization: Zoho-oauthtoken " . $access_cd, "Content: application/x-www-form-urlencoded"];

			$params = 'type=received&state=answered&id=' . $cdr1.'&from=' . urlencode($from) .'&to=' . urlencode($to);
			$handle = curl_init();
			echo '++++++++++++++++++++++++++++++++';
 
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
			print_r($data);
 
			curl_close($handle);

			$query = "UPDATE current_calls SET call_answered = 1;";
			$update = db::getInstance()->get_result($query);

			$txt = "in_connected" . json_encode($_REQUEST) . "\n";
			fwrite($myfile, $txt);
			$txt = "Test\n" . $event_type;
			fwrite($myfile, $txt);
		break;
		
    case 'in_disconnected':

			$cdr1=$_GET['cid'];

			$query="SELECT * FROM  current_calls WHERE `cid` LIKE '$cdr1';";
			$sockets = db::getInstance()->get_result($query);

			//print_r($sockets);

			$cdr2 = $sockets['cid'];
			$from = $sockets['caller'];
			$to = $sockets['callee'];
			$st_time = $sockets['call_start'];
			$answered = $sockets['call_answered'];
			$duration = $_GET['duration'];
			$ext = $sockets['u_ext'];

				/*if(substr($from,0,1)=='+'){
					if(substr($from,0,2)=='+1'){
						$international_num = 0;
					}else{
						$international_num = 1;
					}
				}

				if($international_num == 0){
					if(substr($from,0,2)=='+1'){
						$from = '+1' . substr($from,2);
					}
				}elseif($international_num == 1){
					if(substr($from,0,1)=='+'){
						$from = substr($from,1);
					}
				}*/



			$seconds_dur = $duration;

			$time = time() -$seconds_dur;//time stamp is in seconds, so now -1 would be the current date minus 1
	
			$start_time = $st_time;

			if ($answered == 0){
				/*if(substr($from,0,1)=='+'){
					if(substr($from,0,2)=='+1'){
						$international_num = 0;
					}else{
						$international_num = 1;
					}
				}

				if($international_num == 0){
					if(substr($from,0,2)!='+1'){
						$from = '+1' . substr($from,0);
					}
				}elseif($international_num == 1){
					if(substr($from,0,1)!='+'){
						$from = '+' . substr($from,0);
					}
				}*/
				
				$duration = 0;
				/*$url = $z_api_url . '/phonebridge/v3/clicktodialerror';
				///REGEX for invalid numbers

				$headers = ["Authorization: Zoho-oauthtoken " . $zoho_access_token];
				$params=['code'=>'refused', 'from'=>'' . $from . '', 'to'=>'' . $ext .'', 'message' => 'Agent Refused', 'zohouser' => '' . $pbx_user . ''];
				//var_dump($params);
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

				print_r($data);
 
				curl_close($handle);*/

				$url = $z_api_url . '/phonebridge/v3/callnotify';

				$headers = ["Authorization: Zoho-oauthtoken " . $zoho_access_token, "Content: application/x-www-form-urlencoded"];

				//return print_r($headers);
				$params = 'type=received&state=missed&id=' . urlencode($cdr2) .'&from=' . urlencode($from) .'&to=' . $ext . '&start_time=' . urlencode($st_time);
				//print_r($params);
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
				//print_r($data);
				curl_close($handle);
			}else{

				$url = $z_api_url . '/phonebridge/v3/callnotify';

				$headers = ["Authorization: Zoho-oauthtoken " . $zoho_access_token, "Content: application/x-www-form-urlencoded"];

				//return print_r($headers);
				$params = 'type=received&state=ended&id=' . urlencode($cdr2) .'&from=' . urlencode($from) .'&to=' . $ext . '&start_time=' . urlencode($st_time) .'&duration='. $duration;
				//print_r($params);
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
				//print_r($data);
				curl_close($handle);
			}


			$query="DELETE FROM  current_calls WHERE `cid` LIKE '$cdr2';";
			$sockets = db::getInstance()->get_result($query);
		break;

	    case 'wrong_num':

			$cdr1=$_GET['cid'];

			$query="SELECT * FROM  current_calls WHERE `cid` LIKE '$cdr1';";
			$sockets = db::getInstance()->get_result($query);

			//print_r($sockets);

			$cdr2 = $sockets['cid'];
			$from = $sockets['caller'];
			$to = $sockets['callee'];
			$st_time = $sockets['call_start'];


			$url = $z_api_url . '/phonebridge/v3/clicktodialerror';
		///REGEX for invalid numbers

			$headers = ["Authorization: Zoho-oauthtoken " . $zoho_access_token];
			$params=['code'=>'invalid', 'from'=>'' . $ext . '', 'to'=>'' .urlencode($to) .'', 'message' => 'Agent Disconnected', 'zohouser' => '' . urlencode($pbx_user) . ''];

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
			//echo 'in if statement';

			//print_r($params);

		break;

		case 'agent_disconnect':

			/*$cdr1=$_GET['cid'];

			$query="SELECT * FROM  current_calls WHERE `cid` LIKE '$cdr1';";
			$sockets = db::getInstance()->get_result($query);

			//print_r($sockets);

			$cdr2 = $sockets['cid'];
			$from = $sockets['caller'];
			$to = $sockets['callee'];
			$st_time = $sockets['call_start'];
			/*
				if(substr($caller,0,1)=='+'){
					if(substr($caller,0,2)=='+1'){
						$international_num = 0;
					}else{
						$international_num = 1;
					}
				}

				if($international_num == 0){
					if(substr($caller,0,2)!='+1'){
						$caller = '+1' . $caller;
					}
				}elseif($international_num == 1){
					if(substr($caller,0,3)=='011'){
						$caller = '+' . substr($caller,3);
					}elseif(substr($to,0,1)=='+'){
						$caller = '+' . substr($caller,1);
					}
				}
				*/

		$url = $z_api_url . '/phonebridge/v3/clicktodialerror';

			if(substr($from,0,1)=='+'){
				$from = substr($from,1);
			}
		///REGEX for invalid numbers
			echo 'TO:  ' . $to;
			$headers = ["Authorization: Zoho-oauthtoken " . $zoho_access_token];
			//$params=['code'=>'invalid', 'from'=> $from, 'to'=> $to, 'message' => 'Agent Refused', 'zohouser' =>  $pbx_user ];

			$params=['code'=>'invalid', 'from'=>'' . urlencode($from) . '', 'to'=>'' . urlencode($to) .'', 'message' => 'Agent Refused', 'zohouser' => '' . urlencode($pbx_user) . ''];
			
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
			//echo 'in if statement';

			//print_r($data);
	

			



		break;
		
};
 
return $data;

fclose($myfile);
?>
