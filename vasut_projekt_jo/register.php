<?php
session_start();
require_once 'db.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $pass1 = $_POST["pass1"];
    $pass2 = $_POST["pass2"];

    if ($pass1 !== $pass2) {
        $msg = "A jelszavak nem egyeznek!";
    } else {
        $hashed = password_hash($pass1, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed);
        
        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $msg = "Hiba: foglalt név vagy email.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/jpeg" href="kepek/vonat.jpg">
</head>
<body>

    <?php include 'menu.php'; ?>

    <div class="content">
        <div class="page-container bejelentkezes-doboz">
            <h2 class="cim">Regisztráció</h2>
            
            <form method="POST" action="">
                <div class="input-group">
                    <label>Felhasználónév</label>
                    <input type="text" name="username" required>
                </div>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="input-group">
                    <label>Jelszó</label>
                    <input type="password" name="pass1" required>
                </div>
                <div class="input-group">
                    <label>Jelszó megerősítése</label>
                    <input type="password" name="pass2" required>
                </div>
                
                <button type="submit" class="btn btn-blue gomb-teljes">Regisztráció</button>
            </form>
            
            <?php if($msg != ""): ?>
                <p class="hiba-szoveg"><?php echo $msg; ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>