<?php

namespace Database\PDO\Entities {

    use Database\PDO\DbPDO;
    use Database\PDO\EntityOperations;

    class Heading extends EntityOperations
    {
        private $db;

        private $id;
        private $name;
        private $parentHeadingId;

        public function __construct(DbPDO $db)
        {
            parent::__construct($db, Heading::getTable(), $this->id);
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
        public function getParentHeadingId()
        {
            return $this->parentHeadingId;
        }

        /**
         * @param mixed $name
         */
        public function setName($name)
        {
            $this->name = $name;
        }

        /**
         * @param mixed $parentHeadingId
         */
        public function setParentHeadingId($parentHeadingId)
        {
            $this->parentHeadingId = $parentHeadingId;
        }

        public static function getTable()
        {
            return "headings";
        }

        public function newEmptyInstance()
        {
            return new self($this->db);
        }

        /**
         * @param mixed $id
         */
        public function setId($id)
        {
            parent::setId($id);
            $this->id = $id;
        }

        public function getHeadingsWithoutParent($count, $optFrom = null)
        {
            $lFrom = is_null($optFrom) ? '' : (int)$optFrom . ', ';
            $query = "SELECT * FROM " . self::getTable() . " WHERE parent_heading_id IS NULL LIMIT {$lFrom}{$count}";
            return $this->execute($query);
        }

        public function getHeadingsByParentId($id, $count, $optFrom = null)
        {
            $lFrom = is_null($optFrom) ? '' : (int)$optFrom . ', ';
            $query = "SELECT * FROM " . self::getTable() . " WHERE parent_heading_id = '" . $id . "' LIMIT {$lFrom}{$count}";
            return $this->execute($query);
        }

        public function _update()
        {
            if (empty($this->parentHeadingId)) {
                $query = "UPDATE " . self::getTable() . " SET `name`='{$this->name}', "
                    . "`parent_heading_id`=null WHERE `id`={$this->id}";
            } else {
                $query = "UPDATE " . self::getTable() . " SET `name`='{$this->name}', "
                    . "`parent_heading_id`={$this->parentHeadingId} WHERE `id`={$this->id}";
            }
            $this->execute($query);
            $this->id = $this->db->lastInsertId();
            return $this->db->lastInsertId();
        }

        public function _insert()
        {
            if (empty($this->parentHeadingId)) {
                $query = "INSERT INTO " . self::getTable() . " (`name`, `parent_heading_id`)"
                    . " VALUES ('{$this->name}', null)";
            } else {
                $query = "INSERT INTO " . self::getTable() . " (`name`, `parent_heading_id`)"
                    . " VALUES ('{$this->name}', '{$this->parentHeadingId}')";
            }
            $this->execute($query);
            $this->id = $this->db->lastInsertId();
            return $this->db->lastInsertId();
        }
    }
}