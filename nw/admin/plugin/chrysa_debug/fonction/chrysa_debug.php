<?php

//fonction de tracage de variable et d'affichage de contenu
function debug($nom,$var){
	//stockage du contexte de debug
	$debug=debug_backtrace();
	//appel de jquerry
	echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>';
	//affichage du chemin de la page et de la ligne où est situé le débug 
	echo '<p><a href="#" onclick="$(this).parent().next(\'ol\').slideToggle(); return false;"><strong>'.$debug[0]['file'].'</strong> ligne '.$debug[0]['line'].'</a></p>';
	//créationd e la liste numérotée
	echo '<ol style="display:none;">';
	//parcours des lignes ou passe la variable
	foreach($debug as $k=>$v){
		//affichage des lignes si il y en a au moins une
		if($k>0){
			echo '<li><strong>'.$v['file'].'</strong> ligne '.$v['line'].'</strong></li>';	
		}
	}
	echo '</ol>';
	//initialisation du message de retour
	echo 'la variable '.$nom.' ';
	//test du rendu finale de la variable
	if(!empty($var)){		
		//test du type de la variable
		if(is_string($var)){
			//affichage du contenu si ce n'est pas un array
			echo ' vaut '.$var;
		}else{
			//affichage du contenu si c'est un array
			echo ' contient ';
			echo '<pre>';
			print_r($var);
			echo '</pre>';
		}		
	}else{
		echo 'est vide';
	}
}

?>
