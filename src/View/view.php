<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Данные</title>
    <link rel="stylesheet" href="../View/style.css">
</head>

<body>
    <div class="content">
        <?php if (!isset($_GET['projects'])): ?>
            <div class="partner-rows">
                <?= $paginator->showPartners(); ?>
            </div>
            <div class="pages">
                <?= $paginator->showTotalPages(); ?>
            </div>
        <?php else: ?>
            <div class="project-rows">
                <?= $paginator->showPartnerProjects(); ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="../View/main.js"></script>
</body>

</html>
