<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pop it MVC</title>
</head>
<body>
<header>
    <nav>
        <a href="<?= app()->route->getUrl('/hello') ?>">Главная</a>
        <?php if (app()->auth::check()): ?>
            <a href="<?= app()->route->getUrl('/go') ?>">Посты</a>
            <a href="<?= app()->route->getUrl('/create') ?>">Добавление сотрудника</a>
            <a href="<?= app()->route->getUrl('/user_create') ?>">Пост</a>
            <a href="<?= app()->route->getUrl('/employees') ?>">Сотрудники</a>
            <a href="<?= app()->route->getUrl('/compositions') ?>">Составы</a>
            <a href="<?= app()->route->getUrl('/departments') ?>">Подразделения</a>
            <a href="<?= app()->route->getUrl('/logout') ?>">Выход (<?= app()->auth::user()->name ?>)</a>
        <?php else: ?>
            <a href="<?= app()->route->getUrl('/login') ?>">Вход</a>
            <a href="<?= app()->route->getUrl('/signup') ?>">Регистрация</a>
        <?php endif; ?>
    </nav>
</header>
<main>
    <?= $content ?? '' ?>
</main>
<footer>
    <nav>
        <a href="<?= app()->route->getUrl('/hello') ?>">Главная</a>
    </nav>
</footer>

</body>
</html>