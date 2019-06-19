<div class="text-center">
    <h3>Добавление рубрики</h3>
</div>
<?php

use MVC\Helpers\Url;

if (null != $viewModel->get('errors')) {
    echo "<ul style='color: red'>";
    foreach ($viewModel->get('errors') as $error) {
        echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
}
?>
<form method="post" action="<?php echo Url::to("headings", "add"); ?>">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name">
    </div>
    <div class="form-group">
        <label for="address">ParentHeading</label>
        <select id="parent_heading_id" name="parent_heading_id">
            <option value="-" selected>(БЕЗ РОДИТЕЛЬСКОЙ РУБРИКИ)</option>
            <?php
            if (null != $viewModel->get('headings')) {
                foreach ($viewModel->get('headings') as $heading) {
                    echo "<option value='" . $heading['id'] . "'>" . $heading['name'] . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Добавить</button>
</form>