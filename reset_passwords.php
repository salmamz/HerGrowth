<?php
$pdo = new PDO('mysql:host=localhost;dbname=hergrowth;charset=utf8mb4', 'root', '');
$hash = password_hash('test', PASSWORD_DEFAULT);
$pdo->exec("UPDATE users SET password = '$hash' WHERE email IN ('sarra@coach.com', 'fatima@client.com', 'marie@client.com')");
echo "Passwords reset successfully.";
?>
