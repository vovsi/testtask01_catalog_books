<?php

use MVC\Helpers\Url;

?>
<h3>Панель управления миграциями б/д</h3>
<a href="<?php echo Url::to("migrations", "load") ?>" class="btn btn-success">Загрузить обновления</a>
(может потребовать 30 секунд)