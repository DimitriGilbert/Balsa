<?php
global $path,$path_w,$base_url;
inc($path.'admin/plugin/manager.php');
$p_man=new plugin_manager($_GET['plugin']);
$p_man->uninstall_all();
?>
