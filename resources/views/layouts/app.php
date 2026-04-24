<?php
declare(strict_types=1);

$appConfig = require BASE_PATH . '/config/app.php';
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? $appConfig['name'], ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="<?= htmlspecialchars($bodyClass ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    <?php require BASE_PATH . '/resources/views/partials/header.php'; ?>
    <?php require BASE_PATH . '/resources/views/partials/login-modal.php'; ?>
	<?php require BASE_PATH . '/resources/views/partials/register-modal.php'; ?>
	<?php require BASE_PATH . '/resources/views/partials/verify-modal.php'; ?>
	<?php require BASE_PATH . '/resources/views/partials/fleamarket-modal.php'; ?>

    <main class="site-main">
        <div class="container">
        </div>
    </main>

    <?php require BASE_PATH . '/resources/views/partials/footer.php'; ?>

    <script src="/assets/js/app.js"></script>
</body>
</html>