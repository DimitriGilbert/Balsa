<?php
global $path,$path_w,$base_url;

class plugin_manager
{
	public $path;
	public $name;
	public $xml;
	public $to_install;
	
	function __construct($plugin)
	{
		global $path;
		$this->path=$path.'admin/plugin/'.$plugin.'/';
		if(is_file($this->path.'install.xml'))
		{
			$this->name=$plugin;
			$this->xml=new DOMDocument();
			$this->xml->load($this->path.'install.xml');
			$this->to_install=array('fonction','page','ajax','hook','media/css','media/js');
		}
		else
		{
			echo'Il n\'y a pas de ficher d\'installation pour ce plugin ou il n\'existe pas...';
		}
	}
	
	function install($quoi,$node)
	{
		global $path;
		if($node->getAttribute('file')!='')
		{
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
	}
	
	function uninstall($quoi,$node)
	{
		global $path;
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
		global $path;
		$fold=$this->xml->getElementsByTagName('folder');
		foreach($fold as $f)
		{
		  if($f->getAttribute('parent')=='')
		  {
		  	if(!is_dir($path.'data/'.$f->getAttribute('name')))
		  	{
		  		mkdir($path.'data/'.$f->getAttribute('name'));
		  	}		
				if(is_dir($path.'admin/plugin/'.$this->name.'/data/'.$f->getAttribute('name'))){				
					$data=scandir($path.'admin/plugin/'.$this->name.'/data/'.$f->getAttribute('name'));
					foreach($data as  $d){
						copy_r($this->path.'data/'.$f->getAttribute('name').'/'.$d,$path.'data/'.$f->getAttribute('name').'/'.$d);
					}
				}
			}
		  else
		  {
		  	if(!is_dir($path.'data/'.$f->getAttribute('parent').'/'.$f->getAttribute('name')))
		  	{
		  		mkdir($path.'data/'.$f->getAttribute('parent').'/'.$f->getAttribute('name'));
		  	}		    
				if(is_dir($path.'data/'.$f->getAttribute('parent').'/'.$f->getAttribute('name'))){				
					$data=scandir($path.'data/'.$f->getAttribute('parent').'/'.$f->getAttribute('name'));
					foreach($data as  $d){
						copy_r($this->path.'data/'.$f->getAttribute('parent').'/'.$f->getAttribute('name').'/'.$d,$path.'data/'.$f->getAttribute('parent').'/'.$f->getAttribute('name').'/'.$d);
					}
				}
		  }
		}
	}
	
	function install_flag()
	{
		global $base_url;
		$install_t=fopen($this->path.'installed','a');
		fclose($install_t);
		hook('after_plugin_install',array('plugin'=>$this->name));
		echo 'l\'installation de '.$this->name.' c\'est bien deroule<br/><a href="'.$base_url.'admin.php?page_admin=a&module=controll_panel&action=plugin">retour à la gestion des plugins</a>';
	}
		
	function uninstall_flag()
	{
		global $base_url;
		unlink($this->path.'installed');
		hook('after_plugin_uninstall',array('plugin'=>$this->name));
		echo 'la desinstallation de '.$this->name.' c\'est bien deroule<br/><a href="'.$base_url.'admin.php?page_admin=a&module=controll_panel&action=plugin">retour à la gestion des plugins</a>';
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
			$this->install($t,$this->xml->getElementsByTagName($t2)->item(0));
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
			$this->uninstall($t,$this->xml->getElementsByTagName($t2)->item(0));
		}		
		//reste les image a desintaller !!	
		$this->uninstall_flag();
	}
}
