<a href="admin/user/formRegister" class="btn btn-default">Novo Usúario</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Usuário</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($view_list) > 0) {
            foreach ($view_list as $user_data) { ?>
                <tr>
                    <td><?php echo $user_data->id; ?></td>
                    <td><?php echo $user_data->name; ?></td>
                    <td><?php echo $user_data->nick; ?></td>
                    <td><a href="admin/user/formRegister/<?php echo $user_data->id; ?>">Editar</a></td>
                    <td><a href="admin/user/exclude/<?php echo $user_data->id; ?>/<?php echo $user_data->name; ?>">Excluir</a></td>
                </tr>
            <?php }
        } ?>
    </tbody>
</table>