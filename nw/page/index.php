<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<?php echo inclure_css() ?>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Mon projet avec Balsa</title>
	</head>
	<body>
		<div class="site" id="site">
			<h1>Bienvenue sur Balsa !</h1>
			<?php
			hook('index_before_inclure_page',array('page'=>$_GET['page'],'display'=>''));
			echo $_HOOK['display'];
			if(isset($_GET['page']))
			{
				inclure_page($_GET['page']);
			}
			hook('index_after_inclure_page',array('page'=>$_GET['page'],'display'=>''));
			echo $_HOOK['display'];
			?>
		</div>
		<div style="clear:both"></div>
		<div class="lightbox_filtre" id="lightbox_filtre"></div>
		<div class="lightbox" id="lightbox">
			<div class="lightbox_titre" id="lightbox_titre"></div>
			<div class="lightbox_close" onclick="lightbox_close();"></div>
			<div class="lightbox_content" id="lightbox_content"></div>
		</div>
		<?php echo inclure_js() ?>
	</body>
</html>
