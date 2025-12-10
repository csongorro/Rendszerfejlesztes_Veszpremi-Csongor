<?php
session_start();
require_once 'db.php';

$from = "";
$to = "";
$date = "";

if (isset($_GET['from'])) {
    $from = $_GET['from'];
}
if (isset($_GET['to'])) {
    $to = $_GET['to'];
}
if (isset($_GET['date'])) {
    $date = $_GET['date'];
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menetrend</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/jpeg" href="kepek/vonat.jpg">
</head>
<body class="menetrend-hatter">

    <?php include 'menu.php'; ?>

    <div class="content">
        <div class="kereso-doboz">
            <h1 class="cim">Menetrend Kereső</h1>
            
            <form method="GET" action="">
                <div class="mezo">
                    <label>Honnan:</label>
                    <input type="text" name="from" placeholder="Indulási állomás..." value="<?php echo htmlspecialchars($from); ?>">
                </div>
                <div class="mezo">
                    <label>Hová:</label>
                    <input type="text" name="to" placeholder="Érkezési állomás..." value="<?php echo htmlspecialchars($to); ?>">
                </div>
                <div class="mezo">
                    <label>Mikor:</label>
                    <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>">
                </div>
                <button type="submit" class="gomb">Keresés</button>
            </form>

            <?php
            if (isset($_GET['from']) || isset($_GET['to'])) {
                
                $keres_honnan = "%" . $from . "%";
                $keres_hova = "%" . $to . "%";

                if ($date != "") {
                    $sql = "SELECT * FROM jarat WHERE indulasi_allomas LIKE ? AND erkezesi_allomas LIKE ? AND DATE(indulasi_ido) = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sss", $keres_honnan, $keres_hova, $date);
                } else {
                    $sql = "SELECT * FROM jarat WHERE indulasi_allomas LIKE ? AND erkezesi_allomas LIKE ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $keres_honnan, $keres_hova);
                }

                $stmt->execute();
                $result = $stmt->get_result();
                
                echo '<div class="talalatok">';
                echo '<h3>Találatok:</h3>';
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()){
                        echo '<div class="kartya">';
                        echo '  <span class="jaratszam">' . $row['jarat_szam'] . '</span>';
                        echo '  <div class="utvonal">' . $row['indulasi_allomas'] . ' &rarr; ' . $row['erkezesi_allomas'] . '</div>';
                        echo '  <small class="idopont">Indulás: ' . $row['indulasi_ido'] . '</small>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="nincs-talalat">Nincs találat a megadott feltételekkel.</p>';
                }
                echo '</div>';
                
                $stmt->close();
            }
            ?>
        </div>
    </div>
</body>
</html>