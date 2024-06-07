<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
require('../../db.php');

$myfile = fopen("c2d.txt", "w") or die("Unable to open file!");


$setup = json_encode($_REQUEST);
$call_int = json_decode($setup);

fwrite($myfile, "Arracy" . $setup);

fwrite($myfile, "\nCALLEE" . $call_int->{'callee'});
fwrite($myfile, "\nCALLEE" . $call_int->{'emailaddress'});
fwrite($myfile, "\nCALLEE" . $call_int->{'pbxuserid'});
fclose($myfile);

$phone_updated=0;
$zoho_updated=0;


	$callee_phone = $call_int->{'callee'};

	if (isset($callee_phone)){
		$u_email = $call_int->{'emailaddress'};
		if($u_email == 'sessionsadam@gmail.com'){
			$u_email = 'brandon@at1ts.com';
		}
	}

	//$u_email = $_GET['emailaddress'];


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

	$query = "select * from assigned_integration_short where u_email = '$u_email' AND i_shortname = 'zoho';";
	$sockets = db::getInstance()->get_result($query);
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

		$n_a_token = $ai_int->{'access_token'};

		$query = "UPDATE assigned_integration SET ai_accessToken = '$n_a_token', ai_tempTokenUpdate = CURRENT_TIMESTAMP WHERE `ai_id` = " . $sockets['ai_id'] . ";";
		//echo "\n" . $query;
		$update = db::getInstance()->get_result($query);
		//print_r($sockets);

		$query = "select * from assigned_integration_short where u_email = '$u_email' AND i_shortname = 'zoho';";
		$sockets = db::getInstance()->get_result($query);

		$zoho_updated=1;
	
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


	$num_adjust = $_REQUEST['callee'];
	if(substr($num_adjust,0,1)=='+'){
		$num_adjust = substr($num_adjust, 1);
	}

	$z_api_url = $sockets['ai_apiURL'];
	$cdr1 = 'zoho' . $num_adjust .date("Y-m-dTH:i:s");
	global $call_ph;
	$callee_phone = $call_int->{'callee'};
	//echo urldecode($call_int->{'callee'});
	$call_ph = $callee_phone;
	$date_starter = date("Y-m-d H:i:s");
	global $caller;
	$caller = $sockets2['ai_username']; 
	$pbx_user = $sockets['ai_username'];
	//s$zoho_user = $sockets['ai_username'];
	global $zoho_access_token;
	$zoho_access_token = $sockets['ai_accessToken'];

	global $phone_access_token;
	$phone_access_token = $sockets2['ai_accessToken'];
	$user_id = $sockets['u_id'];
	$phone_domain = $sockets2['i_phone_domain'];
	$user_extension = $sockets2['u_extension'];

	//echo $user_extension;

			//check to see if Calling Agent online

		$url = 'https://pbx.1wire.co/ns-api/?format=json&object=presence&action=list';
		$params=['domain'=>$phone_domain];

		$headers = ["Authorization: Bearer " . $phone_access_token];

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
		global $user_ext;
		
		$user_ext = $user_extension;

		$usr_results = array_filter($arr, function($users) {
		$session_user = $GLOBALS['user_ext'];
	
			 //echo '\nEMAIOL0' . $session_user;
			return $users['user'] == $session_user;
		});

		//print_r($usr_results);
		$user_key = array_shift(array_keys($usr_results));
		$is_ext = 0;
		
		//print_r($usr_results[1]);

		if ($usr_results[$user_key]['user'] > ''){
		//$result_num = count($user_key);

		//echo $user_key;

			$phone_presence = $usr_results[$user_key]['presence'];
			$phone_message = $usr_results[$user_key]['message'];
			$pbx_ext = $usr_results[$user_key]['user'];
			$is_ext = 1;
			//echo $phone_presence;
			//print_r($usr_results[$user_key]);
			//echo "presence - " . $phone_presence;
	
			if($phone_presence !='open'){

				$url = $z_api_url . '/phonebridge/v3/clicktodialerror';
				echo $url;
				$headers = ["Authorization: Zoho-oauthtoken " . $zoho_access_token];
				$params=['code'=>'notavailable', 'from'=>$user_extension, 'to'=> $callee_phone, 'message' => 'Your extenstion is not available, please login again or contact your system administrator', 'zohouser' => '' . $pbx_user . ''];

				//print_r($params);

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

				print_r($data);

			
				return $data;
			}

		
		}






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
			//echo $phone_presence;
			//print_r($usr_results[$user_key]);

	
			if($phone_presence !='open' || !is_null($phone_message)){

				$url = $z_api_url . '/phonebridge/v3/clicktodialerror';
				//echo $callee_phone;
				$headers = ["Authorization: Zoho-oauthtoken " . $zoho_access_token];
				$params=['code'=>'notavailable', 'from'=>$user_extension, 'to'=> $call_ph, 'message' => 'Agent '. $call_ph .' is not available', 'zohouser' => '' . $pbx_user . ''];

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
 
				curl_close($handle);
				//echo 'in if statement';

				//print_r($data);

			
				return $data;
			}

		
		}
	

	function validating($phone, $callee_phone,$caller, $phone_access_token, $date_starter, $cdr1, $pbx_user, $zoho_access_token, $pbx_ext, $user_extension, $is_ext, $phone_domain, $z_api_url){
		/*$cdr1 = 'zoho' . $_REQUEST['callee'].date("Y-m-dTH:i:s");

		$callee_phone = $GLOBALS['$call_ph'];
		$caller = $GLOBALS['$caller'];
		$phone_access_token = $GLOBALS['$phone_access_token'];
		$date_starter = date("Y-m-d H:i:s");
		*/


		echo 'validate - ' . $callee_phone;
		$international_num = 0;
		//if ($is_ext ==0){
			if(substr($callee_phone,0,1)=='+'){
			//	echo '+ is added';
				if(substr($callee_phone,0,2)=='+1'){
				//	echo '+1';
					$international_num = 0;
				}else{
					echo 'here';
					$international_num = 1;
				}
			}
		//}

		

		if ($is_ext == 1){
					$url = 'https://pbx.1wire.co/ns-api/?format=json&object=call&action=call';

					$params=['callid'=>$cdr1, 'uid'=>$caller, 'destination'=> $user_extension];

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

					$call_to = $callee_phone;
					if($is_ext != 1){
						if(substr($callee_phone,0,2)!='+1'){
							$call_to = '+1' . $callee_phone;
						}
					}
	
					$query = "INSERT INTO `dblnef5hfuazae`.`current_calls` (`cid`, `callee`, `call_start`, `caller`, `direction`, `integration_user_name`,`u_ext`) VALUES ('[$cdr1]','$call_to', '$date_starter', '$caller', '1', '$caller', $user_extension);";
					$sockets = db::getInstance()->get_result($query);
		}elseif($international_num == 0){
				$call_to = $callee_phone;
				if(substr($callee_phone,0,2)=='+1'){
					$call_to = substr($callee_phone, 2);
				}

				$phone = $call_to;
			
					if(!preg_match('/^[0-9]{10}+$/', $phone) || substr($phone, 0, 3) == '555' ) {
						//echo "Invalid Phone Number";
						$url = $z_api_url . '/phonebridge/v3/clicktodialerror';

						$headers = ["Authorization: Zoho-oauthtoken " . $zoho_access_token];
						$params=['code'=>'invalid', 'from' =>$user_extension, 'to'=> $callee_phone, 'message' => 'Invalid Number', 'zohouser' => '' . $pbx_user . ''];
						//print_r($params);
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
						//print_r($data);
			
					}else{
						//echo "Valid Phone Number";


						$url = 'https://pbx.1wire.co/ns-api/?format=json&object=call&action=call';

						$params=['callid'=>$cdr1, 'uid'=>$caller, 'destination'=> $call_to];

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

						$call_to = $callee_phone;
						if($is_ext != 1){
							if(substr($callee_phone,0,2)!='+1'){
								$call_to = '+1' . $callee_phone;
							}
						}
	
						$query = "INSERT INTO `dblnef5hfuazae`.`current_calls` (`cid`, `callee`, `call_start`, `caller`, `direction`, `integration_user_name`,`u_ext`) VALUES ('[$cdr1]','$call_to', '$date_starter', '$caller', '1', '$caller', $user_extension);";
						$sockets = db::getInstance()->get_result($query);
					}
		}elseif($international_num == 1){
			
				if(substr($callee_phone,0,1)=='+'){
					$call_to = substr($callee_phone, 1);
				}

				echo $call_to;
				if(preg_match('/^[0-9]{7,15}+$/', $call_to)) {
					//echo "Valid Phone Number";

					$call_to_st = '011' . $call_to;

					$url = 'https://pbx.1wire.co/ns-api/?format=json&object=call&action=call';

					$params=['callid'=>$cdr1, 'uid'=>$caller, 'destination'=> $call_to_st];

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
	
					$query = "INSERT INTO `dblnef5hfuazae`.`current_calls` (`cid`, `callee`, `call_start`, `caller`, `direction`, `integration_user_name`,`u_ext`) VALUES ('[$cdr1]','$callee_phone', '$date_starter', '$caller', '1', '$caller',$user_extension);";
					$sockets = db::getInstance()->get_result($query);
					//echo $query;

			
				}else{
					//echo $call_to;

					//echo "Invalid Phone Number";
					$url = $z_api_url . '/phonebridge/v3/clicktodialerror';

					$headers = ["Authorization: Zoho-oauthtoken " . $zoho_access_token];
					$params=['code'=>'invalid', 'from' =>$user_ext, 'to'=> $callee_phone, 'message' => 'Invalid Number', 'zohouser' => '' . $pbx_user . ''];
					//print_r($params);
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
					//print_r($data);
				
				
				}

			
		}
		
		return $data;
	}

	validating($callee_phone,$callee_phone, $caller, $phone_access_token,$date_starter, $cdr1, $pbx_user, $zoho_access_token,$pbx_ext,$user_extension, $is_ext, $phone_domain, $z_api_url);


	

	?>