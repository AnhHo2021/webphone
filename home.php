<!DOCTYPE html>

<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
require('db.php');
$u_mail = $_REQUEST['user'];

$query = "select * from assigned_integration_short where u_email = '$u_mail' AND i_shortname = 'zoho';";
//echo $query;
$sockets = db::getInstance()->get_result($query);
///print_r($sockets);
if($sockets['ai_refreshToken'] <= ""){

//Need address login type. i.e. phone, zoho, etc.
	header('Location: https://accounts.zoho.com/oauth/v2/auth?scope=PhoneBridge.call.log,PhoneBridge.zohoone.search&client_id='. $sockets['i_token'].'&redirect_uri='. $sockets['i_redirectURL'] .'&state=nrft_connected&response_type=code&access_type=offline');
}else{
    header('Location: https://phone.1cxpro.com/phone/index.php?state=rft_connected&rft=' . $sockets['ai_refreshToken']);
}
?>
<!--  
This is just a start page to access the various WebPhone skins and samples.
There is nothing to be seen here; this index.html was created only for your convenience to easily open the various webphone skins/samples.
-->

<html lang="en" >
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Webphone usage</title>
    <meta name="author" content="Mizutech"/>
    <style>
                #js_not_enabled { float: left; clear: both; width: 100%; height: 100%; background: #1d1d1d; color: #f00; text-transform: none; text-align: center; padding: 10px; font-size: 1.2em; }
        #header_container {
        background-image:linear-gradient(to top, #cde9f7 0%, #a2cfe6 51%);
        background-image:-webkit-linear-gradient(top, #cde9f7 0%, #a2cfe6 51%);
        background-image:-moz-linear-gradient(top, #cde9f7 0%, #a2cfe6 51%);
        background-image:-ms-linear-gradient(top, #cde9f7 0%, #a2cfe6 51%);
        background-image:-webkit-gradient(
            linear,
            left top,
            left bottom,
            color-stop(0, #cde9f7),
            color-stop(0.51, #a2cfe6)
        );
        }
        body { margin: auto; padding: 0; font-family: Verdana; font-size: 12px; color:#2e3d47; text-align: center; background: #ffffff; }
        iframe{ overflow:hidden; border:none; }
        #header_container { width: 100%; margin: 0; padding-top: 1em; padding-bottom: 1em; height: 2.2em; }
        #header_container h1 { margin: 0; padding: 0; font-size: 1.9em; }
        #footer { display: inline-block; clear: both; width: 100%; 
            background-image:linear-gradient(to top, #cde9f7 0%, #fff 100%);
            background-image:-webkit-linear-gradient(top, #cde9f7 0%, #fff 100%);
            background-image:-moz-linear-gradient(top, #cde9f7 0%, #fff 100%);
            background-image:-ms-linear-gradient(top, #cde9f7 0%, #fff 100%);
            background-image:-webkit-gradient(
                linear,
                left top,
                left bottom,
                color-stop(0, #cde9f7),
                color-stop(0, #cde9f7),
                color-stop(1, #fff)
            );
        }
        #footer_cp { display: block; float: right; font-size: .75em; }
        #footer_content { display: inline-block; width: 100%; margin: .5em 1.5em 1em 1.5em; text-align: left; line-height: 130%; font-size: .9em; }
        #footer_links_container { display: block; width: 100%; float: left; text-align: center; margin-top: .5em; }
        .footer_links { display: inline-block; margin-left: .3em; margin-right: .3em; }
        #main_container { line-height: 155%; width: 900px; margin: auto; display: inline-block;
                          /*background: #ecf3f8; border-right: solid 1px #a2cfe6; */
                          background: #ffffff;
        }
        #left { width: 150px; margin-right: 20px; float: left;  text-align: left; background: #ffffff; }
        #content { float: left; text-align: center; width: 700px; background: #ffffff; margin-bottom: 3em; margin-top: .5em; }
        #home_content, #api_content, #url_content { text-align: left; }
        .clear { width: 100%; height: 1px; float: left; clear: both; }
        #left_menu_list { float: left; width: 160px;  margin: 0; padding: 0; }
        #left_menu_list .a_menu{
            display: inline-block; width: 140px; padding-left: .5em; background: #ffffff; text-decoration: none; font-size: 1.15em; font-weight: bold; line-height: 1.7em;
            color: #4ea6ed; margin-top: 2px;
        }
        #left_menu_list .a_menu:hover { background: #abd4ea;   }
        .link_list { display: inline-block; float: left; margin: 0em 0em 2em 0em; padding-left: 0em; }
        .link_list LI { display: inline-block; float: left; clear: both; margin-bottom: .7em; }
        .jscode { color: #111; font-size: 1em; }
        .code_html_tag { color: #0000e6; }
        .code_html_commnet { color: #969696; }
        .code_js_quotes { color: #ce7b00; }
        .link { color: #2964d2; font-size: 12px; }
        .frame_conatiner { overflow: hidden; }
    </style>
    <!--
    <script src="js/techdemo_example/jquery-3.6.0.min.js"></script>

    -->
    <script src="js/techdemo_example/jquery-1.9.1.min.js"></script>
    <script>
window.onload = function ()
{
    MeasurePage();
    HandlePage();
};

var timerid;
$( window ).resize(function() // window resize handling
{
    if ( typeof (timerid) !== 'undefined' && timerid !== null ) { clearTimeout(timerid); }
    timerid = setTimeout(function ()
    {
        MeasurePage();
    }, 100);
});

function HandlePage()
{
    var url = document.location.href;
    if (typeof (url) === 'undefined' || url === null) { return; }
    var pos = url.lastIndexOf('#');
    if (pos < 1) { return; }
    var pageid = url.substring(pos + 1);
    if (typeof (pageid) === 'undefined' || pageid === null) { return; }
    
    switch (pageid)
    {
        case 'basic_example':
            MenuHandler('basic');
            break;
        case 'techdemo_example':
            MenuHandler('techdemo');
            break;
        case 'softphone':
            MenuHandler('softphone');
            break;
        case 'mobile_example':
            MenuHandler('mobile');
            break;
        case 'click2call':
            MenuHandler('click2call');
            break;
        case 'linkify_example':
            MenuHandler('linkify_example');
            break;
        case 'api_example':
            MenuHandler('api');
            break;
        case 'htmlurl':
            MenuHandler('htmlurl');
            break;
        case 'more':
            MenuHandler('manymore');
            break;
    }
}

function MeasurePage()
{
    $("#content").width( $("#main_container").width() - $("#left").width() - 40 );
    $("#left").height( $("#content").height() );
}

function MenuHandler(id)
{
    if (typeof (id) === 'undefined' || id.length < 1) { return; }
    
    var open_in_new_tab = false;
// skin does not work in IE8 in iframe, so open it in new page
    if (IsIeVersion3(8) === true && (id === 'techdemo' || id === 'basic' || id === 'softphone' || id === 'mobile' || id === 'click2call' || id === 'basic_example')) { open_in_new_tab = true; }
    
    if (open_in_new_tab !== true)
    {
        $("#home_content").hide();
        $("#alternate_content").html('');
        $("#alternate_content").hide();
        $("#api_content").hide();
        $("#url_content").hide();
        $("#manymore").hide();
    }
    
    
    var ifrm_template = '<iframe allow="microphone; camera; autoplay" allowfullscreen="true" frameborder="0" width="[WIDTH]" height="[HEIGHT]" src="[SOURCE]" name="[NAME]" id="[ID]" class="frame_conatiner" scrolling="no"></iframe>';
    
    if (id === 'home')
    {
        $("#home_content").show();
    }
    else if (id === 'techdemo')
    {
        var urltmp = 'samples/techdemo_example.html?isdemopage=true';
        if (open_in_new_tab === true)
        {
            window.open(urltmp);
            return;
        }

        var ifrm = ifrm_template.replace('[SOURCE]', urltmp);
        ifrm = ifrm.replace('[NAME]', 'techdemo');
        ifrm = ifrm.replace('[ID]', 'techdemo');
        ifrm = ifrm.replace('[WIDTH]', '100%');
        ifrm = ifrm.replace('[HEIGHT]', '620');
        
        $("#alternate_content").html(ifrm);
        $("#alternate_content").show();
    }
    else if (id === 'basic')
    {
        var urltmp = 'samples/basic_example.html?isdemopage=true';
        if (open_in_new_tab === true)
        {
            window.open(urltmp);
            return;
        }

        var ifrm = ifrm_template.replace('[SOURCE]', urltmp);
        ifrm = ifrm.replace('[NAME]', 'basic');
        ifrm = ifrm.replace('[ID]', 'basic');
        ifrm = ifrm.replace('[WIDTH]', '100%');
        ifrm = ifrm.replace('[HEIGHT]', '350');
        
        $("#alternate_content").html(ifrm);
        $("#alternate_content").show();
    }
    else if (id === 'softphone')
    {
        var urltmp = 'softphone.html?isdemopage=true';
        if (open_in_new_tab === true)
        {
            window.open(urltmp);
            return;
        }
        
        var ifrm = ifrm_template.replace('[SOURCE]', urltmp);
        ifrm = ifrm.replace('[NAME]', 'softphone');
        ifrm = ifrm.replace('[ID]', 'softphone');
        ifrm = ifrm.replace('[WIDTH]', '350');
        ifrm = ifrm.replace('[HEIGHT]', '540');
        
        var txt = '<span style="font-family: Tahoma, Arial ; font-size: 16px; color:#4eaaec; text-alignalign:left">Softphone</span><br><br><div>A customizable turn-key browser softphone implementation. <br />You can use it as-is or customize via the various webphone parameters <br />(or change its html/css/js after your needs if you are a JavaScript developer).</div><br />';
        $("#alternate_content").html(txt + ifrm);
        $("#alternate_content").show();
    }
    else if (id === 'mobile')
    {
        var urltmp = 'samples/mobile_example.html?isdemopage=true';
        if (open_in_new_tab === true)
        {
            window.open(urltmp);
            return;
        }

        var ifrm = ifrm_template.replace('[SOURCE]', urltmp);
        ifrm = ifrm.replace('[NAME]', 'mobile');
        ifrm = ifrm.replace('[ID]', 'mobile');
        ifrm = ifrm.replace('[WIDTH]', '350');
        ifrm = ifrm.replace('[HEIGHT]', '780');
        
        $("#alternate_content").html(ifrm);
        $("#alternate_content").show();
    }
    else if (id === 'click2call')
    {
        var urltmp = 'click2call.html?isdemopage=true';
        if (open_in_new_tab === true)
        {
            window.open(urltmp);
            return;
        }

        var ifrm = ifrm_template.replace('[SOURCE]', urltmp);
        ifrm = ifrm.replace('[NAME]', 'click2call');
        ifrm = ifrm.replace('[ID]', 'click2call');
        ifrm = ifrm.replace('[WIDTH]', '100%');
        ifrm = ifrm.replace('[HEIGHT]', '630');
        
        var extrahtml = '';
       /*
        var extrahtml = 'These configurations can be set in the .html source, but just for this online demo, you can enter the configuration below:<br /><br />' +
                        '<input type="text" placeholder="VoIP Server address" id="c2c_serveraddress"><br />' +
                        '<input type="text" placeholder="Username" id="c2c_username"><br />' +
                        '<input type="text" placeholder="Password" id="c2c_password"><br />' +
                        '<input type="text" placeholder="Callto" id="c2c_callto"><br />';
       */
        $("#alternate_content").html(ifrm + extrahtml);
        $("#alternate_content").show();
        /*
        setTimeout(function ()
        {
            var ifrm = document.getElementById('click2call');
            var iwindow = ifrm.contentWindow || ifrm;
            
            if (typeof (iwindow) === 'undefined' || iwindow === null) { return; }
            
            var c2c_serveraddress_input = document.getElementById('c2c_serveraddress');
            var c2c_username_input = document.getElementById('c2c_username');
            var c2c_password_input = document.getElementById('c2c_password');
            var c2c_destination_input = document.getElementById('c2c_callto');
            
            var serveraddress = iwindow.webphone_api.getparameter('serveraddress');
            var username = iwindow.webphone_api.getparameter('username');
            var password = iwindow.webphone_api.getparameter('password');
            var destination = iwindow.webphone_api.getparameter('destination');
            
            if (serveraddress.length > 0) { c2c_serveraddress_input.value = serveraddress; }
            if (username.length > 0) { c2c_username_input.value = username; }
            if (password.length > 0) { c2c_password_input.value = password; }
            if (destination.length > 0) { c2c_destination_input.value = destination; }
        }, 5000);*/
    }
    else if (id === 'linkify_example')
    {
        var ifrm = ifrm_template.replace('[SOURCE]', 'samples/linkify_example.html');
        ifrm = ifrm.replace('[NAME]', 'linkify_example');
        ifrm = ifrm.replace('[ID]', 'linkify_example');
        ifrm = ifrm.replace('[WIDTH]', '100%');
        ifrm = ifrm.replace('[HEIGHT]', '300');
        
        $("#alternate_content").html(ifrm);
        $("#alternate_content").show();
    }
    else if (id === 'basic_example')
    {
        var urltmp = 'samples/basic_example.html';
        if (open_in_new_tab === true)
        {
            window.open(urltmp);
            return;
        }

        var ifrm = ifrm_template.replace('[SOURCE]', urltmp);
        ifrm = ifrm.replace('[NAME]', 'basic_example');
        ifrm = ifrm.replace('[ID]', 'basic_example');
        ifrm = ifrm.replace('[WIDTH]', '100%');
        ifrm = ifrm.replace('[HEIGHT]', '350');
        
        $("#alternate_content").html(ifrm);
        $("#alternate_content").show();
    }
    else if (id === 'api')
    {
        $("#api_content").show();
    }
    else if (id === 'htmlurl')
    {
        $("#url_content").show();
    }
    else if (id === 'manymore')
    {
        $("#manymore").show();
    }
	
    else if (id === 'php_example')
    {
        var ifrm = ifrm_template.replace('[SOURCE]', 'samples/php_example.html');
        ifrm = ifrm.replace('[NAME]', 'php_example');
        ifrm = ifrm.replace('[ID]', 'php_example');
        ifrm = ifrm.replace('[WIDTH]', '100%');
        ifrm = ifrm.replace('[HEIGHT]', '700');
		
		ifrm = ifrm.replace('class="frame_conatiner"', '');
        ifrm = ifrm.replace('scrolling="no"', '');
        
        $("#alternate_content").html(ifrm);
        $("#alternate_content").show();
    }
    else if (id === 'callcenter')
    {
        var ifrm = ifrm_template.replace('[SOURCE]', 'samples/callcenter.html');
        ifrm = ifrm.replace('[NAME]', 'callcenter');
        ifrm = ifrm.replace('[ID]', 'callcenter');
        ifrm = ifrm.replace('[WIDTH]', '120%');
        ifrm = ifrm.replace('[HEIGHT]', '700');
		
		ifrm = ifrm.replace('class="frame_conatiner"', '');
        ifrm = ifrm.replace('scrolling="no"', '');
        
        $("#alternate_content").html(ifrm);
        $("#alternate_content").show();
    }
	else if (id === 'many')
    {
        var ifrm = ifrm_template.replace('[SOURCE]', 'samples/more_example.html');
        ifrm = ifrm.replace('[NAME]', 'many');
        ifrm = ifrm.replace('[ID]', 'many');
        ifrm = ifrm.replace('[WIDTH]', '100%'); 
        ifrm = ifrm.replace('[HEIGHT]', '700');
		
		ifrm = ifrm.replace('class="frame_conatiner"', '');
        ifrm = ifrm.replace('scrolling="no"', '');
        
        $("#alternate_content").html(ifrm);
        $("#alternate_content").show();
    }
    /*
    setTimeout(function ()
    {
        MeasurePage();
    }, 100);*/
}
function IsIeVersion3 (version) // :boolean  check if it is IE browser version xxx
{
    try{
    if (typeof(version) === 'undefined' || version === null) { return false; }

    var agent = navigator.userAgent;
    var msie = agent.indexOf('MSIE ');
    var currver = 0;
    if (msie > 0)
    {
        // IE 10 or older => return version number
        currver = parseInt(agent.substring(msie + 5, agent.indexOf('.', msie)), 10);
        if (version === currver) { return  true; }
    }
    } catch(err) { ; }
    return false;
}
</script>
</head>
<body link="#0000ff" dir="ltr" spellcheck="false">

<div id="js_not_enabled">Enable JavaScript or click the &quot;Allow&quot; button in your browser!!!</div>
<script type="text/javascript">document.getElementById('js_not_enabled').style.display = 'none'; try{ document.getElementById('phone_app_main_container').style.display = 'none'; } catch(err) {  }</script>
<!-- <div id="header_container">
    <h1 class="western" align="center">Mizutech Webphone</h1>
</div> -->
<div id="main_container">
    <div id="left">
        <div id="left_menu_list">
            <a href="javascript:;" class="a_menu" onclick="MenuHandler('home')">Home</a><br /><br />
            <a href="javascript:;" class="a_menu" onclick="MenuHandler('basic')">Basic</a>
            <a href="javascript:;" class="a_menu" onclick="MenuHandler('techdemo')" style="padding-left: .4em;">Tech demo</a>
            <a href="javascript:;" class="a_menu" onclick="MenuHandler('softphone')" style="padding-left: .4em;">Softphone</a>
            <a href="javascript:;" class="a_menu" onclick="MenuHandler('mobile')" style="padding-left: .4em;">Mobile</a>
            <a href="javascript:;" class="a_menu" onclick="MenuHandler('click2call')">Click to call</a>
            <a href="javascript:;" class="a_menu" onclick="MenuHandler('linkify_example')">Linkify</a>
            <a href="javascript:;" class="a_menu" onclick="MenuHandler('api')">API</a>
            <a href="javascript:;" class="a_menu" onclick="MenuHandler('htmlurl')">HTML URL</a>
			<!-- <a href="javascript:;" class="a_menu" onclick="MenuHandler('php_example')">PHP</a>
			<a href="javascript:;" class="a_menu" onclick="MenuHandler('callcenter')">Call center</a> -->
			<a href="javascript:;" class="a_menu" onclick="MenuHandler('manymore')">More</a>
			<!--
            <br /><br /><br />
            <a href="https://www.mizu-voip.com/Software/WebPhone.aspx" class="a_menu" target="_blank">Homepage</a>
            <a href="https://www.mizu-voip.com/Portals/0/Files/Webphone_Documentation.pdf" class="a_menu" target="_blank">Documentation</a>
            <a href="https://www.mizu-voip.com/Portals/0/Files/webphone.zip" class="a_menu" target="_blank">Download</a>
			-->
        </div>
    </div>
    <div id="content" align="left">
        
        <!--<h3 class="western" align="center"><font size="3" style="font-size: 16pt">Usage examples</font></h3><br />-->
        <div id="home_content">
		<div style="font-family: Tahoma, Arial ; font-size: 16px; color:#4eaaec; text-align:center">Home</div><br>
		
            The Mizutech <a href="https://www.mizu-voip.com/Software/WebPhone.aspx" target="_blank">webphone</a>  is a SIP client for browsers, implementing multiple engines
to take advantage of the best available client-side VoIP
technology across the majority of OS and browsers, including Java
Applet, HTML5/WebRTC, Native Plugin service, Flash and others covered
by a simple to use universal API and customizable user interface templates.
<br /><br />
This is a simple index page with direct links to the examples shipped with the webphone package. You are free to use any of the included solutions, modify/customize each of the supplied HTML/CSS code or implement your VoIP client from scratch by providing your own user interface (or no user interface at all) using the webphone's Java Script API.
<br />
Try out the solutions/examples from the left side to quickly check the webphone functionalities. This page is part of the <a href="https://www.mizu-voip.com/Portals/0/Files/webphone.zip">downloadable webphone package</a>. Check the source codes for more details (softphone.html and the html files in the samples folder).
<br /><br /> 
If you don't have a SIP server or VoIP account yet, you can use our <a href="https://www.mizu-voip.com/Services/VoIPServices.aspx" target="_blank">VoIP service</a>:
<ul class="link_list3" style="list-style:none">
<li>- Server address: voip.mizu-voip.com</li>
<li>- Account: create <a href="https://voip.mizu-voip.com/?function=newuser" target="_blank">free VoIP account from here</a> or use the following username/passwords: webphonetest1/webphonetest1, webphonetest2/webphonetest2 (others might also use these public accounts so calls might be misrouted)</li>
<li>- Call: You can call the "testivr3" access number to hear a music or register with both accounts and call each others (you can also use one account for webphone and the other one for a regular SIP client)</li>
</ul>


            <div class="clear"><!--//--></div>
            
            <div class="clear"><!--//--></div>
            <ul class="link_list">
				<li><a href="javascript:;" onclick="MenuHandler('basic')">Basic</a>: simplest usage example (not for production) [basic_example.html]</li>
				<li><a href="javascript:;" onclick="MenuHandler('techdemo')">Tech Demo</a>: try out the webphone by using this simple demo. You can also use this as a starting template for your custom development [techdemo_example.html / techdemo_example.js]</li>
				<li><a href="javascript:;" onclick="MenuHandler('softphone')">Softphone</a>: implements a full featured VoIP softphone in browser which can be used as is [softphone_launch.html/softphone.html/softphone folder]</li>	
				<li><a href="javascript:;" onclick="MenuHandler('mobile')">Mobile</a>: the webphone works also on mobile. This is just a show-case [mobile_example.html]</li>
                <li><a href="javascript:;" onclick="MenuHandler('click2call')">Click to call</a>: click to call button [click2call.html / click2call.js]</li>
                <li><a href="javascript:;" onclick="MenuHandler('linkify_example')">Linkify</a>: convert all phone number like strings on your website to click to call links [linkify_example.html / linkify.js]</li>
				<li><a href="javascript:;" onclick="MenuHandler('api')">API</a>: use the "webphone_api.js" file. Check the examples and the documentation for the usage.</li>
				<li><a href="javascript:;" onclick="MenuHandler('htmlurl')">HTML URL</a>: the webphone can load its settings also from the webpage URL and perform various actions such as initiate a call.</li>
				<!-- <li><a href="javascript:;" onclick="MenuHandler('php_example')">PHP</a>: the webphone can be integrated easily into a PHP based website</li>
				<li><a href="javascript:;" onclick="MenuHandler('callcenter')">Call center</a>: webphone usage for callcenters</li> -->
				<li>There are <a href="javascript:;" onclick="MenuHandler('manymore')">many other</a> ways to use the webphone.</li>
            </ul>
            <br><br>
            <div class="clear"><!--//--></div>			
			<br /><br />
			For more details <a href="https://www.mizu-voip.com/Portals/0/Files/webphone.zip" target="_blank">download</a> or check the <a href="https://www.mizu-voip.com/Portals/0/Files/Webphone_Documentation.pdf" target="_blank">documentation</a>.
			<br /><br />
            <div class="clear"><!--//--></div>
        </div>
        <div id="alternate_content" style="display: none;"></div>
        <div id="api_content" style="display: none;">
<div style="font-family: Verdana ; font-size: 16px; color:#4eaaec; text-align:center">JavaScript API</div><br><br>            
The public JavaScript API can be found in "webphone_api.js" file, under global javascript namespace "webphone_api".
To be able to use the webphone as a javascript VoIP library, just copy the webphone folder to your web project and add the webphone_api.js to your page.
Simple example:<br /><br /><br />
            <div class="jscode">
                <span class="code_html_tag">&lt;head&gt;</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code_html_commnet">&lt;!--include the webphone_api.js to your webpage--&gt;</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code_html_tag">&lt;script src=</span><span class="code_js_quotes">"webphone_api.js"</span><span class="code_html_tag">&gt;&lt;/script&gt;</span><br />
<span class="code_html_tag">&lt;/head&gt;</span><br />
<span class="code_html_tag">&lt;body&gt;</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code_html_tag">&lt;script&gt;</span><br />

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code_html_commnet">// IMPORTANT: Wait until the webphone is loaded, before calling any API functions</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;webphone_api.onAppStateChange(function (state) {<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if (state === <span class="code_js_quotes">'loaded'</span>) {<br /><br />

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code_html_commnet">//set parameters (alternatively these can be also preset in your html)</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;webphone_api.setparameter(<span class="code_js_quotes">'serveraddress'</span>, SERVERADDRESS); <span class="code_html_commnet">// Replace the word in uppercase with your SIP server IP or domain name (add also the :port number if not using the standard UDP 5060)</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;webphone_api.setparameter(<span class="code_js_quotes">'username'</span>, USERNAME); <span class="code_html_commnet">// Replace the word in uppercase with a valid SIP extension username</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;webphone_api.setparameter(<span class="code_js_quotes">'password'</span>, PASSWORD); <span class="code_html_commnet">// Replace the word in uppercase with the password for the above user</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code_html_commnet">//see the  Parameters  section in the documentation for more options</span><br /><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code_html_commnet">//start the webphone (optional but recommended)</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;webphone_api.start();<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code_html_commnet">//make a call (usually initiated by user action, such as click on a click to call button)</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;webphone_api.call(number);<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code_html_commnet">//hangup</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;webphone_api.hangup();<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code_html_commnet">//send instant message</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;webphone_api.sendchat(number, message);<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;});<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code_html_tag">&lt;/script&gt;</span><br />
<span class="code_html_tag">&lt;/body&gt;</span>
<br /><br /><br />
For more details consult the Javascript API section in the <a href="https://www.mizu-voip.com/Portals/0/Files/Webphone_Documentation.pdf" target="_blank">documentation</a>.
<br />
            </div>
        </div>
        <div id="url_content" style="display: none;">
		<div style="font-family: Verdana; font-size: 16px; color:#4eaaec; text-align:center">HTML URL</div><br><br>
The webphone can load its settings also from the webpage URL and perform various actions such as initiate a call. All the documented parameters can be used, prefixed
with  wp_ .<br /><br />
Example to trigger a call with the softphone by html url parameters:<br />
<span class="link">http://www.yourwebsite.com/webphonedir/softphone.html?wp_serveraddress=YOURSIPDOMAIN&wp_username=USERNAME&wp_password=PASSWORD&wp_callto=CALLEDNUMBER</span><br /><br />
Example to trigger a call with the clicktocall by html url parameters:<br />
<span class="link">http://www.yourwebsite.com/webphonedir/click2call.html?wp_serveraddress=YOURSIPDOMAIN&wp_username=USERNAME&wp_password=PASSWORD&wp_callto=CALLEDNUMBER</span><br /><br />
A working example using the click to call button:<br />
<a href="https://www.webvoipphone.com/webphone_online_demo/click2call.html?wp_serveraddress=voip.mizu-voip.com&wp_username=webphonetest1&wp_password=webphonetest1&wp_callto=testivr3&wp_autoaction=1" target="_blank">https://www.webvoipphone.com/webphone_online_demo/click2call.html?wp_serveraddress=voip.mizu-voip.com&wp_username=webphonetest1&wp_password=webphonetest1&wp_callto=testivr3&wp_autoaction=1</a>
<br /><br />
A working example using the softhone skin:<br />
<a href="https://www.webvoipphone.com/webphone_online_demo/softphone.html?wp_serveraddress=voip.mizu-voip.com&wp_username=webphonetest1&wp_password=webphonetest1&wp_callto=testivr3&wp_autoaction=1" target="_blank">https://www.webvoipphone.com/webphone_online_demo/samples/click2call.html?wp_serveraddress=voip.mizu-voip.com&wp_username=webphonetest1&wp_password=webphonetest1&wp_callto=testivr3&wp_autoaction=1</a>
<br /><br />
Example trigger chat by html parameters<br />
<span class="link">http://www.yourwebsite.com/webphonedir/softphone.html?wp_serveraddress=YOURSIPDOMAIN&wp_username=USERNAME&wp_password=PASSWORD&wp_sendchat=TEXT&wp_to=DESTINATION</span><br /><br />
Note: you should use clear text password only if the account is locked on your server (can t call costly outside numbers). Otherwise you should pass it encrypted or
use MD5 instead.<br /><br />
        </div>
    
	
	<div id="manymore" style="display: none;">
		<div style="font-family: Verdana; font-size: 16px; color:#4eaaec; text-align:center">Many more</div><br><br>
<div style="text-align:left">There are many other ways to use the webphone. See the "Usage examples" on the <a href="https://www.mizu-voip.com/Software/WebPhone.aspx" target="_blank">home page</a> for some suggestions. 
	You can create your own solutions for scratch using the WebPhone API or modify the existing examples after your needs. 
	There is no need for any JavaScipt or other development knowledge if you just need a web softphone or click to call solution for your website. (Use the "softphone.html" or the "click2call.html" as-is or customize them after your needs viat the webphone parameters which you can set in the "webphone_config.js" file).
	<br />
	You can also find various other examples from the webphone documentation. For example <a href="https://www.mizu-voip.com/Portals/0/Files/voicerechandler.txt" target="_blank">php voice recording upload</a>,  <a href="https://www.mizu-voip.com/Portals/0/Files/SalesforceWebPhone.pdf" target="_blank">salesforce web sip client integration</a> or see the "multipage_example.html" about using a single webphone instances across all web pages.
	<br />
	For a quick tutorial, check <a href="https://www.mizu-voip.com/Support/Wiki/tabid/99/Default.aspx?topic=WebPhone+Quick+Start" target="_blank">here</a>.
	
	<br />
	<br />
	The rest is your imagination.
<br>

</div>
</div>
</div>
</div>
<!-- <div id="footer">
    <div id="footer_content">
        Problems running the demo? Send an email to <a href="mailto:webphone@mizu-voip.com?subject=Webphone">webphone@mizu-voip.com</a> with a 
        short description about the problem/question with the <br /> <a href="https://www.mizu-voip.com/Support/Wiki/tabid/99/Default.aspx?topic=Webphone+logs" target="_blank">browser console logs</a> about your attempt and we will check it asap. If possible please send us also a test account valid on your SIP server.
        <br /><br />
        <div id="footer_links_container">
            <span class="footer_links"><a href="https://www.mizu-voip.com/" target="_blank">Mizutech</a></span>|
            <span class="footer_links"><a href="https://www.mizu-voip.com/Software/WebPhone.aspx" target="_blank">Webphone</a></span>|
            <span class="footer_links"><a href="https://www.mizu-voip.com/Portals/0/Files/Webphone_Documentation.pdf" target="_blank">Documentation</a></span>|
            <span class="footer_links"><a href="https://www.mizu-voip.com/Portals/0/Files/webphone.zip" target="_blank">Download</a></span>|
            <span class="footer_links"><a href="https://www.mizu-voip.com/Support.aspx" target="_blank">Support</a></span>|
            <span class="footer_links"><a href="https://www.mizu-voip.com/Company/Contact.aspx" target="_blank">Contact</a></span>
        </div>
    </div>
    <div id="footer_cp">Copyright (c) 2017 Mizutech S.R.L.</div>
</div>
</div> -->
</body>
</html>
