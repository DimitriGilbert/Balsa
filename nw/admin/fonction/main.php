<?php

function inclure_plugin($plugin)
{
	global $path;
	return inc($path.'admin/plugin/'.$plugin.'/index.php');
}

?>
