<?php
global $base_url,$nom_projet,$get; 
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<link rel="stylesheet" href="<?php echo $base_url; ?>admin.php?ajax_admin=1&module=controll_panel&action=css" type="text/css" media="all" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php echo $nom_projet ?></title>
	</head>
	<body>
		<div class="site" id="site">
    <a href="<?php echo $base_url ?>admin.php?ajax_admin=1&module=chrysa_cache&action=suppr_cache">supprimer tous les fichiers de cache</a>
    <a href="<?php echo $base_url ?>admin.php?ajax_admin=1&module=chrysa_cache&action=voir_cache">gérer les fichiers de cache</a>
    <?php
      switch($get['action']){
        case 'suppr_cache':
		        include_once($path.'admin/plugin/chrysa_cache/suppr_cache.php');
          break;
        case 'voir_cache':
		        include_once($path.'admin/plugin/chrysa_cache/voir_cache.php');
          break;
      }        
    ?>
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
		</div>
	</body>
</html>
