<?php

//vérification de la validité du fichier de cache
function verif_cache($file,$duree=10){
	global $path;
	//test de l'age de la page de cache
		if((time()-filemtime($path.'data/cache/'.$file.'.cache'))>$duree){
			unlink($path.'cache/'.$file.'.cache');
			return false;
		}else{
			return true;
		}
}

//mise en cache de la page en cours
function mise_en_cache($page,$content){
	global $path;
	//création du fichier de cache
	$fichier = fopen($path.'data/cache/'.$page.'.cache', 'w+');
	//remplissage du fichier de cache
	fwrite($fichier, $content);
	//fermeture du fichier de cache
	fclose($fichier);
}

//affichage du cache
function affiche_cache($page){
	global $path;    
	//récupération du contenu du cache
	$content = file_get_contents($path.'data/cache/'.$page.'.cache');    
	return $content;
}

?>
