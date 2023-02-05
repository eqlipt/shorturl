<?php
require_once 'init.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Short URL service</title>
    <link rel="stylesheet" href="style.css">
    </style>
</head>
<body>
    <div class="wraper">
        <div class="stats">
            <table>
                <thead>
                    <tr>
                        <th>Short URL</th>
                        <th>Long URL</th>
                        <th>Hits</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    // Выводим все записи из БД
                    $url = new URL;
                    $result = $url->findAllRecords();

                    while ( $url_row = $result->fetch_assoc() ) : ?>

                    <tr>
                        <td><?php echo Utility::buildShortURLFromHash($url_row['url_hash']); ?></td>
                        <td><?php echo $url_row['url_long']; ?></td>
                        <td><?php echo $url_row['count']; ?></td>
                    </tr>

                    <?php endwhile; ?>

                </tbody>
            </table>
        </div>
        <div class="stats-link">
            <a href="index.php">Shorten another URL</a>
        </div>
    </div>
</body>
</html>