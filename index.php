<?php
if (!extension_loaded('openssl')) {
  $error_openssl = "请在 php.ini 中启用 OpenSSL 扩展！";
  $error_openssl_2 = "在 php.ini 中找到“;extension=openssl”并删除前面的“;”，";
  $error_openssl_3 = "重新启动 PHP 和 HTTP 服务器。然后刷新此页面，应该就好了。";
  $error_openssl_4 = "如果你不知道应该怎么操作，一定要去求助他人或搜索引擎。";
  $error_openssl_5 = "无论如何，很高兴你在你的服务器上部署这个工具。感谢使用！";
  $error_openssl_6 = "如果你成功启用了扩展，那么这个提示将会消失。<hr>";
}
error_reporting(0);
// 设置默认时区为北京时间
date_default_timezone_set('Asia/Shanghai');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];

  // 构建请求 URL
  $url = "https://api.mojang.com/users/profiles/minecraft/{$username}";

  // 发送 GET 请求并获取响应
  $response = file_get_contents($url);

  if ($response) {
    // 解析响应 JSON 数据
    $data = json_decode($response);

    // 获取 UUID 并输出
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
    $error_text = "无法获取相关信息。<br><br>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Minecraft 玩家信息获取工具</title>
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
  <h1 class="title" >Minecraft 玩家信息获取工具</h1>
  <h2 class="title" >获取一个 Java 正版玩家的一些信息</h2>
  <hr>
  <form method="post">
    <input
      class="inputbox"
      type="text"
      name="username"
      placeholder="输入一个 Java 正版用户名……"
    >
    <br>
    <button class="button" type="submit">查询玩家信息</button>
    <br>
    <p class="page" ><?php echo $error_text; ?></p>
    <p class="page" >查询时间：<?php echo $datetime; ?></p>
    <p class="page" >玩家名称：<?php echo $player_name; ?></p>
    <p class="page" >玩家 UUID：<?php echo $player_id; ?></p>
    <p class="page" >玩家模型：<?php echo $player_model; ?></p>
    <p class="page" >皮肤地址：<?php echo $skin_url; ?></p>
    <p class="page" >披风地址：<?php echo $cape_url; ?></p>
    <br>
    <img class="page" src="<?php echo $skin_url; ?>" alt="你尚未查询玩家信息，这里本应显示皮肤图片的。">
    <br>
    <img class="page" src="<?php echo $cape_url; ?>" alt="这个玩家没有披风，或者你还没有查询玩家信息。">
    <br>
  </form>
  <h2 class="title" >使用方法</h2>
    <p class="page" >ㅤㅤ在上面的也是此工具唯一的输入框中输入任意的 Minecraft: Java Edition 
      玩家的用户名，即可下载该玩家的皮肤并显示在页面上。与此同时，还可以显示查询时间（由 Mojang 
      API 返回）、玩家名称的正确大小写，以及皮肤和披风的 URL 等信息。
    </p>
    <br>
    <p class="page" >ㅤㅤ右键图片，并点按“将图像另存为”或类似选项，即可保存皮肤或者是披风到
      本地。
    </p>
  <h2 class="title" >关于此工具</h2>
    <p class="page">
      　　这个工具的在线 Demo 地址是
      <a href="http://cn-fz-plc-1.openfrp.top:18000/tools/playerinfo/"
      target="_blank">http://cn-fz-plc-1.openfrp.top:18000/tools/playerinfo/</a>。
      你可以进行尝试，但是千万别用得太频繁，不然我这边内网穿透的流量要不够了。
    </p>
    <br>
    <p class="page" >ㅤㅤ这个工具由 Ygbs 开发，也就是我。所以你可以尝试着在上面的框框里输入
      “Ygbs”并看到我画得并不是很好看得皮肤。当然，我没有披风，哈哈。
    </p>
    <p class="page" >
      ㅤㅤ由于我第一次写 PHP 代码，以及 HTML 我也不大了解，所以在一知半解的情况下懵懂地写完了
      这个工具，做得不好，还请见谅。如果希望参与改进，请提交拉取请求（Pull Request）。
    </p>
    <br>
    <p class="page" >
      ㅤㅤ此项目基于 PHP，你可以在 PHP 8 上运行它。至于其他的 PHP 版本，我不知道是否可行，
      但是你可以尝试一下。如果这个项目不错，你也可以将这个项目 Fork 到你自己的 HTTP 服务器上。
      可以是 Nginx 和 PHP 或是 Apache 和 PHP 的搭配。其他的 HTTP 服务器也可以自行测试。
    </p>
    <br>
    <p class="page" >
      ㅤㅤ需要注意的是，你必须开启 PHP 的 OpenSSL 扩展才能下载皮肤。如果你没有启用此扩展，
      那么你将可以在此页面顶部看见一个巨大的警告横幅。我建议你按照那个横幅的提示操作，当然你如
      果没有看见那个提示，那将是再好不过的了。
    </p>
    <br>
    <p class="page" >
      ㅤㅤ你也许想问，我没有 HTTP 服务器怎么办？这个工具有 Python 版本，但是不大好用，
      所以我没有开源。或者我之后改进一下再开源吧。或者你也可以使用 Demo 页面，哈哈。
    </p>
    <br>
    <p class="page" >
      ㅤㅤ此工具在 GPLv3 协议下发布，因此请务必遵守开源协议，好吗？我相信你的答案是“好的”。另
      外，由于此工具并不收费，也没有其他有违背 Minecraft EULA 的行为，因此我个人认为这个工具
      没有问题，可以在自己的服务器上以不违反该协议的情况下部署。
    </p>
    <br>
    <br>
    <br>
</body>
</html>
