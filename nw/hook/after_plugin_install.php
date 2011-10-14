<?php
global $_HOOK,$path,$base_url;
$module=$_HOOK['module'];

inc($path.'admin/plugin/controll_panel/controll_panel.php');
add_admin_menu($module);

?>
