<?php
include_once('../nw/init.php');
global $path,$path_w,$bdd,$base_url;
include_once($path.'admin/plugin/project_manager/project_manager.php');

if(!isset($_GET['tkn']) or !valid_token($_GET['tkn']))
{
	echo check_repos_command();
}
else
{
	switch($_GET['ask'])
	{
		case 'has_up':
			echo has_up($_GET['p'],$_GET['f'],$_GET['t']);
			break;
		case 'serv_up':		    
			serv_up($_GET['f']);
			break;
		case 'serv_command':
#			print_pre($_GET);
			echo serv_command($_GET['p'],$_GET['f'],$_GET['l']);
			break;
		case 'report_up':
#			print_pre($_GET);
			break;
	}
}
?>
