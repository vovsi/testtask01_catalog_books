<div class="text-center">
    <h3>Редактирование издательства</h3>
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
<form method="post" action="<?php echo Url::to("publishers", "edit"); ?>">
    <input type="hidden" id="id" name="id" value="<?php
    if (null != $viewModel->get('publisher')) {
        echo $viewModel->get('publisher')['id'];
    }
    ?>">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php
        if (null != $viewModel->get('publisher')) {
            echo $viewModel->get('publisher')['name'];
        }
        ?>">
    </div>
    <div class="form-group">
        <label for="address">Address</label>
        <input type="text" class="form-control" id="address" name="address" value="<?php
        if (null != $viewModel->get('publisher')) {
            echo $viewModel->get('publisher')['address'];
        }
        ?>">
    </div>
    <div class="form-group">
        <label for="phone">Phone</label>
        <input type="tel" class="form-control" id="phone" name="phone" value="<?php
        if (null != $viewModel->get('publisher')) {
            echo $viewModel->get('publisher')['phone'];
        }
        ?>">
    </div>
    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>