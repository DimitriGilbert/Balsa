<?php


//fonction de génération de token
function generer_token($nom='')
{
	//génération d'un token unique
	$token = uniqid(rand(), true);
	//stockage du token dans une $_SESSION
	$_SESSION[$nom.'_token'] = $token;
	//stockage du du timestamp de génération dans une $_SESSION
	$_SESSION[$nom.'_token_time'] = time();
	return $token;
}

//fonction de vérification de validité des token
function verifier_token($temps, $referer, $nom='')
{
	global $post;
	//test d'existence d'un token
	if(isset($_SESSION[$nom.'_token']) AND isset($_SESSION[$nom.'_token_time']) AND isset($post['token'])){
		//vérification du token
		if($_SESSION[$nom.'_token'] == $post['token']){
			//vérification de la non expiration du token
			if($_SESSION[$nom.'_token_time'] >= (time() - $temps)){
				//vérification de la page d'appel
				if($_SERVER['HTTP_REFERER'] == $referer){
					return true;
				}
			}
		}
	}else{
		return false;
	}
}

?>
