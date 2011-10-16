<?php
global $path,$base_url;
//récupération du contenu de la page sélectionnée	
$content=file_get_contents($path.'data/locale/'.$_GET['lang'].'/LC_MESSAGES/'.$_GET['page'].'.po');
//test de la demande de modification de la page sélectionné
if(isset($_POST['modif_page'])){	
	preg_match_all('/msgid "(.+)"/',$content,$msgid2);
	for($i=0, $i_max=ceil((count($msgid2[1])/2)); $i<=$i_max; $i++){
		if($_POST['suppr_'.$i]==0){
			$str_po.='msgid "'.$_POST['msgid_'.$i].'"';
			$str_po.="\n";
			$str_po.='msgstr "'.$_POST['msgstr_'.$i].'" ';	
			if($i<$i_max){
				$str_po.="\n\n";
			}
		}
	}
	if(file_put_contents($path.'data/locale/'.$_GET['lang'].'/LC_MESSAGES/'.$_GET['page'].'.po', $str_po)){
		header('location: '.$base_url.'admin.php?page_admin=1&module=chrysa_lang&action=gestion_lang&lang='.$_GET['lang']);
	}	
//test de la demande d'ajout de nouveau code
}elseif(isset($_POST['ajouter_code'])){
	if(isset($_POST['ajouter_code_2'])){
		$str_po=$content;
		$str_po.="\n";
		for($i=1, $i_max=(count($_POST)/2)-1; $i<=$i_max; $i++){
			$str_po.='msgid "'.$_POST['msgid_'.$i].'"';
			$str_po.="\n";
			$str_po.='msgstr "'.$_POST['msgstr_'.$i].'" ';	
			if($i<$i_max){
				$str_po.="\n\n";
			}
		}
		if(file_put_contents($path.'data/locale/'.$_GET['lang'].'/LC_MESSAGES/'.$_GET['page'].'.po', $str_po)){
			header('location: '.$base_url.'admin.php?page_admin=1&module=chrysa_lang&action=gestion_lang&lang='.$_GET['lang']);
		}	
	}
?>
	<form method="post" action="<?php echo $base_url ?>admin.php?page_admin=1&module=chrysa_lang&lang=<?php echo $_GET['lang'] ?>&action=modif_page&page=<?php echo $_GET['page'] ?>">
		<table width="100%">
			<caption>page : <?php echo $_GET['page'];?></caption>
			<tbody>
				<tr>
					<td width="50%">code a remplacer</td>
					<td width="50%">massage traduit</td>
				</tr>
			<?php
			for($i=1, $i_max=$_POST['nombre']; $i<=$i_max; $i++){
				?>
				<tr>
					<td width="50%"><input type="text" name="msgid_<?php echo $i; ?>" size="50"/></td>
					<td width="50%"><textarea name="msgstr_<?php echo $i; ?>" cols="50" rows="10"></textarea></td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<input type="hidden" name="ajouter_code"/>
		<input type="submit" name="ajouter_code_2" value="ajouter"/>
	</form>
<?php	
}else{	
	//génération d'array contenant les msgid et msgstr
	preg_match_all('/msgid "(.+)"/',$content,$msgid);
	preg_match_all('/msgstr "(.+)"/',$content,$msgstr);
	//test du contenu pour savoir si il faut afficher le formulaire de modification ou pas
	if(count($msgid[1])>0){
?>
		<form method="post" action="<?php echo $base_url ?>admin.php?page_admin=1&module=chrysa_lang&lang=<?php echo $_GET['lang'] ?>&action=modif_page&page=<?php echo $_GET['page'] ?>">
			<label for="nombre">nombre de traduction a ajouter :</label>
			<select name="nombre">
			<?php		
				for($i=1, $i_max=100; $i<=$i_max; $i++){
					if($i<10){
						$i_aff='0'.$i;
					}else{
						$i_aff=$i;
					}
					$liste.='<option value="'.$i.'">'.$i_aff.'</option>';
				}
				echo $liste;
			?>		
			</select>
			<input type="submit" name="ajouter_code" value="ajouter"/>
		</form>

		<form method="post" action="<?php echo $base_url ?>admin.php?page_admin=1&module=chrysa_lang&lang=<?php echo $_GET['lang'] ?>&action=modif_page&page=<?php echo $_GET['page'] ?>">
			<table width="100%">
				<caption>page : <?php echo $_GET['page']; ?></caption>
				<tbody>
					<tr>
						<td width="33%">code a remplacer</td>
						<td width="34%">massage traduit</td>
						<td width="33%">supprimer</td>
					</tr>
				<?php
				for($i=0, $i_max=count($msgid[1]); $i<$i_max; $i++){
					?>
					<tr>
						<td width="33%"><input type="text" name="msgid_<?php echo $i; ?>" value="<?php echo $msgid[1][$i]; ?>" size="50"/></td>
						<td width="34%"><textarea name="msgstr_<?php echo $i; ?>" cols="50" rows="10"><?php echo $msgstr[1][$i]; ?></textarea></td>
						<td width="33%">
							<label for="cons_<?php echo $i; ?>">oui </label>
							<input type="radio" id="cons_<?php echo $i; ?>" name="suppr_<?php echo $i; ?>" value="1"/>
							<label for="suppr_<?php echo $i; ?>">non </label>
							<input type="radio" id="suppr_<?php echo $i; ?>" name="suppr_<?php echo $i; ?>" value="0" checked="1"/>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<input type="submit" name="modif_page" value="modifier"/>
		</form>
<?php
	}else{
		echo '<br/>le fichier selectionné est vide';
	}
}
?>