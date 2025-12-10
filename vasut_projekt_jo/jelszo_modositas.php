<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_pass = $_POST["new_password"];
    $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
    
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashed, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        $msg = "Sikeres jelszócsere!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelszó módosítás</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/jpeg" href="kepek/vonat.jpg">
</head>
<body>

    <?php include 'menu.php'; ?>

    <div class="content">
        <div class="page-container bejelentkezes-doboz">
            <h2 class="cim">Jelszó módosítása</h2>
            
            <form method="POST">
                <div class="input-group">
                    <label>Új jelszó:</label>
                    <input type="password" name="new_password" required>
                </div>
                
                <button type="submit" class="btn btn-red gomb-teljes">Módosítás</button>
                <a href="profil.php" class="btn btn-blue gomb-teljes">Vissza</a>
            </form>
            
            <?php if($msg != ""): ?>
                <p class="siker-szoveg"><?php echo $msg; ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>