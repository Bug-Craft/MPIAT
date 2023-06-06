<?php
if (!extension_loaded('openssl')) {
  $error_openssl = "Please enable the OpenSSL extension in the php.ini file!";
  $error_openssl_2 = "Find ';extension=openssl' in php.ini and remove the preceding semicolon ';'.";
  $error_openssl_3 = "Restart the PHP and HTTP servers. Then refresh this page, and it should work properly.";
  $error_openssl_4 = "If you don't know how to perform the operation, be sure to seek help from others or search engines.";
  $error_openssl_5 = "Anyway, it's great that you've deployed this tool on your server. Thank you for using it!";
  $error_openssl_6 = "If you have successfully enabled the extension, then this prompt will disappear.<hr>";
}
error_reporting(0);
date_default_timezone_set('Asia/Shanghai');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $url = "https://api.mojang.com/users/profiles/minecraft/{$username}";
  $response = file_get_contents($url);
  if ($response) {
    $data = json_decode($response);
    $player_data_url = "https://sessionserver.mojang.com/session/minecraft/profile/{$data->id}";
    $player_data_response = file_get_contents($player_data_url);
    $player_data_response_json = json_decode($player_data_response);
    $encoded_skin_data = $player_data_response_json->properties[0]->value;
    $uncoded_skin_data = base64_decode($encoded_skin_data);
    $player_skin_data = json_decode($uncoded_skin_data, true);
    $timestamp = $player_skin_data["timestamp"] / 1000;
    $date = new DateTime("@$timestamp");
    $datetime = $date->format('Y-m-d H:i:s.u T');
    $player_name = $player_data_response_json->name;
    $player_id = $player_data_response_json->id;
    if (isset($player_skin_data["textures"]["SKIN"]["metadata"]["model"])) {
      if ($player_skin_data["textures"]["SKIN"]["metadata"]["model"] == "slim") {
        $player_model = "Alex";
      }
    } else {
      $player_model = "Steve";
    }
    $skin_url = $player_skin_data["textures"]["SKIN"]["url"];
    if (isset($player_skin_data["textures"]["CAPE"]["url"])) {
      $cape_url = $player_skin_data["textures"]["CAPE"]["url"];
    }
  } else {
    $error_text = "Unable to retrieve relevant information.<br><br>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>MPIAT</title>
  <link type="image/png" rel="shortcut icon" href="favicon.png" />
  <style>
    .err_openssl {
      color: #FF0000;
      text-align: center;
    }
    .err_openssl_msg {
      text-align: center;
    }
    .title {
      text-align: center;
      word-wrap: break-word;
    }
    .page {
      width: 80%;
      height: auto;
      display: block;
      margin: 0 auto;
      word-wrap: break-word;
    }
    .inputbox {
      width: 512px;
      height: 24px;
      display: block;
      margin: 0 auto;
      word-wrap: break-word;
    }
    .button {
      width: 256px;
      height: 32px;
      display: block;
      margin: 0 auto;
      word-wrap: break-word;
    }
  </style>
</head>
<body>
  <h1 class="err_openssl"><?php echo $error_openssl; ?></h1>
  <h2 class="err_openssl_msg"><?php echo $error_openssl_2; ?></h2>
  <h2 class="err_openssl_msg"><?php echo $error_openssl_3; ?></h2>
  <h2 class="err_openssl_msg"><?php echo $error_openssl_4; ?></h2>
  <h2 class="err_openssl_msg"><?php echo $error_openssl_5; ?></h2>
  <h2 class="err_openssl_msg"><?php echo $error_openssl_6; ?></h2>
  <h1 class="title" >Minecraft player information acquisition tool</h1>
  <h2 class="title" >Retrieve some information about a legitimate Java player.</h2>
  <hr>
  <form method="post">
    <input
      class="inputbox"
      type="text"
      name="username"
      placeholder="Enter a legitimate Java username..."
    >
    <br>
    <button class="button" type="submit">SUBMIT</button>
    <br>
    <p class="page" ><?php echo $error_text; ?></p>
    <p class="page" >Query Time: <?php echo $datetime; ?></p>
    <p class="page" >Player Name: <?php echo $player_name; ?></p>
    <p class="page" >Player UUID: <?php echo $player_id; ?></p>
    <p class="page" >Player Model: <?php echo $player_model; ?></p>
    <p class="page" >Skin URL: <?php echo $skin_url; ?></p>
    <p class="page" >Cape URL: <?php echo $cape_url; ?></p>
    <br>
    <img class="page" src="<?php echo $skin_url; ?>" alt="You haven't got the player information yet, the skin picture should have been displayed here.">
    <br>
    <img class="page" src="<?php echo $cape_url; ?>" alt="This player does not have a cape, or you have not got player information.">
    <br>
  </form>
  <h2 class="title" >How to use</h2>
    <p class="page" >    Enter any Minecraft: Java Edition player's
      username in the top and only input box for this tool, and that
      player's skin will be downloaded and displayed on the page. At
      the same time, information such as query times (returned by the
      Mojang API), correct capitalization of player names, and URLs
      for skins and capes can also be displayed.
    </p>
    <br>
    <p class="page" >    Right-click the image and click "Save Image
      As" or similar options to save the skin or cape locally.
    </p>
  <h2 class="title" >About this tool</h2>
    <p class="page">
          The online demo address of this tool is
      <a href="http://cn-fz-plc-1.openfrp.top:18000/tools/playerinfo/mpiat.php"
      target="_blank">http://cn-fz-plc-1.openfrp.top:18000/tools/playerinfo/mpiat.php</a>。
          You can try it out, but don't use it too frequently, otherwise
      my server may not be able to handle it.
    </p>
    <br>
    <p class="page" >    This tool is developed by Ygbs, aka me. So you
      can try typing "Ygbs" in the box above and see that I don't draw
      very nice looking skins. Of course, I don't have a cape, lol.
    </p>
    <p class="page" >
          Since I wrote PHP code for the first time, and I don't know
          much about HTML, so I finished writing this tool ignorantly
          with a little knowledge, and I didn't do it well, please
          forgive me. If you want to contribute to improvements, please
          submit a pull request.
    </p>
    <br>
    <p class="page" >
      This project is based on PHP, you can run it on PHP 8. As for other
      PHP versions, I don't know if it works, but you can give it a try.
      If this project is good, you can also fork this project to your own
      HTTP server. It can be Nginx and PHP or Apache and PHP. Other HTTP
      servers can also be tested by themselves.
    </p>
    <br>
    <p class="page" >
      Note that you must enable PHP's OpenSSL extension to download skins.
      If you don't have this extension enabled, you'll see a big warning
      banner at the top of this page. I suggest you follow the prompts in
      that banner, of course, if you don't see that prompt, that would be
      great.
    </p>
    <br>
    <p class="page" >
      You may want to ask, what if I don't have an HTTP server? There is
      a Python version of this tool, but it's not very easy to use, so I
      didn't open source it. I will improve it later and open source it.
      Or you can use the Demo page, lol.
    </p>
    <br>
    <p class="page" >
      This tool is released under the GPLv3 license, so make sure to follow
      the open source license, okay? I believe your answer is "yes". Also,
      since there is no charge for this tool, and nothing else that violates
      the Minecraft EULA, I personally think there is nothing wrong with
      this tool and can be deployed on your own server without violating the
      agreement.
    </p>
    <br>
    <br>
    <br>
</body>
</html>
