<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<link rel="stylesheet" href="media/css/main.css" type="text/css" media="all" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php

#http://php.net/manual/en/function.copy.php
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

function move_nw($path,$path_w,$admin_name)
{
	if(!is_dir($path.''))
	{
		if(copy_r('../nw',$path))
		{
#			if(!rmdir_r('../nw'))
#			{
#				$erreur.='<div>Suppression du repertoire ../nw</div>';
#			}
			if(!rename($path.'admin',$path.'admin_'.$admin_name))
			{
				$erreur.='<div>renommage du repertoire '.$path.'/nw/admin</div>';
			}
			else
			{
				$admin_php=
				'
				<?php
				
				include_once(\''.$path.'init.php\');
				include_once(\''.$path.'admin_'.$admin_name.'/fonction/auth.php\');				
				
				?>
				';
				if(!file_put_contents($path_w.'admin_'.$admin_name.'.php',$admin_php))
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
		else
		{
			$erreur.='<div>creation du repertoire '.$path.'/nw</div>';
			echo $erreur;
			return false;
		}
	}
}

function bdd_admin($bdd_user,$bdd_host,$bdd_pass,$bdd_name)
{
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
	
}

function create_admin($login,$mail,$pass,$pass2)
{
	global $bdd,$path,$path_w;
	$id=$bdd->get_primkey();
	if($pass==$pass2)
	{
		$pass=hash('sha512',$pass);
	}
	$req=
	'
	INSERT INTO `Balsa`.`admin` (`id` ,`login` ,`mail` ,`pass`)VALUES(\''.$id.'\', \''.$login.'\', \''.$mail.'\', \''.$pass.'\');
	';
	if($bdd->query2($req))
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
	global $path,$path_w;
	$init_str=
	'<?php
//demarrage de la session
session_start();

//base systeme de fichier
$path=\''.$path.'\';
$path_w=\''.$path_w.'\';';

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

function install_conposant()
{
	if(move_nw($_POST['admin_path_nw'],$_POST['admin_path_www'],$_POST['admin_path']))
	{		
			
			if(bdd_admin($_POST['db_user'],$_POST['db_host'],$_POST['db_pass'],$_POST['db_name']))
			{
						echo '1';
				if(create_admin($_POST['admin_login'],$_POST['admin_mail'],$_POST['admin_pass'],$_POST['admin_pass_c']))
				{
						echo '2';
					if(create_index())
					{
						echo '3';
						if(create_goulot())
						{
							echo '4';
							creat_init_php();
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
				<label for="admin_path_nw">Chemin du repertoire /nw</label><input type="text" id="admin_path_nw" name="admin_path_nw" />
			</div>
			<div>
				<label for="admin_path_www">Chemin du repertoire /www</label><input type="text" id="admin_path_www" name="admin_path_www" />
			</div>
			<div>
				<label for="admin_path">Modification du chemin de l'admin</label><input type="text" id="admin_path" name="admin_path" />
			</div>				
			<div>
				<label for="url">URL du projet</label><input type="text" id="url" name="url" />
			</div>
			<h3>Configuration de la base de donnée admin</h3>
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
					/!\/!\/!\ ceci n'est pas la base de donnée de votre projet mais la base administrateur de Balsa /!\/!\/!\
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
			<h3>Finalisation de l'instalation</h3>
			<div>
				<label for="projet_nom">Nom du projet</label><input type="text" id="projet_nom" name="projet_nom" />
			</div>
			<div>
				<label for="projet_bdd">Nom de la base de donnée du projet</label><input type="text" id="projet_bdd" name="admin_path" />
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
		$path=$_POST['admin_path_nw'];
		$path_w=$_POST['admin_path_www'];
		install_conposant();
		echo'<pre>';
		print_r($_POST);
		echo'</pre>';

		break;
}


?>
