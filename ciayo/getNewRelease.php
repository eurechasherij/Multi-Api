<?php
$url = "https://vueserion.ciayo.com/3.1/comics/new-release?app=mobile&language=id&count=16&with=image,genres";

$curl = curl_init();
curl_setopt_array($curl, array(
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_URL => $url,
));
$resp = curl_exec($curl);

$json = json_decode($resp);

curl_close($curl);

$konten = array();
$data = $json->c->data;
foreach ($data as $isi) {
	$konten[] = array('title' => $isi->title, 'desc' => $isi->description_short, 'desc' => $isi->description_short, 'url' => $isi->share_url, 'image' => $isi->image->share);

}

echo json_encode($konten);
?>