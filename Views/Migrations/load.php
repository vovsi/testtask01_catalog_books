<h3>Загрузка обновлений миграции</h3>
<?php
if (null != $viewModel->get('info')) {
    echo "<ul style='color: green'>";
    foreach ($viewModel->get('info') as $infoOne) {
        echo "<li>" . $infoOne . "</li>";
    }
    echo "</ul>";
}
?>