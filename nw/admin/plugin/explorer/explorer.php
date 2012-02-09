<?php

class explorer
{	
	function dir_xml($path,$md5=false)
	{
		
		if( is_dir($path) )
		{
		    $objects = scandir($path);
		    if( sizeof($objects) > 0 )
		    {
		        foreach( $objects as $file )
		        {
		            if( $file == "." || $file == ".." )
		            {
		            	continue;
		            }
		            preg_replace('#/#',"",$file);
		            if( is_dir( $path.'/'.$file ) )
		            {
		                $xml.='<dir path="'.$path.'/'.$file.'" name="'.$file.'">'.$this->dir_xml( $path.'/'.$file,$md5).'</dir>';
		            }
		            else
		            {
		            	if($md5==true)
		            	{
		            		$fmd5='<md5>'.md5_file($path.'/'.$file).'</md5>';
		            	}
		            	else
		            	{
		            		$fmd5='';
		            	}
		                $xml.='<file path="'.$path.'/'.$file.'" name="'.$file.'" type="'.filetype($path.'/'.$file).'" size="'.filesize($path.'/'.$file).'">'.$fmd5.'</file>';
		            }
		        }
		    }
		    return $xml;
		}
		elseif( is_file($path) )
		{
			$xml.='<file path="'.$path.'" name="'.$path.'" perms="'.fileperms($path).'" owner="'.fileowner($path).'" time="'.filemtime($path).'" type="'.filetype($path).'" size="'.filesize($path).'"></dir>';
		    
		    return $xml;
		}
		else
		{
		    return false;
		}
	}
	
	function serv_xml($no_path=true,$file='dir')
	{
		global $path;
		if(!is_file($path.'data/explorer/'.$file.'.xml'))
		{
			$p=substr($path,0,-1);
			$xml='<root>'.$this->dir_xml($p).'</root>';
			if($no_path)
			{
				$xml=str_replace($path,'',$xml);
			}
			if(!file_put_contents($path.'data/explorer/'.$file.'.xml',$xml))
			{
				return false;
			}
		}
		header('Content-type: text/xml');
		include_once($path.'data/explorer/'.$file.'.xml');
		return true;
	}
	
	function back_xml($file='dir')
	{
		global $path;
		if(is_file($path.'data/explorer/'.$file.'.xml'))
		{
			if(!rename($path.'data/explorer/'.$file.'.xml',$path.'data/explorer/'.$file.'_'.time().'.xml'))
			{
				return false;
			}
		}
		return $this->serv_xml();
	}
	
	function rm_xml($file='dir')
	{
		global $path;
		if(is_file($path.'data/explorer/'.$file.'.xml'))
		{
			if(unlink($path.'data/explorer/'.$file.'.xml'))
			{
				return true;
			}
		}
	}
	
	#http://php.net/manual/en/function.copy.php
	function copy_r( $from, $dest )
	{
		if( is_dir($from) )
		{
		    @mkdir( $dest );
		    $objects = scandir($from);
		    if( sizeof($objects) > 0 )
		    {
		        foreach( $objects as $file )
		        {
		            if( $file == "." || $file == ".." )
		                continue;
		            // go on
		            if( is_dir( $from.'/'.$file ) )
		            {
		                copy_r( $from.'/'.$file, $dest.'/'.$file );
		            }
		            else
		            {
		                copy( $from.'/'.$file, $dest.'/'.$file );
		            }
		        }
		    }
		    return true;
		}
		elseif( is_file($from) )
		{
		    return copy($from, $dest);
		}
		else
		{
		    return false;
		}
	}

	#http://nashruddin.com/Remove_Directories_Recursively_with_PHP
	function rmdir_r($dir) 
	{
		$files = scandir($dir);
		array_shift($files);    // remove '.' from array
		array_shift($files);    // remove '..' from array
	   
		foreach ($files as $file) 
		{
		    $file = $dir . '/' . $file;
		    if (is_dir($file)) 
		    {
		        rmdir_r($file);
		        rmdir($file);
		    }
		    else
		    {
		        unlink($file);
		    }
		}
		rmdir($dir);
	}

}

?>
