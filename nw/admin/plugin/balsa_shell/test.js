if(!jsi)
{
	var jsi=new JSI;
}
var processed=Array();
var proc_index=0;

var jsiShell=function()
{
	
	var t=this;
	
	this.process=function(str)
	{
		processed.push(document.getElementById('terminal_input').value);
		proc_index=processed.length;
		document.getElementById('terminal_input').value="";
		document.getElementById('terminal_prompt').innerHTML+='<div>user@JSI:~$ '+str+'</div>';
		try
		{
			eval(str);
		}		
		catch(e)
		{
			document.getElementById('terminal_prompt').innerHTML+='<p style="color:red">'+e+'</p>';
		}	
	}

	this.input=function(e)
	{
//		if(e.keyCode==13)
//		{
//			t.process(document.getElementById('terminal_input').value);
//		}
		switch(e.keyCode)
		{
			case 13:
				if(document.getElementById('terminal_input').value!='')
				{
					t.process(document.getElementById('terminal_input').value);
				}				
				break;
			case 38:
				proc_index--;
				document.getElementById('terminal_input').value=processed[proc_index];
				break;
		}
	}

   this.builtIn(input)
   {
     var input2=input;
     input=firstWord(input);
     if(t.BIF[input])
     {
       return eval(t.BIF[input]);
     }
     else
     {
       return false;
     }
   }

	this.init=function()
	{
		document.getElementById('terminal').appendChild(jsi.div(['class','id'],['terminal_prompt','terminal_prompt']));
	
		var input_cont=jsi.div(['class','id'],['terminal_i','terminal_i']);
		input_cont.appendChild(jsi.text(['class','id','onkeyup'],['terminal_input','terminal_input','jsiS.input(event)']));
		input_cont.appendChild(jsi.button(['class','id','onclick'],['terminal_input_ok','terminal_input_ok','jsiS.(document.getElementById(\'terminal_input\').value)'],'envoyer !'));
		document.getElementById('terminal').appendChild(input_cont);
	}

	
}

var jsiS=new jsiShell;

jsiS.init();