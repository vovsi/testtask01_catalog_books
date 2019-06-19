<?php

namespace database\PDO\Entities {

    use database\PDO\DbPDO;
    use database\PDO\EntityOperations;

    class BookAuthor extends EntityOperations
    {
        private $db;

        private $id;
        private $bookId;
        private $authorId;

        public function __construct(DbPDO $db)
        {
            parent::__construct($db, BookAuthor::getTable(), $this->id);
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
        public function getBookId()
        {
            return $this->bookId;
        }

        /**
         * @return mixed
         */
        public function getAuthorId()
        {
            return $this->authorId;
        }

        /**
         * @param mixed $bookId
         */
        public function setBookId($bookId)
        {
            $this->bookId = $bookId;
        }

        /**
         * @param mixed $authorId
         */
        public function setAuthorId($authorId)
        {
            $this->authorId = $authorId;
        }

        public static function getTable()
        {
            return "books_authors";
        }

        public function deleteAuthorsOfBook($idBook)
        {
            $query = "DELETE FROM " . self::getTable() . " WHERE `book_id`=" . $idBook;
            return $this->execute($query) > 0;
        }

        public function newEmptyInstance()
        {
            return new self($this->db);
        }

        public function getBooksByAuthorId($authorId, $count, $optFrom = null)
        {
            $lFrom = is_null($optFrom) ? '' : (int)$optFrom . ', ';
            $query = "SELECT * FROM " . self::getTable() . " WHERE `author_id` = " . $authorId . " LIMIT {$lFrom}{$count}";
            return $this->execute($query);
        }

        public function getAuthorsOfBook($bookId, $count, $optFrom = null)
        {
            $lFrom = is_null($optFrom) ? '' : (int)$optFrom . ', ';
            $query = "SELECT * FROM " . self::getTable() . " WHERE `book_id` = " . $bookId . " LIMIT {$lFrom}{$count}";
            return $this->execute($query);
        }

        /**
         * @param mixed $id
         */
        public function setId($id)
        {
            parent::setId($id);
            $this->id = $id;
        }

        public function _update()
        {
            $query = "UPDATE " . self::getTable() . " SET `book_id`='{$this->bookId}', "
                . "`author_id`='{$this->authorId}' WHERE `id`={$this->id}";
            $this->execute($query);
            $this->id = $this->db->lastInsertId();
            return $this->db->lastInsertId();
        }

        public function _insert()
        {
            $query = "INSERT INTO " . self::getTable() . " (`book_id`, `author_id`)"
                . " VALUES ('{$this->bookId}', '{$this->authorId}')";
            $this->execute($query);
            $this->id = $this->db->lastInsertId();
            return $this->db->lastInsertId();
        }

        public function getAuthorsOfBookJoin($idBook)
        {
            $query = "select book_author.*, author.* " .
                "from " . $this->getTable() . " as book_author " .
                "inner join " . Author::getTable() . " as author on author.id = book_author.author_id WHERE book_id = "
                . $idBook;
            return $this->execute($query);
        }
    }
}