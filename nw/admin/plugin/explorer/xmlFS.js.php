var XMLFS=function()
{
	this.xml='';
	this.fs='';
	this.dir_class='xmlfs_dir';
	this.file_class='xmlfs_file';
	this.browser=false;
	this.t_jsi=new JSI;
	this.Ready=false;
	var t=this;
	
	this.load=function(url)
	{
		t.xml=loadXml(url);
	}
	
	this.isReady=function()
	{
		try
		{
			t.xml.documentElement.nodeName;
			t.Ready=true;
		}
		catch(e)
		{
			t.Ready=false
		}
		return t.Ready;
	}
	
	this.set_fs=function(node)
	{
		if(node=='')
		{
			node=t.xml.documentElement;
		}
		if(t.isReady()==false)
		{
			setTimeout(function(node){t.set_fs(node)},150);
		}
		else
		{
			t.fs=node;
		}
		
	}
	
	this.set_browser=function(b)
	{
		t.browser=b;
	}
	
	this.HTML=function(node_id)
	{
		if(t.isReady()==false)
		{
			setTimeout(function(node_id){t.HTML(node_id)},150);
		}
		else
		{
			var node=docel.id(node_id);
			node.innerHTML='';
			node.appendChild(t.HTML_dir(t.fs));
//			alert(t.HTML_dir(t.fs))
		}
	}
	
	this.dir_has_child=function(node)
	{
		if(node.childNodes.length>0)
		{
			if(node.childNodes[0].nodeName=="file" || node.childNodes[0].nodeName=="dir")
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}		
	}
	
	this.HTML_dir=function(node,classN,fn)
	{
		if(node.nodeName=='dir' || node.nodeName=='file' || node.nodeName=='project')
		{
			if(!classN || classN=='')
			{
				var classN=t.dir_class;
			}
			if(!fn)
			{
				var fn=function(npath_){depileDir(npath_)};
			}
			var dir_att=['class','id'];
			var dir_att_v=[classN,node.getAttribute('path')];
			var dirn_att=['class','id'];
			var dirn_att_v=[classN+'_name',node.getAttribute('path')+'_'+classN+'_name'];
			var childs=node.childNodes;
			var childs_len=childs.length;
			var n_dir=t.t_jsi.div(dir_att,dir_att_v);
			var nn_dir=n_dir.appendChild(t.t_jsi.div(dirn_att,dirn_att_v,node.getAttribute('name')));
			
			if(t.dir_has_child(node))
			{				
				nn_dir.setAttribute('onclick',"depileDir('"+node.getAttribute('path')+"')");
			}
			var childs_cont=t.t_jsi.div(['class','id'],[classN+'_child',node.getAttribute('path')+'_child']);
			var x=0;
			while(x<childs_len)
			{
				switch(childs.item(x).nodeName)
				{
					case'file':				
						childs_cont.appendChild(t.HTML_file(childs.item(x),t.file_class));
						break;
					case'dir':
						childs_cont.appendChild(t.HTML_dir(childs.item(x),classN));
						break;
				}
				x++;
			}
			n_dir.appendChild(childs_cont);
			return n_dir;
		}
		
	}
	
	this.HTML_file=function(node,classN,fn)
	{
		if(!classN)
		{
			var classN=t.file_class;
		}
		if(!fn)
		{
//			var fn=function(path){alert(path)};
		}
		
		var fi=t.t_jsi.div(['class','id'],[classN,node.getAttribute('path')],node.getAttribute('name'));
//		fi.onclick=fn(node.getAttribute('path'));
		return fi;
	}
	
	this.HTML_dir_switch=function(node_id)
	{
		var dir=docel.id(node_id+'_child');
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
//			t.activate_dir(node_id);
		}
		
	}
	
}

var active_dir='';

function depileDir(dirId)
{
	var dir=docel.id(dirId+'_child');
	if(dir)
	{
		if(dir.style.display=="none" || dir.style.display=="")
		{
			dir.style.display="block";
			browse_dir(dirId);
		}
		else
		{
			dir.style.display="none";
			unbrowse_dir(dirId)	
		}
		activate_dir(dirId);
	}	
}

function browse_dir(dirId)
{
	var dir=docel.id(dirId);
	if(dir)
	{
		dir.className='xmlfs_dir shown';
		var sib=docel.sibling(dir);
		var y=sib.length;
		var x=0;
		while(x<y)
		{
			if(sib[x].id!=dirId)
			{
				sib[x].className+=' hided';
			}
			x++;
		}
	}	
}

function unbrowse_dir(dirId)
{
	var dir=docel.id(dirId);
	if(dir)
	{
		dir.className='xmlfs_dir';
		var sib=docel.sibling(dir);
		var y=sib.length;
		var x=0;
		while(x<y)
		{
			sib[x].className=sib[x].classList[0];
			x++;
		}
	}	
}

function activate_dir(uri)
{
	if(active_dir!='')
	{		
		docel.id(active_dir+'_xmlfs_dir_name').style.background='white';
		docel.id(active_dir+'_xmlfs_dir_name').style.color='black';
	}
	active_dir=uri;
	docel.id(uri+'_xmlfs_dir_name').style.background='blue';
	docel.id(uri+'_xmlfs_dir_name').style.color='white';
}
