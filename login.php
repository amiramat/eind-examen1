<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = $_POST['wachtwoord'];

    $stmt = $pdo->prepare("SELECT * FROM medewerkers WHERE gebruikersnaam = ?");
    $stmt->execute([$gebruikersnaam]);
    $gebruiker = $stmt->fetch();

    if ($gebruiker && password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
        $_SESSION['medewerker'] = $gebruikersnaam;
        header('Location: medewerker.php');
        exit;
    } else {
        $error = "Ongeldige gebruikersnaam of wachtwoord.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Login - Medewerker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="navbar">
    <div class="nav-container">
        <div class="nav-left">
            <a href="index.php" class="logo-link">
                <h1>🍫 De Bonte Koe</h1>
            </a>
        </div>
        <div class="nav-right">
            <div class="dropdown">
                <button class="dropbtn">Menu ▾</button>
                <div class="dropdown-content">
                    <a href="index.php">Home</a>
                    <?php
                    $pagina = basename($_SERVER['PHP_SELF']);

                    if ($pagina == "index.php") {
                        echo '<a href="login.php">Login</a>';
                    } else {
                        if (isset($_SESSION['medewerker'])) {
                            echo '<a href="dashboard.php">Dashboard</a>';
                            echo '<a href="logout.php">Uitloggen</a>';
                        } else {
                            echo '<a href="login.php">Login</a>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</header>



<main class="container">
    <section class="form-section">
        <h2>Medewerker Login</h2>
        <?php if (!empty($error)) echo "<p class='alert-error'>$error</p>"; ?>
        <form method="post">
            <label>Gebruikersnaam:
                <input type="text" name="gebruikersnaam" required>
            </label>
            <label>Wachtwoord:
                <input type="password" name="wachtwoord" required>
            </label>
            <button type="submit">Inloggen</button>
        </form>
        <p>Nog geen account? <a href="register.php">Registreren</a></p>
        <p><a href="index.php" class="teruglink">← Terug naar startpagina</a></p>
    </section>
</main>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-contact">
            <h3>Contact</h3>
            <p>📍 Hoogstraat 78, Rotterdam</p>
            <p>📞 010 - 123 4567</p>
            <p>✉️ <a href="mailto:debontkoe@info.nl">debontkoe@info.nl</a></p>
        </div>
        <div class="footer-social">
            <h3>Volg ons</h3>
            <p>📷 Instagram: <a href="https://www.instagram.com/debontekoechocolade/" target="_blank">@DeBonteKoe</a></p>
            <p>🎥 TikTok: <a href="#">@DeBonteKoe</a></p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 De Bonte Koe - Alle rechten voorbehouden</p>
    </div>
</footer>

</body>
</html>
