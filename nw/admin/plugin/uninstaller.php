<?php
global $path,$path_w;
if(is_dir($path.'admin/plugin/'.$_GET['plugin']))
{
  $p_path= $path.'admin/plugin/'.$_GET['plugin'].'/';
  if(is_file($p_path.'install.xml') and is_file($p_path.'installed'))
  {
    $install=new DOMDocument();
    $install->load($p_path.'install.xml');
    $xpath=new DOMXpath($install);

    //copy des fichiers de fonction
    $fn=$install->getElementsByTagName('fonction')->item(0)->getAttribute('file');
    if($fn!='all')
    {
      $fn=$xpath->query('//installer/fonction/file');
      foreach($fn as $f)
      {
        unlink($path.'fonction/'.$f->getAttribute('name'));
      }
    }
    else
    {
      $all_fn=scandir($p_path.'fonction');
      foreach($all_fn as $a_fn)
      {
      	if($a_fn=='.' or $a_fn=='..')
		{
			continue;
		}
		else
		{
			unlink($path.'fonction/'.$a_fn);
		}
      }
    }
    $libs=$xpath->query('//installer/fonction/libs/lib');
    if($libs)
    {
      foreach($libs as $l)
      {
        rmdir_r($path.'fonction/lib/'.$l);
      }
    }
 
 //copy des fichiers page
    $pg=$install->getElementsByTagName('page')->item(0)->getAttribute('file');
    if($pg!='all')
    {
      $pg=$xpath->query('//installer/page/file');
      foreach($pg as $f)
      {
        unlink($path.'page/'.$f->getAttribute('name'));
      }
    }
    else
    {
      $all_pg=scandir($p_path.'page');
      foreach($all_pg as $a_pg)
      {
      	if($a_pg=='.' or $a_pg=='..')
		{
			continue;
		}
		else
		{
			unlink($path.'page/'.$a_pg);
		}
      }
    }
//copy des fichiers de ajax
    $aj=$install->getElementsByTagName('ajax')->item(0)->getAttribute('file');
    if($aj!='all')
    {
      $aj=$xpath->query('//installer/ajax/file');
      foreach($aj as $f)
      {
        unlink($path.'ajax/'.$f->getAttribute('name'));
      }
    }
    else
    {
    	$all_aj=scandir($p_path.'ajax');
		foreach($all_aj as $a_aj)
		{
		  	if($a_aj=='.' or $a_aj=='..')
			{
				continue;
			}
			else
			{
				unlink($path.'ajax/'.$a_aj);
			}
		}
    }
//copy des fichiers de css
    $css=$install->getElementsByTagName('css')->item(0)->getAttribute('file');
    if($css!='all')
    {
      $css=$xpath->query('//installer/media/css/file');
      foreach($css as $f)
      {
        unlink($path_w.'media/css/'.$f->getAttribute('name'));
      }
    }
    else
    {
    	$all_css=scandir($p_path.'media/css');
      foreach($all_css as $a_css)
      {
      	if($a_css=='.' or $a_css=='..')
		{
			continue;
		}
		else
		{
			unlink($path_w.'media/css/'.$a_css);
		}
      }
    }
//copy des fichiers de js
    $js=$install->getElementsByTagName('js')->item(0)->getAttribute('file');
    if($js!='all')
    {
      $js=$xpath->query('//installer/media/js/file');
      foreach($js as $f)
      {
        unlink($path.'media/js/'.$f->getAttribute('name'));
      }
    }
    else
    {
      $all_js=scandir($p_path.'media/js');
      foreach($all_js as $a_js)
      {
      	if($a_js=='.' or $a_js=='..')
		{
			continue;
		}
		else
		{
			unlink($path.'media/js/'.$a_js);
		}
      }
    }

//flag d'installation
    unlink($p_path.'installed');
    echo 'la desinstallation de '.$_GET['plugin'].' c\'est bien deroule<br/><a href="admin.php">retour a l\'admin</a>';
  }
  else
  {
    echo'Il n\'y a pas de ficher d\'installation pour ce plugin.';
  }
}
else
{
  echo 'Le plugin n\'existe pas';
}

?>
