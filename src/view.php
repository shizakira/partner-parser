<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Данные</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {}

        a {
            text-decoration: none;
        }

        a:visited {
            color: blue;
        }

        .content {
            margin: 20px auto;
            max-width: 1400px;

            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .partner-rows {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 10px;
        }

        .partner-row {
            padding: 10px;
            width: 600px;
            border: 1px solid black;

        }

        .project-rows {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .project-row {
            border: 1px solid black;
            padding: 10px;
        }

        .pages {
            display: flex;
            gap: 5px;
        }

        .active {
            text-decoration: underline;
        }
    </style>
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
    <script>
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const page = urlParams.get('page');
        const links = document.querySelectorAll('.pagination');

        links[page - 1].classList.add('active');
    </script>
</body>

</html>
