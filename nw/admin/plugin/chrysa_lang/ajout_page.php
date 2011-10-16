<?php
	global $path,$base_url;
	
	if(isset($_POST['ajouter_page'])){
		//on divise par 2 le nombre de posts pour trouver le nombre de couple et on lui retranche 2 a cause du submit et du hidden
		for($i=1, $i_max=(count($_POST)-2)/2; $i<=$i_max; $i++){
			$str_po.='msgid "'.$_POST['msgid_'.$i].'"';
			$str_po.="\n";
			$str_po.='msgstr "'.$_POST['msgstr_'.$i].'" ';	
			if($i<$i_max){
				$str_po.="\n\n";
			}
		}				
		if(file_put_contents($path.'data/locale/'.$_GET['lang'].'/LC_MESSAGES/'.$_POST['nom_page'].'.po', $str_po)){
			header('location: '.$base_url.'admin.php?page_admin=1&module=chrysa_lang&action=gestion_lang&lang='.$_GET['lang']);
		}
	}
?>
<form method="post" action="<?php echo $base_url ?>admin.php?page_admin=1&module=chrysa_lang&lang=<?php echo $_POST['lang']; ?>&action=ajout_page">
	<table width="100%">
		<caption>page : <?php echo $_POST['nom_page'];?></caption>
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
	<input type="hidden" name="nom_page" value="<?php echo str_replace(' ','_',$_POST['nom_page']); ?>"/>
	<input type="submit" name="ajouter_page" value="ajouter"/>
</form>