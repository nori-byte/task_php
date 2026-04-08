<!--<h1>Список сотрудников</h1>-->
<!---->
<!--<table border="1">-->
<!--    <tr>-->
<!--        <th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Дата рождения</th><th>Пол</th><th>Адрес</th>-->
<!--    </tr>-->
<!--    --><?php //foreach ($employees as $employee): ?>
<!--        <tr>-->
<!--            <td>--><?php //= $employee->last_name ?><!--</td>-->
<!--            <td>--><?php //= $employee->first_name ?><!--</td>-->
<!--            <td>--><?php //= $employee->middle_name ?><!--</td>-->
<!--            <td>--><?php //= $employee->birth_date ?><!--</td>-->
<!--            <td>--><?php //= $employee->gender ?><!--</td>-->
<!--            <td>--><?php //= $employee->address ?><!--</td>-->
<!--        </tr>-->
<!--    --><?php //endforeach; ?>
<!--</table>-->

<h1>Список сотрудников</h1>

<table border="1">
    <thead>
    <tr>
        <th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Дата рождения</th><th>Пол</th><th>Адрес</th><th>Подразделение</th><th>Действие</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($employees as $employee): ?>
        <tr>
            <td><?= htmlspecialchars($employee->last_name) ?></td>
            <td><?= htmlspecialchars($employee->first_name) ?></td>
            <td><?= htmlspecialchars($employee->middle_name) ?></td>
            <td><?= htmlspecialchars($employee->birth_date) ?></td>
            <td><?= htmlspecialchars($employee->gender) ?></td>
            <td><?= htmlspecialchars($employee->address) ?></td>
            <td>
                <?php if ($employee->department): ?>
                    <?= htmlspecialchars($employee->department->name_department) ?>
                <?php else: ?>
                    Не назначено
                <?php endif; ?>
            </td>
            <td>
                <form method="post" action="./employees">
                    <input type="hidden" name="csrf_token" value="<?= app()->auth::generateCSRF() ?>">
                    <input type="hidden" name="id_employee" value="<?= $employee->id_employee ?>">
                    <select name="id_department">
                        <option value=""> Выберите подразделение </option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= $dept->id_department ?>" <?= ($employee->id_department == $dept->id_department) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dept->name_department) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Прикрепить</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>



