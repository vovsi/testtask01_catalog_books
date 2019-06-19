<?php

use MVC\Helpers\Url;

?>
<div class="text-center" style="margin: 10px;">
    <a href="<?php echo Url::to("authors", "add"); ?>" class="btn btn-success">Добавить</a>
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
<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">FirstName</th>
        <th scope="col">LastName</th>
        <th scope="col">MiddleName</th>
        <th scope="col">Опции</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (null != $viewModel->get('authors')) {
        $authors = $viewModel->get('authors');
        foreach ($authors as $row) {
            echo "<tr>";
            echo "<th scope=\"row\">" . $row['id'] . "</th>";
            echo "<td>" . $row['first_name'] . "</td>";
            echo "<td>" . $row['last_name'] . "</td>";
            echo "<td>" . $row['middle_name'] . "</td>";
            echo "<td>
                          <a href='" . Url::to("authors", "edit", $row['id']) . "' 
                            class='btn btn-primary'>Изменить</a>
                          <a href='" . Url::to("authors", "remove", $row['id']) . "' 
                            class='btn btn-danger'>Удалить</a>
                      </td>";
            echo "</tr>";
        }
    }
    ?>
    </tbody>
</table>