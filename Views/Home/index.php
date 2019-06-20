<style>
    .mark-info {
        color: green;
        font-weight: bold;
    }

    .mark-warning {
        color: red;
        font-weight: bold;
    }
</style>
<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1 class="display-4">Добро пожаловать в библиотеку книг!</h1>
        <p class="lead">Выберите пункт меню вверху сайта.<br/>
            Если таблицы б/д отсутствуют, перейдите во вкладку Миграции б/д, и загрузите актуальные данные б/д.<br/>
            <label class="mark-warning">(ВАЖНО) перед миграциями обязательно создайте базу данных с названием
                <span class="mark-info">catalog_books</span> (urf8_general_ci)</label><br/>
            Данные б/д по-умолчанию: username: <span class="mark-info">root</span>, password:
            <span class="mark-info">ПУСТО</span><br/>
            Если нужно изменить данные подключения, то перейдите в класс-файл <span class="mark-info">
                /Database/DbConfig.php</span> и укажите актуальные.
        </p>
    </div>
</div>