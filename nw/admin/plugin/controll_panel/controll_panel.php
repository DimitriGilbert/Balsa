 <?php

function list_ajax_controller()
{
	global $path, $base_url;
	$display=
	'
	<div class="file_list" id="page_list">
	<h2>Liste des controller ajax</h2>
	';
	$dir=scandir($path.'ajax/');
	foreach($dir as $d)
	{
		if($d=='.' or $d=='..')
		{
			continue;
		}
		else
		{
			$d2=$d;
			$d=explode('.',$d);
			array_pop($d);
			$d=implode('.',$d);
			$display.=
			'
			<div class="page" id="page_'.$d.'">
				<a href="'.$base_url.'goulot.php?page='.$d.'">'.$d.'</a>
				<a target="_blank" href="http://127.0.0.1/Balsa/www/admin.php?ajax_admin=1&module=editor&edit=ajax/'.$d2.'">éditer</a>
			</div>
			';
		}
	}
	$display.='</div>';
	return $display;
}

function list_page_controller()
{
	global $path, $base_url;
	$display=
	'
	<div class="file_list" id="page_list">
	<h2>Liste des pages</h2>
	';
	$dir=scandir($path.'page/');
	foreach($dir as $d)
	{
		if($d=='.' or $d=='..')
		{
			continue;
		}
		else
		{
			$d2=$d;
			$d=explode('.',$d);
			array_pop($d);
			$d=implode('.',$d);
			$display.=
			'
			<div class="page" id="page_'.$d.'">
				<a href="'.$base_url.'index.php?page='.$d.'">'.$d.'</a>
				<a target="_blank" href="http://127.0.0.1/Balsa/www/admin.php?ajax_admin=1&module=editor&edit=page/'.$d2.'">éditer</a>
			</div>
			';
		}
	}
	$display.='</div>';
	return $display;
}

function list_plugin()
{
	global $path, $base_url;
	$display=
	'
	<div class="file_list" id="plugin_list">
	<h2>Liste des plugins</h2>
	';
	$dir=scandir($path.'admin/plugin/');
	foreach($dir as $d)
	{
		if($d=='.' or $d=='..')
		{
			continue;
		}
		elseif(!is_dir($path.'admin/plugin/'.$d))
		{
			continue;
		}
		else
		{
			$display.=
			'
			<div class="plugin" id="plug_'.$d.'">
				<a href="'.$base_url.'admin.php?ajax_admin=1&module='.$d.'">'.$d.'</a>
			</div>
			';
		}
	}
	$display.='</div>';
	return $display;
}

function list_function()
{
	global $path, $base_url;
	$display=
	'
	<div class="file_list" id="page_list">
	<h2>Liste des bibliotheques de fonctions</h2>
	';
	$dir=scandir($path.'fonction/');
	foreach($dir as $d)
	{
		if($d=='.' or $d=='..' or is_dir($path.'fonction/'.$d))
		{
			continue;
		}
		else
		{
			$display.=
			'
			<div class="page" id="page_'.$d.'">
				<a target="_blank" href="http://127.0.0.1/Balsa/www/admin.php?ajax_admin=1&module=editor&edit=fonction/'.$d.'">'.$d.'</a>
			</div>
			';
		}
	}
	$display.='</div>';
	return $display;
}

function list_function_libs()
{

}

function list_js()
{
	global $path, $base_url;
	$display=
	'
	<div class="file_list" id="js_list">
	<h2>Liste des fichiers javascript</h2>
	';
	$dir=scandir($path.'media/js/');
	foreach($dir as $d)
	{
		if($d=='.' or $d=='..' or is_dir($path.'media/js/'.$d))
		{
			continue;
		}
		else
		{
			$display.=
			'
			<div class="js" id="js_'.$d.'">
				<a href="http://127.0.0.1/Balsa/www/admin.php?ajax_admin=1&module=editor&edit=media/js/'.$d.'" target="_blank">'.$d.'</a>
			</div>
			';
		}
	}
	$display.='</div>';
	return $display;
}

function list_css()
{
	global $path, $base_url;
	$display=
	'
	<div class="file_list" id="css_list">
	<h2>Liste des fichiers CSS</h2>
	';
	$dir=scandir($path.'media/css/');
	foreach($dir as $d)
	{
		if($d=='.' or $d=='..' or is_dir($path.'media/css/'.$d))
		{
			continue;
		}
		else
		{
			$display.=
			'
			<div class="css" id="css_'.$d.'">
				<a href="http://127.0.0.1/Balsa/www/admin.php?ajax_admin=1&module=editor&edit=media/css/'.$d.'" target="_blank">'.$d.'</a>
			</div>
			';
		}
	}
	$display.='</div>';
	return $display;
}

function recompile_css()
{

}

function recompile_js()
{

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
