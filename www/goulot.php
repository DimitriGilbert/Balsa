<?php

include_once '../nw/init.php';

foreach($_GET as $gettemp=>$key)
{
	if(!valid_input($gettemp))
	{
		report_erreur('systeme','la variable '.$key.' n\'est pas valide');
		die(0);
	}
}

inclure_ajax($_GET['page']);


traite_fin_de_page();
?>
