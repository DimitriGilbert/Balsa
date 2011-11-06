<?php
global $base_url;
?>
<div class="menu">
	<h3><a href="<?php echo $base_url.'admin.php?page_admin=a&module=gestion_plugin' ?>">gestion de plugin</a></h3>
	<div class="sub_menus">
		<div class="sub_menu"><a href="<?php echo $base_url.'admin.php?page_admin=a&module=gestion_plugin&action=upload' ?>">uploader un plugin</a></div>
		<div class="sub_menu"><a href="<?php echo $base_url.'admin.php?page_admin=a&module=gestion_plugin&action=download' ?>">télécharger un plugin</a></div>
		<div class="sub_menu"><a href="<?php echo $base_url.'admin.php?page_admin=a&module=gestion_plugin&action=gestion' ?>">installer/désinstaller un plugin</a></div>
	</div>
</div>
