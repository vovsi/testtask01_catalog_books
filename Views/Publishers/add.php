<div class="text-center">
    <h3>Добавление издательства</h3>
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
<form method="post" action="<?php echo Url::to("publishers", "add"); ?>">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name">
    </div>
    <div class="form-group">
        <label for="address">Address</label>
        <input type="text" class="form-control" id="address" name="address">
    </div>
    <div class="form-group">
        <label for="phone">Phone</label>
        <input type="tel" class="form-control" id="phone" name="phone">
    </div>
    <button type="submit" class="btn btn-primary">Добавить</button>
</form>