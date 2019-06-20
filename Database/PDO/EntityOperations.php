<?php

namespace Database\PDO {

    abstract class EntityOperations implements EntityInterface
    {
        private $db;
        private $tableName;
        private $id;

        public function __construct(DbPDO $db, $tableName, $id)
        {
            $this->db = $db;
            $this->tableName = $tableName;
            $this->id = $id;
        }

        protected function setId($id)
        {
            $this->id = $id;
        }

        private function getTableDb()
        {
            return $this->tableName;
        }

        // Удаление записи
        public function delete($id)
        {
            $lId = (int)$id;
            if ($lId < 0 || $lId > PHP_INT_MAX) {
                return false;
            }
            $query = "DELETE FROM " . $this->getTableDb() . " WHERE `id`=" . $lId;
            return $this->execute($query) > 0;
        }

        // Получить одну запись по идентификатору
        public function newInstance($id)
        {
            $lId = (int)$id;
            if ($lId < 0 || $lId > PHP_INT_MAX) {
                return false;
            }
            $query = "SELECT * FROM " . $this->getTableDb() . " WHERE `id`=" . $lId . " LIMIT 1";
            return $this->execute($query);
        }

        // Получить пустой экземпляр модели таблицы
        abstract public function newEmptyInstance();

        // Найти записи по параметрам
        public function find($count, $optFrom = null)
        {
            $lFrom = is_null($optFrom) ? '' : (int)$optFrom . ', ';
            $query = "SELECT * FROM " . $this->getTableDb() . " LIMIT {$lFrom}{$count}";
            return $this->execute($query);
        }

        // Сохранить модель в б/д
        public function save()
        {
            if (isset($this->id)) {
                return $this->_update();
            } else {
                return $this->_insert();
            }
        }

        // Обновить модель в б/д
        abstract public function _update();

        // Вставить модель в б/д
        abstract public function _insert();

        // Выполнить запрос
        public function execute($query)
        {
            $statement = $this->db->prepare($query);
            $statement->execute();
            return $statement->fetchAll();
        }
    }
}