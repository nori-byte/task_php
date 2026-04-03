<!--<h1>Список подразделений</h1>-->
<!--<table border="1">-->
<!--    <tr>-->
<!--        <th>Номер подразделения</th><th>Название </th><th>Вид подразделения</th>-->
<!--    </tr>-->
<!--    --><?php //foreach ($departments as $department): ?>
<!--        <tr>-->
<!--            <td>--><?php //= $department->	id_department  ?><!--</td>-->
<!--            <td>--><?php //= $department->name_department ?><!--</td>-->
<!--            <td>--><?php //= $department->view_department ?><!--</td>-->
<!--        </tr>-->
<!--    --><?php //endforeach; ?>
<!--</table>-->
<h1>Список подразделений</h1>
<table border="1">
    <thead>
    <tr>
        <th>Номер подразделения</th>
        <th>Название</th>
        <th>Вид подразделения</th>
        <th>Средний возраст сотрудников</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($departments as $department): ?>
        <tr>
            <td><?= htmlspecialchars($department->id_department) ?></td>
            <td><?= htmlspecialchars($department->name_department) ?></td>
            <td><?= htmlspecialchars($department->view_department) ?></td>
            <td>
                <?php if ($department->avg_age !== null): ?>
                    <?= $department->avg_age ?> лет
                <?php else: ?>
                    Нет сотрудников
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

