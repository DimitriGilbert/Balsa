<?php

//it dump a print_r result of the variable $var into pres tag, if $r=true , the result will be return
function print_pre($var,$r=false)
{
	if($r!=false)
	{
		return '<pre>'.print_r($var,true).'</pre>';
	}
	else
	{
		echo'<pre>';
		print_r($var);
		echo'</pre>';
	}	
}

//echo $s into a div with a "plop" class.....sorry ^^
function plop($s="")
{
	echo '<div class="plop">'.$s.'</div>';
}

//check if the current request have a session open and logged
function is_logged()
{
	hook('before_is_logged',array());
	if(isset($_SESSION['user_id']) and $_SESSION['user_id']!='')
	{
		return true;
	}
	else
	{
		return false;
	}
}

//check $input with several regular expression see the function below for the names of the different regex,
//if you want to add regex send me a mail with yours and i will implement them :)
function valid_input($input,$checker=array('default'))
{
	hook('before_valid_input',array('input'=>$input));
	foreach($checker as $check)
	{
		$c=pre_reg($check);
		if(is_array($c))
		{
			foreach($c as $c2)
			{
				if(preg_match($c2,$input))
				{
					hook('valid_input_false',array('input'=>$input,'checker'=>$c2));
					return false;
				}
			}
		}
		else
		{
			if(preg_match($c,$input))
			{
				hook('valid_input_false',array('input'=>$input,'checker'=>$c));
				return false;
			}
		}
		
	}
	
	hook('after_valid_input',array('input'=>$input));
	return true;
	
}
//return a regular expression correspondant to $type, these names are used in the function upper
function pre_reg($type)
{
	switch($type)
	{
		case 'injection':
			return array('[\x00\x08\x0B\x0C\x0E-\x1F]','[^\P{Cc}\t\r\n]',"/".preg_quote("~`!#$%^&*()+=\\][{}¦;\"'?/><","/")."/");
			break;
		case 'default':
			return array('[\x00\x08\x0B\x0C\x0E-\x1F]','[^\P{Cc}\t\r\n]',"/".preg_quote("~`!#$%^&*()+=\\][{}¦;\"'?/><","/")."/");
			break;
		case 'mail':
			return '\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b';
			break;
		default:
			return array('[\x00\x08\x0B\x0C\x0E-\x1F]','[^\P{Cc}\t\r\n]',"/".preg_quote("~`!#$%^&*()+=\\][{}¦;\"'?/><","/")."/");
			break;
	}
}

//add error to the session error variable
function report_erreur($type,$erreur,$return=false)
{
	//les erreurs sont stockée dans un tableau, chaque entrée se rentre de la maniere suivante
	//<le_type_de_l'erreur>_<le_descriptif_de_l'erreur>
	hook('before_report_erreur',array('type'=>$type,'erreur'=>$erreur));
	$_SESSION['erreurs'][$_SESSION['count_erreurs']]=$type.'_'.$erreur;
	$_SESSION['count_erreurs']++;
	if($return!==false)
	{
		return _($type.'_'.$erreur);
	}
}

//return the different errors reported with report_erreur(), formated in html 
function traite_erreur($erreurs)
{
	$display=
	'
		<div class="error_wrapper">
			<div>'._('Les erreurs suivantes ont été rencontrées').'</div>
	';
	foreach($erreurs as $err)
	{
		$t=explode($err,'_');
		$display.=
		'
			<div class="error">
				<div class="error_type">'._($t[0]).'</div>
				<div class="error_desc">'._($t[1]).'</div>
			</div>
		';
	}
	$display.=
	'
		</div>
	';
	return $display;
}

