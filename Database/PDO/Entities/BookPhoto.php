<?php

namespace Database\PDO\Entities {

    use Database\PDO\DbPDO;
    use Database\PDO\EntityOperations;

    class BookPhoto extends EntityOperations
    {
        private $db;

        private $id;
        private $bookId;
        private $photoId;

        public function __construct(DbPDO $db)
        {
            parent::__construct($db, BookPhoto::getTable(), $this->id);
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
        public function getPhotoId()
        {
            return $this->photoId;
        }

        /**
         * @param mixed $bookId
         */
        public function setBookId($bookId)
        {
            $this->bookId = $bookId;
        }

        /**
         * @param mixed $photoId
         */
        public function setPhotoId($photoId)
        {
            $this->photoId = $photoId;
        }

        public static function getTable()
        {
            return "books_photos";
        }

        public function deletePhotoOfBook($idBook, $idPhoto)
        {
            $query = "DELETE FROM " . self::getTable() . " WHERE `book_id`=" . $idBook . " AND `photo_id`=" . $idPhoto;
            return $this->execute($query) > 0;
        }

        public function newEmptyInstance()
        {
            return new self($this->db);
        }

        public function getPhotosOfBookJoin($idBook, $count, $optFrom = null)
        {
            $lFrom = is_null($optFrom) ? '' : (int)$optFrom . ', ';
            $query = "select book_photo.*, photo.* " .
                "from " . $this->getTable() . " as book_photo " .
                "inner join " . Photo::getTable() . " as photo on photo.id = book_photo.photo_id WHERE book_id = " . $idBook .
                " LIMIT {$lFrom}{$count}";
            return $this->execute($query);
        }

        public function getPhotosOfBook($idBook, $count, $optFrom = null)
        {
            $lFrom = is_null($optFrom) ? '' : (int)$optFrom . ', ';
            $query = "SELECT * FROM " . self::getTable() . " WHERE `book_id` = " . $idBook . " LIMIT {$lFrom}{$count}";
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
                . "`photo_id`='{$this->photoId}' WHERE `id`={$this->id}";
            $this->execute($query);
            $this->id = $this->db->lastInsertId();
            return $this->db->lastInsertId();
        }

        public function _insert()
        {
            $query = "INSERT INTO " . self::getTable() . " (`book_id`, `photo_id`)"
                . " VALUES ('{$this->bookId}', '{$this->photoId}')";
            $this->execute($query);
            $this->id = $this->db->lastInsertId();
            return $this->db->lastInsertId();
        }
    }
}