<?php

use app\entities\UserModel;

$user = null;

if ($_SERVER["REQUEST_METHOD"] === 'GET' && $_GET['id']) {
    $userModel = new UserModel(new Connection());
    $user = $userModel->deleteUser($_GET['id']);
}

header("location: index.php");
die();

