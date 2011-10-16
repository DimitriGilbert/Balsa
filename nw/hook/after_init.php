<?php
global $_HOOK,$path,$base_url;
$p_n=$_HOOK['p_name'];
if(is_file($path.'admin/plugin/chrysa_lang/installed'))
{		
	//definition de la langue
	putenv("LANG=$langage"); // On modifie la variable d\'environnement
	setlocale(LC_ALL, $langage); // On modifie les informations de localisation en fonction de la langue	
	$nomDesFichiersDeLangue = 'traduction'; // Le nom de nos fichiers .mo	
	bindtextdomain($nomDesFichiersDeLangue, $path."data/locale"); // On indique le chemin vers les fichiers .mo
	textdomain($nomDesFichiersDeLangue); // Le nom du domaine par défaut
}

?>
