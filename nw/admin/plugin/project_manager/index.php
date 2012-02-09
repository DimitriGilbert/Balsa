<?php
global $path,$path_w,$bdd,$base_url;
include_once($path.'admin/plugin/project_manager/project_manager.php');
$action=$_GET['action'];
switch($action)
{
	case'':
		?>
		<div id="p_m_menus" class="p_m_menus">
						
		</div>
		<div id="p_m_content">
			<?php
			
			echo list_projects();
			echo form_add_project();
			
			?>			
		</div>
		<div class="clear"></div>
		<textarea id="debug_out"></textarea>
		<div id="scipt_js">
			<?php echo inclure_js() ?>
			<script type="text/javascript" src="<?php echo $base_url; ?>admin.php?ajax_admin=1&module=project_manager&action=js"></script>
			<link media="all" type="text/css" href="admin.php?ajax_admin=1&module=project_manager&action=css" rel="stylesheet">
			<?php
			if(isset($_GET['edit']))
			{
			?>
			<?php
			}
			?>
		</div>
	<?php
		break;
	case'add_project':
		echo add_project($_GET['path'],$_GET['name']);
		break;
	case'reload_project':
		echo reload_project($_GET['name']);
		break;
	case'pack_update':
		echo pack_update($_GET['name'],$_GET['for']);
		break;
	case'unpack_update':
		echo unpack_update($path.'data/project_manager/update/'.$_GET['name'],$_GET['n'],$_GET['t'],true);
		break;
	case'js':
		header('Content-type: text/javascript');
		include_once($path.'admin/plugin/project_manager/project_manager.js.php');
		include_once($path.'admin/plugin/explorer/xmlFS.js.php');
		break;
	case'css':		
		header('Content-type: text/css');
		include_once($path.'admin/plugin/project_manager/project_manager.css.php');
		break;
	case'browse_project':		
		header('Content-type: text/xml');
		echo file_get_contents($path.'data/project_manager/projects/'.$_GET['name'].'.xml');
		break;
}

?>
