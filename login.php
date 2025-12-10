<?php
// 取得表單資料
$username = $_POST["username"] ?? "";
$passcode = $_POST["passcode"] ?? "";

// 安全處理（避免 XSS）
$safeUser = htmlspecialchars($username);
$safePass = htmlspecialchars($passcode);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Login Result</title>
  <style>
    body {
      font-family: Arial;
      padding: 20px;
      background: #FFF0F5;
    }
    .box {
      background: white;
      padding: 20px;
      border-radius: 10px;
      border: 2px solid black;
      width: 300px;
    }
    h2 { margin-top: 0; }
    a { display: block; margin-top: 20px; }
  </style>
</head>
<body>
  
  <div class="box">
    <h2>Login Result</h2>

    <p><b>User:</b> <?= $safeUser ?></p>
    <p><b>Passcode:</b> <?= $safePass ?></p>

    <a href="index.php">← Back to Home</a>
  </div>

</body>
</html>
