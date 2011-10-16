<?php
global $_HOOK,$path,$base_url;
$p_n=$_HOOK['p_name'];
if(is_file($path.'admin/plugin/'.$p_n.'/install.xml'))
{
	if(is_file($path.'admin/plugin/'.$p_n.'/installed'))
	{
		$_HOOK['display']=
		'
			<a href="'.$base_url.'admin.php?uninstall=1&plugin='.$p_n.'"> desinstaller</a>
		';
	}
	else
	{
		$_HOOK['display']=
		'
			<a href="'.$base_url.'admin.php?install=1&plugin='.$p_n.'"> installer</a>
		';
	}
}
else
{
	$_HOOK['display']='';
}


?>
