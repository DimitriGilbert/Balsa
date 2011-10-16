<?php
global $path,$base_url;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<link rel="stylesheet" href="<?php echo $base_url; ?>admin.php?ajax_admin=1&module=plugin_packager&action=css" type="text/css" media="all" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Créateur de plugin simple pour Balsa :)</title>
	</head>
	<body>
		<div class="site" id="site">

			<h1>Upload d'un Plugin</h1>
<form method="post" action="<?php echo $base_url ?>admin.php?page_admin=1&module=upload" enctype="multipart/form-data">
  <input type="file" name="upl_plug"/>
  <input type="submit" name="valider" value="valider" />
	(l'extensions valide est .zip)
</form>
<?php
  if(isset($_POST['valider']) AND isset($_FILES) AND !empty($_FILES)){
		
				$fichier=$_FILES['upl_plug'];
				$ext = strtolower(substr($fichier['name'],-3));
				$allow_ext=array('rar','zip');
				if(in_array($ext,$allow_ext)){
					move_uploaded_file ($fichier['tmp_name'], $path.'admin/plugin/'.$fichier['name']);
					if($ext=='zip'){
						$c='cd '.$path.'admin/plugin && unzip '.$fichier['name'];
						echo exec($c);						
					}
					unlink($path.'admin/plugin/'.$fichier['name']);
					echo'votre plugins a bien été uploader vous pouvez maintenant l\'installer';	
				}else{
						echo'votre fichier ne possèdes pas une extension valide';
				}
  }
?>
			
		</div>
		<div style="clear:both"></div>
		<div class="lightbox_filtre" id="lightbox_filtre"></div>
		<div class="lightbox" id="lightbox">
			<div class="lightbox_titre" id="lightbox_titre"></div>
			<div class="lightbox_close" onclick="lightbox_close();"></div>
			<div class="lightbox_content" id="lightbox_content"></div>
		</div>
		<div id="scipt_js">
			<?php echo inclure_js() ?>
		</div>
	</body>
</html>