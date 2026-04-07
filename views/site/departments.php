<h1>Список подразделений</h1>

<?php
$selectedDepartments = $selectedDepartments ?? [];
$employees = $employees ?? [];
?>

<h2>Выбор сотрудников по подразделению</h2>

<form method="get">
    <fieldset>
        <legend>Выберите подразделение(я):</legend>
        <?php foreach ($departments as $dept): ?>
            <label>
                <input type="checkbox" name="department_ids[]" value="<?= $dept->id_department ?>"
                        <?= in_array($dept->id_department, $selectedDepartments) ? 'checked' : '' ?>>
                <?= htmlspecialchars($dept->name_department) ?>
            </label><br>
        <?php endforeach; ?>
    </fieldset>
    <button type="submit">Показать сотрудников</button>
    <a href="/task_php/departments">Сбросить</a>
</form>

<?php if (!empty($selectedDepartments)): ?>
    <?php if (count($employees) > 0): ?>
        <h3>Сотрудники выбранных подразделений:</h3>
        <table border="1">
            <thead>
            <tr><th>ФИО</th><th>Подразделение</th></tr>
            </thead>
            <tbody>
            <?php foreach ($employees as $emp): ?>
                <tr>
                    <td><?= htmlspecialchars("{$emp->last_name} {$emp->first_name} {$emp->middle_name}") ?></td>
                    <td><?= htmlspecialchars($emp->department->name_department ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>В выбранных подразделениях нет сотрудников.</p>
    <?php endif; ?>
<?php endif; ?>