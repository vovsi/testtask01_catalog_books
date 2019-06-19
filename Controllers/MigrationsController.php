<?php

namespace Controllers {

    use database\migrations\MigrationUtils;
    use database\PDO\DbPDO;
    use Models\MigrationModel;
    use MVC\Controllers\BaseController;
    use MVC\Services\UtilsService;

    class MigrationsController extends BaseController
    {
        private $db;

        public function __construct($init)
        {
            parent::__construct($init);
            // создаем модель
            $this->model = new MigrationModel();
            try {
                $this->db = new DbPDO();
            } catch (\Exception $ex) {
                UtilsService::redirect();
            }
        }

        // действие по умолчанию
        protected function index()
        {
            $this->view->output($this->model->index());
        }

        protected function load()
        {
            $info = array();
            // Получаем список файлов для миграций за исключением тех, которые уже есть в таблице versions
            $files = MigrationUtils::getMigrationFiles($this->db);
            // Проверяем, есть ли новые миграции
            if (null != $files) {
                $info[] = 'Начинаем миграцию...';

                // Накатываем миграцию для каждого файла
                foreach ($files as $file) {
                    MigrationUtils::migrate($this->db, $file);

                    // Выводим название выполненного файла
                    $info[] = basename($file);
                }

                $info[] = 'Миграция завершена.';
            } else {
                $info[] = 'Ваша база данных в актуальном состоянии.';
            }
            $this->view->output($this->model->load($info));
        }
    }
}


