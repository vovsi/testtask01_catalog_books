<?php

namespace Database\PDO\Entities {

    use Database\PDO\DbPDO;
    use Database\PDO\EntityOperations;

    class Book extends EntityOperations
    {
        private $db;

        private $id;
        private $name;
        private $datePublishing;
        private $headingId;
        private $publisherId;

        public function __construct(DbPDO $db)
        {
            parent::__construct($db, Book::getTable(), $this->id);
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
        public function getDatePublishing()
        {
            return $this->datePublishing;
        }

        /**
         * @return mixed
         */
        public function getHeadingId()
        {
            return $this->headingId;
        }

        /**
         * @param mixed $name
         */
        public function setName($name)
        {
            $this->name = $name;
        }

        /**
         * @param mixed $datePublishing
         */
        public function setDatePublishing($datePublishing)
        {
            $this->datePublishing = $datePublishing;
        }

        /**
         * @param mixed $headingId
         */
        public function setHeadingId($headingId)
        {
            $this->headingId = $headingId;
        }

        public static function getTable()
        {
            return "books";
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

        public function getBooksByPublisherId($publisherId, $count, $optFrom = null)
        {
            $lFrom = is_null($optFrom) ? '' : (int)$optFrom . ', ';
            $query = "SELECT * FROM " . self::getTable() . " WHERE `publisher_id` = " . $publisherId . " LIMIT {$lFrom}{$count}";
            return $this->execute($query);
        }

        public function _update()
        {
            $query = "UPDATE " . self::getTable() . " SET `name`='{$this->name}', "
                . "`date_publishing`='{$this->datePublishing}', `heading_id`='{$this->headingId}', 
                `publisher_id`='{$this->publisherId}' WHERE `id`={$this->id}";
            $this->execute($query);
            $this->id = $this->db->lastInsertId();
            return $this->db->lastInsertId();
        }

        /**
         * @return mixed
         */
        public function getPublisherId()
        {
            return $this->publisherId;
        }

        /**
         * @param mixed $publisherId
         */
        public function setPublisherId($publisherId)
        {
            $this->publisherId = $publisherId;
        }

        public function _insert()
        {
            $query = "INSERT INTO " . self::getTable() . " (`name`, `date_publishing`, `heading_id`, `publisher_id`)"
                . " VALUES ('{$this->name}', '{$this->datePublishing}', '{$this->headingId}', '{$this->publisherId}')";
            $this->execute($query);
            $this->id = $this->db->lastInsertId();
            return $this->db->lastInsertId();
        }
    }
}