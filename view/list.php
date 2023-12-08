<div class="page-header clearfix">
    <h2 class="pull-left">Usuários</h2>
    <a href="index.php?action=create" class="btn btn-success pull-right">Add Novo Usuário</a>
</div>
<?php

use app\entities\UserModel;

$userModel = new UserModel(new Connection());

echo "<table class='table table-bordered table-striped'>
    <thead>
        <tr>
            <th class='text-center'>ID</th>    
            <th class='text-center'>Nome</th>    
            <th class='text-center'>Email</th>
            <th class='text-center'>Ação</th>    
        </tr>
    </thead>
";

echo "<tbody>";

foreach($userModel->getUsers() as $user) {
    echo sprintf("<tr>
                      <td>%s</td>
                      <td>%s</td>
                      <td>%s</td>
                      <td class='text-center'>
                        <a class='btn btn-primary' href='index.php?action=display&id=". $user->id ."' title='Ver Usuário' data-toggle='tooltip'><i class='fa fa-eye'></i></a>
                        <a class='btn btn-secondary' href='index.php?action=edit&id=". $user->id ."' title='Atualizar Usuário' data-toggle='tooltip'><i class='fa fa-pencil'></i></a>
                        <a class='btn btn-danger' href='index.php?action=remove&id=". $user->id ."' title='Remover Usuário' data-toggle='tooltip'><i class='fa fa-trash'></i></a>
                      </td>
                   </tr>",
        $user->id, $user->name, $user->email);

}
echo "</tbody>";
echo "</table>";
