function shell(str)
{
	str=Base64.encode(str);
	var s=serv_req(base_url+'admin.php?ajax_admin=1&module=terminal&action=shell','text','POST','command='+str);
	if(s!='0')
	{
		document.getElementById('terminal_prompt').innerHTML+='<p>'+s+'</p>';
		document.getElementById('terminal_input').value="";
	}	
}
/*
function valid_terminal_input(e)
{
	if(e.keyCode==13)
	{
		shell(document.getElementById('terminal_input').value);
	}
}

function terminal_init()
{
	document.getElementById('terminal').appendChild(jsi.div(['class','id'],['terminal_prompt','terminal_prompt']));
	
	var input_cont=jsi.div(['class','id'],['terminal_i','terminal_i']);
	input_cont.appendChild(jsi.text(['class','id','onkeyup'],['terminal_input','terminal_input','valid_terminal_input(event)']));
	input_cont.appendChild(jsi.button(['class','id','onclick'],['terminal_input_ok','terminal_input_ok','shell(document.getElementById(\'terminal_input\').value)'],'envoyer !'));
	document.getElementById('terminal').appendChild(input_cont);
}

if(!jsi)
{
	var jsi=new JSI;
}

terminal_init();
*/
