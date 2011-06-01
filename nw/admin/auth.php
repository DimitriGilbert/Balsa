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
		switch($_GET['module'])
		{
			case'':
			
				break;
			case'explorer':
				inclure_plugin('explorer');
				break;
			case'editor':
				inclure_plugin('editor');
				break;
			case'terminal':
				inclure_plugin('terminal');
				break;
		}
	}
	else
	{
		plop('entre une url');
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
