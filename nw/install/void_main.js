
function file(url)
{
	if (window.XMLHttpRequest)
	{
		xhr_object = new XMLHttpRequest();
	}        
    else
    {
    	if (window.ActiveXObject)
    	{
    		xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
    	}
    	else
    	{
    		return (false);
    	}            
    } 
        
    xhr_object.open("GET", url, false);
    xhr_object.send(null);
	if (xhr_object.readyState == 4)
	{
		return (xhr_object.responseText);
	}        
    else
    {
   		return (false);
    }        
}

function serv_req(url,rep,met,data)
{
	if (window.XMLHttpRequest)
	{
		xhr_object = new XMLHttpRequest();
	}        
    else
    {
    	if (window.ActiveXObject)
    	{
    		xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
    	}
    	else
    	{
    		return (false);
    	}            
    }
    
    if(!met || met=="")
    {
    	var met="GET";
    }        
    xhr_object.open(met, url, false);
    
    if(met=="POST")
    {
    	xhr_object.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    	xhr_object.send(data);
    }
    else
    {
   		xhr_object.send(null);
    }
    
	
	if (xhr_object.readyState == 4)
	{
		if(!rep)
		{
			var rep="";
		}
		switch(rep)
		{
			case'':
				return xhr_object.responseText;
				break;
			case'text':
				return xhr_object.responseText;
				break;
			case'xml':
				return xhr_object.responseXml;
				break;
			case'object':
				return xhr_object;
				break;
		}
		
	}        
    else
    {
   		return (false);
    }
}

function lightbox(titre,contenu)
{
	document.getElementById('lightbox_titre').innerHTML=titre;
	document.getElementById('lightbox_content').appendChild(contenu);
	document.getElementById('lightbox_filtre').style.display='block';
	document.getElementById('lightbox').style.display='block';
}

function lightbox_close()
{
	document.getElementById('lightbox').style.display='none';
	document.getElementById('lightbox_filtre').style.display='none';
	document.getElementById('lightbox_titre').innerHTML='';
	document.getElementById('lightbox_content').innerHTML='';
}

var BrowserDetect = {
	init: function () {
		this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
		this.version = this.searchVersion(navigator.userAgent)
			|| this.searchVersion(navigator.appVersion)
			|| "an unknown version";
		this.OS = this.searchString(this.dataOS) || "an unknown OS";
	},
	searchString: function (data) {
		for (var i=0;i<data.length;i++)	{
			var dataString = data[i].string;
			var dataProp = data[i].prop;
			this.versionSearchString = data[i].versionSearch || data[i].identity;
			if (dataString) {
				if (dataString.indexOf(data[i].subString) != -1)
					return data[i].identity;
			}
			else if (dataProp)
				return data[i].identity;
		}
	},
	searchVersion: function (dataString) {
		var index = dataString.indexOf(this.versionSearchString);
		if (index == -1) return;
		return parseFloat(dataString.substring(index+this.versionSearchString.length+1));
	},
	dataBrowser: [
		{
			string: navigator.userAgent,
			subString: "Chrome",
			identity: "Chrome"
		},
		{ 	string: navigator.userAgent,
			subString: "OmniWeb",
			versionSearch: "OmniWeb/",
			identity: "OmniWeb"
		},
		{
			string: navigator.vendor,
			subString: "Apple",
			identity: "Safari",
			versionSearch: "Version"
		},
		{
			prop: window.opera,
			identity: "Opera"
		},
		{
			string: navigator.vendor,
			subString: "iCab",
			identity: "iCab"
		},
		{
			string: navigator.vendor,
			subString: "KDE",
			identity: "Konqueror"
		},
		{
			string: navigator.userAgent,
			subString: "Firefox",
			identity: "Firefox"
		},
		{
			string: navigator.vendor,
			subString: "Camino",
			identity: "Camino"
		},
		{		// for newer Netscapes (6+)
			string: navigator.userAgent,
			subString: "Netscape",
			identity: "Netscape"
		},
		{
			string: navigator.userAgent,
			subString: "MSIE",
			identity: "Explorer",
			versionSearch: "MSIE"
		},
		{
			string: navigator.userAgent,
			subString: "Gecko",
			identity: "Mozilla",
			versionSearch: "rv"
		},
		{ 		// for older Netscapes (4-)
			string: navigator.userAgent,
			subString: "Mozilla",
			identity: "Netscape",
			versionSearch: "Mozilla"
		}
	],
	dataOS : [
		{
			string: navigator.platform,
			subString: "Win",
			identity: "Windows"
		},
		{
			string: navigator.platform,
			subString: "Mac",
			identity: "Mac"
		},
		{
			   string: navigator.userAgent,
			   subString: "iPhone",
			   identity: "iPhone/iPod"
	    },
		{
			string: navigator.platform,
			subString: "Linux",
			identity: "Linux"
		}
	]

};

function pop_up_box(titre,url)
{
	var box=jsi.iframe(['id','class'],['pop_up_box','pop_up_box'],url);
	lightbox(titre,box);
}

//http://www.webtoolkit.info/javascript-base64.html
var Base64 = {
 
	// private property
	_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
 
	// public method for encoding
	encode : function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;
 
		input = Base64._utf8_encode(input);
 
		while (i < input.length) {
 
			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);
 
			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;
 
			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}
 
			output = output +
			this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
			this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
 
		}
 
		return output;
	},
 
	// public method for decoding
	decode : function (input) {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;
 
		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
 
		while (i < input.length) {
 
			enc1 = this._keyStr.indexOf(input.charAt(i++));
			enc2 = this._keyStr.indexOf(input.charAt(i++));
			enc3 = this._keyStr.indexOf(input.charAt(i++));
			enc4 = this._keyStr.indexOf(input.charAt(i++));
 
			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;
 
			output = output + String.fromCharCode(chr1);
 
			if (enc3 != 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 != 64) {
				output = output + String.fromCharCode(chr3);
			}
 
		}
 
		output = Base64._utf8_decode(output);
 
		return output;
 
	},
 
	// private method for UTF-8 encoding
	_utf8_encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";
 
		for (var n = 0; n < string.length; n++) {
 
			var c = string.charCodeAt(n);
 
			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}
 
		}
 
		return utftext;
	},
 
	// private method for UTF-8 decoding
	_utf8_decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
 
		while ( i < utftext.length ) {
 
			c = utftext.charCodeAt(i);
 
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}
 
		}
 
		return string;
	}
}
/*connexion*/
function connect_form(div_id)
{
	document.getElementById(div_id).appendChild(jsi.input4form('login_input','Utilisateur : ',['name'],['login_input']));
	document.getElementById(div_id).appendChild(jsi.input4form('pass_input','Mot de passe : ',['name'],['pass_input']));
	document.getElementById(div_id).appendChild(jsi.button(['onclick'],['connect_req()'],'Connexion'));
}

function connect_req()
{
	var conn_req=serv_req(base_url+'goulot.php?page=connect_req&login='+document.getElementById('login_input').value, '', 'POST', 'p='+Base64.encode(document.getElementById('pass_input').value));
	document.getElementById('conn').innerHTML='';
	load_compte();
}

function deco()
{
	serv_req(base_url+'goulot.php?page=deco');
}
