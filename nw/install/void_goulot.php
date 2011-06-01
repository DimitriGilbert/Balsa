foreach($_GET as $gettemp=>$key)
{
	if(!valid_input($gettemp))
	{
		report_erreur(\'systeme\',\'la variable \'.$key.\' n\\\'est pas valide\');
		die(0);
	}
}

switch($get[\'page\'])
{
	case \'\':
		inclure_ajax(\'ajax\');
		break;
}

$logged=is_logged();
if($logged)
{
	switch($get[\'page\'])
	{
		case \'\':
			inclure_ajax(\'ajax\');
			break;
	}
}

traite_fin_de_page();
?>
