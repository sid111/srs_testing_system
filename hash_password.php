<?php
$password = "admin123"; // Change this to your desired password
$hashed = password_hash($password, PASSWORD_BCRYPT);
echo "Hashed Password: " . $hashed;
echo "<br><br>";
echo "Copy this SQL and run it in phpMyAdmin:<br><br>";
echo "INSERT INTO admin (username, password, email) VALUES ('admin', '" . $hashed . "', 'admin@srs.com');";
?>