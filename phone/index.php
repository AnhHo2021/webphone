<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
    require '../db.php';
    require_once '../const_out_admin.php';
    session_start();
//print_r($_SESSION['1wire']["integration_ass"]["ai_id"]); die();
?>
<html>
<head>

<script src="../webphone_api.js?jscodeversion=169"></script>

<?php 
    $user_email = $_SESSION['1wire']['u_email'];
    $query = "select * from assigned_integration_short where u_email = '$user_email' AND i_shortname = 'zoho';";
    $zoho_active = db::getInstance()->get_result($query);
    //print_r($zoho_active);
    if($_REQUEST['state']=='nrft_connected' || $_REQUEST['state']=='rft_connected'){  ?>
    <script src="zoho.js?jscodeversion=396"></script>
<?php  echo 'Connected to Zoho';
    }  ?>



    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="<?=ASSETS_URL;?>/css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="<?=ASSETS_URL;?>/css/app.bundle.css">
    <link id="mytheme" rel="stylesheet" media="screen, print" href="<?=ASSETS_URL;?>/css/mytheme.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="<?=ASSETS_URL;?>/css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="<?=ASSETS_URL;?>/css1/web_phone.css">
    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?=ASSETS_URL;?>/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=ASSETS_URL;?>/img/favicon/favicon-32x32.png">
    <link rel="mask-icon" href="<?=ASSETS_URL;?>/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
</head>
<body class="mod-bg-1 mod-nav-link ">
<div class="page-wrapper1">
    <div class="page-inner">
        <div class="page-content-wrapper">
            <?php include_once APP_PATH.'/includes/header1.php'; ?>
        </div
    </div>
</div>

