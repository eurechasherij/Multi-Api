<?php
function ciayoSearch($judul=null)
{
	$judul = (isset($judul) ? $judul : 'their story');
	$judul = preg_replace('/\s+/', '+', $judul);
	$url = "https://www.google.com/search?q=site:ciayo.com+$judul&oq=site:ciayo.com+$judul";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$html = curl_exec($ch);

	curl_close($ch);

	@preg_match_all('/<div id="ires"><ol><div class="g">(.+?)<\/div>/m', $html, $links);
	@preg_match('/<cite>(.+?)<\/cite>/m', $html, $cite);
	@$cite = strip_tags($cite[1]);
	@$data = explode('/', $cite);
	@$alias = end($data);

	return $alias;
}

function getChapters($alias)
{
	$url = "https://vueserion.ciayo.com/3.1/comics/$alias/chapters?app=mobile&language=id";

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $url,
	));
	$resp = curl_exec($curl);

	$json = json_decode($resp);

	curl_close($curl);

	$data = $json->c->data;
	for ($i=0; $i < count($data) ; $i++) { 
		$konten[] = array('chapters' => $data[$i]->alias, 'url' => $data[$i]->share_url, 'alias' => $alias);
	}

	return $konten;
}

if (isset($_GET['jd'])) {
	$json = json_encode(getChapters(ciayoSearch($_GET['jd'])));
	echo $json;
}
?>