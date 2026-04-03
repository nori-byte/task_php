<h1>Список статей</h1>
<ol>
    <?php
    foreach ($employees as $employee) {
        echo '<li>' . $employee->title . '</li>';
    }
    ?>
</ol>