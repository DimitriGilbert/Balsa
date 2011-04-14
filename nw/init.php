<?php
//demarrage de la session
session_start();


//fake log
$_SESSION['user_id']='123';
//initialisation des erreurs
if(!isset($_SESSION['erreurs']))
{
	$_SESSION['erreurs']=array();
	$_SESSION['count_erreurs']=0;
}

$_SESSION['in_time']=microtime();
//base systeme de fichier
$basep='/home/didi/Documents/web_serveur/facturation/commercial/dev/nw/';
//base url
$base_url='http://127.0.0.1/facturation/commercial/dev/www/';

//inclusion de fonction de base
include_once $basep.'fonction/fonction.php';

//connexion Bdd
inclure_fonction('bdd.class');
$bdd=new Bdd;
if($bdd->connect()!==true)
{
	$_SESSION['erreur'][$_SESSION['count_erreurs']]='sql_connexion bdd';
	$_SESSION['count_erreurs']++;
}

//reassignation des variable GET
$get=$_GET;

//recupération des parametre client
if(!isset($_SESSION['param_client']))
{
	$navigateur['nom']='firefox';
	$navigateur['version']='x.x';
	$navigateur['type']='desktop';
	$navigateur['os']='windows';
	$_SESSION['param_client']=array();
	$_SESSION['param_client']['navigateur']=$navigateur;
	$_SESSION['param_client']['from']=false;
}

//controle du login
if(!is_logged())
{
	//valeur de la page par default
	$page_de_base='accueil';
	$get['page']=$page_de_base;
}

//definition de la langue
$langage='fr_FR';
putenv("LANG=$langage"); // On modifie la variable d'environnement
setlocale(LC_ALL, $langage); // On modifie les informations de localisation en fonction de la langue	
$nomDesFichiersDeLangue = 'traduction'; // Le nom de nos fichiers .mo	
bindtextdomain($nomDesFichiersDeLangue, $basep."nw/locale"); // On indique le chemin vers les fichiers .mo
textdomain($nomDesFichiersDeLangue); // Le nom du domaine par défaut


?>
