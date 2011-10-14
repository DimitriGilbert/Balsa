<?php
global $path,$path_w,$bdd,$base_url,$nom_projet;
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
		inclure_admin_header();
		hook('after_admin_header',array());
		inclure_plugin($_GET['module']);
		inclure_admin_footer();
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
		inclure_admin_header();
		hook('after_admin_header',array());
		inclure_plugin('controll_panel');
		inclure_admin_footer();
	}	
}
else
{
	if(!is_ban)
	{
		echo admin_login_form();
	}	
}
traite_fin_de_page();
?>
