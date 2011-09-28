<?php
global $path,$path_w,$bdd,$base_url;
include_once($path.'admin/plugin/balsa_shell/balsa_shell.php');
$action=$_GET['action'];
switch($action)
{
	case'':
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<link rel="stylesheet" href="<?php echo $base_url; ?>admin.php?ajax_admin=1&module=balsa_shell&action=css" type="text/css" media="all" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Mon projet avec Balsa</title>
	</head>
	<body>
		<div class="site" id="site">
			<div class="terminal" id="terminal">
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
			<script type="text/javascript" src="<?php echo $base_url; ?>admin.php?ajax_admin=1&module=balsa_shell&action=js"></script>
		</div>
	</body>
</html><?php
		break;
	case'js':
		header('Content-type: text/javascript');
		include_once($path.'admin/plugin/balsa_shell/balsa_shell.js.php');
		break;
	case'css':		
		header('Content-type: text/css');
		include_once($path.'admin/plugin/balsa_shell/balsa_shell.css.php');
		break;
	case'shell':		
		echo shell($_POST['command']);
		break;
	case'bif':
    	header('Content-type: text/xml');
		include_once($path.'admin/plugin/balsa_shell/bif.xml');
		break;
}

?>
