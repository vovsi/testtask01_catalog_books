<?php

namespace Database\PDO\Entities {

    use Database\PDO\DbPDO;
    use Database\PDO\EntityOperations;

    class Photo extends EntityOperations
    {
        private $db;

        private $id;
        private $path;

        public function __construct(DbPDO $db)
        {
            parent::__construct($db, Photo::getTable(), $this->id);
            $this->db = $db;
        }

        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @return mixed
         */
        public function getPath()
        {
            return $this->path;
        }

        /**
         * @param mixed $path
         */
        public function setPath($path)
        {
            $this->path = $path;
        }

        public static function getTable()
        {
            return "photos";
        }

        /**
         * @param mixed $id
         */
        public function setId($id)
        {
            parent::setId($id);
            $this->id = $id;
        }

        public function newEmptyInstance()
        {
            return new self($this->db);
        }

        public function _update()
        {
            $query = "UPDATE " . self::getTable() . " SET `path`='{$this->path}' WHERE `id`={$this->id}";
            $this->execute($query);
            $this->id = $this->db->lastInsertId();
            return $this->db->lastInsertId();
        }

        public function _insert()
        {
            $query = "INSERT INTO " . self::getTable() . " (`path`)"
                . " VALUES ('{$this->path}')";
            $this->execute($query);
            $this->id = $this->db->lastInsertId();
            return $this->db->lastInsertId();
        }
    }
}