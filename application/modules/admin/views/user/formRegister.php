<form action="admin/user/save" method="POST">
    <p><input type="text" name="id" value="<?php echo $view_user_data->id; ?>"></p>
    <p><input type="text" name="name" value="<?php echo $view_user_data->name; ?>"></p>
    <p><input type="text" name="nick" value="<?php echo $view_user_data->nick; ?>"></p>
    <p><input type="text" name="password" value="<?php echo $view_user_data->password; ?>"></p>
    <button type="submit">Gravar</button>
</form>
