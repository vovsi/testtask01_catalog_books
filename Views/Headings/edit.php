<div class="text-center">
    <h3>Редактирование рубрики</h3>
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
<form method="post" action="<?php echo Url::to("headings", "edit"); ?>">
    <input type="hidden" id="id" name="id" value="<?php
    if (null != $viewModel->get('heading')) {
        echo $viewModel->get('heading')['id'];
    }
    ?>">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php
        if (null != $viewModel->get('heading')) {
            echo $viewModel->get('heading')['name'];
        }
        ?>">
    </div>
    <div class="form-group">
        <label for="address">ParentHeading</label>
        <select id="parent_heading_id" name="parent_heading_id">
            <option value="-" selected>(БЕЗ РОДИТЕЛЬСКОЙ РУБРИКИ)</option>
            <?php
            if (null != $viewModel->get('headings')) {
                foreach ($viewModel->get('headings') as $heading) {
                    if ($viewModel->get('heading')['parent_heading_id'] == $heading['id']) {
                        echo "<option selected value='" . $heading['id'] . "'>" . $heading['name'] . "</option>";
                    } else {
                        echo "<option value='" . $heading['id'] . "'>" . $heading['name'] . "</option>";
                    }
                }
            }
            ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>