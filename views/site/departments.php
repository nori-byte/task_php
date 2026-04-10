<h1>Список подразделений</h1>

<?php

$selectedDepartments = $selectedDepartments ?? [];
$employees = $employees ?? [];
$showAverageAge = $showAverageAge ?? false;
$averageAges = $averageAges ?? [];
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
    <button type="submit" name="show_age" value="1">Подсчитать средний возраст</button>
    <a href="/task_php/departments">Сбросить</a>
</form>

<?php if (!empty($selectedDepartments)): ?>
    <?php if (count($employees) > 0): ?>
        <h3>Сотрудники выбранных подразделений:</h3>
        <table border="1">
            <thead>
            <tr>
                <th>ФИО</th>
                <th>Подразделение</th>
                <?php if ($showAverageAge): ?>
                    <th>Средний возраст по подразделению</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($employees as $emp): ?>
                <tr>
                    <td><?= htmlspecialchars("{$emp->last_name} {$emp->first_name} {$emp->middle_name}") ?></td>
                    <td><?= htmlspecialchars($emp->department->name_department ?? '') ?></td>
                    <?php if ($showAverageAge): ?>
                        <td>
                            <?= isset($averageAges[$emp->id_department]) ? $averageAges[$emp->id_department] . ' лет' : '—' ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>В выбранных подразделениях нет сотрудников.</p>
    <?php endif; ?>
<?php endif; ?>

<h1>Новое подразделение</h1>
<form method="post">
    <label>Название: <input type="text" name="name" required></label><br>
    <label>Вид подразделения: <input type="text" name="type" required></label><br>
    <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
    <button type="submit">Создать</button>
</form>