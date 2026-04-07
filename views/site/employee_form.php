<h2>Добавление сотрудника</h2>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= app()->auth::generateCSRF() ?>">
    <label>Фамилия: <input type="text" name="last_name" required></label><br>
    <label>Имя: <input type="text" name="first_name" required></label><br>
    <label>Отчество: <input type="text" name="middle_name"></label><br>
    <label>Пол:
        <select name="gender">
            <option value="М">М</option>
            <option value="Ж">Ж</option>
        </select>
    </label><br>
    <label>Дата рождения: <input type="date" name="birth_date" required></label><br>
    <label>Адрес: <input type="text" name="address"></label><br>
    <label>Подразделение:
        <select name="id_department" required>
            <option value=""> Выберите </option>
            <?php foreach ($departments as $dept): ?>
                <option value="<?= $dept->id_department ?>"><?= htmlspecialchars($dept->name_department) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <button>Сохранить</button>
</form>
