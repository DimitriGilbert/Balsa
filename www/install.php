<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<link rel="stylesheet" href="media/css/main.css" type="text/css" media="all" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php

#http://php.net/manual/en/function.copy.php
function copy_r2( $path, $dest )
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
                    copy_r2( $path.'/'.$file, $dest.'/'.$file );
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
function rmdir_r_2($dir) 
{
    $files = scandir($dir);
    array_shift($files);    // remove '.' from array
    array_shift($files);    // remove '..' from array
   
    foreach ($files as $file) {
        $file = $dir . '/' . $file;
        if (is_dir($file)) {
            rmdir_r_2($file);
            rmdir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dir);
}

function move_nw($path,$path_w,$admin_name)
{
	if(is_dir($path))
	{
		if(!is_dir($path.'fonction'))
		{
			if(copy_r2('../nw',$path))
			{
	#			if(!rmdir_r_2('../nw'))
	#			{
	#				$erreur.='<div>Suppression du repertoire ../nw</div>';
	#			}
	#			if(!rename($path.'admin',$path.'admin_'.$admin_name))
	#			{
	#				$erreur.='<div>renommage du repertoire '.$path.'/nw/admin</div>';
	#			}
	#			else
	#			{
	#			}
			}
			else
			{
				$erreur.='<div>creation du repertoire '.$path.'/nw</div>';
				echo $erreur;
				return false;
			}


		}
		$admin_php=
		'
		<?php
	
		include_once(\''.$path.'init.php\');
		include_once(\''.$path.'admin/auth.php\');				
	
		?>
		';
		if(!file_put_contents($path_w.'admin.php',$admin_php))
		{
			$erreur.='<div>Création du fichier admin.php</div>';
			echo $erreur;
			return false;
		}
		else
		{
			return true;
		}			
	}
}

function bdd_create($bdd_user,$bdd_host,$bdd_pass,$bdd_name)
{
#	if($_POST['bdd_']=='1')
#	{
		global $bdd,$path,$path_w;
		$bdd_class_str=
		'
		<?php
	class Bdd
	{
		private $bdUser;
		private $bdPassWord;
		private $bdDataBase;
		private $bdServer;
		private $connexion;
		private $estConnecte;

	
		function Bdd()
		{
			$this->bdUser = "'.$bdd_user.'";
			$this->bdPassWord = "'.$bdd_pass.'";
			$this->bdDataBase = "'.$bdd_name.'";
			$this->bdServer = "'.$bdd_host.'";
			$this->estConnecte = false;
			$this->nbreq=0;
			$this->reqtime=0;
		}
		';
		$bdd_class_str.=file_get_contents($path.'install/void_bdd.class.php');
		if(file_put_contents($path.'fonction/bdd.class.php',$bdd_class_str))
		{
			include_once $path.'fonction/fonction.php';
			inclure_fonction('bdd.class');
			$bdd=new Bdd;
			if($bdd->creat_db_Balsa($bdd_name))
			{
				return true;
			}
			else
			{
				$erreur.='<div>creation de la base de donnée</div>';
				echo $erreur;
				return false;
			}
		}
		else
		{
			$erreur.='<div>creation du fichier php de la base de donnée</div>';
			echo $erreur;
			return false;
		}		
#	}
#	else
#	{
#		return true;
#	}

}

function create_admin($login,$mail,$pass,$pass2)
{
	global $bdd,$path,$path_w;
	$id=1;
	if($pass==$pass2)
	{
		$pass=hash('sha512',$pass);
	}
	$xml='<comptes><'.$login.' mail="'.$mail.'" id="'.$id.'">'.$pass.'</'.$login.'></comptes>';
	if(file_put_contents($path.'/admin/admin.xml',$xml))
	{
		return true;
	}
	else
	{
		$erreur.='<div>insertion de l\'administrateur dans la base de donnée</div>';
		echo $erreur;
		return false;
	}
}

function create_index()
{
	global $path,$path_w;
	$index_str=
	'<?php

include_once \''.$path.'init.php\';

inclure_page(\'index\');

traite_fin_de_page();
?>
	';
	if(file_put_contents($path_w.'index.php',$index_str))
	{
		return true;
	}
	else
	{
		$erreur.='<div>creation du fichier www/index.php</div>';
		echo $erreur;
		return false;
	}
}

function create_goulot()
{
	global $path,$path_w;
	$index_str=
	'<?php

include_once  \''.$path.'init.php\';
	';

	$index_str.=file_get_contents($path.'install/void_bdd.class.php');

	if(file_put_contents($path_w.'goulot.php',$index_str))
	{
		return true;
	}
	else
	{
		$erreur.='<div>creation du fichier www/goulot.php</div>';
		echo $erreur;
		return false;
	}
}

function creat_init_php()
{
	global $path,$path_w,$base_url;
	
	if(is_file($path.'init.php')){
		unlink($path.'init.php');
	}
	
	$init_str=
	'<?php
//demarrage de la session
session_start();

//base systeme de fichier
$path=\''.$path.'\';
$path_w=\''.$path_w.'\';
$base_url=\''.$base_url.'\';

$nom_projet=\''.$_POST['projet_nom'].'\';

$langage=\''.$_POST['lang'].'.utf8\';

//inclusion de fonction de base
include_once $path.\'fonction/fonction.php\';
//connexion Bdd
';
if($_POST['bdd_']=="oui")
{
  $int_str.='inclure_fonction(\'bdd.class\');
  $bdd=new Bdd;
  if($bdd->connect()!==true)
  {
    	$_SESSION[\'erreur\'][$_SESSION[\'count_erreurs\']]=\'sql_connexion bdd\';
	  $_SESSION[\'count_erreurs\']++;
  }

';
}

	$init_str.=file_get_contents($path.'install/void_init.php');
	if(file_put_contents($path.'init.php',$init_str))
	{
		return true;
	}
	else
	{
		$erreur.='<div>creation du fichier nw/init.php</div>';
		echo $erreur;
		return false;
	}
}

function create_js()
{
	global $path;
	$js_str='var base_url='.$_POST['url'];
	$js_str=file_get_contents($path.'install/void_js.js');
	if(file_put_contents($path.'media/js/main.js',$js_str))
	{
		return true;
	}
	else
	{
		$erreur.='<div>creation du fichier nw/media/js/js.js</div>';
		echo $erreur;
		return false;
	}
}


 function create_mail($adresse_serveur,$port_serveur,$utilisateur,$mdp_mail)
  {
    global $path;
    if(is_file($path.'fonction/mail.php')){
    	unlink($path.'fonction/mail.php');
    }
    
   	$mail_str=file_get_contents($path.'install/void_mail.php');
  	$mail_str=str_replace('||adresse_serveur||', $adresse_serveur, $mail_str);
  	$mail_str=str_replace('||port_serveur||', $port_serveur, $mail_str);
  	$mail_str=str_replace('||utilisateur||', $utilisateur, $mail_str);
  	$mail_str=str_replace('||mdp_mail||', $mdp_mail, $mail_str);

    if(file_put_contents($path.'fonction/mail.php',$mail_str))
	  {
		  return true;
	  }
	  else
	  {
		  $erreur.='<div>creation du fichier nw/fonction/maiL.php</div>';
		  echo $erreur;
		  return false;
	  }
  }
  
function install_composant()
{
  global $path,$path_w;
	echo 'Deplacement du dossier nw';
	if(move_nw($path,$path_w,$_POST['admin_path']))
	{		
		echo ' ---> ok<br/>creation de la base de donnée';
		if(bdd_create($_POST['db_user'],$_POST['db_host'],$_POST['db_pass'],$_POST['db_name']))
		{
			echo ' ---> ok <br/>creation du fichier admin';
			if(create_admin($_POST['admin_login'],$_POST['admin_mail'],$_POST['admin_pass'],$_POST['admin_pass_c']))
			{
			  echo ' ---> ok <br/>creation du fichier mail';
			  if(create_mail($_POST['adresse_serveur'],$_POST['port_serveur'],$_POST['utilisateur'],$_POST['mdp_mail']))
				{
				  echo ' ---> ok <br/>creation du fichier index';
				  if(create_index())
				  {
					  echo ' ---> ok <br/>creation du fichier goulot';
					  if(create_goulot())
					  {
						  echo ' ---> ok <br/>creation du fichier init';
						  if(creat_init_php())
						  {
							  echo' ---> ok <br/>creation du fichier js';
							  if(create_js())
							  {
								  echo' ---> ok <br/>suppression du fichier install.';
								  return unlink($path_w.'install.php');
							  }
						  }
					  }	
					}	
				}					
			}
		}
	}
	else
	{
		return false;
	}
}


switch($_GET['action'])
{
	case '':
?>
<title>Installation de Balsa : étape I</title>
</head>
<body>
	<div class="site" id="site">
		<h1>Bienvenue sur Balsa !</h1>
		<h2>Configuration de la partie d'administration</h2>
		<form method="post" action="install.php?action=admin">
			<h3>configuration du système de fichier</h3>
			<div>
				<label for="admin_path_nw">Chemin du repertoire /nw</label><input type="text" id="admin_path_nw" name="admin_path_nw" />/nw/
          <div style="">c'est le repertoire de base, la ou se trouveront les fichiers de fonctions et les controlleurs</div>
			</div>
			<div>
				<label for="admin_path_www">Chemin du repertoire /www</label><input type="text" id="admin_path_www" name="admin_path_www" />/www/
          <div style="">c'est le repertoire web qui contiendrat les controlleurs maitre (index et goulot) ainsi ue les medias</div>
			</div>
			<div>
				<label for="admin_path">Modification du chemin de l'admin</label><input type="text" id="admin_path" name="admin_path" />
          <div style="">pour plus de securité, il faut modifie le chemin d'accés a l'interface d'administration</div>
			</div>				
			<div>
				<label for="url">URL du projet</label><input type="text" id="url" name="url" />/
          <div style="">l'url qui permet l'accé au repertoire www depuis internet</div>
			</div>
			<h3>Configuration de la base de donnée admin</h3>
			<div>
				<label for="db_">utilisé une base de donnée</label> oui : <input type="radio" id="db_" name="db_" value="oui" /> non : <input type="radio" id="db_" name="db_" value="non" checked />
			</div>
			<div>
				<label for="db_host">Hote de la base de donnée</label><input type="text" value="localhost" id="db_host" name="db_host" />
			</div>
			<div>
				<label for="db_user">Utilisateur de la base de donnée</label><input type="text" value="root" id="db_user" name="db_user" />
			</div>
			<div>
				<label for="db_pass">Password de la base de donnée</label><input type="password" id="db_pass" name="db_pass" />
			</div>
			<div>
				<label for="db_name">Nom de la base de donnée</label><input type="text" value="Balsa" id="db_name" name="db_name" />
				<p>
				</p>
			</div>
			<div>
				<div>Faut-il créer la base de donnée ? </div>
				<label for="db_cre1">non</label><input type="radio" value="0" id="db_cre1" name="db_cre" checked="true" />
				<label for="db_cre2">oui</label><input type="radio" value="1" id="db_cre2" name="db_cre" />
			</div>
			<h3>Création du compte admin</h3>
			<div>
				<label for="admin_login">Login admin</label><input type="text" value="Balsa_Admin" id="admin_login" name="admin_login" />
			</div>
			<div>
				<label for="admin_pass">Password admin</label><input type="password" id="admin_pass" name="admin_pass" />
			</div>
			<div>
				<label for="admin_pass_c">Confirmation password admin</label><input type="password" id="admin_pass_c" name="admin_pass_c" />
			</div>
			<div>
				<label for="admin_mail">Mail de l'admin</label><input type="text" id="admin_mail" name="admin_mail" />
			</div>			
			<h3>configuration du serveur mail</h3>
			<div>
				<label for="adresse_serveur">adresse du serveur </label><input type="text" id="adresse_serveur" name="adresse_serveur" />
			</div>
			<div>
				<label for="port_serveur">port de connexion au serveur </label><input type="text" id="port_serveur" name="port_serveur" />
			</div>
			<div>
				<label for="utilisateur">utilisateur </label><input type="text" id="utilisateur" name="utilisateur" />
			</div>
			<div>
				<label for="mdp_mail">mot de passe </label><input type="password" id="mdp_mail" name="mdp_mail" />
			</div>			
			<h3>Finalisation de l'instalation</h3>
			<div>
				<label for="lang">langue : </label>
				<select name="lang" id="lang">
					<option value="fr_FR" selected="selected">français</option>
				</select>
			</div>
			<div>
				<label for="projet_nom">Nom du projet</label><input type="text" id="projet_nom" name="projet_nom" />
			</div>
			<input type="submit" value="suivant">
		</form>
	</div>
	<div style="clear:both"></div>
</body>
</html>
<?php
		break;
	case 'admin':
		/*
		modif du chemin de l'admin (rename('../nw/admin','../nw/admin_'.$str))
		creation de admin_<str>.php dans /www (incluant le chemin de l'admin)
		creation et remplissage de la bdd admin
		login admin
		configuration de init.php
		*/
		$bdd;
		$path=$_POST['admin_path_nw'].'/nw/';
		$path_w=$_POST['admin_path_www'].'/www/';
		$base_url=$_POST['url'].'/';
		if(install_composant())
		{
			echo' ---> ok<br/><a href="'.$base_url.'admin.php">entrez dans la page d\'administration</a>';
		}

		break;
}
?>
