<?php
	global $path,$base_url;
	$repertoire_traite=$path.'data/cache';
	$repertoire = opendir($repertoire_traite);
	while (false !== ($fichier = readdir($repertoire)))
	{
		//définition du fichier a effacer
		$chemin = $repertoire_traite.'/'.$fichier;
		//on test si c'est bien un fichier valide
		if($fichier!='.' AND $fichier!='..' AND !is_dir($fichier)){
			//suppression du fichier
			unlink($chemin);
		}
	}
	//fermeture du dossier
	closedir($repertoire);  	
	header('location : '.$base_url.'admin.php?ajax_admin=1&module=chrysa_cache');
?>
