<?php
global $base_url;
?>
<div class="menu">
	<h3><a href="<?php echo $base_url.'admin.php?page_admin=a&module=controll_panel' ?>">Control Panel</a></h3>
	<div class="sub_menus">
		<div class="sub_menu"><a href="<?php echo $base_url.'admin.php?page_admin=a&module=controll_panel&action=ajax' ?>">controller ajax</a></div>
		<div class="sub_menu"><a href="<?php echo $base_url.'admin.php?page_admin=a&module=controll_panel&action=page' ?>">controller page</a></div>
		<div class="sub_menu"><a href="<?php echo $base_url.'admin.php?page_admin=a&module=controll_panel&action=fonction' ?>">bibliothèques de fonctions</a></div>
		<div class="sub_menu"><a href="<?php echo $base_url.'admin.php?page_admin=a&module=controll_panel&action=f_js' ?>">fichiers javascript</a></div>
		<div class="sub_menu"><a href="<?php echo $base_url.'admin.php?page_admin=a&module=controll_panel&action=f_css' ?>">fichiers css</a></div>
		<div class="sub_menu"><a href="<?php echo $base_url.'admin.php?page_admin=a&module=controll_panel&action=plugin' ?>">gestion de plugins</a></div>
	</div>
</div>