//add logs and finish the page process :)
function traite_fin_de_page()
{
	global $get, $path;
	hook('before_traite_fin_page',array());
	$gen_time=microtime()-$_SESSION['in_time'];
	$rapport=
	'
		<requete time="'.date('Y-m-d H:i:s').'" gen="'.$gen_time.'">
			<client id="'.$_SESSION['user_id'].'">
				<navigateur>
	';
	foreach($_SESSION['client']['navigateur'] as $info=>$val)
	{
		$rapport.=
		'
			<'.$info.'>'.$val.'</'.$info.'>
		';
	}
	$rapport.=
	'
		</navigateur>
		<url>
			'.$_SERVER['REQUEST_URI'].'
		</url>
	';
	
	if($_SESSION['count_erreurs']>0)
	{
		$rapport.=
		'
			<erreurs>
		';
		
		foreach($_SESSION['erreurs'] as $erreur)
		{
			$rapport.=
			'
				<erreur>'.$erreur.'</erreur>
			';
		}
		
		$rapport.=
		'
			</erreurs>
		';
	}
	hook('traite_fin_de_page_compete_report',array("report"=>$rapport));
	$rapport.=$_HOOK['report'];
	'
			</client>
		</requete>
	';
	
	
	$log_file=date('Y_m_d');
	$log=fopen($path.'data/log/'.$log_file,'a+');
	fputs($log,$rapport);
	fclose($log);
	return true;
}

//compress js and css script
function compresse_text($str)//will remove blank, \t, \n ,... to compress js and css file
{
#	http://castlesblog.com/2010/august/14/php-javascript-css-minification
	$str = preg_replace("/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/", "", $str);
    /* remove tabs, spaces, newlines, etc. */
    $str = str_replace(array("\r\n","\r","\t","\n",'  ','    ','     '), '', $str);
    /* remove other spaces before/after ) */
    $str = preg_replace(array('(( )+\))','(\)( )+)'), ')', $str);
	return $str;
}

//create a tar.gz file xith a specified directory
function compress_dir($dir,$d_path,$dest_file='',$dest_path='')
{
	if($dest_file=='')
	{
		$dest_file=str_replace('/','_',$dir);
	}
	if($dest_path=='')
	{
		$dest_file=$path;
	}
	
	$c='cd '.$d_path.' && tar cvzf '.$dest_path.$dest_file.'.tar.gz '.$dir;
#	echo $c;
	echo exec($c);
}

//include a php or a file text and treat the error if needed
//$page contain the full path of the page
function inc($page,$php=true)
{
	if(is_file($page))
	{
		if($php)
		{
			include_once $page;
			return true;
		}
		else
		{
			return file_get_contents($page);
		}
	}
	else
	{
		report_erreur('systeme','la page '.$page.' n\'existe pas.');
		return false;
	}
}

//inculde the function page contained in nw/fonction/ with the name contained in $page
function inclure_fonction($page)
{
	global $path;
	return inc($path.'/fonction/'.$page.'.php');
}

//inculde the 'page' page contained in nw/page/ with the name contained in $page
function inclure_page($page)
{
	global $path;
	return inc($path.'/page/'.$page.'.php');
}

//inculde the ajx page contained in nw/ajax/ with the name contained in $page
function inclure_ajax($page,$ext='php')
{
	global $path;
	if($ext!='php')
	{
		$b=false;
	}
	else
	{
		$b=true;
	}
	$ajax=inc($path.'/ajax/'.$page.'.'.$ext,$b);
	return $ajax;
}

//return a strng containing all the content concacted of the file in the $pages array 
function inclure_text_pages($pages,$sep='',$dir='')
{
	$str='';
	foreach($pages as $p)
	{
		if($p!='.' or $p!='..')
		{
			$str.=$sep.inc($dir.$p,false);		
		}
	}
	return $str;
}

//compress the js files if needed and return the script tag to get it
function inclure_js($min=false,$php=false)
{
	global $path,$base_url;
	if(!is_file($path.'media/js/balsa_comp_js.php'))
	{
		$pages=scandir($path.'media/js/');

		$js_str=inclure_text_pages($pages,'', $path.'media/js/');

		if($min)
		{
			$js_str=compresse_text($js_str);
		}
		if(!file_put_contents($path.'media/js/balsa_comp_js.php',$js_str))
		{
			return false;//$js_str;
		}
	}
	
	return '<script type="text/javascript" src="'.$base_url.'/media/js/js.php"></script>';
	
}

