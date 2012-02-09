.p_m_menus
{
	width:100%;
	height:50px;
	position:relative;
}

.project
{
	width:150px;
	height:150px;
	border:1px black solid;
	float:left;
}

.explorer
{
	position:relative;
	float:left;
	width:150px;
	border:1px solid black;
	overflow:auto;
}

.xmlfs_dir
{

}

.xmlfs_file
{
	cursor:pointer;
}

.xmlfs_dir_name
{
	cursor:pointer;
}

.xmlfs_dir * .xmlfs_dir_child
{
	display:none;
}

.xmlfs_dir_child * .xmlfs_dir,.xmlfs_dir_child * .xmlfs_file
{
	padding-left:10px;
	border-left:1px dashed black;
}

.browser
{
	position:relative;
	width:100%;
}

.browser * .xmlfs_dir,.browser * .xmlfs_file,.shown * .xmlfs_file,.shown * .xmlfs_dir 
{
	float:left;
	width:96px;
	height:52px;
	padding-top:44px;
	text-align:center;
	padding-left:0;
	border-left:none;
}

.browser * .xmlfs_file,.shown * .xmlfs_file
{
	background-image:url("http://app.magestionclient.com/media/img/fichier.png");
}

.browser * .xmlfs_dir,.shown * .xmlfs_dir
{
	background-image:url("http://app.magestionclient.com/media/img/folder.png");
}

.browser * .hided
{
	display:none;
	height:12px;
	padding-top:0;
}

.browser * .shown
{
	width:100%;
	display:block;
	background:none;
}

.shown .xmlfs_dir_name
{
	position:absoltute;
	float:left;
	top:80px;
	width:150px;
}
