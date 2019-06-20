<?php

namespace Database\PDO\Entities {

    use Database\PDO\DbPDO;
    use Database\PDO\EntityOperations;

    class Author extends EntityOperations
    {
        private $db;

        private $id;
        private $firstName;
        private $lastName;
        private $middleName;

        public function __construct(DbPDO $db)
        {
            parent::__construct($db, Author::getTable(), $this->id);
            $this->db = $db;
        }

        /**
         * @return mixed
         */
        public function getFirstName()
        {
            return $this->firstName;
        }

        /**
         * @param mixed $firstName
         */
        public function setFirstName($firstName)
        {
            $this->firstName = $firstName;
        }

        /**
         * @return mixed
         */
        public function getLastName()
        {
            return $this->lastName;
        }

        /**
         * @param mixed $lastName
         */
        public function setLastName($lastName)
        {
            $this->lastName = $lastName;
        }

        /**
         * @return mixed
         */
        public function getMiddleName()
        {
            return $this->middleName;
        }

        /**
         * @param mixed $middleName
         */
        public function setMiddleName($middleName)
        {
            $this->middleName = $middleName;
        }

        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }

        public static function getTable()
        {
            return "authors";
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
            $query = "UPDATE " . self::getTable() . " SET `first_name`='{$this->firstName}', "
                . "`last_name`='{$this->lastName}', `middle_name`='{$this->middleName}' 
            WHERE `id`={$this->id}";
            $this->execute($query);
            $this->id = $this->db->lastInsertId();
            return $this->db->lastInsertId();
        }

        public function _insert()
        {
            $query = "INSERT INTO " . self::getTable() . " (`first_name`, `last_name`, `middle_name`)"
                . " VALUES ('{$this->firstName}', '{$this->lastName}', '{$this->middleName}')";
            $this->execute($query);
            $this->id = $this->db->lastInsertId();
            return $this->db->lastInsertId();
        }
    }
}