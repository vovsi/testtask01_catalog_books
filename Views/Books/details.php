<div class="text-center" style="margin: 10px;">
    <h3>Детальная информация о книге</h3>
</div>
<?php
if (null != $viewModel->get('errors')) {
    echo "<ul style='color: red'>";
    foreach ($viewModel->get('errors') as $error) {
        echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
}
?>
<div class="card text-center">
    <div class="card-header">
        <?php
        if (null != $viewModel->get('book')) {
            echo $viewModel->get('book')[0]['name'];
        }
        ?>
    </div>
    <div class="card-body">
        <?php
        if (null != $viewModel->get('book')) {
            if (null != $viewModel->get('book')['photos']) {
                for ($i = 0; $i < count($viewModel->get('book')['photos']); $i++) {
                    echo "<img src=\"../../" . $viewModel->get('book')['photos'][$i]['path'] . "\" height=\"300px\" 
                    style=\"background-size: auto\" alt=\"image\">";
                    echo "<hr />";
                }
            } else {
                echo "<img src=\"../../database/dbStorage/bookImages/placeholder_book.png\" height=\"300px\" 
                style=\"background-size: auto\" alt=\"image\">";
            }
        }
        ?>
        <h5 class="card-title">
            Рубрика:
            <?php
            if (null != $viewModel->get('book')) {
                echo $viewModel->get('book')[0]['heading'];
            }
            ?>
        </h5>
        <p class="card-text">
            Авторы:
            <?php
            if (null != $viewModel->get('book')) {
                echo "<ul style='color: gray'>";
                foreach ($viewModel->get('book')[0]['authors'] as $author) {
                    echo "<li>" . $author['first_name'] . " " . $author['last_name'] . "</li>";
                }
                echo "</ul>";

                echo "Издательство: " . $viewModel->get('book')[0]['publisher']['name'];
            }
            ?>
        </p>

    </div>
    <div class="card-footer text-muted">
        Добавлено:
        <?php
        if (null != $viewModel->get('book')) {
            echo $viewModel->get('book')[0]['date_publishing'];
        }
        ?>
    </div>
</div>