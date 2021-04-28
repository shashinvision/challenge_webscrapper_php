<?php

if(isset($_POST['btn'])){

    function file_POST_contents_curl($url, $username, $password){

        //initial request with login data

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);


        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "username=$username&password=$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie-name');  //could be empty, but cause problems on some hosts
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/var/www/ip4.x/file/tmp');  //could be empty, but cause problems on some hosts

        $data = curl_exec($ch);
        if (curl_error($ch)) {
            echo curl_error($ch);
        }

        //another request preserving the session

        curl_setopt($ch, CURLOPT_URL, 'http://www.example.com/profile');
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "");
        if (curl_error($ch)) {
            echo curl_error($ch);
        }

        curl_close($ch);


        return $data;
    }
    function limpiarString($String){ 
        $String = str_replace(array("|","|","[","^","´","`","¨","~","]","'","#","{","}",".",""),"",$String);
        return $String;
    }

    $url 	=	$_POST['url'];
    $username 	=	$_POST['username'];
    $password 	=	$_POST['password'];
	$html 	= 	file_POST_contents_curl($url, $username, $password);                    
    $doc 	= 	new DOMDocument();
    @$doc->loadHTML($html);
    $nodes 	= 	$doc->getElementsByTagName('title');
    $title 	= 	limpiarString($nodes->item(0)->nodeValue);
    $metas 	= 	$doc->getElementsByTagName('meta');
    $description = "";
	$keywords = "";


    for ($i = 0; $i < $metas->length; $i++){
		$meta = $metas->item($i);

/*
    echo "<pre>";
    var_dump($meta->getAttribute('name'));
    echo "</pre>";
 	die();
*/    


        if($meta->getAttribute('name') == 'description'){
        	$description = limpiarString($meta->getAttribute('content'));
        } 
        if($meta->getAttribute('name') == 'keywords'){
        	$keywords = limpiarString($meta->getAttribute('content'));
        }
	}
	
		$data['title'] = (!empty($title)) ? $title : "no title";
	    $data['description'] = (!empty($description)) ? $description : "no description";
	    $data['keywords'] = (!empty($keywords)) ? $keywords : "no keywords";
	    $data['web'] = (!empty($html)) ? $html : "no web";
		

} else {


		$data['title'] =  "no title";
	    $data['description'] = "no description";
	    $data['keywords'] =  "no keywords";
	    $data['web'] =  "no web";

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scrapper</title>
</head>
<style>
  code {
  font-family: Consolas,"courier new";
  color: crimson;
  background-color: #f1f1f1!important;
  padding: 2px;
  font-size: 105%;

}
</style>
<body>

<form method="POST">
<hr>
    <br>
    <br>
	<input type="url" name="url" id="url" placeholder="Ej. http://empresa.com" required>
    <br>
    <br>
	<input type="text"  name="username" id="username" placeholder="usuario..." >
    <br>
    <br>
	<input type="password" id="password" name ="password" placeholder="contraseña...">
    <br>
    <br>
    <button name="btn" type="submit" onclick="login()">SCRAPEAR</button>	
<hr>

</form>

	<div class="container">
	<p>Titulo: <?= $data['title'];?></p>	
	<p>description: <?= $data['description'];?></p>	
	<p>keywords: <?= $data['keywords'];?></p>
	</div>	

	<div class="web">
		<code>
			<i>
				<?= $data['web']; ?>
			</i>
		</pre>

	</div>

</body>
</html>