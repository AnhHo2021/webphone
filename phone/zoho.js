  //start interacting with the webphone only once the onAppStateChange callback has been fired with the “loaded” event.
//        webphone_api.onAppStateChange(function (state)
//        {
//                if (state === 'loaded') //webphone loaded
 
//                {
    webphone_api.onCallStateChange(function (event, direction, peername, peerdisplayname, line, callid)
            {
               // alert(event); 

          
                    var cdr_text = callid;
                    var system_click2dial = cdr_text.substring(1, 5);                 
                    var line_dets = webphone_api.getlinedetails(line);
                    var dets_arr = line_dets.split(',');
                    var user_name = dets_arr[5];// + '@' + dets_arr[30];
                    var ext = dets_arr[5];
                    var call_id = dets_arr[8];
                    var outside_callee = dets_arr[4];
                    var dis_code1 = dets_arr[17];
                    var dis_code2 = dets_arr[18];
                      
                   console.log('Line Info' + line_dets);       
                //   alert('Line Info' + line_dets);  
                     console.log('line dets array:  ' + dets_arr[3] + '  - Oustide:' + dets_arr[7] + '  -  Cdr:' + dets_arr[8]);

                    if(system_click2dial=='zoho'){
                        direction = 1;
                        //alert(direction + 'click2dial - ' + system_click2dial);
                    }
                    //alert('hello)');

                  //  alert('Here:  ' + ext + '-' + direction + '-' + user_name + '-' + call_id + '-' + event + '-'); 
                    if (event === 'trying' || event === 'ringing' || event === 'setup')
                    {
					    if(direction==1){
                            if(system_click2dial != 'zoho'){
                                var url = 'https://phone.1cxpro.com/phone/zoho.php?to=' + outside_callee + '&event=out_ringing&u_id=' + user_name + '&cid=' + call_id + '&from=' + ext;	
                                console.log('RINGURLOUT: ' + url);
                                var method = 'GET';
                               
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
                                // alert('URL' + url + asnwer);
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
                            }
					    } else if (direction==2){

                              
                               // alert('Incomming'+ webphone_api.getparameter('access_token'));
                                var url = 'https://phone.1cxpro.com/phone/zoho.php?event=in_ringing&from=' + outside_callee +'&to=' + ext + '&u_id=' + user_name + '&cid=' + call_id;	
                                //alert(url);
                                console.log('incoming connect try:   ' + url);
                                var method = 'GET';
                               
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
	                                // alert('URL' + url + asnwer);
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
					    }
                    }
                   if (event === 'connected')
                        {
                            //alert(acode);
                            if(direction==1){
                                if(system_click2dial==='zoho'){
                                   // alert(system_click2dial);
                                        var url = 'https://phone.1cxpro.com/phone/zoho.php?to=' + outside_callee + '&event=out_ringing&u_id=' + user_name + '&cid=' + call_id + '&from=' + ext;	
                                        console.log('RINGURLOUT: ' + url);
                                        var method = 'GET';
                               
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
                                        // alert('URL' + url + asnwer);
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
                                    }else{
                                        // alert('out');
                                        //alert(event + direction + peername + peerdisplayname + line + callid);//add your code here
                                        var url = 'https://phone.1cxpro.com/phone/zoho.php?event=out_connected&cid=' + call_id + '&u_id=' + user_name;	
                                        console.log('URL: ' + url);
                                            var method = 'GET';
                               
                                                                                    // alert(url);
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
		                                    // alert('URL' + url + asnwer);
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
                                    }
					        } else if (direction==2){ 
                                // alert('in');
                                /// alert("connected");//add your code here
                                     var url = 'https://phone.1cxpro.com/phone/zoho.php?&event=in_connected&cid=' + call_id + '&u_id=' + user_name;
                                    // alert(url);
                                    console.log('URL: ' + url);
                                        var method = 'GET';
                               
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
		                                // alert('URL' + url + asnwer);
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
                            }
                                      
                        }

                   if (event === 'midcall')
                        {
                                                        // alert('out');
                            //alert(event + direction + peername + peerdisplayname + line + callid);//add your code here
                            var url = 'https://phone.1cxpro.com/phone/zoho.php?event=out_connected&cid=' + call_id + '&u_id=' + user_name;	
                            console.log('URL: ' + url);
                                var method = 'GET';
                               
                                                                        // alert(url);
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
		                        // alert('URL' + url + asnwer);
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
                        }






                                    
                           
                            
                        //handle any events here. For example to catch the call connect events:
                            
                    if (event === 'disconnected')
                    { 
                        //alert(event + '-' + direction + '-' + callid);
                            console.log('Bad' + dis_code1 + '-' + dis_code2 + '-' + outside_callee);  

                        if(direction==1){
                            webphone_api.onCdr(function (caller, called, connecttime, duration, direction, peerdisplayname, reason, line){
                                // convert string value to integer;
                                var durationInt = parseInt(duration,10);
                                var durationSec = Math.floor((durationInt+500)/1000);
                                //var durationSec = 25;
                                //alert('Call duration is: ' + durationSec + ' seconds' + connecttime);
                                var url = 'https://phone.1cxpro.com/phone/zoho.php?event=out_disconnected&cid=' + call_id + '&u_id=' + user_name + '&duration=' + durationSec;
                                // alert(url);
                                console.log('OUTURL: ' + direction + '-----  ' + url);
                                var method = 'GET';
                               
                                // alert(url);
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
		                            // alert('URL' + url + asnwer);
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
                            });

                                           	
					    } else if (direction==2){

                            webphone_api.onCdr(function (caller, called, connecttime, duration, direction, peerdisplayname, reason, line){
                                // convert string value to integer;
                                var durationInt = parseInt(duration,10);
                                var durationSec = Math.floor((durationInt+500)/1000);
                                // alert('Call duration is: ' + durationSec + ' seconds' + connecttime);
                                    var url = 'https://phone.1cxpro.com/phone/zoho.php?event=in_disconnected&cid=' + call_id + '&u_id=' + user_name + '&duration=' + durationSec;
                                //alert(url);
                                console.log('INURL: ' +direction + '-----  ' + url);
                                var method = 'GET';
                               
                                                                        // alert(url);
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
		                            // alert('URL' + url + asnwer);
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
                            });

       	
                        }
                                      
                    } 
               

            });