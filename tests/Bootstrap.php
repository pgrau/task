<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__.'/../.env');

$databases = file_get_contents(__DIR__.'/../config/database/mysql/database.sql');
$schema = file_get_contents(__DIR__.'/../config/database/mysql/task.sql');

$dsn = 'mysql:host=' . $_ENV['MYSQL_HOST'];
$user = $_ENV['MYSQL_USER'];
$password = '';

try {
    $pdo = new \PDO($dsn, $user, $password);
    $pdo->exec($databases);
    $pdo->exec('USE task_test');
    $pdo->exec($schema);
} catch (\PDOException $e) {
    echo $e->getMessage() . PHP_EOL;
    die;
}
