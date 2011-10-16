<?php
 function gen_multilingue($dossier_en_cour=null,$lang=null){
	global $path,$exclu;
	$exclu=array('.','..');
	//définition du dossier a traiter
	if($dossier_en_cour==null){
		$dossier_traite=$path.'data/locale';
	}else{
		$dossier_traite=$dossier_en_cour;
	}
	//ouverture du dossier
	$dossier=opendir($dossier_traite);
	//parcours du dossier
	while($item=readdir($dossier))
	{	
		//test de la validité du nom de l'item en cours
		if(!in_array($item,$exclu)){		
			$folder=$dossier_traite.'/'.$item;
			//vérification du type de l'item
			if(is_dir($folder)){
				$new_dir=$dossier_traite.'/'.$item.'/LC_MESSAGES';	
				gen_multilingue($new_dir,$item);
			}else{		
				//définition des fichiers finaux
				$file_mo=$dossier_traite.'/traduction.mo';
				$file_po=$dossier_traite.'/traduction.po';
				//suppression des fichiers existants
				if(is_file($file_mo)){
					unlink($file_mo);	
				}
				if(is_file($file_po)){
					unlink($file_po);
				}
				//listing des pages a traiter
				$pages=scandir($dossier_traite.'/');
				//génération du contenu du fichier final
				$content=inclure_text_pages($pages,'',$dossier_traite.'/');
				//création du fichier final
				file_put_contents($file_po,$content);
				//génération de la requête linux de convertion
				$conv='msgfmt -o '.$file_mo.' '.$file_po;
				///convertion du fichier				
				echo exec($conv,$return);
			}
		}
	}
	//fermeture du dossier
	closedir($dossier);
 }	
?>
