var xfss=Array();

function add_new_project()
{
	var rep=serv_req(base_url+'admin.php?ajax_admin=a&module=project_manager&action=add_project&path='+docel.id('path_new_project').value+'&name='+docel.id('name_new_project').value);
}

function reload_project(p_name)
{
	var rep=serv_req(base_url+'admin.php?ajax_admin=a&module=project_manager&action=reload_project&name='+p_name);
}

function browse_project(p_name)
{
	docel.id('p_m_content').className='browser';
	xfss[p_name]=new XMLFS();
	xfss[p_name].load(base_url+'admin.php?ajax_admin=a&module=project_manager&action=browse_project&name='+p_name);
	xfss[p_name].set_fs('');
	xfss[p_name].HTML('p_m_content');
}

function pack_update(p_name,targ)
{
	if(!targ)
	{
		var targ='';
	}
	var rep=serv_req(base_url+'admin.php?ajax_admin=a&module=project_manager&action=pack_update&name='+p_name+'&for='+targ);
	docel.id('debug_out').value=rep;
}

function unpack_update(p_name,p_n,p_t)
{
	var rep=serv_req(base_url+'admin.php?ajax_admin=a&module=project_manager&action=unpack_update&name='+p_name+'&n='+p_n+'&t='+p_t);
	docel.id('debug_out').value=rep;
}
