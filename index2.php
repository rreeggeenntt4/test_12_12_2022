<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bdconnect.php";


mysqli_query($mysqli, "CREATE TABLE IF NOT EXISTS `users` (
    id int(10) unsigned NOT NULL AUTO_INCREMENT,
    name text DEFAULT NULL,
    PRIMARY KEY (id)
    )");

mysqli_query($mysqli, "CREATE TABLE IF NOT EXISTS `orders` (
    id int(10) unsigned NOT NULL AUTO_INCREMENT,
    users_id int(10) DEFAULT NULL,
    status text DEFAULT NULL,
    PRIMARY KEY (id)
    )");


$i = 0;
$rows = 3;
while ($i <= $rows) {
    $u = array("Антон", "Иван", "Мария", "Ритта");
    $u_name = $u[mt_rand(0, 3)];

    $sql = "INSERT INTO `users` (`name`) VALUES ('" . $u_name . "')";
    $mysqli->query($sql);
    $i++;
}


$i = 0;
$rows = 3;
while ($i <= $rows) {
    $u_id = mt_rand(1, 4);
    $status = mt_rand(0, 3);

    $sql = "INSERT INTO `orders` (`users_id`,`status`) VALUES ('" . $u_id . "','" . $status . "')";
    $mysqli->query($sql);

    $i++;
}