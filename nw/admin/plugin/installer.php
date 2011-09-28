<?php
global $path,$path_w;
if(is_dir($path.'admin/plugin/'.$_GET['plugin']))
{
  $p_path= $path.'admin/plugin/'.$_GET['plugin'].'/';
  if(is_file($p_path.'install.xml'))
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
        copy($p_path.'fonction/'.$f->getAttribute('name'),$path.'fonction/'.$f->getAttribute('name'));
      }
    }
    else
    {
      copy_r($p_path.'fonction',$path.'fonction');
    }
    $libs=$xpath->query('//installer/fonction/libs/lib');
    if($libs)
    {
      foreach($libs as $l)
      {
        copy_r( $p_path.'fonction/lib/'.$l->getAttribute('name'), $path.'fonction/lib/'.$l->getAttribute('name'));
      }
    }
 //copy des fichiers page
    $pg=$install->getElementsByTagName('page')->item(0)->getAttribute('file');
    if($pg!='all')
    {
      $pg=$xpath->query('//installer/page/file');
      foreach($pg as $f)
      {
        copy($p_path.'page/'.$f->getAttribute('name'),$path.'page/'.$f->getAttribute('name'));
      }
    }
    else
    {
      copy_r($p_path.'page',$path.'page');
    }
//copy des fichiers de ajax
    $aj=$install->getElementsByTagName('ajax')->item(0)->getAttribute('file');
    if($aj!='all')
    {
      $aj=$xpath->query('//installer/ajax/file');
      foreach($aj as $f)
      {
        copy($p_path.'ajax/'.$f->getAttribute('name'),$path.'ajax/'.$f->getAttribute('name'));
      }
    }
    else
    {
      copy_r($p_path.'ajax',$path.'ajax');
    }
//copy des fichiers de css
    $css=$install->getElementsByTagName('css')->item(0)->getAttribute('file');
    if($css!='all')
    {
      $css=$xpath->query('//installer/media/css/file');
      foreach($css as $f)
      {
        copy($p_path.'media/css/'.$f->getAttribute('name'),$path_w.'media/css/'.$f->getAttribute('name'));
      }
    }
    else
    {
      copy_r($p_path.'media/css',$path_w.'media/css');
    }
//copy des fichiers de js
    $js=$install->getElementsByTagName('js')->item(0)->getAttribute('file');
    if($js!='all')
    {
      $js=$xpath->query('//installer/media/js/file');
      foreach($js as $f)
      {
        copy($p_path.'media/js/'.$f->getAttribute('name'),$path.'media/js/'.$f->getAttribute('name'));
      }
    }
    else
    {
      copy_r($p_path.'media/js',$path.'media/js');
    }
//copy des fichiers images
    $img=$install->getElementsByTagName('img')->item(0)->getAttribute('file');
    if($img!='all')
    {
      $img=$xpath->query('//installer/media/img/file');
      foreach($img as $f)
      {
        copy($p_path.'media/img/'.$f->getAttribute('name'),$path_w.'media/img/'.$f->getAttribute('name'));
      }
    }
    else
    {
      copy_r($p_path.'media/img',$path_w.'media/img');
    }
//creation des dossiers data
    $fold=$xpath->query('//installer/data/folder');
    foreach($fold as $f)
    {
      if($f->getAttribute('parent')=='')
      {
        mkdir($path.'data/'.$f->getAttirbute('name'));
      }
      else
      {
        mkdir($path.'data/ '.$f->getAttirbute('parent').'/'.$f->getAttirbute('name') );
      }
    }
//flag d'installation
    $install_t=fopen($p_path.'installed','a');
    fclose($install_t);
    echo 'l\'installation de '.$_GET['plugin'].' c\'est bien deroule<br/><a href="admin.php">retour a l\'admin</a>';
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
