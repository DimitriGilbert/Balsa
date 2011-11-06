<?php

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
		elseif(!is_dir($path.'admin/plugin/'.$d))
		{
			continue;
		}
		else
		{
			hook('after_plugin_link',array("display","p_name"=>$d));
			$display.=
			'
			<div class="plugin" id="plug_'.$d.'">
      ';
              
      if(!is_file($path.'admin/plugin/'.$d.'/installed') AND is_file($path.'admin/plugin/'.$d.'/install.xml')){      
			  $display.=
			  '
				  <a href="'.$base_url.'admin.php?page_admin=1&module='.$d.'">'.$d.'</a>'.$_HOOK['display'].'
        ';
     }

			$display.=
			'
			</div>
			';
		}
	}
	$display.='</div>';
	return $display;

?>