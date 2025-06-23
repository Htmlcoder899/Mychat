<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$filename = "chat.txt";
$alertsFile = "alerts.txt";

if (isset($_GET["delete"]) && $_SESSION["role"] == "admin") {
    $lines = explode("\n", file_get_contents($filename));
    unset($lines[$_GET["delete"]]);
    file_put_contents($filename, implode("\n", $lines));
    header("Location: chat.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_SESSION["user"]);
    $message = htmlspecialchars($_POST["message"]);

    $rules = [
        "оскорбл" => "Оскорбление",
        "расист" => "Расизм",
        "модерат" => "Оскорбление модератора",
        "письк" => "Рофл-правило (НЕ выполнять)"
    ];

    foreach ($rules as $badword => $reason) {
        if (stripos($message, $badword) !== false) {
            file_put_contents($alertsFile, "$name нарушил правило: $reason\n", FILE_APPEND);
            break;
        }
    }

    $text = "<div class='message'><b>$name</b>: $message</div>\n";
    file_put_contents($filename, $text, FILE_APPEND);
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Чат</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="top-bar">
  Вы вошли как: <b><?= $_SESSION["user"] ?></b> [<a href="logout.php">Выйти</a>]
</div>

<h2>Мини-чат</h2>

<form method="POST">
  <textarea name="message" rows="3" placeholder="Напиши что-нибудь..." required></textarea>
  <button>Отправить</button>
</form>

<div class="chat-box">
  <?php
  if (file_exists($filename)) {
      $lines = explode("\n", file_get_contents($filename));
      foreach ($lines as $index => $line) {
          if (trim($line) == "") continue;
          echo $line;
          if ($_SESSION["role"] == "admin") {
              echo " <a href='?delete=$index' style='color:red;'>[Удалить]</a>";
          }
      }
  } else {
      echo "Нет сообщений.";
  }
  ?>
</div>

<div class="rules-box">
  <h4>Правила чата:</h4>
  <ol>
    <li>Не оскорблять других пользователей</li>
    <li>Не быть расистом</li>
    <li>Не оскорблять модераторов</li>
    <li>Обязательно скинуть свою письку (РОФЛ! НЕ НАДО ЭТОГО!)</li>
  </ol>
</div>

</body>
</html>
