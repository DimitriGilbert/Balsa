<?php
global $path,$path_w,$bdd,$base_url;
include_once($path.'admin/plugin/editor/editor.php');
$action=$_GET['action'];
switch($action)
{
	case'':
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<link rel="stylesheet" href="<?php echo $base_url; ?>admin.php?ajax_admin=1&module=editor&action=css" type="text/css" media="all" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Mon projet avec Balsa</title>
	</head>
	<body>
		<div class="site" id="site">
			<div id="editor_menus" class="editor_menus">
			</div>
			<div id="explorer" class="explorer">
			</div>
			<div id="editor_panel" class="editor_panel">
				<div id="editor_tabs" class="editor_tabs">
				</div>
				<div id="editors">
				</div>
				<div class="terminal" id="terminal">
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
		<div class="lightbox_filtre" id="lightbox_filtre"></div>
		<div class="lightbox" id="lightbox">
			<div class="lightbox_titre" id="lightbox_titre"></div>
			<div class="lightbox_close" onclick="lightbox_close();"></div>
			<div class="lightbox_content" id="lightbox_content"></div>
		</div>
		<div id="scipt_js">
			<?php echo inclure_js() ?>
			<script type="text/javascript" src="<?php echo $base_url; ?>admin.php?ajax_admin=1&module=editor&action=js"></script>
			<?php
			if(isset($_GET['edit']))
			{
			?>
				<script type="text/javascript">editFile("<?php echo $_GET['edit'] ?>")</script>
			<?php
			}
			?>
		</div>
	</body>
</html><?php
		break;
	case'js':
		header('Content-type: text/javascript');
		include_once($path.'admin/plugin/terminal/terminal.js.php');
		include_once($path.'admin/plugin/editor/editor.js.php');
		include_once($path.'admin/plugin/explorer/explorer.js.php');
		break;
	case'css':		
		header('Content-type: text/css');
		include_once($path.'admin/plugin/terminal/terminal.css.php');
		include_once($path.'admin/plugin/explorer/explorer.css.php');
		include_once($path.'admin/plugin/editor/editor.css.php');
		break;
}

?>
