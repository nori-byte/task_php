<!DOCTYPE html>
<html>
<head>
    <title>Список пользователей</title>
    <style>
        .flash { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .flash.success { background: #d4edda; color: #155724; }
        .flash.error { background: #f8d7da; color: #721c24; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h1>Управление пользователями</h1>




<p><a href="/task_php/signup"> Зарегистрировать нового пользователя</a></p>

<table>
    <thead>
    <tr>

        <th>Имя</th>
        <th>Логин</th>
        <th>Роль</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($user->login, ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($user->role->role_name ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>