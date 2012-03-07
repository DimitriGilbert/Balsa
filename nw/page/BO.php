<?php
global$path,$base_url;
inclure_fonction('BO');
echo'<script type="text/javascript" src="'.$base_url.'media/lib/tiny_mce/tiny_mce.js"></script>';
if(isset($_GET['w']))
{
	if(is_file($path.'fonction/'.$_GET['w'].'.class.php'))
	{
		inclure_fonction($_GET['w'].'.class');
		$BO_object=new $_GET['w']();
		switch($_GET['ask'])
		{
			case'':
				echo $BO_object->manage($base_url.'?page=BO&w='.$_GET['w'].'&ask=edit&id=');
				break;
			case'manage':
				echo $BO_object->manage($base_url.'?page=BO&w='.$_GET['w'].'&ask=edit&id=');
				break;
			case'edit':
				$BO_object->load($_GET['id']);
				echo $BO_object->back_office($base_url.'?page=BO&w='.$_GET['w'].'&ask=insert&update='.$_GET['id']);
				break;
			case'insert':
				if($BO_object->insert_back_office('post',$_GET['update'])!==false)
				{
					echo $BO_object->manage($base_url.'?page=BO&w='.$_GET['w'].'&ask=edit&id=');
				}
				break;
			case'search':
				echo $BO_object->search('',$base_url.'?page=BO&w='.$_GET['w'].'&ask=search_result');
				break;
			case'search_result':
				echo $BO_object->search_result($base_url.'?page=BO&w='.$_GET['w'].'&ask=edit&id=');
				break;
			default:
				if(is_file($path.'page/BO/ask/'.$_GET['ask'].'.php'))
				{
					include($path.'page/BO/ask/'.$_GET['ask'].'.php');
				}
				else
				{
					plop('action inconnue');
				}
				break;
		}
	}
	elseif($_GET['w']=='home')
	{
		switch($_GET['ask'])
		{
			case'':
				echo home_form();
				break;
			case'edit':
				echo home_form();
				break;
			case'insert':
				insert_home();
				echo home_form();
				break;
			default:
				
				break;
		}
	}
	elseif(is_file($path.'page/BO/page/'.$_GET['w'].'.php'))
	{
		include($path.'page/BO/page/'.$_GET['w'].'.php');
	}
	else
	{
		echo'module inconnus';
	}
}

?>
