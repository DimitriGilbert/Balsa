<?php
global $path,$path_w,$bdd,$base_url;
include_once($path.'admin/plugin/explorer/explorer.php');
$ex=new explorer();
$action=$_GET['action'];
switch($action)
{
	case'':
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<link rel="stylesheet" href="<?php echo $base_url; ?>admin.php?ajax_admin=1&module=explorer&action=css" type="text/css" media="all" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Mon projet avec Balsa</title>
	</head>
	<body>
		<div class="site" id="site">
			<div id="explorer">
			</div>
		</div>
		<div style="clear:both"></div>
		<div class="lightbox_filtre" id="lightbox_filtre"></div>
		<div class="lightbox" id="lightbox">
			<div class="lightbox_titre" id="lightbox_titre"></div>
			<div class="lightbox_close" onclick="lightbox_close();"></div>
			<div class="lightbox_content" id="lightbox_content"></div>
		</div>
		<?php echo inclure_js(); ?>
		<script type="text/javascript" src="<?php echo $base_url; ?>admin.php?ajax_admin=1&module=explorer&action=js"></script>
	</body>
</html><?php
		break;
	case'load_xml':
		$ex->serv_xml();
		break;
	case'back_xml':
		$ex->back_xml();
		break;
	case'copy':
		
		break;
	case'cut':
		
		break;
	case'ren':
		
		break;
	case'rem':
		
		break;
	case'compress':
		compress_dir($_GET['uri'],$path,'',$path.'data/');
		$ex->rm_xml();
		break;
	case'js':
		header('Content-type: text/javascript');
		include_once($path.'admin/plugin/explorer/explorer.js.php');
		break;
	case'css':		
		header('Content-type: text/css');
		include_once($path.'admin/plugin/explorer/explorer.css.php');
		break;
	case'open':
		echo file_get_contents($path.$_GET['uri']);
		break;
	case'save':
		$f2save=str_replace(' ','+',$_POST['s']);
		$f2save=base64_decode($f2save);
		if($f2save!=false)
		{			
			if(file_put_contents($path.$_GET['uri'],$f2save))
			{
				echo '0';
			}
			else
			{
				echo 'Problème lors de l\'enregistrement.';
			}
		}
		else
		{
			echo'La source envoyée est corrompue.';
		}
		break;
}

?>
