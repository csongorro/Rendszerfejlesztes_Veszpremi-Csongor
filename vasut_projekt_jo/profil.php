<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

if (isset($_POST['upload'])) {
    $target_dir = "uploads/";
    
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $filename = basename($_FILES["fileToUpload"]["name"]);
    $uj_nev = time() . "_" . $filename;
    $target_file = $target_dir . $uj_nev;
    
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $sql = "UPDATE users SET profile_image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $target_file, $user_id);
        $stmt->execute();
        $msg = "Sikeres feltöltés!";
    } else {
        $msg = "Hiba a feltöltéskor.";
    }
}

$sql = "SELECT username, email, profile_image FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$profil_kep = "kepek/default.png";
if ($user['profile_image'] != 'default.png' && $user['profile_image'] != "") {
    $profil_kep = $user['profile_image'];
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/jpeg" href="kepek/vonat.jpg">
</head>
<body>
    
    <?php include 'menu.php'; ?>

    <div class="content">
        <div class="page-container">
            <h1 class="cim">Profilom</h1>
            
            <div class="profil-adatok">
                <img src="<?php echo $profil_kep; ?>" class="profil-kep" alt="Profilkép">
                <h2><?php echo htmlspecialchars($user['username']); ?></h2>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div>

            <hr class="elvalaszto">

            <h3>Profilkép módosítása</h3>
            <form method="POST" enctype="multipart/form-data" class="feltoltes-form">
                <input type="file" name="fileToUpload" required>
                <button type="submit" name="upload" class="btn btn-yellow">Kép feltöltése</button>
            </form>

            <h3>Biztonság</h3>
            <a href="jelszo_modositas.php" class="btn btn-red">Jelszó módosítása</a>
            
            <?php if($msg != ""): ?>
                <p class="siker-szoveg"><?php echo $msg; ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>