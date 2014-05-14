<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
		<title>Google Sheet Outputer</title>
</head>
<body>
<?php
	if(true == (isset($_GET['sheet_url']) && !empty($_GET['sheet_url']))){
		$data = explode("/",$_GET['sheet_url']); 	
		$key = $data[5];
?>
	<data style="display:none;"><?php echo 'http://cors.io/spreadsheets.google.com/feeds/list/'.$key.'/od6/public/values?alt=json'; ?></data>
	<?php var_dump(json_decode(file_get_contents('http://cors.io/spreadsheets.google.com/feeds/list/'.$key.'/od6/public/values?alt=json'))); ?>
<?php
	}else{
?>
	<form action="index.php" method="get">
		<h4>Please give the google sheet url from google docs.</h4>
		<p>You need to make the File publish to Web by clicking File -> Publish to Web</p>
		<p>Give us the publish url to us.</p>
		<input type="text" name="sheet_url"/>
		<input type="submit" value="Get my Output">
	</form>
<?php
	}
?>
</body>
</html>
