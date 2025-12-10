<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="top-nav">
    <a href="index.php">Főoldal</a>
    <a href="menetrendek.php">Menetrend</a>
    <a href="jegyvasarlas.php">Jegyvásárlás</a>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="profil.php">Profil</a>
        <a href="logout.php">Kijelentkezés</a>
    <?php else: ?>
        <a href="login.php">Bejelentkezés</a>
        <a href="register.php">Regisztráció</a>
    <?php endif; ?>
</div>

<div class="side-nav">
    <a href="index.php" title="Főoldal"><i class="fas fa-home"></i></a>
    <a href="menetrendek.php" title="Menetrend"><i class="fas fa-clock"></i></a>
    <a href="jegyvasarlas.php" title="Jegyvásárlás"><i class="fas fa-ticket-alt"></i></a>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="profil.php" title="Profil"><i class="fas fa-user"></i></a>
        <a href="logout.php" title="Kijelentkezés"><i class="fas fa-sign-out-alt"></i></a>
    <?php else: ?>
        <a href="login.php" title="Bejelentkezés"><i class="fas fa-sign-in-alt"></i></a>
        <a href="register.php" title="Regisztráció"><i class="fas fa-user-plus"></i></a>
    <?php endif; ?>
</div>