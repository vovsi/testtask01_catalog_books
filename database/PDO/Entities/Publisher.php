<?php

namespace database\PDO\Entities {

    use database\PDO\DbPDO;
    use database\PDO\EntityOperations;

    class Publisher extends EntityOperations
    {
        private $db;

        private $id;
        private $name;
        private $address;
        private $phone;

        public function __construct(DbPDO $db)
        {
            parent::__construct($db, Publisher::getTable(), $this->id);
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
        public function getName()
        {
            return $this->name;
        }

        /**
         * @return mixed
         */
        public function getAddress()
        {
            return $this->address;
        }

        /**
         * @return mixed
         */
        public function getPhone()
        {
            return $this->phone;
        }

        /**
         * @param mixed $name
         */
        public function setName($name)
        {
            $this->name = $name;
        }

        /**
         * @param mixed $address
         */
        public function setAddress($address)
        {
            $this->address = $address;
        }

        /**
         * @param mixed $phone
         */
        public function setPhone($phone)
        {
            $this->phone = $phone;
        }

        public static function getTable()
        {
            return "publishers";
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
            $query = "UPDATE " . self::getTable() . " SET `name`='{$this->name}', "
                . "`address`='{$this->address}', `phone`='{$this->phone}' 
            WHERE `id`={$this->id}";
            $this->execute($query);
            $this->id = $this->db->lastInsertId();
            return $this->db->lastInsertId();
        }

        public function _insert()
        {
            $query = "INSERT INTO " . self::getTable() . " (`name`, `address`, `phone`)"
                . " VALUES ('{$this->name}', '{$this->address}', '{$this->phone}')";
            $this->execute($query);
            $this->id = $this->db->lastInsertId();
            return $this->db->lastInsertId();
        }
    }
}