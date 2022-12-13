<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'test_12_12_2022';

$mysqli = new mysqli($host, $user, $pass, $db_name);

if ($mysqli->connect_errno > 0)
    die('Не могу подключиться к базе данных');

$mysqli->set_charset('utf8');
