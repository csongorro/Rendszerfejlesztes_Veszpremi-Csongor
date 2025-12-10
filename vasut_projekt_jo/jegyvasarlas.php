<?php
session_start();
require_once 'db.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['user_id'])) {
        $jarat_id = $_POST['jarat_id'];
        
        $sql = "INSERT INTO jegyek (user_id, jarat_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $_SESSION['user_id'], $jarat_id);
        
        if($stmt->execute()){
            $msg = "Sikeres vásárlás!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jegyvásárlás</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/jpeg" href="kepek/vonat.jpg">
</head>
<body class="jegyvasarlas-hatter">

    <?php include 'menu.php'; ?>

    <div class="content">
        <div class="page-container jegyvasarlas-doboz">
            <h1 class="cim">Jegyvásárlás</h1>
            
            <?php if(!isset($_SESSION['user_id'])): ?>
                
                <div class="figyelmeztetes">
                    <p>Kérlek jelentkezz be a vásárláshoz!</p>
                    <a href="auth.php" class="btn btn-blue">Bejelentkezés / Regisztráció</a>
                </div>

            <?php else: ?>
                
                <?php if($msg != ""): ?>
                    <p class="siker-uzenet"><?php echo $msg; ?></p>
                <?php endif; ?>

                <h3>Elérhető járatok:</h3>
                
                <div class="jarat-lista">
                <?php
                $sql = "SELECT * FROM jarat";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()){
                ?>
                        <div class="jarat-sor">
                            <div class="jarat-adatok">
                                <strong class="szam"><?php echo $row['jarat_szam']; ?></strong>
                                <span class="ut"><?php echo $row['indulasi_allomas']; ?> &rarr; <?php echo $row['erkezesi_allomas']; ?></span>
                                <small class="ido">Indulás: <?php echo $row['indulasi_ido']; ?></small>
                            </div>
                            <div class="jarat-vasarlas">
                                <span class="ar"><?php echo $row['ar']; ?> Ft</span>
                                <form method="POST">
                                    <input type="hidden" name="jarat_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-yellow gomb-kicsi">Megvétel</button>
                                </form>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p class='nincs-adat'>Jelenleg nincs elérhető járat az adatbázisban.</p>";
                }
                ?>
                </div>

            <?php endif; ?>
        </div>
    </div>

</body>
</html>