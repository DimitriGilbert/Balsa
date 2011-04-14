<?php
function is_logged()
{
	if(isset($_SESSION['user_id']) and $_SESSION['user_id']!='')
	{
		return true;
	}
	else
	{
		return false;
	}
}

function valid_input($input,$checker=array('default'))
{
	
	foreach($checker as $check)
	{
		$c=pre_reg($check);
		if(is_array($c))
		{
			foreach($c as $c2)
			{
				if(preg_match($c2,$input))
				{
					return false;
				}
			}
		}
		else
		{
			if(preg_match($c,$input))
			{
				return false;
			}
		}
		
	}
	return true;
	
}

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

function report_erreur($type,$erreur,$return=false)
{
	//les erreurs sont stockée dans un tableau, chaque entrée se rentre de la maniere suivante
	//<le_type_de_l'erreur>_<le_descriptif_de_l'erreur>
	$_SESSION['erreurs'][$_SESSION['count_erreurs']]=$type.'_'.$erreur;
	$_SESSION['count_erreurs']++;
	if($return!==false)
	{
		return _($type.'_'.$erreur);
	}
}

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

function inclure_fonction($fonction)
{
	global $basep;
	if(is_file($basep.'fonction/'.$fonction.'.php'))
	{
		include_once $basep.'fonction/'.$fonction.'.php';
		return true;
	}
	else
	{
		$fonction=str_replace('_','-',$fonction);
		report_erreur('systeme','la page de fonction '.$fonction.' n\'existe pas.');
		return false;
	}
}

function inclure_ajax($fonction)
{
	global $basep;
	if(is_file($basep.'ajax/'.$fonction.'.php'))
	{
		include_once $basep.'ajax/'.$fonction.'.php';
		return true;
	}
	else
	{
		$fonction=str_replace('_','-',$fonction);
		report_erreur('systeme','la page ajax '.$fonction.' n\'existe pas.');
		return false;
	}
}

function traite_fin_de_page()
{
	global $get;
	global $basep;
	$gen_time=microtime()-$_SESSION['in_time'];
	$rapport=
	'
		<requete time="'.date('Y-m-d H:i:s').'" gen="'.$gen_time.'">
			<client id="'.$_SESSION['user_id'].'">
				<navigateur>
	';
	/*foreach($_SESSION['client']['navigateur'] as $info=>$val)
	{
		$rapport.=
		'
			<'.$info.'>'.$val.'</'.$info.'>
		';
	}*/
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
	$rapport.=
	'
			</client>
		</requete>
	';
	
	
	$log_file=date('Y_m_d');
	$log=fopen($basep.'data/log/'.$log_file,'a+');
	fputs($log,$rapport);
	fclose($log);
	return true;
}

function load_log($fichier='')
{
	global $basep;
	if($fichier=='')
	{
		$fichier=date('Y_m_d');
	}
	if(is_file($basep.'/data/log/'.$fichier))
	{
		$log=
		'
		<log>
			'.file_gets_content($basep.'/data/log/'.$fichier).'
		</log>
		';
		return $log;
	}
	else
	{
		return traite_erreur(array(report_erreur('systeme','le fichier de log '.$fichier.' est introuvable.',true)));
	}
}

function merge_log($debut='',$fin='')
{
	global $basep;
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
	$dir=scandir($basep.'/data/log/');
	$merged='';
	foreach($dir as $d)
	{
		if($d==$debut)
		{
			$b=true;
		}
		if($b===true)
		{
			$merged.=file_gets_content($basep.'/data/log/'.$d);
			unlink($basep.'/data/log/'.$d);
		}
		if($d==$fin)
		{
			$b=false;
			break;
		}
	}
	$log=fopen($basep.'/data/log/'.$debut.'_'.$fin);
	fput($merged,$log);
	fclose($log);
}


function plop($s="")
{
	echo '<div class="plop">'.$s.'</div>';
}
?>
