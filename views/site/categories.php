<h1>Список статей</h1>
<ol>
    <?php
    foreach ($categories as $category) {
        echo '<li>' . $category->title . '</li>';
    }
    ?>
</ol>
