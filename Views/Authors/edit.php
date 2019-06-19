<div class="text-center">
    <h3>Редактирование автора</h3>
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
<form method="post" action="<?php echo Url::to("authors", "edit"); ?>">
    <input type="hidden" id="id" name="id" value="<?php
    if (null != $viewModel->get('author')) {
        echo $viewModel->get('author')['id'];
    }
    ?>">
    <div class="form-group">
        <label for="first_name">FirstName</label>
        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php
        if (null != $viewModel->get('author')) {
            echo $viewModel->get('author')['first_name'];
        }
        ?>">
    </div>
    <div class="form-group">
        <label for="last_name">LastName</label>
        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php
        if (null != $viewModel->get('author')) {
            echo $viewModel->get('author')['last_name'];
        }
        ?>">
    </div>
    <div class="form-group">
        <label for="middle_name">MiddleName</label>
        <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php
        if (null != $viewModel->get('author')) {
            echo $viewModel->get('author')['middle_name'];
        }
        ?>">
    </div>
    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>