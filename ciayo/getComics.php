<?php
function getComics($alias, $chapter)
{
	$url = "https://vueserion.ciayo.com/3.1/comics/$alias/chapters/$chapter?app=mobile&language=id&with=slices";

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $url,
	));
	$resp = curl_exec($curl);

	$json = json_decode($resp);

	curl_close($curl);

	$img = array();
	foreach ($json->c->data->slices as $key) {
		$img[] = $key->image;
	}

	$img = array('image' => $img);

	return $img;
}

if (isset($_GET['al']) && isset($_GET['ch'])) {
	$json = json_encode(getComics($_GET['al'], $_GET['ch']));
	echo $json;
}
?>