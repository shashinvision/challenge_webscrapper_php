<?php

if(isset($_GET['btn'])){

    function file_get_contents_curl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    function limpiarString($String){ 
        $String = str_replace(array("|","|","[","^","´","`","¨","~","]","'","#","{","}",".",""),"",$String);
        return $String;
    }

    $url 	=	$_GET['url'];
	$html 	= 	file_get_contents_curl($url);                    
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

<form>
	<input type="url" name="url" placeholder="Ej. http://empresa.com" required>
	<button name="btn" type="submit">SCRAPEAR</button>	

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