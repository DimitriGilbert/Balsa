<?php

class serv_req
{
	protected $type,$url,$vars,$rep,$out_file;
	public $curl;
	
	function __construct($url='',$type='',$vars='',$out_file='',$exec=false)
	{
		$this->curl=curl_init();
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curl, CURLOPT_BINARYTRANSFER, true);
		
		if($url!='')
		{
			$this->set('url',$url);
		}
		if($vars!='')
		{
			$this->set('vars',$vars);
		}
		if($out_file!='')
		{
			$this->set('file',$out_file);
		}
		if($type=='')
		{
			$this->set('type','get');
		}
		
		$this->type=$type;
		
		if($exec==true)
		{
			$this->exec(true);
		}
	}
	
	function set($k,$v)
	{
		switch($k)
		{
			case'method':
				return $this->set('type',$v);
				break;
			case'type':
				$this->type=$v;
				return true;				
				break;
			case'url':
				$this->url=$v;
				curl_setopt($this->curl,CURLOPT_URL,$v);
				return true;				
				break;
			case'out_file':
				return $this->set('file',$v);
				break;
			case'file':
				$this->out_file=$v;
				return true;
				break;
			case'vars':
				if(is_str($v))
				{
					return $this->str2vars($v);
				}
				elseif(is_array($v))
				{
					$this->vars=$v;
					return true;
				}
				else
				{
					return false;
				}
				
				break;
		}
	}
	
	function get($k)
	{
		switch($k)
		{
			case'method':
				return $this->get('type');
				break;
			case'type':
				return $this->type;								
				break;
			case'url':
				return $this->url;				
				break;
			case'out_file':
				return $this->get('file');
				break;
			case'file':
				return $this->out_file;				
				break;
			case'vars':
				return $this->vars;
				break;
			case'var_str':
				return $this->vars2str();
				break;
			case'rep':
				return $this->rep;
				break;
		}
	}
	
	function set_var($vn,$value)
	{
		$this->vars[$vn]=$value;
		return true;
	}
	
	function get_var($vn)
	{
		if(in_array_keys($this->vars,$vn))
		{
			return $this->vars[$vn];
		}
		else
		{
			return false;
		}
	}
	
	function vars2str()
	{
		$vstr='';
		foreach($this->vars as $k=>$v)
		{
			$vstr.=$k.'='.$v.'&';
		}
		rtrim($vstr,'&');
		return $vstr;
	}
	
	function str2vars($str,$add=false)
	{
		if($add==false)
		{
			$this->vars=array();
		}
		
		$ex=explode('&',$str);
		$ex2='';
		foreach($ex as $e)
		{
			$ex2=explode('=',$e);
			$this->vars[$ex2[0]]=$ex2[1];
		}
		return true;
	}
	
	function exec($ret=false)
	{
		if($this->get('type')=='post')
		{
			curl_setopt($this->curl,CURLOPT_POST,count($this->vars));
			curl_setopt($this->curl,CURLOPT_POSTFIELDS,$this->vars);
		}
		elseif($this->get('type')=='get' and count($this->get('vars'))>0)
		{
			$var_str=$this->vars2str();
			if(preg_match('#\?#',$this->get('url')))
			{
				$this->set('url',$this->get('url').'&'.$var_str);
			}
			else
			{
				$this->set('url',$this->get('url').'?'.$var_str);			
			}
		}
		if($ret!=false or $this->get('file')!='')
		{
			$this->rep=curl_exec($this->curl);
			if($this->get('file')!='')
			{
				$filep=file_put_contents($this->get('file'),$this->rep);
			}
			if($ret==true)
			{
				return $this->rep;
			}
			else
			{
				if($this->get('file')!='')
				{
					return $filep;
				}
			}
		}
		else
		{
			return false;
		}
		
	}
	
	function download($url='',$file='',$return=false)
	{
		if($file=='' and $this->get('file')=='')
		{
			$return=true;
		}
		if($file!='')
		{
			$this->set('file',$file);
		}
		if($url!='')
		{
			$this->set('url',$url);
		}
		return $this->exec($return);
	}
	
	function add_file_to_upload($up_name,$file_path)
	{
		if(is_file($file_path))
		{
			return $this->set_var($up_name,'@'.$file_path);
		}
		else
		{
			return false;
		}
	}
		
	function __destruct()
	{
		curl_close($this->curl);
	}
	
}

?>
