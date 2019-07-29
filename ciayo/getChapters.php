<?php
function ciayoSearch($judul=null)
{
	$judul = (isset($judul) && $judul != null ? $judul : 'their story');
	$url = "https://id09wekzii-dsn.algolia.net/1/indexes/*/queries?x-algolia-application-id=ID09WEKZII&x-algolia-api-key=e023a8b3946ae4953b0c545c0d853c80";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,
            '{"requests":[{"indexName":"comics_id","params":"query='.$judul.'&page=0"}]}');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$html = curl_exec($ch);

	curl_close($ch);

	$alias = json_decode($html)->results[0]->hits[0]->alias;

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