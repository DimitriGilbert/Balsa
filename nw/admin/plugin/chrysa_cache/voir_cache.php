<?php
	global $post,$get,$path,$base_url,$exclu;
	//test de l'esxistence d'un nom de fichier en paramètre pour une suppression unique
	if(isset($get['name']) AND !empty($get['name'])){
		//suppression du fichier cache
		if(unlink($path.'cache/'.$get['name'].'.cache')){		
			//redirection vers la page de visualisation des fichiers cache pour supprimer de l'url du parametre nom
			header('location: '.$base_url.'admin.php?module=cache&action=voir_cache');
		}
	}
	//test d'existence de l'envoi d'un formulaire	
	if(isset($post['supprimer'])){
		//calcul du nombre de secondes postées
		$temps=$post['seconde']+($post['minute']*60)+($post['heure']*3600)+($post['jour']*86400);		
		//définition tu répertoire d'install a vider et supprimer
		$repertoire_traite=$path.'cache';
		//ouverture du dossier d'install
		$repertoire=opendir($repertoire_traite);
		//parcours du dossier avec lecture de chaques fichiers
		while (false!==($fichier=readdir($repertoire)))
		{
			//définition du fichier a effacer
			$chemin=$repertoire_traite.'/'.$fichier;
			//on test si c'est bien un fichier valide
			if(!in_array($fichier, $exclu) AND !is_dir($fichier) AND (time()-filemtime($chemin))>$temps-1){
				//suppression du fichier
				unlink($chemin);
			}
		}
		//fermeture du dossier
		closedir($repertoire);  		
	}

	//listing des fichiers de cache
	$dir=scandir($path.'data/cache/');
  for($i=0, $i_max=count($dir); $i<$i_max; $i++){
    if($dir[$i]=='.' OR $dir[$i]=='..'){
      unset($dir[$i]); 
    }
  }
  if(!empty($dir)){
	  //génération des option de la liste de nombre de jours
	  for($i=0, $i_max=30; $i<=$i_max; $i++){
		  if($i<10){
			  $val='0'.$i;
		  }else{
			  $val=$i;
		  }
		  $liste_j.='<option value='.$val.'>'.$val.'</option>';
	  }
	  //génération des option de la liste de nombre d'e jours'heures
	  for($i=0, $i_max=24; $i<=$i_max; $i++){
		  if($i<10){
			  $val='0'.$i;
		  }else{
			  $val=$i;
		  }
		  $liste_h.='<option value='.$val.'>'.$val.'</option>';
	  }
	  //génération des option de la liste de minutes/secondes
	  for($i=0, $i_max=60; $i<=$i_max; $i++){
		  if($i<10){
			  $val='0'.$i;
		  }else{
			  $val=$i;
		  }
		  $liste_m_s.='<option value='.$val.'>'.$val.'</option>';
	  }
  //formulaire de sélection des fichiers a supprimer	
    $content='
    <form method="POST" action="'.$base_url.'admin.php?ajax_admin=1&module=chrysa_cache&action=voir_cache">
      supprimer les fichiers de cache qui ont plus de : 
      <select name="jour">'.$liste_j.'</select> jours 
      <select name="heure">'.$liste_h.'</select> heures 
      <select name="minute">'.$liste_m_s.'</select> minutes 
      <select name="seconde">'.$liste_m_s.'</select> secondes
      <input type="submit" name="supprimer" value="supprimer les fichiers de cache">
    </form>
	  <table width="100%"><tr><td>nom</td><td>age</td><td>poids</td><td>supprimer</td></tr>';
	  foreach($dir as $d){		
		  if($d!='.' AND $d!='..'){
		    $d_aff=str_replace('.cache',' ',$d);
		    $d_aff=str_replace('_',' ',$d_aff);
			  $age=convertion_temps(time()-filemtime($path.'cache/'.$d));
			  $taille=filesize($path.'data/cache/'.$d);
			  //conertion du poids
			  if($taille>1048576){
				  $taille=($taille/1048576);
				  if(is_float($taille)){
					  $taille=round($taille, 2);		
				  }
				  $taille=$taille.' Mega octets';
			  }else{
				  if($taille>1024){
					  $taille=($taille/1024);
					  if(is_float($taille)){
						  $taille=round($taille, 2);		
					  }
					  $taille=$taille.' Kilo octets';
				  }else{
					  $taille=$taille.' octets';					
				  }	
			  }			
			  $content.='<tr><td>'.$d_aff.'</td><td>'.$age.'</td><td>'.$taille.'</td><td><a href="'.$base_url.'ajax_admin=1&module=chrysa_cache&action=voir_cache&name='.$d_aff.'">supprimer</a></td></tr>';
		  }
	  }
	  $content.='</table>';
  }else{
    $content='<br/>aucuns fichiers de cache générés';
  }
	echo $content;
?>
