
include_once '../nw/init.php';

foreach($_GET as $gettemp=>$key)
{
	if(!valid_input($gettemp))
	{
		report_erreur('systeme','la variable '.$key.' n\'est pas valide');
		die(0);
	}
}
hook('goulot_before_inclure_ajax',array('page'=>$_GET['page']));
inclure_ajax($_GET['page']);
hook('goulot_after_inclure_ajax',array('page'=>$_GET['page']));


traite_fin_de_page();
?>
