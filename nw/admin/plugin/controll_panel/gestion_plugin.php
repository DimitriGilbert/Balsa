<?php 
	global $base_url,$path;
?>
<div>
	<a href="<?php echo $base_url.'admin.php?page_admin=a&module=controll_panel&action=plugin&gestion=gestion' ?>">gestion d'activation des plugins</a>
	<a href="<?php echo $base_url.'admin.php?page_admin=a&module=controll_panel&action=plugin&gestion=upload' ?>">uploader un plugin</a>
	<a href="<?php echo $base_url.'admin.php?page_admin=a&module=controll_panel&action=plugin&gestion=download' ?>">télécharger un plugin</a>
	<a href="<?php echo $base_url.'admin.php?page_admin=a&module=controll_panel&action=plugin&gestion=delete' ?>">supprimer un plugin</a>
</div>
<?php
$action=$_GET['gestion'];
switch($action)
{
	case'':
		echo list_plugin_add();
		break;
	case'gestion':
		echo list_plugin_add();
		break;
	case'upload':
		?>
		<h1>Upload d'un Plugin</h1>
		<form method="post" action="<?php echo $base_url ?>admin.php?page_admin=a&module=controll_panel&action=plugin&gestion=upload" enctype="multipart/form-data">
			<input type="file" name="upl_plug"/>
			<input type="submit" name="valider" value="uploader" />
			(l'extensions valide est .zip)
		</form>
		<?php
			if(isset($_POST['valider']) AND isset($_FILES) AND !empty($_FILES)){				
				$fichier=$_FILES['upl_plug'];
				$nom=strtolower(substr($fichier['name'],0,-4));
				if(is_dir($path.'admin/plugin/'.$nom)){
					echo 'un plugin portant le même nom existe déjà';
				}else{
					$ext=strtolower(substr($fichier['name'],-3));
					if($ext=='zip'){
						if(is_file($path.'admin/plugin/'.$fichier['name'])){
							unlink($path.'admin/plugin/'.$fichier['name']);
						}
						move_uploaded_file ($fichier['tmp_name'], $path.'admin/plugin/'.$fichier['name']);
						if($ext=='zip'){
							$c='cd '.$path.'admin/plugin && unzip '.$fichier['name'];
							exec($c);
						}
						unlink($path.'admin/plugin/'.$fichier['name']);
						if(is_file($path.'admin/plugin/'.$nom.'/installed')){
							unlink($path.'admin/plugin/'.$nom.'/installed');
						}						
						echo'votre plugins a bien été uploader vous pouvez maintenant <a href="'.$base_url.'admin.php?install=1&plugin='.$fichier['name'].'">l\'installer</a>';	
					}else{
							echo'votre fichier ne possèdes pas une extension valide';
					}
				}
			}
		break;
	case'download':
		echo'<h1>téléchargement d\'un Plugin</h1>pour télécharger un plugin il doit être désinstallé';
		echo list_plugin_dl();
		if(isset($_GET['name'])){	
			if(is_file($path.'admin/plugin/'.$_GET['name'].'.zip')){
				unlink($path.'admin/plugin/'.$_GET['name'].'.zip');
			}
			$c='cd '.$path.'admin/plugin && zip -r '.$_GET['name'].'.zip '.$_GET['name'].'/';
			exec($c);
			ob_start();
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.$_GET['name'].'.zip');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: '.filesize($path.'admin/plugin/'.$_GET['name'].'.zip'));
			ob_clean();
			flush();
			readfile($path.'admin/plugin/'.$_GET['name'].'.zip');
			header('location: '.$base_url.'admin.php?page_admin=1&module=controll_panel&action=plugin&gestion=download');		
		}
		break;
	case'delete':
		echo'<h1>supprimer un Plugin</h1>pour être supprimé un plugin il doit être désinstallé';
		if(isset($_GET['name'])){
			if(!isset($_GET['OK']) OR !empty($_GET['OK'])){
			?>		
				<form method="post" action="<?php echo $base_url ?>admin.php?page_admin=a&module=controll_panel&action=plugin&gestion=delete&name=<?php echo $_GET['name']; ?>&OK=">
					êtes-vous sûr de vouloir supprimer le plugin <?php echo $_GET['name']; ?> ?<br/> 
					<label for="supprimer">oui </label><input type="radio" id="supprimer" name="confirmation" value="1"/>
					<label for="conserver">non </label><input type="radio" id="conserver" name="confirmation" value="0" checked="1"/><br/>
					<input type="submit" name="valider" value="supprimer" />
				</form>
			<?php
			}else{
				if($_POST['confirmation']==1){
					if(rmdir_r($path.'admin/plugin/'.$_GET['name'])){
						echo '<br/>le plugin '.$_GET['name'].' a bien été supprimé';
					}else{
						echo '<br/>le plugin '.$_GET['name'].' n\'as pas pu été supprimé';
					}
				}else{
					header('location: '.$base_url.'admin.php?page_admin=1&module=controll_panel&action=plugin&gestion=delete');	
				}
			}
		}else{
			echo list_plugin_delete();			
		}
		break;
}
?>
