<h2>Регистрация нового пользователя</h2>
<h3><?= $message ?? ''; ?></h3>
<form method="post">
    <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
    <label>Имя <input type="text" name="name"></label>
    <label>Логин <input type="text" name="login"></label>
    <label>Пароль <input type="password" name="password"></label>
    <label>Роль:
        <select name="role">
            <option value="hr_staff">hr_staff</option>
            <option value="admin">admin</option>
        </select>
    </label><br>
    <button>Зарегистрировать</button>
</form>
