<div class="text-center">
    <h3>Редактирование книги</h3>
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
<form method="post" action="<?php echo Url::to("books", "edit"); ?>" enctype="multipart/form-data">
    <input type="hidden" id="id" name="id" value="<?php
    if (null != $viewModel->get('book')) {
        echo $viewModel->get('book')['id'];
    }
    ?>">
    <input id="remove_photos" name="remove_photos" value="" type="hidden">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php
        if (null != $viewModel->get('book')) {
            echo $viewModel->get('book')['name'];
        }
        ?>">
    </div>
    <div class="form-group">
        <label for="date_publishing">DatePublishing</label>
        <input type="date" class="form-control" id="date_publishing" name="date_publishing" value="<?php
        if (null != $viewModel->get('book')) {
            echo $viewModel->get('book')['date_publishing'];
        }
        ?>" max=
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
                    if ($viewModel->get('book')['heading_id'] == $heading['id']) {
                        echo "<option selected value='" . $heading['id'] . "'>" . $heading['name'] . "</option>";
                    } else {
                        echo "<option value='" . $heading['id'] . "'>" . $heading['name'] . "</option>";
                    }
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
                    if ($viewModel->get('book')['publisher_id'] == $publisher['id']) {
                        echo "<option selected value='" . $publisher['id'] . "'>" . $publisher['name'] . "</option>";
                    } else {
                        echo "<option value='" . $publisher['id'] . "'>" . $publisher['name'] . "</option>";
                    }
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
                    echo json_encode($author);
                    if (in_array($author['id'], $viewModel->get('book')['authors_id'])) {
                        echo "<option selected value='" . $author['id'] . "'>" . $author['first_name'] . " "
                            . $author['last_name'] . " " .
                            $author['middle_name'] . "</option>";
                    } else {
                        echo "<option value='" . $author['id'] . "'>" . $author['first_name'] . " " . $author['last_name'] . " " .
                            $author['middle_name'] . "</option>";
                    }
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
        <label for="images_urls">Photos urls (optional) separator - new line</label>
        <textarea id="images_urls" name="images_urls" style="width: 100%"></textarea>
    </div>
    <div class="form-group">
        <label for="images">Current photos:</label>
        <br/>
        <?php
        if (null != $viewModel->get('book')) {
            for ($i = 0; $i < count($viewModel->get('book')['photos']); $i++) {
                echo "<div id='photo" . $viewModel->get('book')['photos'][$i]['id'] . "'>
                            <img src=\"../../" . $viewModel->get('book')['photos'][$i]['path'] . "\" height=\"300px\" 
                            style=\"background-size: auto\" alt=\"image\">
                            <button class='btn btn-link' 
                            onclick='removePhoto(" . $viewModel->get('book')['photos'][$i]['id'] . ")'>Удалить изображение
                            </button>
                            <hr />
                      </div>";
            }
        }
        ?>
    </div>
    <button id="add_book" type="submit" class="btn btn-primary" style="margin-left: 47%;margin-top: 10px">Сохранить
    </button>
</form>
<script>
    // Удалить div элемент фото книги (текущей)
    function removePhoto(id_photo) {
        $('#photo' + id_photo).remove();
        let value = $('#remove_photos').val();
        if (value === "") {
            $('#remove_photos').val(id_photo);
        } else {
            $('#remove_photos').val(value + "|" + id_photo);
        }
    }
</script>