//compress the css files if needed and return the script tag to get it
function inclure_css($min=true,$php=false)
{
	global $path,$path_w,$base_url;
	if(!is_file($path_w.'media/css/css.css'))
	{
		$pages=scandir($path.'media/css/');

		$css_str=inclure_text_pages($pages,'', $path.'media/css/');

		if($min)
		{
			$css_str=compresse_text($css_str);
		}
		if(!file_put_contents($path.'media/css/balsa_comp_css.php',$css_str))
		{
			return false;//$js_str;
		}
	}
	
	return '<link rel="stylesheet" href="'.$base_url.'media/css/css.php" type="text/css" media="all" />';
	
}

function inclure_stat($page,$ext='php')
{
	global $path;
	if($ext!='php')
	{
		$b=false;
	}
	else
	{
		$b=true;
	}
	$ajax=inc($path.'/ajax/'.$page.'.'.$ext,$b);
	return $ajax;
}

//put that function anywhere in an other function to include a hook file
//$hook_name is the name of the file without the extension (.php)
//$hook_var is an array with all the var you want to use in your hook, you'll caall them via $_HOOK in your hook file
//if you want to return a html content you have to pass it through a $_HOOK variable and get it after the  hook execution
function hook($hook_name,$hook_var)
{
	global $_HOOK,$path,$path_w,$base_url;
	$_HOOK=$hook_var;
	if(is_file($path.'hook/'.$hook_name.'.php'))
	{
		return include $path.'hook/'.$hook_name.'.php';
	}
	else
	{
		//report_erreur
	}
}

//dummy stuff, need to be coded and tsted :p
class activity_logger
{
	function load($fichier='')
	{
		global $inclure,$path;
		if($fichier=='')
		{
			$fichier=date('Y_m_d');
		}
		$log=$inclure->text_pages(array($path.'data/log/'.$fichier));
		$log=
		'
		<log>
			'.$log.'
		</log>
		';
		return $log;
		
	}

	function merge_log($debut='',$fin='')
	{
		global $inclure,$path;
		if($fin=='')
		{
			$fin=date('Y_m_d');
		}
		if($debut=='')
		{
			$t=time();
			$t-=604800;
			$debut=date('Y_m_d',$t);
		}
		$dir=scandir($path.'/data/log/');
		$merged=array();
		foreach($dir as $d)
		{
			if($d==$debut)
			{
				$b=true;
			}
			if($b===true)
			{
				array_push($merged,$path.'/data/log/'.$d);
			}
			if($d==$fin)
			{
				$b=false;
				break;
			}
		}
		$logged=$inclure->text_pages($merged,'<!-- new day -->');
		$log=fopen($path.'/data/log/'.$debut.'__'.$fin);
		fput($logged,$log);
		fclose($log);
		
		foreach($merged as $m)
		{
			unlink($m);
		}		
	}
}

function copy_r( $path, $dest )
{
    if( is_dir($path) )
    {
        @mkdir( $dest );
        $objects = scandir($path);
        if( sizeof($objects) > 0 )
        {
            foreach( $objects as $file )
            {
                if( $file == "." || $file == ".." )
                    continue;
                // go on
                if( is_dir( $path.'/'.$file ) )
                {
                    copy_r( $path.'/'.$file, $dest.'/'.$file );
                }
                else
                {
                    copy( $path.'/'.$file, $dest.'/'.$file );
                }
            }
        }
        return true;
    }
    elseif( is_file($path) )
    {
        return copy($path, $dest);
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
   
    foreach ($files as $file) {
        $file = $dir . '/' . $file;
        if (is_dir($file)) {
            rmdir_recursive($file);
            rmdir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dir);
}
?>
