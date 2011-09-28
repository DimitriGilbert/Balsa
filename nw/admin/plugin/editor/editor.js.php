var active_editor='';
var edited_file=Array();
function editor_menu()
{
	//new
	document.getElementById('editor_menus').appendChild(jsi.div(['class','id','onclick'],['editor_menu','editor_menu_new','']));
	//save
	document.getElementById('editor_menus').appendChild(jsi.div(['class','id','onclick'],['editor_menu','editor_menu_save','saveFile(active_editor)']));
	//save_all
	document.getElementById('editor_menus').appendChild(jsi.div(['class','id','onclick'],['editor_menu','editor_menu_save_all','saveFile(active_editor)']));
	//terminal
	document.getElementById('editor_menus').appendChild(jsi.div(['class','id','onclick'],['editor_menu','editor_menu_term','show_terminal()']));
}

function show_terminal()
{
	var dir=document.getElementById('terminal');
	if(dir.style.display=="none" || dir.style.display=="")
	{
		dir.style.display="block";
	}
	else
	{
		dir.style.display="none";	
	}
}

function add_editor_tab(uri)
{
	var tab=jsi.div(['class','id'],['editor_tab','editor_tab_'+uri]);
	tab.appendChild(jsi.span(['onclick'],['activate_editor(\''+uri+'\')'],uri));
	tab.appendChild(jsi.span(['onclick'],['closeFile(\''+uri+'\')'],' X'));
	document.getElementById('editor_tabs').appendChild(tab);
   edited_file.push(uri);
	activate_editor(uri);
}

function activate_editor(uri)
{
	if(active_editor!='')
	{
		document.getElementById('editor_frame_'+active_editor).style.display='none';
		document.getElementById('editor_tab_'+active_editor).style.background='white';
		document.getElementById('editor_tab_'+active_editor).style.color='black';
	}
	
	document.getElementById('editor_frame_'+uri).style.display='block';
	active_editor=uri;
	document.getElementById('editor_tab_'+uri).style.background='blue';
	document.getElementById('editor_tab_'+uri).style.color='white';	
}

function editFile(uri)
{
	document.getElementById('editors').appendChild(jsi.iframe(['id','class'],['editor_frame_'+uri,'editor_frame'],base_url+'media/lib/ace/editor.php?file='+uri));
	add_editor_tab(uri);
	edited_file[uri]=1;
}

function closeFile(uri)
{
	if(active_editor==uri)
	{
		active_editor='';
	}
	document.getElementById('editors').removeChild(document.getElementById('editor_frame_'+uri));
	document.getElementById('editor_tabs').removeChild(document.getElementById('editor_tab_'+uri));	
}

function saveFile(uri)
{
	var script=document.getElementById('editor_frame_'+uri).contentDocument.createElement('script');
	script.setAttribute('type','text/javascript');
	script.innerHTML='save_file(file_uri);';
	document.getElementById('editor_frame_'+uri).contentDocument.getElementById('script_temp').appendChild(script);
}

function save_all_file()
{
   for(u in edited_file)
   {
     saveFile(edited_file[u]);
   }
}

function save_session(s_name)
{
   var xml='<session name='+s_name+'>';
   for(u in edited_file)
   {
     xml+='<file>'+edited_file[u]+'</file>';
   }
   xml+='</session>';
   xml=Base64.encode(xml);
   var rep=serv_req('http://admin.php?module=editor&ajax_admin=1&action=session_save&name='+s_name,'POST','','session='+xml);
   if(rep=='1')
   {
     return true;
   }
   else
   {
     return rep;
   }
}

function load_session(s_name)
{
   var rep= serv_req('http://admin.php?module=editor&ajax_admin=1&action=session_get&name='+s_name,'GET','xml','');
   var files=rep.getElementsByTagName('file');
   for(f in files)
   {
     editFile(f.value);
   }
}

function prompt_save_session()
{
   var p=prompt('nom de la session de developpement courante :');
   if(save_session(p))
   {
     alert('sauvegarde de session reussie !');
   }
   else
   {
     alert('la sauvegarde a échoué...');
   }
}

function prompt_load_session()
{
   var p=prompt('nom de la session de developpement courante :');
   load_sesion(p);
}

if(!jsi)
{
	var jsi=new JSI;
}

editor_menu();
