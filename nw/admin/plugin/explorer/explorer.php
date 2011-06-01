<?php

class explorer
{	
	function dir_xml($path)
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
		                $xml.='<dir path="'.$path.'/'.$file.'" name="'.$file.'">'.$this->dir_xml( $path.'/'.$file).'</dir>';
		            }
		            else
		            {
		                $xml.='<file path="'.$path.'/'.$file.'" name="'.$file.'" perms="'.fileperms($path.'/'.$file).'" owner="'.fileowner($path.'/'.$file).'" time="'.filemtime($path.'/'.$file).'" type="'.filetype($path.'/'.$file).'" size="'.filesize($path.'/'.$file).'"></file>';
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
	
	function serv_xml($no_path=true)
	{
		global $path;
		if(!is_file($path.'data/dir_xml/dir.xml'))
		{
			$p=substr($path,0,-1);
			$xml='<root>'.$this->dir_xml($p).'</root>';
			if($no_path)
			{
				$xml=str_replace($path,'',$xml);
			}
			if(!file_put_contents($path.'data/dir_xml/dir.xml',$xml))
			{
				return false;
			}
		}
		header('Content-type: text/xml');
		include_once($path.'data/dir_xml/dir.xml');
		return true;
	}
	
	function back_xml()
	{
		global $path;
		if(is_file($path.'data/dir_xml/dir.xml'))
		{
			if(!rename($path.'data/dir_xml/dir.xml',$path.'data/dir_xml/'.time().'_dir.xml'))
			{
				return false;
			}
		}
		return $this->serv_xml();
	}
	
	function rm_xml()
	{
		global $path;
		if(is_file($path.'data/dir_xml/dir.xml'))
		{
			if(unlink($path.'data/dir_xml/dir.xml'))
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
