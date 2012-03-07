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
		echo'</pre><br />';
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

function debug()
{
	global$debug_mod;
	return $debug_mod;
}
function trace_mod()
{
	global$trace_mod;
	return $trace_mod;
}

function add_trace($str)
{
	if(trace_mod()==true)
	{
		global $tracer_var;
		array_push($tracer_var,$str);
	}
}

function in_array_keys($key,$array)
{
	if(in_array($key,array_keys($array)))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function in_in_array($v,$array,$depth=1,$key='')
{
	if($key!='' and $array[$key]==$v)
	{
		return $array;
	}	
	elseif(in_array($v,$array) and $key=='')
	{
		return $array;
	}
	else
	{	
		foreach($array as $a)
		{
			if(is_array($a))
			{
				if($key!='' and $a[$key]==$v)
				{
					return $a;
				}	
				elseif(in_array($v,$a) and $key=='')
				{
					return $a;
				}
				elseif($depth>1)
				{
					$res=in_in_array($v,$a,($depth-1),$key);
					if($res!=false)
					{
						return $res;
					}
				}
			}
		}
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
					report_erreur2('2000',__FILE__,__LINE__,'valid_input invalid input '.$input.' with regex '.$c2);
					return false;
				}
			}
		}
		else
		{
			if(preg_match($c,$input))
			{
				hook('valid_input_false',array('input'=>$input,'checker'=>$c));
				report_erreur2('2000_b',__FILE__,__LINE__,'valid_input invalid input '.$input.' with regex '.$c);
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


//compress js and css script
function compresse_text($str,$js=false)//will remove blank, \t, \n ,... to compress js and css file
{
	if(!$js)
	{
		#	http://castlesblog.com/2010/august/14/php-javascript-css-minification
		$str = preg_replace("/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/", "", $str);
		/* remove tabs, spaces, newlines, etc. */
		$str = str_replace(array("\r\n","\r","\t","\n",'  ','    ','     '), '', $str);
		/* remove other spaces before/after ) */
		$str = preg_replace(array('(( )+\))','(\)( )+)'), ')', $str);
	}
	else
	{
		inclure_fonction('lib/jsmin.php');
		$str=jsmin::minify($str);
	}

	return $str;
}

//create a tar.gz file xith a specified directory
function compress_dir($dir,$d_path,$dest_file='',$dest_path='')
{
	if($dest_file=='')
	{
		$dest_file=str_replace('/','_',$dir);
	}
	if(substr($dest_path,-1)!='/' and $dest_path!='')
	{
		$dest_path.='/';
	}
	if(substr($dir,-1)!='/')
	{
		$dir.='/';
	}
		
	$c='cd '.$d_path.' && tar cvzf '.$dest_path.$dest_file.'.tar.gz '.$dir;
#	echo $c;
	return exec($c);
}

//unpack a tar.gz file xith a specified directory
function uncompress_dir($tgz,$u_path)
{		
	$c='cd '.$u_path.' && tar xvzf '.$tgz.'';
#	echo $c;
	return exec($c);
}

//include a php or a file text and treat the error if needed
//$page contain the full path of the page
function inc($page,$php=true,$once=true)
{
	if(substr($page,-1)!='.')
	{
		if(is_file($page))
		{
			if($php)
			{
				if($once==true)
				{
					include_once $page;
				}
				else
				{
					include $page;
				}
				
				return true;
			}
			else
			{
				return file_get_contents($page);
			}
		}
		else
		{
			report_erreur2('0000',__FILE__,__LINE__,'inc '.$page.' does not exists');
			return false;
		}
	}
	
}

//inculde the function page contained in nw/fonction/ with the name contained in $page
function inclure_fonction($page)
{
	global $path;
	return inc($path.'/fonction/'.$page.'.php');
}

//inculde the 'page' page contained in nw/page/ with the name contained in $page
function inclure_page($page,$once=false)
{
	inclure_conf('registered_pages');
	global $path,$pages_files;
	if(in_array($page,$pages_files))
	{
	
	}
	else
	{
		report_erreur2('0003',__FILE__,__LINE__,'inclure_page '.$page.' is not registered');
	}
	return inc($path.'/page/'.$page.'.php',true,$once);
}

//inculde the ajx page contained in nw/ajax/ with the name contained in $page
function inclure_ajax($page,$ext='php')
{
	inclure_conf('registered_ajax');
	global $path,$ajax_files;
	if(in_array($page,$ajax_files))
	{
	
	}
	else
	{
		report_erreur2('0003_b',__FILE__,__LINE__,'inclure_ajax '.$page.' is not registered');
	}
	
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

//inculde the ajx page contained in nw/ajax/ with the name contained in $page
function inclure_conf($page)
{
	global $path;
	$ajax=inc($path.'/data/conf/'.$page.'.conf.php');
	return $ajax;
}

//return a strng containing all the content concacted of the file in the $pages array 
function inclure_text_pages($pages,$sep='',$dir='')
{
	$str='';
	foreach($pages as $p)
	{
		if(substr($p,-1)!='.')
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
	if(!is_file($path.'media/js/balsa_comp_js.php') or is_file($path.'debug'))
	{
		inclure_conf('js');
		global $js_base_url,$js_files,$minify_js;
		$pages=$js_files;

		$js_str=$js_base_url.inclure_text_pages($pages,'');

		if($minify_js==true)
		{
			$js_str=compresse_text($js_str,true);
		}
		if(!file_put_contents($path.'media/js/balsa_comp_js.php',$js_str))
		{
			report_erreur2('0001',__FILE__,__LINE__,'inclure_js cant create the compressed javascript file');
			return false;//$js_str;
		}
	}
	
	return '<script type="text/javascript" src="'.$base_url.'/media/js/js.php"></script>';
	
}

//compress the css files if needed and return the script tag to get it
function inclure_css($min=true,$php=false)
{
	global $path,$path_w,$base_url;
	if(!is_file($path.'media/css/balsa_comp_css.php') or is_file($path.'debug'))
	{
		inclure_conf('css');
		global $css_files,$minify_css;
		$pages=$css_files;

		$css_str=inclure_text_pages($pages,'');

		if($minify_css)
		{
			$css_str=compresse_text($css_str);
		}
		if(!file_put_contents($path.'media/css/balsa_comp_css.php',$css_str))
		{
			report_erreur2('0001_b',__FILE__,__LINE__,'inclure_js cant create the compressed css file');
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

//
function is_pair($i)
{
	if($i%2==0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function get_client_param()
{
	$u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $nav_name = 'unknown';
    $OS_name = 'unknown';
    $version= 'unknown';
    
    if (preg_match('/linux/i', $u_agent)) {
        $OS_name = 'linux';
    }elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $OS_name = 'mac';
    }elseif (preg_match('/windows|win32/i', $u_agent)) {
        $OS_name = 'windows';
    }
    
    if(preg_match('/MSIE/i',$u_agent)) { 
        $nav_name = 'Internet Explorer'; 
        $nav_utilisateur = "MSIE"; 
    }elseif(preg_match('/Firefox/i',$u_agent)){ 
        $nav_name = 'Firefox'; 
        $nav_utilisateur = "Firefox"; 
    }elseif(preg_match('/Chrome/i',$u_agent)){ 
        $nav_name = 'Google Chrome'; 
        $nav_utilisateur = "Chrome"; 
    }elseif(preg_match('/Safari/i',$u_agent)){ 
        $nav_name = 'Apple Safari'; 
        $nav_utilisateur = "Safari"; 
    }elseif(preg_match('/Opera/i',$u_agent)){ 
        $nav_name = 'Opera'; 
        $nav_utilisateur = "Opera"; 
    }elseif(preg_match('/Netscape/i',$u_agent)){ 
        $nav_name = 'Netscape'; 
        $nav_utilisateur = "Netscape"; 
    } 
    
    $known = array('Version', $nav_utilisateur, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (preg_match_all($pattern, $u_agent, $matches)) {
        $i = count($matches['browser']);
        if ($i != 1){
            if (strripos($u_agent,"Version") < strripos($u_agent,$nav_utilisateur)){
                $version= $matches['version'][0];
            }else{
                $version= $matches['version'][1];
            }
        }else{
            $version= $matches['version'][0];
        }        
    }
    
	$navigateur['nom']=$nav_name;
	$navigateur['version']=$version;
	$navigateur['type']='desktop';
	$navigateur['os']= $OS_name;
	$navigateur['complete_user_agent']=$u_agent;
	$_SESSION['client']=array();
	$_SESSION['client']['navigateur']=$navigateur;
	$_SESSION['client']['from']=false;
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

//add error to the session error variable
//0->9000 balsa team
//	0->999 : file system issue
//	1000->1999 : sql issue
//	2000->2999 : problems in fonction.php
//	3000->3999	: bdd_entity
function report_erreur2($code,$file,$line,$erreur,$return=false)
{
	//les erreurs sont stockée dans un tableau, chaque entrée se rentre de la maniere suivante
	//<le_type_de_l'erreur>_<le_descriptif_de_l'erreur>
	hook('before_report_erreur',array('type'=>$type,'erreur'=>$erreur));
	$_SESSION['erreurs'][$_SESSION['count_erreurs']]='<error file="'.$file.'" line="'.$line.'" code="'.$code.'">'.$erreur.'</error>';
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
	global $get, $path, $bdd;
	hook('before_traite_fin_page',array());
	$gen_time=microtime(true)-$_SESSION['in_time'];
	$final_mem=memory_get_usage();
	$rapport='<request time="'.date('Y-m-d H:i:s').'" gen="'.$gen_time.'" start_mem="'.$_SESSION['mem_use'].'" final_mem="'.$final_mem.'" peak_mem="'.memory_get_peak_usage().'"><client id="'.$_SESSION['user_id'].'" ip="'.$_SERVER['REMOTE_ADDR'].'"><navigateur>';
	
	foreach($_SESSION['client']['navigateur'] as $info=>$val)
	{
		$rapport.='<'.$info.'>'.$val.'</'.$info.'>';
	}
	
	$rapport.='</navigateur><url>'.$_SERVER['REQUEST_URI'].'</url><sql nb_req="'.$bdd->nbreq.'" req_time="'.$bdd->reqtime.'" />';
	
	if($_SESSION['count_erreurs']>0)
	{
		$rapport.='<errors>';
		
		foreach($_SESSION['erreurs'] as $erreur)
		{
			$rapport.=$erreur;
		}
		
		$rapport.='</errors>';
		$_SESSION['erreurs']=array();
		$_SESSION['count_erreurs']=0;
	}
	
	hook('traite_fin_de_page_compete_report',array("report"=>$rapport));
	
	$rapport.=$_HOOK['report'].'</client>';
	
	if(isset($_POST))
	{
		$rapport.='<post>';
		foreach($_POST as $k=>$v)
		{
			$rapport.='<v>"'.$k.'" => "'.$v.'"';
		}		
		$rapport.='</post>';
	}
	$rapport.='</request>

';
	
	
	$log_file=date('Y_m_d');
	$log=fopen($path.'data/log/'.$log_file,'a+');
	fputs($log,$rapport);
	fclose($log);
	return true;
}


//dummy stuff, need to be coded and tsted :p
class activity_logger
{
	public $log='';
	
	function load($fichier='')
	{
		global $inclure,$path;
		if($fichier=='')
		{
			$fichier=date('Y_m_d');
		}
		$this->log=$inclure->text_pages(array($path.'data/log/'.$fichier));
		$this->log=
		'
		<log>
			'.$this->log.'
		</log>
		';
		return $this->log;		
	}
	
	function compress()
	{
		$this->log=compresse_text($this->log);
		return $this->log;
	}
	

	function merge($debut='',$fin='')
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
		$this->log=$logged;
		foreach($merged as $m)
		{
			unlink($m);
		}
		return $this->log;
	}
}

function truncate_str($str,$length=55,$from=0)
{
	$str=strip_tags($str);
	$str2=substr($str,$from,$length);
	if(strlen($str)!=strlen($str2))
	{
		$str2.=' ...';
	}
	return $str2;
}

//http://www.catswhocode.com/blog/10-php-code-snippets-for-working-with-strings
function seo_friendly_str($str)
{
	$str = strtolower(trim($str));
	$str = preg_replace('/[^a-z0-9-]/', '-', $str);
	$str = preg_replace('/-+/', "-", $str);
	return $str;
}

//
function get_request_location($ip='')
{
	if($ip=='')
	{
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	 $default = 'UNKNOWN';

        if (!is_string($ip) || strlen($ip) < 1 || $ip == '127.0.0.1' || $ip == 'localhost')
            $ip = '8.8.8.8';

        $curlopt_useragent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)';

        $url = 'http://ipinfodb.com/ip_locator.php?ip=' . urlencode($ip);
        $ch = curl_init();

        $curl_opt = array(
            CURLOPT_FOLLOWLOCATION  => 1,
            CURLOPT_HEADER      => 0,
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_USERAGENT   => $curlopt_useragent,
            CURLOPT_URL       => $url,
            CURLOPT_TIMEOUT         => 1,
            CURLOPT_REFERER         => 'http://' . $_SERVER['HTTP_HOST'],
        );

        curl_setopt_array($ch, $curl_opt);

        $content = curl_exec($ch);

        if (!is_null($curl_info)) {
            $curl_info = curl_getinfo($ch);
        }

        curl_close($ch);

        if ( preg_match('{<li>City : ([^<]*)</li>}i', $content, $regs) )  {
            $city = $regs[1];
        }
        if ( preg_match('{<li>State/Province : ([^<]*)</li>}i', $content, $regs) )  {
            $state = $regs[1];
        }

        if( $city!='' && $state!='' ){
          $location = $city . ', ' . $state;
          return $location;
        }else{
          return $default;
        }
}

function gen_password($length=9, $strength=4) {
	$vowels = 'aeiouy';
	$consonants = 'bcdfghjklmnpqrstvwz';
	if ($strength >= 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength >= 2) {
		$vowels .= "AEIOUY";
	}
	if ($strength >= 4) {
		$consonants .= '23456789';
	}
	if ($strength >= 8 ) {
		$vowels .= '@_-*';
	}

	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}

function currency($from_Currency,$to_Currency,$amount) {
    $amount = urlencode($amount);
    $from_Currency = urlencode($from_Currency);
    $to_Currency = urlencode($to_Currency);
    $url = "http://www.google.com/ig/calculator?hl=en&q=$amount$from_Currency=?$to_Currency";
    $ch = curl_init();
    $timeout = 0;
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $rawdata = curl_exec($ch);
    curl_close($ch);
    $data = explode('"', $rawdata);
    $data = explode(' ', $data['3']);
    $var = $data['0'];
    return round($var,2);
}

function get_real_ip(){
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function captcha($img_id='captcha',$input='captcha_code')
{
	global $base_url,$path_w;
	$display=
	'
	<img id="'.$img_id.'" src="'.$base_url.'media/lib/securimage/securimage_show.php" alt="CAPTCHA Image" />
	<input type="text" name="'.$input.'" size="10" maxlength="6" />
2	<a href="#" onclick="document.getElementById(\''.$img_id.'\').src = \''.$base_url.'media/lib/securimage/securimage_show.php?\' + Math.random(); return false"></a>
	';
	include_once $path_w.'media/lib/securimage/securimage.php';
	$securimage=new Securimage();
	return $display;
}

function check_captcha($input='captcha_code',$met='post')
{
	global $path_w;
	if($met=='post')
	{
		$met=$_POST;
	}
	else
	{
		$met=$_GET;
	}
	include_once $path_w.'media/lib/securimage/securimage.php';
	$securimage=new Securimage();
	return $securimage->check($_POST[$input])==false;
}

function upload_field($input='file_upload',$label='Choose the file to uplaod',$max_size='',$classes=array('label'=>'form_label','input'=>''))
{
	$display=
	'
	<label for="'.$input.'" class="'.$classes['label'].'" >'.$label.' : </label><input type="file" id="'.$input.'" name="'.$input.'" class="'.$classes['input'].'" />
	';
	
	if($max_size!='')
	{
		$display.='<input type="hidden" name="MAX_FILE_SIZE" value="'.($max_size*1024).'" />';
	}
	
	return $display;
}

function handle_upload_field($target,$input='file_upload',$name='')
{
	if(is_dir($target))
	{
		if(isset($_FILES[$input]))
		{
			if($name!='')
			{
				$target.=$name.'.'.end(explode('.', $_FILES[$input]['name']));
			}
			else
			{
				$target.=$_FILES[$input]['name'];
			}
			return copy($_FILES[$input]['tmp'],$target);
		}
		else
		{
			return false;
		}
	}
	else
	{
		report_erreur2('0005',__FILE__,__LINE__,'handle_upload directory does not exists : '.$target);
		return false;
	}
}

//file manipulation function
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
    	report_erreur2('0002',__FILE__,__LINE__,'copy_r cant copy '.$path.' to '.$dest);
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
            rmdir_r($file);
        } else {
            unlink($file);
        }
    }
    if(rmdir($dir)){
        return true;
    }else{
    	report_erreur2('0003',__FILE__,__LINE__,'rmdir_r cant remove '.$dir);
        return false;
    }
}

function copies($paths)
{
	if(is_array($paths))
	{
		$from=$paths["from"];
		$to=$paths["to"];
		if(is_array($from) and is_array($to) and count($from)==count($to))
		{
			$x=0;
			foreach($from as $f)
			{
				if(is_dir($f))
				{
					copy_r($f,$to[$x]);
				}
				else
				{
					copy($f,$to[$x]);
				}
				$x++;
			}
		}
		else
		{
			report_erreur2('2003',__FILE__,__LINE__,'copies unmatching parameter for froms and tos');
			return false;
		}
	}
	else
	{
		report_erreur2('2004',__FILE__,__LINE__,'copies unmatching parameter for paths, must be an array');
		return false;
	}
}

function mkdir_r($dir,$mode=0755)
{
	$dir=explode($dir,'/');
	if($dir[0]=='')
	{
		$dir2='/'.$dir[1];
		$x=2;
	}
	else
	{
		$dir2=$dir[0];
		$x=1;
	}
	if(!is_dir($dir2))
	{
		if(!mkdir($dir2,$mode))
		{
			return false;
		}
	}
	$y=count($dir);
	while($x<$y)
	{
		if($dir[$x]!='')
		{
			$dir2.='/'.$dir[$x];
			
			if(!is_dir($dir2))
			{
				if(!mkdir($dir2,$mode))
				{
					return false;
				}
			}
		}
		$x++;
	}
	return true;
}

function is_dir_mk($dir,$mode=0755)
{
	if(!is_dir($dir))
	{
		return mkdir_r($dir,$mode);
	}
	else
	{
		return true;
	}
}

//dummy stuff for time operation
function get_times_month($month,$year='')
{
	if($year=='')
	{
		$year=date('Y');
	}
	$from=mktime(0,0,0,$month,1,$year);
	$to=mktime(23,59,0,$month+1,0,$year);
	return array($from,$to);
}

function get_next_month($month,$year='')
{
	if($year=='')
	{
		$year=time();
	}
	if(date('m',$month)=="12")
	{
		return '1&a='.(date('Y',$year)+1);
	}
	else
	{
		return (date('m',$month)+1).'&a='.(date('Y',$year));
	}
}

function get_last_month($month,$year='')
{
	if($year=='')
	{
		$year=time();
	}
	if(date('m',$month)=="01")
	{
		return array(12,date('Y',$year)-1);
	}
	else
	{
		return array(date('m',$month)-1,date('Y',$year));
	}
}

function get_jour_nom($i)
{
	switch($i)
	{
		case 1:
			return 'lundi';
			break;
		case 2:
			return 'mardi';
			break;
		case 3:
			return 'mercredi';
			break;
		case 4:
			return 'jeudi';
			break;
		case 5:
			return 'vendredi';
			break;
		case 6:
			return 'samedi';
			break;
		case 7:
			return 'dimanche';
			break;
	}
}

function daystamp($t='')
{
	if($t=='')
	{
		$t=time();
	}
	return mktime(0,0,0,date('n',$t),date('j',$t),date('Y',$t));
}

function tomorowstamp($t='')
{
	if($t=='')
	{
		$t=time();
	}
	return mktime(0,0,0,date('n',$t),date('j',$t)+1,date('Y',$t));
}

function yesterdaystamp($t='')
{
	if($t=='')
	{
		$t=time();
	}
	return mktime(0,0,0,date('n',$t),date('j',$t)-1,date('Y',$t));
}

function monthstamp($t='')
{
	if($t=='')
	{
		$t=time();
	}
	return mktime(0,0,0,date('n',$t),1,date('Y',$t));
}

function to_heure($sec,$sep=':')
{
	$hours = floor($sec / 3600);
	$minutes = floor(($sec / 60) % 60);
	if($hours<10)
	{
		$hours='0'.$hours;
	}
	if($minutes<10)
	{
		$minutes='0'.$minutes;
	}
	return $hours.$sep.$minutes;
}

function to_second($h,$sep=':')
{
	if(strlen($h)<=2)
	{
		$h.=$sep.'00';
	}
	$h=explode($sep,$h);
	$h=(((int)$h[0])*3600)+(((int)$h[1])*60);
	return $h;
}

function day_selector($id='',$today=true)
{
	$display=
	'
	<select id="day_'.$id.'" name="day_'.$id.'">
	';
	$x=1;
	while($x<=31)
	{
		$display.=
		'
		<option value="'.$x.'"
		';
		if($today==true)
		{
			if(date('d')==$x)
			{
				$display.='selected';
			}
		}
		$display.='>'.$x.'</option>';
		$x++;
	}
	$display.=
	'
	</select>
	';
	return $display;
}

function month_selector($id='',$today=true)
{
	$display=
	'
	<select id="month_'.$id.'" name="month_'.$id.'">
	';
	$x=1;
	while($x<=12)
	{
		$display.=
		'
		<option value="'.$x.'"
		';
		if($today==true)
		{
			if(date('m')==$x)
			{
				$display.='selected';
			}
		}
		$display.='>'.$x.'</option>';
		$x++;
	}
	$display.=
	'
	</select>
	';
	return $display;
}

function year_selector($id='',$today=true,$start='',$end='')
{
	$display=
	'
	<select id="year_'.$id.'" name="year_'.$id.'">
	';
	
	if($start=='')
	{
		$start=date('Y');
	}
	if($end=='')
	{
		$end=$start+25;
	}
	
	$x=$start;
	while($x<=$end)
	{
		$display.=
		'
		<option value="'.$x.'"
		';
		if($today==true)
		{
			if(date('Y')==$x)
			{
				$display.='selected';
			}
		}
		$display.='>'.$x.'</option>';
		$x++;
	}
	$display.=
	'
	</select>
	';
	return $display;
}
?>
