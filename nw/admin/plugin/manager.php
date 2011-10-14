<?php
global $path,$path_w,$base_url;

class plugin_manager()
{
	$path='';
	$name='';
	$xml=new DOMDocument();
	$to_install=array('fonction','page','ajax','hook','media/css','media/js');
	
	function __init($plugin)
	{
		global $path
		$this->path=$path.'admin/plugin/'.$plugin.'/';
		if(is_file($this->path.'install.xml'))
		{
			$this->name=$plugin;
			$this->xml->load($this6>path.'install.xml');
		}
		else
		{
			echo'Il n\'y a pas de ficher d\'installation pour ce plugin ou il n\'existe pas...';
		}
	}
	
	function install($quoi,$node)
	{
		global $path
		if($node->getAttribute('file')!='all')
		{
			$f_list=$node->childNodes;
			foreach($f_list as $f)
			{
				copy($this->path.$quoi.'/'.$f->getAttribute('name'),$path.$quoi.'/'.$f->getAttribute('name'));
			}
		}
		else
		{
			copy_r($this->path.$quoi,$path.$quoi);
		}
	}
	
	function uninstall($quoi,$node)
	{
		global $path
		if($node->getAttribute('file')!='all')
		{
			$f_list=$node->childNodes;
			foreach($f_list as $f)
			{
				unlink($path.$quoi.'/'.$f->getAttribute('name'));
			}
		}
		else
		{
			$all=scandir($this->path.$quoi);
			foreach($all as $a_fn)
			{
				if($a_fn=='.' or $a_fn=='..')
				{
					continue;
				}
				else
				{
					unlink($path.$quoi.'/'.$a_fn);
				}
			}
		}
	}
	
	function install_data()
	{
		global $path
		$fold=$this->xml->getElementsByTagName('folder');
		foreach($fold as $f)
		{
		  if($f->getAttribute('parent')=='')
		  {
		    mkdir($path.'data/'.$f->getAttribute('name'));
		  }
		  else
		  {
		    mkdir($path.'data/ '.$f->getAttribute('parent').'/'.$f->getAttribute('name') );
		  }
		}
	}
	
	function install_flag()
	{
		$install_t=fopen($this->path.'installed','a');
		fclose($install_t);
		hook('after_plugin_install',array('plugin'=>$this->name));
		echo 'l\'installation de '.$this->name.' c\'est bien deroule<br/><a href="'.$base_url.'admin.php">retour a l\'admin</a>';
	}
		
	function uninstall_flag()
	{
		unlink($this->path.'installed','a');
		hook('after_plugin_uninstall',array('plugin'=>$this->name));
		echo 'la desinstallation de '.$this->name.' c\'est bien deroule<br/><a href="'.$base_url.'admin.php">retour a l\'admin</a>';
	}
	
	function install_all()
	{
		global $path_w;
		foreach($this->to_install as $t)
		{
			if($t=='media/js')
			{
				$t2='js';
			}
			elseif($t=='media/css')
			{
				$t2='css';
			}
			else
			{
				$t2=$t;
			}
			$this->install($t,$this->xml->getElementsByTagName($t2)->item(0))
		}
		$this->install_data();
		copy_r($this->path.'media/img',$path_w.'media/img');
		$this->install_flag();
	}
	
	function uninstall_all()
	{
		global $path_w;
		foreach($this->to_install as $t)
		{
			if($t=='media/js')
			{
				$t2='js';
			}
			elseif($t=='media/css')
			{
				$t2='css';
			}
			else
			{
				$t2=$t;
			}
			$this->uninstall($t,$this->xml->getElementsByTagName($t2)->item(0))
		}		
		//reste les image a desintaller !!	
		$this->uninstall_flag();
	}
}
