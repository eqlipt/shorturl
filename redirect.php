<?php
require_once 'init.php' ;

$url_hash = isset($_GET['url']) ? $_GET['url'] : '';

$url = new URL;

// Проверяем наличие короткого URL в базе
$url_row = $url->findActiveRecordByHash( $url_hash );

if ( $url_row ) { // найден принятый короткий URL
    $url->updateCount( $url_row ); // обновляем счётчик посещений
    Utility::redirect( $url_row['url_long'] );  // осуществляем редирект
} else { // URL нет в базе или запись старше 30 дней
		echo
		'<!DOCTYPE html>
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
				<div class="result">
					<p>Short URL has expired or doesn\'t exist</p><br>
				</div>
					<div class="stats-link">
								<a href="index.php">Shorten another URL</a>
					</div>
				</div>
		</body>
		</html>';
}
