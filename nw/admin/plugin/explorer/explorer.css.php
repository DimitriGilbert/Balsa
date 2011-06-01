.explorer
{
	position:relative;
	float:left;
	width:150px;
	border:1px solid black;
	overflow:auto;
}

.dir
{

}

.dir_commands
{
	float:right;
}

.dir_command
{
	float:left;
	width:16px;
	height:16px;
}

#dir_command_compress
{
	background:url(http://www.lsi.upc.edu/~jmartinez/zip.gif);
}
#dir_command_copy
{
	background:url(http://www.ssidm.co.uk/studio/images/xstandard/copy.gif);
}
#dir_command_cut
{
	background:url(http://www.xilinx.com/itp/xilinx10/isehelp/graphics/gq-cmd-edit-cut.gif.gif);
}
#dir_command_paste
{
	background:url(http://publib.boulder.ibm.com/infocenter/rfthelp/v7r0m0/topic/com.ibm.rational.test.ft.doc/images/paste_16.gif);
}

.file
{
	cursor:pointer;
}

.dir_name
{
	cursor:pointer;
}

.dir * .dir_child
{
	display:none;
}

.dir_child * .dir,.dir_child * .file
{
	padding-left:10px;
	border-left:1px dashed black;
}
