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
				<a target="_blank" href="'.$base_url.'admin.php?page_admin=1&module=editor&edit=ajax/'.$d2.'">éditer</a>
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
				<a target="_blank" href="'.$base_url.'admin.php?page_admin=1&module=editor&edit=ajax/'.$d2.''.$d2.'">éditer</a>
			</div>
			';
		}
	}
	$display.='</div>';
	return $display;
}

function list_plugin_install()
{
	global $path, $base_url;
	$display=
	'
	<div class="file_list" id="plugin_list">
	<h2>Liste des plugins installés</h2>
	';
	$dir=scandir($path.'admin/plugin/');
	foreach($dir as $d)
	{
		if($d=='.' or $d=='..')
		{
			continue;
		}
		elseif(!is_dir($path.'admin/plugin/'.$d) OR (!is_file($path.'admin/plugin/'.$d.'/installed') AND is_file($path.'admin/plugin/'.$d.'/install.xml')))
		{
			continue;
		}
		else
		{
			$display.=
			'
			<div class="plugin" id="plug_'.$d.'">
				<a href="'.$base_url.'admin.php?page_admin=1&module='.$d.'">'.$d.'</a>
			</div>
			';
		}
	}
	$display.='</div>';
	return $display;
}

function list_plugin_add()
{
	global $path, $base_url,$_HOOK;
	$display=
	'
	<div class="file_list" id="plugin_list">
	<h2>Liste des plugins additionnels</h2>
	';
	$dir=scandir($path.'admin/plugin/');
	foreach($dir as $d)
	{
		if($d=='.' or $d=='..')
		{
			continue;
		}
		elseif(!is_dir($path.'admin/plugin/'.$d) OR !is_file($path.'admin/plugin/'.$d.'/install.xml'))
		{
			continue;
		}
		else
		{
			hook('after_plugin_link',array("display","p_name"=>$d));
			
			if(is_file($path.'admin/plugin/'.$d.'/installed')){
				$display.=
				'
				<div class="plugin" id="plug_'.$d.'">
					<a href="'.$base_url.'admin.php?page_admin=1&module='.$d.'">'.$d.'</a>';
			}else{
				$display.='<div class="plugin" id="plug_'.$d.'">'.$d;			
			}
			$display.=$_HOOK['display'].'</div>';
		}
	}
	$display.='</div>';
	return $display;
}

function list_plugin_dl()
{
	global $path, $base_url;
	$display=
	'
	<div class="file_list" id="plugin_list">
	<h2>Liste des plugins téléchargeable</h2>
	';
	$dir=scandir($path.'admin/plugin/');
	foreach($dir as $d)
	{
		if($d=='.' or $d=='..')
		{
			continue;
		}
		elseif(!is_dir($path.'admin/plugin/'.$d) OR is_file($path.'admin/plugin/'.$d.'/installed') OR !is_file($path.'admin/plugin/'.$d.'/install.xml'))
		{
			continue;
		}
		else
		{
			$display.=
			'
			<div class="plugin" id="plug_'.$d.'">
				'.$d.' <a href="'.$base_url.'admin.php?page_admin=1&module=controll_panel&action=plugin&gestion=download&name='.$d.'">télécharger</a>
			</div>
			';
		}
	}
	$display.='</div>';
	return $display;
}

function list_plugin_delete()
{
	global $path, $base_url;
	$display=
	'
	<div class="file_list" id="plugin_list">
	<h2>Liste des plugins téléchargeable</h2>
	';
	$dir=scandir($path.'admin/plugin/');
	foreach($dir as $d)
	{
		if($d=='.' or $d=='..')
		{
			continue;
		}
		elseif(!is_dir($path.'admin/plugin/'.$d) OR is_file($path.'admin/plugin/'.$d.'/installed') OR !is_file($path.'admin/plugin/'.$d.'/install.xml'))
		{
			continue;
		}
		else
		{
			$display.=
			'
			<div class="plugin" id="plug_'.$d.'">
				'.$d.' <a href="'.$base_url.'admin.php?page_admin=1&module=controll_panel&action=plugin&gestion=delete&name='.$d.'">supprimer</a>
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
				<a target="_blank" href="'.$base_url.'admin.php?page_admin=1&module=editor&edit=fonction/'.$d.'">'.$d.'</a>
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
		<a href="#" onclick="regen_js()">vider le cache</a>
	';
	$dir=scandir($path.'media/js/');
	foreach($dir as $d)
	{
		if($d=='.' or $d=='..' or is_dir($path.'media/js/'.$d) or $d=="balsa_comp_js.php")
		{
			continue;
		}
		else
		{
			$display.=
			'
			<div class="js" id="js_'.$d.'">
				<a href="'.$base_url.'admin.php?page_admin=1&module=editor&edit=media/js/'.$d.'" target="_blank">'.$d.'</a>
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
		<a href="#" onclick="regen_css()">vider le cache</a>
	';
	$dir=scandir($path.'media/css/');
	foreach($dir as $d)
	{
		if($d=='.' or $d=='..' or is_dir($path.'media/css/'.$d) or $d=="balsa_comp_css.php")
		{
			continue;
		}
		else
		{
			$display.=
			'
			<div class="css" id="css_'.$d.'">
				<a href="'.$base_url.'admin.php?page_admin=1&module=editor&edit=media/css/'.$d.'" target="_blank">'.$d.'</a>
			</div>
			';
		}
	}
	$display.='</div>';
	return $display;
}

function admin_menu()
{
	global $path;
	$xml=new DOMDocument();
	$xml->load($path.'admin/plugin/controll_panel/data/menu.xml');
	$menu=$xml->getElementsByTagName('menu');
	echo '<div class="access" id="access"><div class="menus" id="menus">';
	foreach($menu as $m)
	{
		inc($path.'admin/plugin/'.$m->getAttribute('module').'/menu.php');
	}
	echo '<div class="clear"></div></div>';
}

function add_admin_menu($module)
{
	global $path;
	if(is_file($path.'admin/plugin/'.$module.'/menu.php'))
	{
		$xml=new DOMDocument();
		$xml->load($path.'admin/plugin/controll_panel/data/menu.xml');
		$m=$xml->createElement('menu');
		$m->setAttribute('module',$module);
		$xml->documentElement->appendChild($m);
		$xml->save($path.'admin/plugin/controll_panel/data/menu.xml');
	}
}

function del_admin_menu($module)
{
	global $path;
	if(is_file($path.'admin/plugin/'.$module.'/menu.php'))
	{
		$xml=new DOMDocument();
		$xml->load($path.'admin/plugin/controll_panel/data/menu.xml');
		$x=new DOMXpath($xml);
		$m=$x->query('//menus/menu[@module="'.$module.'"]')->item(0);
		if($m!='' or $m!=null or $m)
		{
			$xml->documentElement->removeChild($m);
			$xml->save($path.'admin/plugin/controll_panel/data/menu.xml');		
		}
	}
}

function supp_recur_folder($dossier){
	$ouverture=opendir($dossier);
	while($fichier=readdir($ouverture)){
		if($fichier=='.' or $fichier=='..'){
			continue;
		}else{
			if(is_dir($dossier.'/'.$fichier)){
				supp_recur_folder($dossier.'/'.$fichier);
			}else{
				unlink($dossier.'/'.$fichier);
			}
		}
	}
	closedir($ouverture);
	if(rmdir($dossier)){
		return true;
	}else{
		return false;
	}
}
?>