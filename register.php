<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $users = file_exists("users.json") ? json_decode(file_get_contents("users.json"), true) : [];

    $login = $_POST["login"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    if (isset($users[$login])) {
        echo "Такой логин уже существует.";
    } else {
        $users[$login] = [
            "password" => $password,
            "role" => "user"
        ];
        file_put_contents("users.json", json_encode($users, JSON_PRETTY_PRINT));
        echo "Регистрация успешна. <a href='login.php'>Войти</a>";
    }
}
?>
<form method="POST">
  <input name="login" placeholder="Логин" required><br>
  <input type="password" name="password" placeholder="Пароль" required><br>
  <button>Зарегистрироваться</button>
</form>