<pre>
    <?php
    
            $user_email = $_SESSION['1wire']['u_email'];
            $query = "select * from assigned_integration_short where u_email = '$user_email' AND i_shortname = 'phone';";
            $user_info = db::getInstance()->get_result($query);
           // print_r($user_info);
            $wp_domain = $user_info['i_phone_domain'];
            $wp_user =  $user_info['u_wpUser'];
            $wp_pass = $user_info['u_wpPassword'];
            $pbx_user = $user_info['ai_username'];
            $pbx_pass = $user_info['ai_password'];
            $wp_ext = $user_info['u_extension'];


    if($_REQUEST['state']=='nrft_connected' && $zoho_active['i_active'] == 1){  ?>
            <center><h6>Connected to Zoho</h6></center>
    <?php
    
            $query="SELECT * FROM integrations WHERE `c_id` = " . $_SESSION['1wire']['c_id'] . " AND `i_shortname` LIKE 'zoho';";
            $sockets = db::getInstance()->get_result($query);
            //print_r($sockets);
            $secret_code = $_REQUEST['code'];
            $z_api_url = $sockets['i_apiURL'];


           // $params=['code'=>$secret_code, 'client_id'=>$sockets['i_token'], 'client_secret'=>$sockets['i_secret'], 'redirect_uri'=>'https://phone.1cxpro.com/phone', 'grant_type' => 'authorization_code'];
            //print_r($params);
            $zoho_integration_id = $sockets['i_id'];

            $handle = curl_init();

            $url = "https://accounts.zoho.com/oauth/serverinfo";

            // Array with the fields names and values.
            // The field names should match the field names in the form.

            curl_setopt_array($handle,
              array(
                 CURLOPT_URL => $url,
                 // Enable the post response.
                CURLOPT_POST       => true,
                // The data to transfer with the response.
                CURLOPT_RETURNTRANSFER     => true,
              )
            );

            $data = curl_exec($handle);

            curl_close($handle);

            $svr_info = json_decode($data, true);



           $zoho_api_url = $svr_info['locations'][$_REQUEST['location']];


            //print_r($ai_int);

	      //  $z_a_token = $ai_int->{'access_token'};
	      //  $r_token = $ai_int->{'refresh_token'};
            //print_r($svr_info->{'locations'});
            $params=['code'=>$secret_code, 'client_id'=>$sockets['i_token'], 'client_secret'=>$sockets['i_secret'], 'redirect_uri'=>'https://phone.1cxpro.com/phone', 'grant_type' => 'authorization_code'];
           

            $query = "UPDATE assigned_integration SET ai_tokenURL = '" . $zoho_api_url . "' WHERE i_id = " . $zoho_integration_id ." AND u_id = " . $_SESSION['1wire']['u_id'] . ";";
	        $sockets4 = db::getInstance()->get_result($query);

            $handle = curl_init();

            $url = $zoho_api_url . "/oauth/v2/token";
            //echo $url;

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

            $data = curl_exec($handle);

            curl_close($handle);

            $ai_int = json_decode($data);

            
            //print_r($ai_int);

	        $z_a_token = $ai_int->{'access_token'};
	        $r_token = $ai_int->{'refresh_token'};
            $z_api_url = $ai_int->{'api_domain'};

            
            //echo '..................' . $z_a_token;

            $url = $z_api_url . '/phonebridge/v3/users';

            $headers = ["Authorization: Zoho-oauthtoken " . $z_a_token];
            $handle = curl_init($url);
            curl_setopt_array($handle,
                array(
                    //CURLOPT_URL => $url,
                    // Enable the post response.
                CURLOPT_CUSTOMREQUEST => 'GET',
	            //CURLOPT_CUSTOMREQUEST => 'DELETE',
                // The data to transfer with the response.
                //CURLOPT_POSTFIELDS => $params,
                CURLOPT_RETURNTRANSFER     => true,
                )
            );

            curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

            $data = curl_exec($handle);
 
            curl_close($handle);

            //echo $data;

            //print_r($data);

            $arr = json_decode($data,true);

           // print_r($arr);


            $usr_results = array_filter($arr['users'], function($users) {
               $session_email = $_SESSION['1wire']['u_email'];

            //  echo '\nEMAIOL0' . $session_email;
              return $users['email'] == $session_email;
            });


            //print_r($usr_results);

            $user_key = array_shift(array_keys($usr_results));

            $z_user = $usr_results[$user_key]['zohouser'];

            $query = "UPDATE assigned_integration SET ai_accessToken = '" . $z_a_token . "', ai_apiURL = '" . $z_api_url . "', ai_refreshToken = '" . $r_token . "', ai_tempTokenUpdate = CURRENT_TIMESTAMP, ai_username = '" . $z_user . "' WHERE i_id = " . $zoho_integration_id ." AND u_id = " . $_SESSION['1wire']['u_id'] . ";";
	        echo $query;
            $sockets = db::getInstance()->get_result($query);
	        print_r($sockets);

            $base_url = "https://phone.1cxpro.com/phone/zoho.php";

            ///auth clicktodial

            $session_email = $_SESSION['1wire']['u_email'];


            $url = $z_api_url . '/phonebridge/v3/integrate';

            $headers = ["Authorization: Zoho-oauthtoken " . $z_a_token];
            $handle = curl_init();
            curl_setopt_array($handle,
                array(
                    CURLOPT_URL => $url,
                    // Enable the post response.
                CURLOPT_POST       => true,
	            //CURLOPT_CUSTOMREQUEST => 'DELETE',
                // The data to transfer with the response.
                CURLOPT_RETURNTRANSFER     => true,
                )
            );

            curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

            $data = curl_exec($handle);
            //print_r($data);
 
            curl_close($handle);	
   


            if ($zoho_active['click2dial'] == 1){
                $zoho_params = "[{name:pbxuserid,value:" . $z_user . "},{name:emailaddress,value:" . $session_email . "},{name:callee,value:'{to}'}]";

                $url = $z_api_url . '/phonebridge/v3/clicktodial';

                $headers = ["Authorization: Zoho-oauthtoken " . $z_a_token];
                $params = 'clicktodialuri=https://phone.1cxpro.com/phone/zoho/clicktodial.php&zohouser='. $z_user .'&clicktodialparam='. $zoho_params;
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
            } elseif ($zoho_active['click2dial'] == 0){
                $zoho_params = "[{name:pbxuserid,value:" . $z_user . "},{name:emailaddress,value:" . $session_email . "},{name:callee,value:'{to}'}]";

                $url = $z_api_url .'/phonebridge/v3/clicktodial';

                $headers = ["Authorization: Zoho-oauthtoken " . $z_a_token];
                $params = 'clicktodialuri=https://phone.1cxpro.com/phone/zoho/clicktodial.php&zohouser='. $z_user .'&clicktodialparam='. $zoho_params;
                $handle = curl_init();
                curl_setopt_array($handle,
                    array(
                        CURLOPT_URL => $url,
                        // Enable the post response.
                   // CURLOPT_POST       => true,
	                CURLOPT_CUSTOMREQUEST => 'DELETE',
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
     }elseif($_REQUEST['state']=='rft_connected' && $zoho_active['i_active'] == 1){
            $query = "select * from assigned_integration_short where ai_refreshToken = '" . $_REQUEST['rft'] . "' AND i_shortname = 'zoho';";
            //echo $query;
            $sockets = db::getInstance()->get_result($query);
            $z_api_url = $sockets['ai_apiURL'];
            $zoho_api_url = $sockets['ai_tokenURL'];
            $z_user = $sockets['ai_username'];
            if ($zoho_api_url <= "" || is_null($zoho_api_url)){
                $handle = curl_init();

                $url = "https://accounts.zoho.com/oauth/serverinfo";

                // Array with the fields names and values.
                // The field names should match the field names in the form.

                curl_setopt_array($handle,
                  array(
                     CURLOPT_URL => $url,
                     // Enable the post response.
                    CURLOPT_POST       => true,
                    // The data to transfer with the response.
                    CURLOPT_RETURNTRANSFER     => true,
                  )
                );

                $data = curl_exec($handle);

                curl_close($handle);

                $svr_info = json_decode($data, true);

          
                //print_r($ai_int);

	             $zoho_api_url = $svr_info['locations'][$_REQUEST['location']];


                 

                $query = "UPDATE assigned_integration SET ai_tokenURL = '" . $zoho_api_url . "' WHERE ai_refreshToken = '" . $_REQUEST['rft']. "';";
	            $sockets4 = db::getInstance()->get_result($query);

            }
            
           // $params=['code'=>$secret_code, 'client_id'=>$sockets['i_token'], 'client_secret'=>$sockets['i_secret'], 'redirect_uri'=>'https://phone.1cxpro.com/phone', 'grant_type' => 'authorization_code'];
            //print_r($params);
            $zoho_integration_id = $sockets['i_id'];
            $params=['refresh_token'=>$_REQUEST['rft'], 'client_id'=>$sockets['i_token'], 'client_secret'=>$sockets['i_secret'], 'grant_type' => 'refresh_token'];

            $handle = curl_init();

            $url = $zoho_api_url . "/oauth/v2/token";

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

            $data = curl_exec($handle);

            curl_close($handle);

            $ai_int = json_decode($data);

            
           // print_r($ai_int);

	        $z_a_token = $ai_int->{'access_token'};
           $z_api_url = $ai_int->{'api_domain'};
            

            $query = "UPDATE assigned_integration SET ai_accessToken = '" . $z_a_token . "', ai_apiURL = '" . $z_api_url . "', ai_tempTokenUpdate = CURRENT_TIMESTAMP WHERE i_id = " . $zoho_integration_id ." AND u_id = " . $_SESSION['1wire']['u_id'] . ";";
	        $sockets = db::getInstance()->get_result($query);
	       // print_r($sockets);

            $base_url = "https://phone.1cxpro.com/phone/zoho.php";

            ///auth clicktodial

            $session_email = $_SESSION['1wire']['u_email'];


            $url = $z_api_url . '/phonebridge/v3/integrate';

            $headers = ["Authorization: Zoho-oauthtoken " . $z_a_token];
            $handle = curl_init();
            curl_setopt_array($handle,
                array(
                    CURLOPT_URL => $url,
                    // Enable the post response.
                CURLOPT_POST       => true,
	            //CURLOPT_CUSTOMREQUEST => 'DELETE',
                // The data to transfer with the response.
                CURLOPT_RETURNTRANSFER     => true,
                )
            );

            curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

            $data = curl_exec($handle);
            //print_r($data);
 
            curl_close($handle);	
   


            if ($zoho_active['click2dial'] == 1){
                $zoho_params = "[{name:pbxuserid,value:" . $z_user . "},{name:emailaddress,value:" . $session_email . "},{name:callee,value:'{to}'}]";

                $url = $z_api_url . '/phonebridge/v3/clicktodial';

                $headers = ["Authorization: Zoho-oauthtoken " . $z_a_token];
                $params = 'clicktodialuri=https://phone.1cxpro.com/phone/zoho/clicktodial.php&zohouser='. $z_user .'&clicktodialparam='. $zoho_params;
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
            } elseif ($zoho_active['click2dial'] == 0){
                $zoho_params = "[{name:pbxuserid,value:" . $z_user . "},{name:emailaddress,value:" . $session_email . "},{name:callee,value:'{to}'}]";

                $url = $z_api_url .'/phonebridge/v3/clicktodial';

                $headers = ["Authorization: Zoho-oauthtoken " . $z_a_token];
                $params = 'clicktodialuri=https://phone.1cxpro.com/phone/zoho/clicktodial.php&zohouser='. $z_user .'&clicktodialparam='. $zoho_params;
                $handle = curl_init();
                curl_setopt_array($handle,
                    array(
                        CURLOPT_URL => $url,
                        // Enable the post response.
                   // CURLOPT_POST       => true,
	                CURLOPT_CUSTOMREQUEST => 'DELETE',
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

     }

            //echo $data;

            //print_r($data); //echo 'none';*/

            ///auth callcontrol
            $zoho_base = 'https://phone.1cxpro.com/phone/zoho/';



            if ($zoho_active['ai_active'] == 1){
               $zoho_params = "[{name:pbxuserid,value:" . $z_user . "},{name:emailaddress,value:" . $session_email . "},{name:callee,value:'{to}'}]";
              // echo $zoho_params;

                $answer_param = '&answeruri=' . $zoho_base . 'answer.php';
                $disconnect_param = '&hungupuri=' . $zoho_base . 'hungup.php';
                $mute_param = '&muteuri=' . $zoho_base . 'mute.php';
                $unmute_param = '&unmuteuri=' . $zoho_base . 'unmute.php';
                $hold_param = '&holduri=' . $zoho_base . 'hold.php';
                $unhold_param = '&unholduri=' . $zoho_base . 'unhold.php';
                $keypress_param = '&keypressuri=';

                $url = $z_api_url . '/phonebridge/v3/callcontrol';

                $headers = ["Authorization: Zoho-oauthtoken " . $z_a_token];
                $params = $zoho_params . $disconnect_param . $mute_param . $unmute_param . $hold_param . $unhold_param . $keypress_param;
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
            }elseif ($zoho_active['ai_active'] == 0) {
               $zoho_params = "[{name:pbxuserid,value:" . $z_user . "},{name:emailaddress,value:" . $session_email . "},{name:callee,value:'{to}'}]";
              // echo $zoho_params;

                $answer_param = '&answeruri=' . $zoho_base . 'answer.php';
                $disconnect_param = '&hungupuri=' . $zoho_base . 'hungup.php';
                $mute_param = '&muteuri=' . $zoho_base . 'mute.php';
                $unmute_param = '&unmuteuri=' . $zoho_base . 'unmute.php';
                $hold_param = '&holduri=' . $zoho_base . 'hold.php';
                $unhold_param = '&unholduri=' . $zoho_base . 'unhold.php';
                $keypress_param = '&keypressuri=';

                $url = $z_api_url . '/phonebridge/v3/callcontrol';

                $headers = ["Authorization: Zoho-oauthtoken " . $z_a_token];
                $params = $zoho_params . $disconnect_param . $mute_param . $unmute_param . $hold_param . $unhold_param . $keypress_param;
                $handle = curl_init();
                curl_setopt_array($handle,
                  array(
                     CURLOPT_URL => $url,
                     // Enable the post response.
                   // CURLOPT_POST       => true,
	                CURLOPT_CUSTOMREQUEST => 'DELETE',
                    // The data to transfer with the response.
                    CURLOPT_POSTFIELDS => $params,
                    CURLOPT_RETURNTRANSFER     => true,
                  )
                );

                curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

                $data = curl_exec($handle);
                print_r($data);
 
                curl_close($handle);	
           //  }


            //echo $data;

           // print_r($data); //echo 'none';*/
        }elseif(($_REQUEST['state']=='nrft_connected' || $_REQUEST['state']=='rft_connected') && $zoho_active['i_active'] == 0){
                $url = $z_api_url . '/phonebridge/v3/integrate';

                $headers = ["Authorization: Zoho-oauthtoken " . $z_a_token];
                $handle = curl_init();
                curl_setopt_array($handle,
                  array(
                     CURLOPT_URL => $url,
                     // Enable the post response.
                   // CURLOPT_POST       => true,
	                CURLOPT_CUSTOMREQUEST => 'DELETE',
                    // The data to transfer with the response.
                    CURLOPT_RETURNTRANSFER     => true,
                  )
                );

                curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

                $data = curl_exec($handle);
                print_r($data);
 
                curl_close($handle);	

        }


$query="SELECT * FROM integrations WHERE `c_id` = " . $_SESSION['1wire']['c_id'] . " AND `i_shortname` LIKE 'phone';";
$sockets = db::getInstance()->get_result($query);
//print_r($sockets);

$query="SELECT * FROM user WHERE `u_id` = " . $_SESSION['1wire']['u_id'] . ";";
$sockets2 = db::getInstance()->get_result($query);

$phone_integration_id = $sockets['i_id'];

$url = 'https://pbx.1wire.co/ns-api/oauth2/token/';
$params=['grant_type'=>'password', 'client_id'=>''. $sockets['i_token'] . '', 'client_secret'=>''. $sockets['i_secret'] . '','username'=>''. $pbx_user. '','password'=>''. $pbx_pass . ''];

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

$query = "UPDATE assigned_integration SET ai_accessToken = '$n_a_token', ai_refreshToken = '$n_r_token', ai_tempTokenUpdate = CURRENT_TIMESTAMP WHERE i_id = " . $phone_integration_id ." AND u_id = " . $_SESSION['1wire']['u_id'] . ";";
$sockets = db::getInstance()->get_result($query);
//print_r($sockets);
?>
</pre>    
<script>
        var urltmp = '../softphone.html?isdemopage=false';

        if (open_in_new_tab === true)
        {
            window.open(urltmp);
            return;
        }

        var ifrm = ifrm_template.replace('[SOURCE]', urltmp);
        ifrm = ifrm.replace('[NAME]', 'softphone');
        ifrm = ifrm.replace('[ID]', 'softphone');
        ifrm = ifrm.replace('[WIDTH]', '350');
        ifrm = ifrm.replace('[HEIGHT]', '640');


        var txt = '<span style="font-family: Tahoma, Arial ; font-size: 16px; color:#4eaaec; text-alignalign:left">Softphone</span><br><br><div>A customizable turn-key browser softphone implementation. <br />You can use it as-is or customize via the various webphone parameters <br />(or change its html/css/js after your needs if you are a JavaScript developer).</div><br />';
        $("#alternate_content").html(txt + ifrm);
        $("#alternate_content").show();
</script>
<center>
<iframe allow="microphone; camera; autoplay" allowfullscreen="true" frameborder="0" width="350" height="540" src="../softphone.html" name="softphone" id="softphone" class="frame_conatiner" scrolling="no"></iframe>

</center>
<script>

        webphone_api.onAppStateChange(function (state)
        {
            if (state === 'loaded') {
                
            var base_url = '<?php echo $base_url; ?>';
            var email = 'brandon@at1ts.com';
            

           // alert(base_url + email);
                webphone_api.setparameter('serveraddress', '<?php echo $wp_domain; ?>');
               webphone_api.setparameter('username', '<?php echo $wp_user; ?>');
			    webphone_api.setparameter('password', '<?php echo $wp_pass; ?>');


                webphone_api.setparameter('c_id', <?php echo $_SESSION['1wire']['c_id']; ?>); 
                webphone_api.setparameter('u_id', <?php echo $_SESSION['1wire']['u_id']; ?>); 

               
			   // webphone_api.setparameter('access_token', access_t);

               webphone_api.register();

               webphone_api.start();
			    //var mystringvariable = webphone_api.getparameter('webrtcserveraddress');

			    //alert(mystringvariable);
                //var acode = webphone_api.getparameter('access_token');
               
		   }
	    });



</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="<?= APP_URL; ?>/js/your_script.js" type="text/javascript"></script>
<script src="<?= APP_URL; ?>/js/click_btn/click_btn.js" type="text/javascript"></script>
<script src="<?= APP_URL; ?>/js/common_f.js" type="text/javascript"></script>
<script>
    var u_id_login='<?php echo $_SESSION['1wire']['u_id'];?>';
    var ai_id_login='<?php echo $_SESSION['1wire']["integration_ass"]["ai_id"];?>';
    var i_id_login='<?php echo $_SESSION['1wire']["integration_ass"]["i_id"];?>';
    var i_shortname_login='<?php echo $_SESSION['1wire']["integration_ass"]["i_shortname"];?>';

    $('#show-dropdown').unbind('click').bind('click',function(){
        if($(this).closest('.show-dropdown').hasClass('show')){
            $('.show-dropdown').removeClass('show')
        }else{
            $('.show-dropdown').addClass('show')
        }
    })

    $('#click2dial-active').unbind('click').bind('click',function(){
        var click2dial_active = $(this).is(":checked")
        var zoho_active = $('#zoho-active').is(":checked")
        click_btn.prototype.setup_session(click2dial_active,zoho_active)
        common_f.prototype.update_asg_intg(ai_id_login,i_id_login,u_id_login,zoho_active,click2dial_active)
        if(!click2dial_active){
            click_btn.prototype.click_click2dial(click2dial_active,u_id_login,ai_id_login,i_id_login,i_shortname_login)
        }

    })

    $('#zoho-active').unbind('click').bind('click',function(){
        var click2dial_active = $('#click2dial-active').is(":checked")
        var zoho_active = $(this).is(":checked")
        click_btn.prototype.setup_session(click2dial_active,zoho_active)
        common_f.prototype.update_asg_intg(ai_id_login,i_id_login,u_id_login,zoho_active,click2dial_active)
        //click_btn.prototype.click_zoho(zoho_active,u_id_login,ai_id_login,i_id_login,i_shortname_login)
    })

</script>
</body>
</html>
