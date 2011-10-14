function regen_css()
{
	serv_req(base_url+'admin.php?ajax_admin=1&module=controll_panel&action=regen_css');
}

function regen_js()
{
	serv_req(base_url+'admin.php?ajax_admin=1&module=controll_panel&action=regen_js');
}
