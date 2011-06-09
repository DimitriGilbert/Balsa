var file_content='';
var file_uri='';
var editor;    
var JavaScriptMode ;

function insert_editor()
{
	//file_content=Base64.decode(file_content);
	//document.getElementById('editor').appendChild(document.createTextNode(file_content));
	
	editor = ace.edit("editor");
	editor.setTheme("ace/theme/twilight");
	JavaScriptMode = require("ace/mode/"+f_ext).Mode;
	editor.getSession().setMode(new JavaScriptMode());
	editor.getSession().setValue(file_content);
}

function is_loaded()
{
	if(file_content!='' || file_content!='undefined')
	{
		insert_editor();
	}
	else
	{
		setTimeout('is_loaded()',50);
	}
}

function load_file(uri)
{
	file_uri=uri
	file_content=serv_req(base_url+'admin.php?ajax_admin=1&module=explorer&action=open&uri='+uri,'text','GET');
	is_loaded();
}

function save_file(uri)
{
	file_content=editor.getSession().getValue();
	var s=Base64.encode(file_content);
	var saved=serv_req(base_url+'admin.php?ajax_admin=1&module=explorer&action=save&uri='+uri,'text','POST','s='+s);
	if(saved!='0')
	{
		alert(saved);
	}	
}
