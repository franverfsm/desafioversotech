<?php
use app\entities\UserModel;

$user = null;

if ($_GET['id']) {
    $userModel = new UserModel(new Connection());
    $user = $userModel->find($_GET['id']);
}

if (!$user) {
    header("location: index.php");
    die();
}
?>

<div class="page-header">
    <h1>Detalhe do Usuário</h1>
</div>
<div class="form-group">
    <label>Nome</label>
    <p class="form-control-static"><?php echo $user->name; ?></p>
</div>
<div class="form-group">
    <label>E-mail</label>
    <p class="form-control-static"><?php echo $user->email; ?></p>
</div>
<div class="form-group mb-3">
    <label>Cores</label>
    <p>
    <?php
        if ($user->colors) {
            foreach ($user->colors as $color) {
                echo sprintf('<span class="btn btn-secondary ms-3">%s</span>', $color['name']);
            }
        } else {
            echo sprintf('<span class="btn btn-secondary ms-3">%s</span>', 'Nenhuma cor selecionada');
        }

    ?>
    </p>
</div>
<p><a href="index.php" class="btn btn-primary">Back</a></p>