<h1>Список состава</h1>

<?php
$selectedCompositions = $selectedCompositions ?? [];
$employees = $employees ?? [];
?>

<h2>Выбор сотрудников по составу</h2>

<form method="get">
    <fieldset>
        <legend>Выберите состав(ы):</legend>
        <?php foreach ($compositions as $comp): ?>
            <label>
                <input type="checkbox" name="composition_ids[]" value="<?= $comp->id_composition ?>"
                        <?= in_array($comp->id_composition, $selectedCompositions) ? 'checked' : '' ?>>
                <?= htmlspecialchars($comp->composition_name) ?>
            </label><br>
        <?php endforeach; ?>
    </fieldset>
    <button type="submit">Показать сотрудников</button>
    <a href="/task_php/compositions">Сбросить</a>
</form>

<?php if (!empty($selectedCompositions)): ?>
    <?php if (count($employees) > 0): ?>
        <h3>Сотрудники выбранных составов:</h3>
        <table border="1">
            <thead>
            <tr><th>ФИО</th><th>Состав</th>
            </thead>
            <tbody>
            <?php foreach ($employees as $emp): ?>
                <tr>
                    <td><?= htmlspecialchars("{$emp->last_name} {$emp->first_name} {$emp->middle_name}") ?></td>
                    <td><?= htmlspecialchars($emp->composition->composition_name ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>В выбранных составах нет сотрудников.</p>
    <?php endif; ?>
<?php endif; ?>

