<?php
include_once('../../../../nw/init.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Editor</title>
  <style type="text/css" media="screen">
    body {
        overflow: hidden;
    }
    
    #editor { 
        margin: 0;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
    }
  </style>
</head>
<body>

<div id="editor">
</div>
<script type="text/javascript">
<?php
$ext='php';
echo 'var f_ext="'.$ext.'"';
?>
</script> 
<script src="src/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="src/theme-twilight.js" type="text/javascript" charset="utf-8"></script>
<script src="src/mode-php.js" type="text/javascript" charset="utf-8"></script>
<?php echo inclure_js(); ?>
<script src="<?php echo $base_url; ?>media/js/jsiEdit.js" type="text/javascript" charset="utf-8"></script>
<script>
window.onload = function() {
    load_file('<?php echo $_GET['file']?>');
};
</script>
<div id="script_temp"></div>
</body>
</html>
