var JSI=function()
{
	var t=this;
	/*
	creation d'elements html
	*/
	this.element=function(type,att_name,att_value,value)
	{
		var elt=document.createElement(type);
		var x=0;
		var y=att_name.length;
		if(att_name)
		{
			while(x<y)
			{
				elt.setAttribute(att_name[x],att_value[x]);
				x++;
			}
			if(value)
			{
				elt.innerHTML=value;
			}
		}
	
		return elt;
	}
	
	this.div=function(att_name,att_value,value)
	{
		return t.element('div',att_name,att_value,value);
	}
	
	this.span=function(att_name,att_value,value)
	{
		return t.element('span',att_name,att_value,value);
	}
	
	this.select=function(o_val,o_inner,att_name,att_value)
	{
		var sel=t.element('select',att_name,att_value);
		var x=0;
		var y=o_val.length;
	
		while(x<y)
		{
			var opt=t.element('option',['value'],[o_val[x]],o_inner[x]);
			sel.appendChild(opt);
			x++;
		}
	
		return sel;
	}
	
	this.clear=function(side)
	{
		if(!side)
		{
			var side='both';
		}
		return t.element('div',['style'],['clear:'+side+';']);
	}
	
	this.text=function(att_name,att_value,value)
	{
		att_name.push('type');
		att_value.push('text');
		if(value)
		{
			att_name.push('value');
			att_value.push(value);
		}
		return t.element('input',att_name,att_value);
	}
	
	this.password=function(att_name,att_value,value)
	{
		att_name.push('type');
		att_value.push('password');
		if(value)
		{
			att_name.push('value');
			att_value.push(value);
		}
		return t.element('input',att_name,att_value);
	}
	
	this.button=function(att_name,att_value,value)
	{
		att_name.push('type');
		att_value.push('button');
		if(value)
		{
			att_name.push('value');
			att_value.push(value);
		}
		return t.element('input',att_name,att_value);
	}
	
	this.label=function(i_id,content)
	{
		return t.element('label',['for','id'],[i_id,i_id+'_label'],content);
	}
	
	this.input4form=function(i_id,label_cont,att_name,att_value,value)
	{
		var div=t.div(['class'],['']);
		div.appendChild(t.label(i_id,label_cont));
		att_name.push('id');
		att_value.push(i_id);
		div.appendChild(t.text(att_name,att_value,value));
		return div;
	}
	
	this.vInput4form=function(i_id,label_cont,att_name,att_value,value)
	{
		var div=t.div(['class'],['']);
		div.appendChild(t.label(i_id,label_cont));
		att_name.push('id');
		att_value.push(i_id);
		div.appendChild(t.not_empty(t.text(att_name,att_value,value)));
		return div;
	}
	
	this.pass4form=function(i_id,label_cont,att_name,att_value,value)
	{
		var div=t.div(['class'],['']);
		div.appendChild(t.label(i_id,label_cont));
		att_name.push('id');
		att_value.push(i_id);
		div.appendChild(t.password(att_name,att_value,value));
		return div;
	}
	
	this.tArea=function(att_name,att_value,value)
	{
		var tarea=t.element('textarea',att_name,att_value);
		if(value)
		{
			tarea.innerHTML=value;
		}
		return tarea;
	}
	
	this.iframe=function(att_name,att_value,url)
	{
		att_name.push('src');
		att_value.push(url);
		return t.element('iframe',att_name,att_value)
	}
	
	
	
	/*
	manipulation d'elements html
	*/
	this.insert=function(childs,node)
	{
		var x=0;
		var y=childs.length;
		while(x<y)
		{
			node.appendChild(childs[x]);
			x++;
		}
		return node;
	}
	
	this.display=function(type,elts)
	{
		var x=0;
		var y=elts.length;
		while(x<y)
		{
			document.getElementById(elts[x]).style.display=type;
			x++;
		}
	}
	
	this.block=function(elts)
	{
		t.display('block',elts);
	}
	
	this.displayNone=function(elts)
	{
		t.display('none',elts);
	}
	
	this.add2Attribute=function(elts,att,val)
	{
		if(elts.hasAttribute(att))
		{
			val=elts.getAttribute(att)+';'+val;
		}
		
		elts.setAttribute(att,val);
		return elts;
	}
	
	/*
	manipulation de input
	*/
	this.not_empty=function(input,def)
	{
		var fn='if(this.value==""';
		if(!def)
		{
			fn+=')';
		}
		else
		{
			fn+=' || this.value=="'+def+'")';
		}		
		fn+='{this.style.background="#FAA0B6";}else{this.style.background="#FFF";}';
		
		return t.add2Attribute(input,'onblur',fn);
	}
	
	this.same_value_as=function(input1,input2_id)
	{
		var fn='if(this.value!=document.getElementById("'+input2_id+'").value){this.style.background="#FAA0B6";}else{this.style.background="#5FFA5A";}';
		
		return t.add2Attribute(input1,'onkeyup',fn);
	}
	
	this.is_strong=function(str)
	{
		var strength=0;
		if(str.length>6)
		{
			strength++;
		}
		if((str.match(/[a-z]/)) && (str.match(/[A-Z]/)))
		{
			strength++;
		}
		if(str.match(/\d+/))
		{
			strength++;
		}
		if(str.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))
		{
			strength++;
		}
		return strength
	}
	
	this.strong_enough=function(pass)
	{
		var str=t.is_strong(new String(pass.value));
		switch(str)
		{
			case 0:
				pass.style.background="#FAA0B6";
				break;			
			case 1:
				pass.style.background="#FAA0B6";
				break;
			case 2:
				pass.style.background="#FACA5A";
				break;
			case 3:
				pass.style.background="#B2FA5A";
				break;
			case 4:
				pass.style.background="#5FFA5A";
				break;
		}
		return pass;
	}
	
	this.strength_checker=function(input)
	{
		var fn='jsi.strong_enough(this)';
		return t.add2Attribute(input,'onkeyup',fn);
	}
	
	this.is_mail=function(input)
	{
		var str=new String(input.value)
		var e_reg = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
		if(str.search(e_reg)==-1)
		{
			input.style.background="#FAA0B6";
		}
		else
		{
			input.style.background="#5FFA5A";
		}
		return input;
	}
	
	this.mail_checker=function(input)
	{
		var fn='jsi.is_mail(this)';
		return t.add2Attribute(input,'onblur',fn);
	}
}













var base_url="http://127.0.0.1/Balsa/www/"
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
