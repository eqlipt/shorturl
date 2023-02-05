<?php
require_once 'init.php';

$url = new URL;

if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
    $url->processInputURL( $_POST['url_long'] );
}

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
        <form action="index.php" method="post">
            <input type="text" name="url_long" placeholder="full URL ( including http:// )" value="<?php echo $_POST['url_long'] ?? '';?>">
            <button type="submit">Shorten URL</button>
        </form>
        <div class="result">
            <?php if ( empty( $url->error ) ) : ?>
                <p><?php echo !empty( $url->url_long ) ? 'Short URL: ' . $url->url_long : ''; ?></p>
                <p><?php echo !empty( $url->url_short ) ? 'Original URL: ' . $url->url_short : ''; ?></p>
            <?php else : ?>
                <p><?php echo !empty( $url->error ) ? $url->error : ''; ?></p>
            <?php endif; ?>
        </div>
        <div class="stats-link">
            <a href="stats.php">Stats</a>
        </div>
    </div>
</body>
</html>
