

<?php
session_start();
if (!isset($_SESSION['medewerker'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

try {
    // Ophalen statistieken
    $totaal = $pdo->query("SELECT COUNT(*) AS totaal FROM bestellingen")->fetch()['totaal'] ?? 0;
    $inBehandeling = $pdo->query("SELECT COUNT(*) AS aantal FROM bestellingen WHERE status = 'In behandeling'")->fetch()['aantal'] ?? 0;
    $opgehaald = $pdo->query("SELECT COUNT(*) AS aantal FROM bestellingen WHERE status = 'Opgehaald'")->fetch()['aantal'] ?? 0;

    // Omzetberekening op basis van opgehaalde bestellingen
    $stmtOmzet = $pdo->query("SELECT SUM(aantal) AS totaal_stuks FROM bestellingen WHERE status = 'Opgehaald'");
    $totaalStuks = $stmtOmzet->fetch()['totaal_stuks'] ?? 0;

    $prijsPerStuk = 12.95;
    $totaalOmzet = $totaalStuks * $prijsPerStuk;

} catch (Exception $e) {
    $foutmelding = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - De Bonte Koe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="navbar">
    <div class="nav-container">
        <div class="nav-left">
            <a href="index.php" class="logo-link">
                <h1><span class="logo-icoon">🍫</span> De Bonte Koe</h1>
            </a>
        </div>
        <div class="nav-right">
            <div class="dropdown">
                <button class="dropbtn">Menu ▾</button>
                <div class="dropdown-content">
                    <a href="index.php">Home</a>

                    <?php if (isset($_SESSION['medewerker'])): ?>
                        <a href="dashboard.php">Dashboard</a>
                        <a href="logout.php">Uitloggen</a>
                    <?php else: ?>
                        <a href="login.php">Login</a>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</header>

<main class="container">
    <section class="form-section">
        <h2>Statistieken</h2>

        <?php if (!empty($foutmelding)): ?>
            <p class="alert-error"><?= htmlspecialchars($foutmelding); ?></p>
        <?php else: ?>
            <ul style="list-style: none; padding: 0; font-size: 1.2rem;">
                <li><strong>Totaal bestellingen:</strong> <?= htmlspecialchars($totaal); ?></li>
                <li><strong>In behandeling:</strong> <?= htmlspecialchars($inBehandeling); ?></li>
                <li><strong>Opgehaald:</strong> <?= htmlspecialchars($opgehaald); ?></li>
                <li><strong>Totaal omzet opgehaald:</strong> €<?= number_format($totaalOmzet, 2, ',', '.'); ?></li>
            </ul>
        <?php endif; ?>
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
