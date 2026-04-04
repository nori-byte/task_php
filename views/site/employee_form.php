<label>Подразделение
    <select name="id_department" required>
        <option value="">-- Выберите --</option>
        <?php foreach ($departments as $dept): ?>
            <option value="<?= $dept->id_department ?>">
                <?= htmlspecialchars($dept->name_department) ?>
            </option>
        <?php endforeach; ?>
    </select>
</label>