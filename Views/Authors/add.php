<div class="text-center">
    <h3>Добавление автора</h3>
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
<form method="post" action="<?php echo Url::to("authors", "add"); ?>">
    <div class="form-group">
        <label for="first_name">FirstName</label>
        <input type="text" class="form-control" id="first_name" name="first_name">
    </div>
    <div class="form-group">
        <label for="last_name">LastName</label>
        <input type="text" class="form-control" id="last_name" name="last_name">
    </div>
    <div class="form-group">
        <label for="middle_name">MiddleName</label>
        <input type="text" class="form-control" id="middle_name" name="middle_name">
    </div>
    <button type="submit" class="btn btn-primary">Добавить</button>
</form>