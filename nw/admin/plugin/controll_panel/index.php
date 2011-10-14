<?php
global $path,$path_w,$bdd,$base_url,$nom_projet;
include_once('controll_panel.php');
$action=$_GET['action'];
switch($action)
{
	case'':
		echo list_plugin();
		echo list_function();
		echo list_page_controller();
		echo list_ajax_controller();
		echo list_js();
		echo list_css();
		?>
		<script type="text/javascript" src="<?php echo $base_url.'admin.php?ajax_admin=1&module=controll_panel&action=js';?>"></script>
		<?php
		break;
	case'plugin':
		echo list_plugin();
		break;
	case'fonction':
		echo list_function();
		break;
	case'page':
		echo list_page_controller();
		break;
	case'ajax':
		echo list_ajax_controller();
		break;
	case'f_js':
		echo list_js();
		break;
	case'f_css':
		echo list_css();
		break;
	case'js':
		header('Content-type: text/javascript');
		include_once($path.'admin/plugin/controll_panel/controll_panel.js.php');
		break;
	case'css':		
		header('Content-type: text/css');
		include_once($path.'admin/plugin/controll_panel/controll_panel.css.php');
		break;
	case'regen_css':
		regen_css();
		break;
	case'regen_js':
		regen_js();
		break;
}

?>
