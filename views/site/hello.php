<div class="container">
    <h1>Добро пожаловать</h1>
    <p><?= $message ?></p>

    <form method="GET" action="">
        <input type="text" name="search" placeholder="Поиск..." value="<?= htmlspecialchars($search ?? '') ?>">
        <button type="submit">Искать</button>
        <?php if(!empty($search)): ?>
            <a href="<?= app()->route->getUrl('/hello') ?>">Сбросить</a>
        <?php endif; ?>
    </form>

    <?php if(!empty($search)): ?>
        <h3>Результаты поиска: «<?= htmlspecialchars($search) ?>»</h3>

        <?php if($departments->count()): ?>
            <div>
                <strong>Отделы:</strong>
                <ul>
                    <?php foreach($departments as $dept): ?>
                        <li><?= $dept->name_department ?> (<?= $dept->view_department ?>)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if($employees->count()): ?>
            <div>
                <strong>Сотрудники:</strong>
                <ul>
                    <?php foreach($employees as $emp): ?>
                        <li><?= $emp->last_name ?> <?= $emp->first_name ?> <?= $emp->patronymic ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if($compositions->count()): ?>
            <div>
                <strong>Составы:</strong>
                <ul>
                    <?php foreach($compositions as $comp): ?>
                        <li><?= $comp->name_composition ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if($positions->count()): ?>
            <div>
                <strong>Должности:</strong>
                <ul>
                    <?php foreach($positions as $pos): ?>
                        <li><?= $pos->position_name ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if($departments->isEmpty() && $employees->isEmpty() && $compositions->isEmpty() && $positions->isEmpty()): ?>
            <p>Ничего не найдено.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>