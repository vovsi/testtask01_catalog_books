<?php

namespace database\migrations {

    use database\DbConfig;
    use database\PDO\DbPDO;
    use database\PDO\Entities\Version;
    use PDOException;

    class MigrationUtils
    {
        // Накатываем миграцию файла
        public static function migrate(DbPDO $db, $file)
        {
            // Считываем файл
            $commands = file_get_contents($file);
            // Выполняем скрипт с файла
            try {
                if ($db->exec($commands) > -1) {
                    // Вытаскиваем имя файла, отбросив путь
                    $baseName = basename($file);
                    // Формируем запрос для добавления миграции в таблицу versions
                    $db = new DbPDO();
                    $version = new Version($db);
                    $version = $version->newEmptyInstance();
                    $version->setName($baseName);
                    return $version->save();
                }
            } catch (PDOException $ex) {
                // Errors
                return $ex->getMessage();
            }

            return -1;
        }

        // Получаем список файлов для миграций
        public static function getMigrationFiles(DbPDO $db)
        {
            // Получаем список всех sql-файлов
            $allFiles = glob(DbConfig::PATH_TO_DB_SQL_MIGRATIONS . '*.sql');

            // Проверяем, есть ли таблица versions
            // Так как versions создается первой, то это равносильно тому, что база не пустая
            try {
                $query = 'SELECT 1 FROM ' . Version::getTable();
                $statement = $db->prepare($query);
                $statement->execute();
                $statement->fetchAll();
                $firstMigration = false;
            } catch (\Exception $ex) {
                $firstMigration = true;
            }

            // Первая миграция, возвращаем все файлы из папки sql
            if ($firstMigration) {
                return $allFiles;
            }

            // Ищем уже существующие миграции
            $versionsFiles = array();
            // Выбираем из таблицы versions все названия файлов
            $version = new Version($db);
            $allRows = $version->find(1000);
            // Загоняем названия в массив $versionsFiles
            // Не забываем добавлять полный путь к файлу
            foreach ($allRows as $row) {
                array_push($versionsFiles, DbConfig::PATH_TO_DB_SQL_MIGRATIONS . $row['name']);
            }

            // Возвращаем файлы, которых еще нет в таблице versions
            return array_diff($allFiles, $versionsFiles);
        }
    }
}
