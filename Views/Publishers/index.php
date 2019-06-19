<?php

use MVC\Helpers\Url;

?>
<div class="text-center" style="margin: 10px;">
    <a href="<?php echo Url::to("publishers", "add"); ?>" class="btn btn-success">Добавить</a>
</div>
<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Name</th>
        <th scope="col">Address</th>
        <th scope="col">Phone</th>
        <th scope="col">Опции</th>
    </tr>
    </thead>
    <tbody>
    <?php

    if (null != $viewModel->get('publishers')) {
        $publishers = $viewModel->get('publishers');
        foreach ($publishers as $row) {
            echo "<tr>";
            echo "<th scope=\"row\">" . $row['id'] . "</th>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['address'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>
                          <a href='" . Url::to("publishers", "edit", $row['id']) . "' 
                            class='btn btn-primary'>Изменить</a>
                          <a href='" . Url::to("publishers", "remove", $row['id']) . "' 
                            class='btn btn-danger'>Удалить</a>
                      </td>";
            echo "</tr>";
        }
    }
    ?>
    </tbody>
</table>