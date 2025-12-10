<?php
session_start();
require_once 'db.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            $msg = "Hibás jelszó!";
        }
    } else {
        $msg = "Nincs ilyen felhasználó!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/jpeg" href="kepek/vonat.jpg">
</head>
<body>

    <?php include 'menu.php'; ?>

    <div class="content">
        <div class="page-container">
            <h2 class="cim">Bejelentkezés</h2>
            
            <form method="POST" action="">
                <div class="input-group">
                    <label>Felhasználónév</label>
                    <input type="text" name="username" required>
                </div>
                <div class="input-group">
                    <label>Jelszó</label>
                    <input type="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-blue gomb-teljes">Belépés</button>
            </form>
            
            <?php if($msg != ""): ?>
                <p class="hiba-uzenet"><?php echo $msg; ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>