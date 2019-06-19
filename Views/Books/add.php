<div class="text-center">
    <h3>Добавление книги</h3>
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
<form method="post" action="<?php echo Url::to("books", "add"); ?>" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name">
    </div>
    <div class="form-group">
        <label for="date_publishing">DatePublishing</label>
        <input type="date" class="form-control" id="date_publishing" name="date_publishing" max=
            <?php
            echo date('Y-m-d');
            ?>>
    </div>
    <div class="form-group">
        <label for="heading_id">Heading</label>
        <select id="heading_id" name="heading_id">
            <?php
            if (null != $viewModel->get('headings')) {
                foreach ($viewModel->get('headings') as $heading) {
                    echo "<option value='" . $heading['id'] . "'>" . $heading['name'] . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="publisher_id">Publisher</label>
        <select id="publisher_id" name="publisher_id">
            <?php
            if (null != $viewModel->get('publishers')) {
                foreach ($viewModel->get('publishers') as $publisher) {
                    echo "<option value='" . $publisher['id'] . "'>" . $publisher['name'] . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="authors_id">Authors</label>
        <select id="authors_id" name="authors_id[]" multiple>
            <?php
            if (null != $viewModel->get('authors')) {
                foreach ($viewModel->get('authors') as $author) {
                    echo "<option value='" . $author['id'] . "'>" . $author['first_name'] . " " . $author['last_name'] . " " .
                        $author['middle_name'] . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="images">Photos files (optional)</label>
        <div class="custom-file">
            <input type="file" multiple="multiple" class="custom-file-input" id="images" name="images[]"
                   accept="image/x-png,image/jpeg">
            <label class="custom-file-label" for="images">Choose file...</label>
        </div>

    </div>
    <div class="form-group">
        <label for="images_urls">Photos urls (optional) (jpg, png, gif) separator - new line</label>
        <textarea id="images_urls" name="images_urls" style="width: 100%"></textarea>
    </div>
    <button id="add_book" type="submit" class="btn btn-primary" style="margin-left: 47%;margin-top: 10px">Добавить
    </button>
</form>