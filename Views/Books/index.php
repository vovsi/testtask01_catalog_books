<?php

use MVC\Helpers\Url;

?>
<div class="text-center" style="margin: 10px;">
    <a href="<?php echo Url::to("books", "add"); ?>" class="btn btn-success">Добавить</a>
</div>
<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Name</th>
        <th scope="col">DatePublishing</th>
        <th scope="col">HeadingId</th>
        <th scope="col">PublisherId</th>
        <th scope="col">Опции</th>
    </tr>
    </thead>
    <tbody>
    <?php

    if (null != $viewModel->get('books')) {
        $books = $viewModel->get('books');
        foreach ($books as $row) {
            echo "<tr>";
            echo "<th scope=\"row\">" . $row['id'] . "</th>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['date_publishing'] . "</td>";
            echo "<td>" . $row['heading_id'] . "</td>";
            echo "<td>" . $row['publisher_id'] . "</td>";
            echo "<td>
                          <a href='" . Url::to("books", "details", $row['id']) . "' 
                          class='btn btn-warning'>Детальней</a>
                          <a href='" . Url::to("books", "edit", $row['id']) . "' 
                          class='btn btn-primary'>Изменить</a>
                          <a href='" . Url::to("books", "remove", $row['id']) . "' 
                          class='btn btn-danger'>Удалить</a>
                      </td>";
            echo "</tr>";
        }
    }
    ?>
    </tbody>
</table>