<h1>Список сотрудников</h1>
<ol>
    <?php
    foreach ($employees as $employee) {
        echo '<li>' . $employee->last_name . '</li>';
        echo '<li>' . $employee->first_name . '</li>';
    }
    ?>
</ol>
