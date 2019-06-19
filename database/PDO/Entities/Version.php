<?php

namespace database\PDO\Entities {

    use database\PDO\DbPDO;
    use database\PDO\EntityOperations;

    class Version extends EntityOperations
    {
        private $db;

        private $id;
        private $name;
        private $created;

        public function __construct(DbPDO $db)
        {
            parent::__construct($db, Version::getTable(), $this->id);
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
         * @param mixed $id
         */
        public function setId($id)
        {
            parent::setId($id);
            $this->id = $id;
        }

        /**
         * @return mixed
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * @param mixed $name
         */
        public function setName($name)
        {
            $this->name = $name;
        }

        /**
         * @return mixed
         */
        public function getCreated()
        {
            return $this->created;
        }

        /**
         * @param mixed $created
         */
        public function setCreated($created)
        {
            $this->created = $created;
        }

        public static function getTable()
        {
            return "versions";
        }

        public function newEmptyInstance()
        {
            return new self($this->db);
        }

        public function _update()
        {
            $query = "UPDATE " . self::getTable() . " SET `name`='{$this->name}', "
                . " WHERE `id`={$this->id}";
            $this->execute($query);
            $this->id = $this->db->lastInsertId();
            return $this->db->lastInsertId();
        }

        public function _insert()
        {
            $query = "INSERT INTO " . self::getTable() . " (`name`)"
                . " VALUES ('{$this->name}')";
            $this->execute($query);
            $this->id = $this->db->lastInsertId();
            return $this->db->lastInsertId();
        }
    }
}