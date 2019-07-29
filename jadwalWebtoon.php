<?php
	$namaHari = array('senin' => '1', 'selasa' => '2', 'rabu' => '3', 'kamis' => '4', 'jumat' => '5', 'sabtu' => '6', 'minggu' => '7',);
	if (isset($_GET['hari'])) {
		foreach ($namaHari as $key => $value) {
			if (!strcasecmp($key, $_GET['hari'])) {
				$controller = $value;
				break;
			}
		}
	}
	$url = "https://www.webtoons.com/id/dailySchedule";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$html = curl_exec($ch);
	$first_step = explode('<div class="daily_section', $html);
	if (isset($controller)) {
		$second_step = explode("</div>", $first_step[$controller]);
		$no = 1;
		$jadwal = array();
		foreach ($second_step as $key => $value) {
			preg_match('/<a[^>]+href="(.+?)"[^>]+class="daily_card_item.*?>/m', $value, $url);
			preg_match('/<p[^>]+class="subj">(.+?)<\/p>/m', $value, $judul);
			preg_match('/<p[^>]+class="author">(.+?)<\/p>/m', $value, $author);

			if (isset($url[1]) && isset($judul[1]) && isset($author[1])) {
				$pushThis = array('url' => $url[1], 'judul' => $judul[1], 'author' => $author[1]);
				array_push($jadwal, $pushThis);
				$no++;
			}
		}
		$hasil = json_encode($jadwal);
		echo $hasil;
	} else {
		for ($i=1; $i <= 7; $i++) { 
			$second_step = explode("</div>", $first_step[$i]);
			preg_match('/_list_(.+?)">/m', $second_step[0], $hari);
			echo "<h1>$hari[1]</h1><br>";
			foreach ($second_step as $key => $value) {
				preg_match('/<a[^>]+href="(.+?)"[^>]+class="daily_card_item.*?>/m', $value, $url);
				preg_match('/<p[^>]+class="subj">(.+?)<\/p>/m', $value, $judul);
				preg_match('/<p[^>]+class="author">(.+?)<\/p>/m', $value, $author);

				if (isset($url[1]) && isset($judul[1]) && isset($author[1])) {
					echo "<a href='$url[1]'>$judul[1] - $author[1]</a><br>";
				}
			}
		}
	}	
?>	