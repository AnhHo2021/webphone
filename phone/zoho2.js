  
        //start interacting with the webphone only once the onAppStateChange callback has been fired with the “loaded” event.
//        webphone_api.onAppStateChange(function (state)
//        {
//                if (state === 'loaded') //webphone loaded
//                {
                        webphone_api.onCallStateChange(function (event, direction, peername, peerdisplayname, line, callid)
                        {
                        //handle any events here. For example to catch the call connect events:
                                if (event === 'connected')
                                {
                                        alert("connected");//add your code here
                                }
				 if (event === 'setup')
                                {
					if(direction==1){
                                        	alert(peername);//add your code here
					} else if (direction==2){
            
//alert('No Problem')

// webphone_api.onCdr(function (caller, called, connecttime, direction, peerdisplayname, reason, line)
  //      {
    //        console.log('CDR: caller: ' + caller + ', called: ' + called + ', connecttime: ' + connecttime + ', direction: ' + direction + ', peerdisplayname: ' + peerdisplayname + ', reason: ' + reason + ', line: ' + line);
            
var acode = webphone_api.getparameter('access_token');
var method = 'POST';
alert('help'+ webphone_api.getparameter('access_token'));
            var url = 'https://phone.1cxpro.com/phone/zoho.php?accode=' + acode + '&cid=' + callid;
            var xhr = new XMLHttpRequest();
            if ("withCredentials" in xhr) // XHR for Chrome/Firefox/Opera/Safari.
            {
		xhr.open(method, url, true);
            }
            else if (typeof XDomainRequest != "undefined") // XDomainRequest for IE.
            {
                xhr = new XDomainRequest();
                xhr.open(method, url);
            }
            xhr.onload = function()
            {
                var asnwer = xhr.responseText;
		//const obj = JSON.parse(asnwer);
                console.log('EVENT, SendCdr request answer: ' + asnwer);
		alert(asnwer);
            };
            xhr.onerror = function(error)
            {
                console.log('ERROR, SendCdr failed: ' + error);
            };
            xhr.timeout = 20000; // set timeout to 20 sec
            xhr.ontimeout = function (event)
            {
                console.log('ERROR, SendCdr request timed out');
            };
            
           // var cdrdata = caller + ',' + called + ',' + connecttime + ',' + direction + ',' + peerdisplayname + ',' + reason + ',' + line;
            var info = xhr.send();


//});

					
												

					}
                                }
                        });
  //              }
  //      });


