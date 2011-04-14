<?php

include_once '../nw/init.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<link rel="stylesheet" href="media/css/main.css" type="text/css" media="all" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />		
	</head>
	<body>
		<div class="site" id="site"></div>
		<div style="clear:both"></div>
		<div class="lightbox_filtre" id="lightbox_filtre"></div>
		<div class="lightbox" id="lightbox">
			<div class="lightbox_titre" id="lightbox_titre"></div>
			<div class="lightbox_close" onclick="lightbox_close();"></div>
			<div class="lightbox_content" id="lightbox_content"></div>
		</div>
		<script type="text/javascript" src="media/js/main.js"></script>

	</body>
</html>
<?php
traite_fin_de_page();
?>

