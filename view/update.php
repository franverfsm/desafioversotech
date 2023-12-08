<?php
use app\data\UserDTO;
use app\entities\ColorModel;
use app\entities\UserModel;

$id = $_GET['id'] ?: $_POST['id'];
if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST['id']) {
    $userData = new UserDTO(
        name: $_POST['name'] ?? '',
        email: $_POST['email'] ?? '',
        colors: $_POST['colors'] ?? [],
    );

    $update = new UserModel(new Connection());
    if ($update->updateUser($id, $userData)) {
        header("location: index.php");
        die();
    }

    $colorsSelected = $_POST['colors'] ?? [];

    echo "erro ao atualizar";
} elseif ($_SERVER["REQUEST_METHOD"] === 'GET' && $_GET['id']) {
    $userModel = new UserModel(new Connection());
    $userData = $userModel->find($id);

    $colorsSelected = array_column($userData->colors, 'id');
} else {
    header('location: index.php');
    die();
}
?>


<div class="page-header">
    <h2>Alterar Usuário</h2>
</div>

<form action="index.php?action=edit" id="edit-user" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
    <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input type="text" id="name" name="name" maxlength="100" class="form-control" value="<?= $userData->name ?? ''; ?>" required>
        <div class="invalid-feedback">
            Por favor preencher o nome do usuário.
        </div>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" maxlength="100" class="form-control" value="<?= $userData->email ?? ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="color" class="form-label">Cor</label>
        <select id="color" multiple name="colors[]" class="form-select">
            <option <?= !$colorsSelected ? 'selected' : ''; ?>>Nenhuma cor</option>
            <?php
            $colors = new ColorModel(new Connection());

            foreach ($colors->getColors() as $color) {
                $selected = in_array($color->id, $colorsSelected ?? []) ? 'selected' : '';
                echo sprintf("<option value='%d' %s>%s</option>", $color->id, $selected, $color->name);
            }
            ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Atualizar</button>
    <a href="index.php" class="btn btn-default">Cancel</a>
</form>
