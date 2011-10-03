<?php
global $path;
function is_admin()
{
	return true;
}

function ban()
{
	
}

function is_ban()
{
	return false;
}

function admin_login_form()
{
	
}

function admin_login()
{
	
}

if(is_admin())
{
	include_once($path.'admin/fonction/main.php');
	
	if(isset($_GET['page_admin']))
	{

	}
	elseif(isset($_GET['ajax_admin']))
	{
		if(is_file($path.'admin/plugin/'.$_GET['module'].'/index.php'))
		{
			inclure_plugin($_GET['module']);
		}
	}
	elseif(isset($_GET['install']))
	{
		include_once$path.'admin/plugin/installer.php';
	}
	elseif(isset($_GET['uninstall']))
	{
		include_once$path.'admin/plugin/uninstaller.php';
	}
	else
	{
		inclure_plugin('controll_panel');
	}	
}
else
{
	if(!is_ban)
	{
		echo admin_login_form();
	}	
}
?>
