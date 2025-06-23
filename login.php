<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $users = json_decode(file_get_contents("users.json"), true);
    $login = $_POST["login"];
    $password = $_POST["password"];

    if (isset($users[$login]) && password_verify($password, $users[$login]["password"])) {
        $_SESSION["user"] = $login;
        $_SESSION["role"] = $users[$login]["role"];
        header("Location: chat.php");
        exit();
    } else {
        echo "Неверный логин или пароль.";
    }
}
?>
<form method="POST">
  <input name="login" placeholder="Логин"><br>
  <input type="password" name="password" placeholder="Пароль"><br>
  <button>Войти</button>
</form>
