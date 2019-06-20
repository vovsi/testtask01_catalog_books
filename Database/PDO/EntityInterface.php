<?php

namespace Database\PDO {

    interface EntityInterface
    {
        // Удаление записи
        public function delete($id);

        // Получить одну запись по идентификатору
        public function newInstance($id);

        // Получить пустой экземпляр модели таблицы
        public function newEmptyInstance();

        // Найти записи по параметрам
        public function find($count, $optFrom = null);

        // Сохранить модель в б/д
        public function save();

        // Обновить модель в б/д
        public function _update();

        // Вставить модель в б/д
        public function _insert();
    }
}