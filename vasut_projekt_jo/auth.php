<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: profil.php");
    exit();
}

$login_msg = "";
$reg_msg = "";
$reg_success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_btn'])) {
    $username = $_POST["login_username"];
    $password = $_POST["login_password"];

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
            header("Location: profil.php");
            exit();
        } else {
            $login_msg = "Hibás felhasználónév vagy jelszó!";
        }
    } else {
        $login_msg = "Hibás felhasználónév vagy jelszó!";
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_btn'])) {
    $username = $_POST["reg_username"];
    $email = $_POST["reg_email"];
    $pass1 = $_POST["reg_pass1"];
    $pass2 = $_POST["reg_pass2"];

    $check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
    $check = $conn->prepare($check_sql);
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $check->store_result();
    
    if ($check->num_rows > 0) {
        $reg_msg = "Ez a felhasználónév vagy email már foglalt!";
    } elseif ($pass1 !== $pass2) {
        $reg_msg = "A két jelszó nem egyezik!";
    } else {
        $hashed = password_hash($pass1, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed);
        
        if ($stmt->execute()) {
            $reg_success = true;
            $reg_msg = "Sikeres regisztráció! Most már bejelentkezhet.";
        } else {
            $reg_msg = "Hiba történt a regisztráció során.";
        }
        $stmt->close();
    }
    $check->close();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés / Regisztráció</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/jpeg" href="kepek/vonat.jpg">
</head>
<body>
    <?php include 'menu.php'; ?>
    
    <div class="content">
        <div class="page-container auth-container">
            <div class="auth-wrapper">
                
                <div class="auth-box">
                    <h2 class="cim">Bejelentkezés</h2>
                    <form method="POST">
                        <div class="input-group">
                            <label>Felhasználónév</label>
                            <input type="text" name="login_username" required>
                        </div>
                        <div class="input-group">
                            <label>Jelszó</label>
                            <input type="password" name="login_password" required>
                        </div>
                        <button type="submit" name="login_btn" class="btn btn-blue gomb-teljes">Belépés</button>
                    </form>
                    <?php if($login_msg): ?>
                        <p class="hiba-uzenet"><?php echo $login_msg; ?></p>
                    <?php endif; ?>
                </div>

                <div class="auth-divider"></div>

                <div class="auth-box">
                    <h2 class="cim">Regisztráció</h2>
                    <?php if($reg_success): ?>
                        <div class="siker-uzenet">
                            <?php echo $reg_msg; ?>
                        </div>
                    <?php else: ?>
                        <form method="POST">
                            <div class="input-group">
                                <label>Felhasználónév</label>
                                <input type="text" name="reg_username" required>
                            </div>
                            <div class="input-group">
                                <label>Email cím</label>
                                <input type="email" name="reg_email" required>
                            </div>
                            <div class="input-group">
                                <label>Jelszó</label>
                                <input type="password" name="reg_pass1" required>
                            </div>
                            <div class="input-group">
                                <label>Jelszó megerősítése</label>
                                <input type="password" name="reg_pass2" required>
                            </div>
                            <button type="submit" name="register_btn" class="btn btn-yellow gomb-teljes">Regisztráció</button>
                        </form>
                        <?php if($reg_msg): ?>
                            <p class="hiba-uzenet"><?php echo $reg_msg; ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</body>
</html>