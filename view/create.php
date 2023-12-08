<?php
use app\data\UserDTO;
use app\entities\ColorModel;
use app\entities\UserModel;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userData = new UserDTO(
        name: $_POST['name'] ?? '',
        email: $_POST['email'] ?? '',
        colors: $_POST['colors'] ?? [],
    );

    $insert = new UserModel(new Connection());
    if ($insert->createUser($userData)) {
        header("location: index.php");
        exit();
    }

    echo "erro ao cadastrar";
}

?>


<div class="page-header">
    <h2>Criar Usuário</h2>
</div>

<form action="index.php?action=create" id="create-user" method="post">
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
            <?php
                $colors = new ColorModel(new Connection());

                foreach ($colors->getColors() as $color) {
                    $selected = in_array($color->id, $userData->colors ?? []) ? 'selected' : '';
                    echo sprintf("<option value='%d' %s>%s</option>", $color->id, $selected, $color->name);
                }
            ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Criar</button>
    <a href="index.php" class="btn btn-default">Cancel</a>
</form>
