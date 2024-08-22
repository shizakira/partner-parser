<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data</title>
    <link rel="stylesheet" href="../View/style.css">
</head>

<body>
    <div class="content">
        <?php if (!isset($_GET['projects'])): ?>
            <h1 class="partners-title">Партнеры</h1>
            <div class="navigation">
                <div class="pages">
                    <?= $paginator->showTotalPages(); ?>
                </div>
                <div class="transition-buttons">
                    <button class="prev-page-btn">Предыдущая страница</button>
                    <button class="next-page-btn">Следующая страница</button>
                </div>
            </div>
            <div class="partner-rows">
                <?= $paginator->showPartners(); ?>
            </div>
        <?php else: ?>
            <h1 class="projects-title">Проекты</h1>
            <div class=" project-rows">
                <?= $paginator->showPartnerProjects(); ?>
            </div>
        <?php endif; ?>
        <button class="up-btn">^^^</button>
    </div>

    <script src="../View/main.js"></script>
</body>

</html>
