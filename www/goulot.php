<?php

include_once '../nw/init.php';
$logged=is_logged();
if($logged)
{
	foreach($_GET as $gettemp)
	{
		if(!valid_input($gettemp))
		{
			die(0);
		}
	}
	switch($get['page'])
	{
		case '':
			inclure_ajax('');
			break;
	}
	traite_fin_de_page();
}
else
{
	traite_fin_de_page();
	die(0);
}
?>
