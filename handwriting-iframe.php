<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>MyScript Text Webcomponent</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700" type="text/css" media="all">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Waiting+for+the+Sunrise" type="text/css" media="all"></head>
<?php 
@$applicationkey = $_GET['applicationkey'];
@$hmackey = $_GET['hmackey'];
@$language = $_GET['language'];

// Is the page in https ? We need to know it or else the component will not work
if (isset($_SERVER['HTTPS'])) {
	if ($_SERVER['HTTPS'] == 'on') {
		$Protocol = 'https';
	} else {
		$Protocol = 'http';
	}
} else {
	$Protocol = 'http';
}

$url = $Protocol."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$url = strstr($url, '/handwriting-iframe.php', true); 

?>
    <script src="<?php echo $url ?>/bower_components/webcomponentsjs/webcomponents-lite.js"></script>
    <link rel="import" href="<?php echo $url ?>/bower_components/myscript-text-web/myscript-text-web.html">
<style>
/* Hack for label display */
paper-toast{
	display:none;
}
</style>
<body>
<myscript-text-web id='text-input' applicationkey='<?php echo $applicationkey ?>' hmackey='<?php echo $hmackey ?>' language='<?php echo $language ?>'>   </myscript-text-web>

</body>
</html>