<?php

use MVC\Helpers\Url;

?>
<div class="text-center" style="margin: 10px;">
    <a href="<?php echo Url::to("headings", "add"); ?>" class="btn btn-success">Добавить</a>
</div>
<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Name</th>
        <th scope="col">ParentHeadingId</th>
        <th scope="col">Опции</th>
    </tr>
    </thead>
    <tbody>
    <?php

    if (null != $viewModel->get('headings')) {
        $headings = $viewModel->get('headings');
        foreach ($headings as $row) {
            echo "<tr>";
            echo "<th scope=\"row\">" . $row['id'] . "</th>";
            echo "<td><a href='" . Url::to("headings", "index", $row['id']) . "'>" . $row['name'] . "</a></td>";
            echo "<td>" . $row['parent_heading_id'] . "</td>";
            echo "<td>
                          <a href='" . Url::to("headings", "index", $row['id']) . "' 
                            class='btn btn-warning'>Подразделы</a>
                          <a href='" . Url::to("headings", "edit", $row['id']) . "' 
                            class='btn btn-primary'>Изменить</a>
                          <a href='" . Url::to("headings", "remove", $row['id']) . "' 
                            class='btn btn-danger'>Удалить</a>
                      </td>";
            echo "</tr>";
        }
    }
    ?>
    </tbody>
</table>