var jsiExplorer=function()
{
	this.t_jsi=new JSI;
	var t=this;
	
	this.dir=function(path,dName,dChild)
	{
		var dir_att=['class','id'];
		var dir_att_v=['dir',path];
		var dirn_att=['class','id'];
		var dirn_att_v=['dir_name',path+'_dir_name'];
		if(dChild)
		{
			dirn_att.push('onclick');
			dirn_att_v.push('depileDir("'+path+'")');
		}
		var n_dir=t.t_jsi.div(dir_att,dir_att_v);
		n_dir.appendChild(t.t_jsi.div(dirn_att,dirn_att_v,dName))
		return n_dir;
	}
	
	this.dir_command=function(path)
	{
			var n_command=t.t_jsi.div(['class','id'],['dir_commands','dir_command_'+path]);
			n_command.appendChild(t.t_jsi.div(['class','id','onclick'],['dir_command','dir_command_compress','compress_dir(\''+path+'\')']));
			n_command.appendChild(t.t_jsi.div(['class','id','onclick'],['dir_command','dir_command_copy','copy_dir(\''+path+'\')']));
			n_command.appendChild(t.t_jsi.div(['class','id','onclick'],['dir_command','dir_command_cut','cut_dir(\''+path+'\')']));
			n_command.appendChild(t.t_jsi.div(['class','id','onclick'],['dir_command','dir_command_paste','paste_dir()']));			
			n_command.appendChild(t.t_jsi.div(['style'],['clear:both;']));
			return n_command;	
	}
	
	this.file=function(path,fName,fPerm,fTime,fSize,fType)
	{
		var f_att=['class','id','onclick'];
		var f_att_v=['file',path,'editFile("'+path+'")'];
		
		return t.t_jsi.div(f_att,f_att_v,fName);
	}
	
	this.fileAddInfo=function()
	{
		
	}
}

var jsiE=new jsiExplorer;
var xmlFS;
var explorerDiv=document.getElementById('explorer');
var active_dir='';

function loadXmlFs(url)
{
	if(!url)
	{
		var url=base_url+'admin.php?ajax_admin=1&module=explorer&action=load_xml'
	}
	var Doc;
	try
	{
		Doc = document.implementation.createDocument("", "", null);
	}
	catch(exc)
	{
		Doc = new ActiveXObject("Microsoft.XMLDOM"); 
	}
	if(typeof ASYNC != "undefined")
	{
		Doc.async=ASYNC;
	}
	
	/*try
	{
		Doc.load(url);
	}
	catch(exc)
	{*/
		var parser = new DOMParser ();
		var xmlhttp = file(url);
		
        Doc = parser.parseFromString (xmlhttp, "text/xml");
	//}
	
	xmlFS=Doc;
}

function activate_dir(uri)
{
	if(active_dir!='')
	{		
		document.getElementById(active_dir+'_dir_name').style.background='white';
		document.getElementById(active_dir+'_dir_name').style.color='black';
		document.getElementById(active_dir+'_dir_name').removeChild(document.getElementById('dir_command_'+active_dir));
	}
	active_dir=uri;
	document.getElementById(uri+'_dir_name').style.background='blue';
	document.getElementById(uri+'_dir_name').style.color='white';
	document.getElementById(uri+'_dir_name').appendChild(jsiE.dir_command(uri));
}

function depileDir(dirId)
{
	var dir=document.getElementById(dirId+'_child');
	if(dir)
	{
		if(dir.style.display=="none" || dir.style.display=="")
		{
			dir.style.display="block";
		}
		else
		{
			dir.style.display="none";	
		}
		activate_dir(dirId);
	}	
}

function buildDir(dirNode)
{
	var n_dir=jsiE.dir(dirNode.getAttribute('path'),dirNode.getAttribute('name'),dirNode.hasChildNodes());
	if(dirNode.hasChildNodes())
	{
		var childs=dirNode.childNodes;
		var childs_len=childs.length;
		if(childs_len>0)
		{
			//alert(dirNode.getAttribute('name'))
			var x=0;
			var childs_cont=jsiE.t_jsi.div(['class','id'],['dir_child',dirNode.getAttribute('path')+'_child']);
		
			while(x<childs_len)
			{
				switch(childs.item(x).nodeName)
				{
					case'file':
					
						var p=childs.item(x).getAttribute('path');
						var n=childs.item(x).getAttribute('name');
						var perm=childs.item(x).getAttribute('perms');
						var time=childs.item(x).getAttribute('time');
						var size=childs.item(x).getAttribute('size');
						var type=childs.item(x).getAttribute('type');
					
						childs_cont.appendChild(jsiE.file(p,n,perm,time,size,type));
						break;
					case'dir':
						childs_cont.appendChild(buildDir(childs.item(x)));
						break;
				}
				x++;
			}
			n_dir.appendChild(childs_cont);
		}
	}
	return n_dir;
}

function xmlFSReady()
{
	var b=false
	try
	{
		b=true;		
	}
	catch(e)
	{
		b=false
		setTimeout('xmlFSReady();',500);
	}
	if(b==true)
	{
		var explorerN=buildDir(xmlFS.documentElement);
		
		document.getElementById('explorer').appendChild(explorerN);
	}
}

function compress_dir(uri)
{
//	xmlFS=serv_req(base_url+'admin.php?ajax_admin=1&module=explorer&action=compress&uri='+uri,'text','GET');
//	document.getElementById('explorer').innerHTML="";
//	explorerInit();
}

function explorerInit()
{
	loadXmlFs();
	xmlFSReady();	
}

explorerInit();
