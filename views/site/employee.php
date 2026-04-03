<h1>Список сотрудников</h1>

<table border="1">
    <tr>
        <th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Дата рождения</th><th>Пол</th><th>Адрес</th>
    </tr>
    <?php foreach ($employees as $employee): ?>
        <tr>
            <td><?= $employee->last_name ?></td>
            <td><?= $employee->first_name ?></td>
            <td><?= $employee->middle_name ?></td>
            <td><?= $employee->birth_date ?></td>
            <td><?= $employee->gender ?></td>
            <td><?= $employee->address ?></td>
        </tr>
    <?php endforeach; ?>
</table>
