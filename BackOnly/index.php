<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
		<title>Google Sheet Outputer</title>
</head>
<body>
<?php
	require('GenerateTable.php');
	if(true == (isset($_GET['sheet_url']) && !empty($_GET['sheet_url']))){
		$url_array = explode("/",$_GET['sheet_url']); 	
		$key = $url_array[5];
		$data["all"] = json_decode(file_get_contents('http://cors.io/spreadsheets.google.com/feeds/list/'.$key.'/od6/public/values?alt=json')) -> feed;
		//$data["title"] = $data["all"]->title->{'$t'};
		//$data["detail"] = array();
		//foreach($data["all"]->entry as $item){
		//	array_push($data["detail"],$item->{'gsx$datatype'}->{'$t'});
		//}
		createPage($data["all"]->entry);
?>
	<!--<h1><?php //echo $data["title"]; ?></h1>-->
	<pre style="display:none;"><?php var_dump($data["all"]); ?></pre>
<?php
	//foreach($data["detail"] as $item){
	//	echo '<p>'.$item.'</p>';
	//}
?>
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
