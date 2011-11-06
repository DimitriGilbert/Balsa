
//fake log
$_SESSION['user_id']='123';
//initialisation des erreurs
if(!isset($_SESSION['erreurs']))
{
	$_SESSION['erreurs']=array();
	$_SESSION['count_erreurs']=0;
}

$_SESSION['in_time']=microtime();

//inclusion de fonction de base
include_once $path.'fonction/fonction.php';
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
	$u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $nav_name = 'inconnu';
    $OS_name = 'inconnue';
    $version= 'inconnue';
    
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
        $nav_name = 'Mozilla Firefox'; 
        $nav_utilisateur = "Firefox"; 
    }elseif(preg_match('/Namoroka/i',$u_agent)){ 
        $nav_name = 'Mozilla Firefox Namoroka'; 
        $nav_utilisateur = "Namoroka"; 
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
	$_SESSION['client']=array();
	$_SESSION['client']['navigateur']=$navigateur;
	$_SESSION['client']['from']=false;
}

//controle du login
if(!is_logged())
{
	//valeur de la page par default
	$page_de_base='accueil';
	$get['page']=$page_de_base;
}


hook('after_init',array('p_name'=>'chrysa_lang'));
$_HOOK['display'];
?>
