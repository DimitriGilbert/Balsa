<?php

function inclure_plugin($plugin)
{
	global $path;
	return inc($path.'admin/plugin/'.$plugin.'/index.php');
}
function inclure_admin_header()
{
	global $path;
	return inc($path.'admin/page/header.php');
}
function inclure_admin_footer()
{
	global $path;
	return inc($path.'admin/page/footer.php');
}

function regen_css()
{
	global $path;
	return unlink($path.'media/css/balsa_comp_css.php');
}

function regen_js()
{	
	global $path;
	return unlink($path.'media/js/balsa_comp_js.php');
}

function list_admin()
{

}

function mod_pass($old,$new,$new2)
{

}

function mod_mail($new)
{

}

function add_admin($nom,$mail,$pass,$pass2)
{

}



function back_file($type,$name)
{

}
?>
