<?php

function files2package()
{
  global $path;
  $f2p=$_POST['fl'];
  $f2p=base64_decode($f2p);
  $f2p=explode('__$$__',$f2p);
  $xml='<installer plugin_name="'.$_GET['p_name'].'">';
  $xml_f='<fonction file="all">';
  $xml_p='<page file="all">';
  $xml_a='<ajax file="all">';
  $xml_m['js']='<js>';
  $xml_m['css']='<css>';
  $xml_m['img']='<img>';
  
  mkdir($path.'admin/plugin/'.$_GET['p_name']);
  $plugin_dir=$path.'admin/plugin/'. $_GET['p_name'].'/');
  mkdir($plugin_dir.'fonction');
  mkdir($plugin_dir.'page');
  mkdir($plugin_dir.'ajax');
  mkdir($plugin_dir.'media');
  mkdir($plugin_dir.'media/css');
  mkdir($plugin_dir.'media/js');
  mkdir($plugin_dir.'media/img');

  foreach($f2p as $f)
  {
    $f_comp=explode('/',$f);
    switch($f_comp[0])
    {
      case'fonction':
        
        break;
      case'page':
        
        break;
      case'ajax':
        
        break;
      case'media':
        
        break;
    }
    copy($path.$f,$plugin_dir.$f);
  }
  $xml_f.='</fonction>';
  $xml_p='</page>';
  $xml_a='</ajax>';
  $xml_m['js']='</js>';
  $xml_m['css']='</css>';
  $xml_m['img']='</img>';
  $xml.=$xml_f.$xml_a.$xml_p.'<media>'.$xml_m['css'].$xml_m['js'].$xml_m['img'] .'</media>';

  return file_put_contents($plugin_dir.'install.xml');
  }
}

?>