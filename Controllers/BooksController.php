<?php

namespace Controllers {

    use database\DbConfig;
    use database\PDO\Entities\Author;
    use database\PDO\Entities\BookAuthor;
    use database\PDO\Entities\BookPhoto;
    use database\PDO\Entities\Heading;
    use database\PDO\DbPDO;
    use database\PDO\Entities\Book;
    use database\PDO\Entities\Photo;
    use database\PDO\Entities\Publisher;
    use Models\BookModel;
    use MVC\Controllers\BaseController;
    use MVC\Services\UtilsService;

    class BooksController extends BaseController
    {

        private $db;

        public function __construct($init)
        {
            parent::__construct($init);
            // создаем модель
            $this->model = new BookModel();
            try {
                $this->db = new DbPDO();
            } catch (\Exception $ex) {
                UtilsService::redirect();
            }
        }

        // действие по умолчанию
        protected function index()
        {
            $errors = array();
            $books = null;
            try {
                $book = new Book($this->db);
                $books = $book->find(1000);
            } catch (\Exception $ex) {
                $errors[] = $ex->getMessage();
            }
            $this->view->output($this->model->index($errors, $books));
        }

        protected function details($id)
        {
            $errors = array();
            try {
                $id = filter_var($id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if ($id) {
                    if (!empty($id)) {
                        // Ищем книгу
                        $book = new Book($this->db);
                        $resBook = $book->newInstance($id);
                        if (null != $resBook) {
                            // Ищем рубрику
                            $heading = new Heading($this->db);
                            $resHeading = $heading->newInstance($resBook[0]['heading_id']);
                            if (null != $resHeading) {
                                $resBook[0]['heading'] = $resHeading[0]['name'];
                                // Ищем авторов
                                $bookAuthor = new BookAuthor($this->db);
                                $resBookAuthors = $bookAuthor->getAuthorsOfBookJoin($resBook[0]['id']);
                                if (null != $resBookAuthors) {
                                    $resBook[0]['authors'] = $resBookAuthors;
                                    // Ищем издательство
                                    $publisher = new Publisher($this->db);
                                    $resPublisher = $publisher->newInstance($resBook[0]['publisher_id']);
                                    if (null != $resPublisher) {
                                        $resBook[0]['publisher'] = $resPublisher[0];
                                        // Ищем фото книги
                                        $booksPhotos = new BookPhoto($this->db);
                                        $resBookPhotos = $booksPhotos
                                            ->getPhotosOfBookJoin($resBook[0]['id'], 1000);
                                        $resBook['photos'] = $resBookPhotos;
                                        $this->view->output($this->model->details($resBook));
                                        exit();
                                    } else {
                                        $errors[] = 'Ошибка получения издательства.';
                                    }
                                } else {
                                    $errors[] = 'Ошибка получения авторов.';
                                }
                            } else {
                                $errors[] = 'Ошибка получения рубрики.';
                            }
                        } else {
                            $errors[] = 'Ошибка поиска книги с id ' . $id;
                        }
                    }
                } else {
                    $errors[] = 'Присутствуют некорректные символы в данных.';
                }
            } catch (\Exception $ex) {
                $errors[] = $ex->getMessage();
            }
            $this->view->output($this->model->details(null, $errors));
        }

        protected function add()
        {
            $errors = array();
            $headings = null;
            $publishers = null;
            $authors = null;
            try {
                // Получаем данные для формы
                $heading = new Heading($this->db);
                $headings = $heading->find(1000);

                $publisher = new Publisher($this->db);
                $publishers = $publisher->find(1000);

                $author = new Author($this->db);
                $authors = $author->find(1000);

                // Если метод POST - то делаем добавление книги
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($_POST['name']) &&
                        isset($_POST['date_publishing']) &&
                        isset($_POST['heading_id']) &&
                        isset($_POST['publisher_id']) &&
                        isset($_POST['authors_id'])) {
                        $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        $datePublishing = filter_var($_POST['date_publishing'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        $headingId = filter_var($_POST['heading_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        $publisherId = filter_var($_POST['publisher_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        if ($name && $datePublishing && $headingId && $publisherId) {
                            $authorsId = $_POST['authors_id'];
                            if (!empty($name) && !empty($datePublishing) && !empty($headingId) && !empty($publisherId) &&
                                !empty($authorsId)) {

                                try {
                                    $this->db->beginTransaction();
                                    // Добавляем книгу
                                    $book = new Book($this->db);
                                    $book = $book->newEmptyInstance();
                                    $book->setName($name);
                                    $book->setDatePublishing($datePublishing);
                                    $book->setHeadingId($headingId);
                                    $book->setPublisherId($publisherId);
                                    $book->save();

                                    // Добавляем фото загруженные с пк (если есть)
                                    if (isset($_FILES['images'])) {
                                        $photo = new Photo($this->db);
                                        $bookPhoto = new BookPhoto($this->db);
                                        if (count($_FILES['images']['tmp_name']) > 0 &&
                                            $_FILES['images']['tmp_name'][0] != "") {
                                            for ($i = 0; $i < count($_FILES['images']['tmp_name']); $i++) {
                                                // Сохраняем файл в папку
                                                $nameFile = uniqid() . '.jpg';
                                                if (!move_uploaded_file($_FILES['images']['tmp_name'][$i],
                                                    DbConfig::PATH_TO_DB_IMAGES . $nameFile)) {
                                                    $errors[] = 'Ошибка сохранения файла.';
                                                    $this->view->output($this->model->add($errors, $headings,
                                                        $publishers, $authors));
                                                    exit();
                                                }
                                                // Добавляем фото в б/д
                                                $image = $photo->newEmptyInstance();
                                                $image->setPath(DbConfig::PATH_TO_DB_IMAGES . $nameFile);
                                                $image->save();
                                                // Добавляем в промежуточную таблицу books_photos
                                                $bookPhoto = $bookPhoto->newEmptyInstance();
                                                $bookPhoto->setBookId($book->getId());
                                                $bookPhoto->setPhotoId($image->getId());
                                                $bookPhoto->save();
                                            }
                                        }
                                    }
                                    // Добавляем фото с ссылок (если есть)
                                    if (isset($_POST['images_urls'])) {
                                        $resMatch = [];
                                        if (!empty($_POST['images_urls'])) {
                                            if (preg_match_all(UtilsService::PATTERN_URL_IMAGE,
                                                $_POST['images_urls'], $resMatch,
                                                PREG_SET_ORDER, 0)) {
                                                foreach ($resMatch as $url) {
                                                    if (!empty($url)) {
                                                        $nameFile = uniqid() . '.jpg';
                                                        if (!file_put_contents(DbConfig::PATH_TO_DB_IMAGES .
                                                            $nameFile, file_get_contents($url[0]))) {
                                                            $errors[] = 'Ошибка загрузки изображения.';
                                                            $this->view->output($this->model->add($errors, $headings,
                                                                $publishers, $authors));
                                                            exit();
                                                        } else {
                                                            // Добавляем фото в б/д
                                                            $image = $photo->newEmptyInstance();
                                                            $image->setPath(DbConfig::PATH_TO_DB_IMAGES .
                                                                $nameFile);
                                                            $image->save();
                                                            // Добавляем в промежуточную таблицу books_photos
                                                            $bookPhoto = $bookPhoto->newEmptyInstance();
                                                            $bookPhoto->setBookId($book->getId());
                                                            $bookPhoto->setPhotoId($image->getId());
                                                            $bookPhoto->save();
                                                        }
                                                    }
                                                }
                                            } else {
                                                $errors[] = 'Url на изображение является некорректным.';
                                                $this->db->rollBack();
                                                $this->view->output($this->model->add($errors, $headings, $publishers,
                                                    $authors));
                                                exit();
                                            }
                                        }
                                    }

                                    // Добавляем авторов книги
                                    $bookAuthor = new BookAuthor($this->db);
                                    foreach ($authorsId as $authorId) {
                                        $authorId = filter_var($authorId, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                                        if (!empty($authorsId)) {
                                            $authorFound = $author->newInstance($authorId);
                                            if (null != $authorFound) {
                                                $bookAuthorNew = $bookAuthor->newEmptyInstance();
                                                $bookAuthorNew->setBookId($book->getId());
                                                $bookAuthorNew->setAuthorId($authorFound[0]['id']);
                                                $bookAuthorNew->save();
                                            }
                                        }
                                    }
                                    $this->db->commit();
                                    UtilsService::redirect("books");
                                } catch (\Exception $ex) {
                                    $this->db->rollBack();
                                    $errors[] = $ex->getMessage();
                                }

                            } else {
                                $errors[] = 'Параметры элемента книги не должны быть пустыми.';
                            }
                        } else {
                            $errors[] = 'Присутствуют некорректные символы в данных.';
                        }
                    } else {
                        $errors[] = 'Не заданы параметры элемента книги.';
                    }
                }
            } catch (\Exception $ex) {
                $errors[] = $ex->getMessage();
            }
            $this->view->output($this->model->add($errors, $headings, $publishers, $authors));
        }

        protected function remove($id)
        {
            $errors = array();
            try {
                $id = filter_var($id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if (!empty($id)) {
                    $book = new Book($this->db);
                    $resBook = $book->newInstance($id);
                    if (null != $resBook) {
                        if ($resBook[0] != "") {
                            try {
                                $this->db->beginTransaction();
                                // Удаление фото
                                $photo = new Photo($this->db);
                                $booksPhotos = new BookPhoto($this->db);
                                $resBooksPhotos = $booksPhotos->getPhotosOfBook($id, 1000);
                                if (null != $resBooksPhotos) {
                                    if ($resBooksPhotos[0] != "") {
                                        for ($i = 0; $i < count($resBooksPhotos); $i++) {
                                            if (!$booksPhotos->delete($resBooksPhotos[$i]['id'])) {
                                                $errors[] = 'Ошибка удаления изображения (промежуточная таблица)';
                                                $this->db->rollBack();
                                                $this->view->output($this->model->remove($errors));
                                            } else {
                                                $resPhoto = $photo->newInstance($resBooksPhotos[$i]['photo_id']);
                                                if (null != $resPhoto) {
                                                    if ($resPhoto[0] != "") {
                                                        if ($photo->delete($resPhoto[0]['id'])) {
                                                            if (!unlink($resPhoto[0]['path'])) {
                                                                $errors[] = 'Ошибка удаления файла изображения.';
                                                                $this->db->rollBack();
                                                                $this->view->output($this->model->remove($errors));
                                                            }
                                                        } else {
                                                            $errors[] = 'Ошибка удаления изображения.';
                                                            $this->db->rollBack();
                                                            $this->view->output($this->model->remove($errors));
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                // Удаление авторов (промежуточная таблица)
                                $booksAuthors = new BookAuthor($this->db);
                                $resBooksAuthors = $booksAuthors->getAuthorsOfBook($id, 1000);
                                if (null != $resBooksAuthors) {
                                    if ($resBooksAuthors[0] != "") {
                                        for ($i = 0; $i < count($resBooksAuthors); $i++) {
                                            if (!$booksAuthors->delete($resBooksAuthors[$i]['id'])) {
                                                $errors[] = 'Ошибка удаления автора книги (промежуточная таблица)';
                                                $this->db->rollBack();
                                                $this->view->output($this->model->remove($errors));
                                            }
                                        }
                                    } else {
                                        $errors[] = 'Автор(ы) книги не найден(ы).';
                                    }
                                } else {
                                    $errors[] = 'Автор(ы) книги не найден(ы).';
                                    $this->db->rollBack();
                                    $this->view->output($this->model->remove($errors));
                                }

                                // Удаление книги
                                if ($book->delete($id)) {
                                    $this->db->commit();
                                    UtilsService::redirect("books");
                                } else {
                                    $errors[] = 'Ошибка удаления книги.';
                                    $this->view->output($this->model->remove($errors));
                                }
                            } catch (\Exception $ex) {
                                $this->db->rollBack();
                                $errors[] = $ex->getMessage();
                            }
                        } else {
                            $errors[] = 'Не найден элемент.';
                        }
                    } else {
                        $errors[] = 'Не найден элемент.';
                    }
                } else {
                    $errors[] = 'Пустой id.';
                }
                $this->db->rollBack();
            } catch (\Exception $ex) {
                $errors[] = $ex->getMessage();
            }
            $this->view->output($this->model->remove($errors));
        }

        protected function edit($id)
        {
            $errors = array();
            try {
                // Получаем данные для формы
                $heading = new Heading($this->db);
                $headings = $heading->find(1000);

                $publisher = new Publisher($this->db);
                $publishers = $publisher->find(1000);

                $author = new Author($this->db);
                $authors = $author->find(1000);

                $book = new Book($this->db);
                $booksAuthors = new BookAuthor($this->db);
                $booksPhotos = new BookPhoto($this->db);

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($_POST['id'])) {
                        $id = filter_var($_POST['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        if ($id) {
                            $resBook = $book->newInstance($id);
                            if (null != $resBook) {
                                if ($resBook[0] != "") {
                                    try {
                                        $this->db->beginTransaction();
                                        // Добавляем авторов книги
                                        $resBooksAuthors = $booksAuthors->getAuthorsOfBookJoin($resBook[0]['id']);
                                        if (null != $resBooksAuthors) {
                                            if ($resBooksAuthors[0] != "") {
                                                for ($i = 0; $i < count($resBooksAuthors); $i++) {
                                                    $resBook[0]['authors_id'][] = $resBooksAuthors[$i]['id'];
                                                }
                                                // Добавляем фото книги
                                                $resBooksPhotos = $booksPhotos->getPhotosOfBookJoin($resBook[0]['id'],
                                                    1000);
                                                $resArrayBooksPhotos = array();
                                                if (null != $resBooksPhotos) {
                                                    if ($resBooksPhotos[0] != "") {
                                                        for ($i = 0; $i < count($resBooksPhotos); $i++) {
                                                            $resArrayBooksPhotos[] = [
                                                                'id' => $resBooksPhotos[$i]['id'],
                                                                'path' => $resBooksPhotos[$i]['path']
                                                            ];
                                                        }
                                                    }
                                                }
                                                $resBook[0]['photos'] = $resArrayBooksPhotos;

                                                // Обновляем данные новыми
                                                if (isset($_POST['name']) &&
                                                    isset($_POST['date_publishing']) &&
                                                    isset($_POST['heading_id']) &&
                                                    isset($_POST['publisher_id']) &&
                                                    isset($_POST['authors_id'])) {
                                                    $name = filter_var($_POST['name'],
                                                        FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                                                    $datePublishing = filter_var($_POST['date_publishing'],
                                                        FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                                                    $headingId = filter_var($_POST['heading_id'],
                                                        FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                                                    $publisherId = filter_var($_POST['publisher_id'],
                                                        FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                                                    if ($name && $datePublishing && $headingId && $publisherId) {
                                                        $authorsId = $_POST['authors_id'];
                                                        if (!empty($name) && !empty($datePublishing) &&
                                                            !empty($headingId) && !empty($publisherId) &&
                                                            !empty($authorsId)) {

                                                            // Добавляем книгу
                                                            $bookUpdate = $book->newEmptyInstance();
                                                            $bookUpdate->setId($resBook[0]['id']);
                                                            $bookUpdate->setName($name);
                                                            $bookUpdate->setDatePublishing($datePublishing);
                                                            $bookUpdate->setHeadingId($headingId);
                                                            $bookUpdate->setPublisherId($publisherId);
                                                            $bookUpdate->save();

                                                            // Добавляем фото загруженные с пк (если есть)
                                                            if (isset($_FILES['images'])) {
                                                                $photo = new Photo($this->db);
                                                                $bookPhoto = new BookPhoto($this->db);
                                                                if (count($_FILES['images']['tmp_name']) > 0 &&
                                                                    $_FILES['images']['tmp_name'][0] != "") {
                                                                    for ($i = 0; $i < count($_FILES['images']['tmp_name']);
                                                                         $i++) {
                                                                        // Сохраняем файл в папку
                                                                        $nameFile = uniqid() . '.jpg';
                                                                        if (!move_uploaded_file($_FILES['images']['tmp_name'][$i],
                                                                            DbConfig::PATH_TO_DB_IMAGES . $nameFile)) {
                                                                            $errors[] = 'Ошибка сохранения файла.';
                                                                            $this->db->rollBack();
                                                                            $this->view->output($this->model->add($errors,
                                                                                $headings, $publishers, $authors));
                                                                            exit();
                                                                        }
                                                                        // Добавляем фото в б/д
                                                                        $image = $photo->newEmptyInstance();
                                                                        $image->setPath(DbConfig::PATH_TO_DB_IMAGES .
                                                                            $nameFile);
                                                                        $image->save();
                                                                        // Добавляем в промежуточную таблицу books_photos
                                                                        $bookPhoto = $bookPhoto->newEmptyInstance();
                                                                        $bookPhoto->setBookId($resBook[0]['id']);
                                                                        $bookPhoto->setPhotoId($image->getId());
                                                                        $bookPhoto->save();
                                                                    }
                                                                }
                                                            }
                                                            // Добавляем фото с ссылок (если есть)
                                                            if (isset($_POST['images_urls'])) {
                                                                $resMatch = [];
                                                                $imageUrls = filter_var($_POST['images_urls'],
                                                                    FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                                                                if (!empty($imageUrls)) {
                                                                    if (preg_match_all(UtilsService::PATTERN_URL_IMAGE,
                                                                        $imageUrls, $resMatch, PREG_SET_ORDER, 0)) {
                                                                        foreach ($resMatch as $url) {
                                                                            if (!empty($url)) {
                                                                                $nameFile = uniqid() . '.jpg';
                                                                                if (!file_put_contents(DbConfig::PATH_TO_DB_IMAGES .
                                                                                    $nameFile,
                                                                                    file_get_contents($url[0]))) {
                                                                                    $errors[] = 'Ошибка загрузки изображения.';
                                                                                    $this->db->rollBack();
                                                                                    $this->view->output($this->model->add($errors,
                                                                                        $headings, $publishers,
                                                                                        $authors));
                                                                                    exit();
                                                                                } else {
                                                                                    // Добавляем фото в б/д
                                                                                    $image = $photo->newEmptyInstance();
                                                                                    $image->setPath(DbConfig::PATH_TO_DB_IMAGES .
                                                                                        $nameFile);
                                                                                    $image->save();
                                                                                    // Добавляем в промежуточную таблицу books_photos
                                                                                    $bookPhoto = $bookPhoto->newEmptyInstance();
                                                                                    $bookPhoto->setBookId($resBook[0]['id']);
                                                                                    $bookPhoto->setPhotoId($image->getId());
                                                                                    $bookPhoto->save();
                                                                                }
                                                                            }
                                                                        }
                                                                    } else {
                                                                        $errors[] = 'Url на изображение является некорректным.';
                                                                        $this->db->rollBack();
                                                                        $this->view->output($this->model->add($errors,
                                                                            $headings, $publishers, $authors));
                                                                        exit();
                                                                    }
                                                                }
                                                            }

                                                            // Удаляем старых авторов книги
                                                            $bookAuthor = new BookAuthor($this->db);
                                                            if ($bookAuthor->deleteAuthorsOfBook($resBook[0]['id'])) {
                                                                // Добавляем обновленных авторов книги
                                                                foreach ($authorsId as $authorId) {
                                                                    if (!empty($authorsId)) {
                                                                        $authorId = filter_var($authorId,
                                                                            FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                                                                        $authorFound = $author->newInstance($authorId);
                                                                        if (null != $authorFound) {
                                                                            if ($authorFound[0] != "") {
                                                                                $bookAuthorNew = $bookAuthor->newEmptyInstance();
                                                                                $bookAuthorNew->setBookId($resBook[0]['id']);
                                                                                $bookAuthorNew->setAuthorId($authorFound[0]['id']);
                                                                                $bookAuthorNew->save();
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            } else {
                                                                $errors[] = 'Ошибка удаления старых авторов книги.';
                                                                $this->db->rollBack();
                                                                $this->view->output($this->model->edit($errors,
                                                                    $resBook[0], $headings, $publishers, $authors));
                                                                exit();
                                                            }

                                                            // Удаляем фото которые пользователь решил удалить
                                                            if (isset($_POST['remove_photos'])) {
                                                                $removePhotos = filter_var($_POST['remove_photos'],
                                                                    FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                                                                if (!empty($removePhotos)) {
                                                                    $photosIds = explode("|", $removePhotos);
                                                                    foreach ($photosIds as $photoId) {
                                                                        $photoDb = $photo->newInstance($photoId);
                                                                        if (null != $photoDb) {
                                                                            if ($photoDb[0] != "") {
                                                                                // Ищем связные данные книга-фото в таблице
                                                                                if (!$bookPhoto
                                                                                    ->deletePhotoOfBook($resBook[0]['id'],
                                                                                        $photoDb[0]['id'])) {
                                                                                    $errors[] = 'Ошибка удаления фото (связная таблица)';
                                                                                    $this->db->rollBack();
                                                                                    $this->view->output($this->model
                                                                                        ->edit($errors, $resBook[0],
                                                                                            $headings,
                                                                                            $publishers, $authors));
                                                                                    exit();
                                                                                }

                                                                                if ($photo->delete($photoDb[0]['id'])) {
                                                                                    if (!unlink($photoDb[0]['path'])) {
                                                                                        $errors[] = 'Ошибка удаления файла изображения.';
                                                                                        $this->db->rollBack();
                                                                                        $this->view->output($this
                                                                                            ->model->edit($errors,
                                                                                                $resBook[0], $headings,
                                                                                                $publishers, $authors));
                                                                                        exit();
                                                                                    }
                                                                                } else {
                                                                                    $errors[] = 'Ошибка удаления фото.';
                                                                                    $this->db->rollBack();
                                                                                    $this->view->output($this
                                                                                        ->model->edit($errors,
                                                                                            $resBook[0],
                                                                                            $headings, $publishers,
                                                                                            $authors));
                                                                                    exit();
                                                                                }
                                                                            } else {
                                                                                $errors[] = 'Не найдено фото для удаления.';
                                                                                $this->db->rollBack();
                                                                                $this->view->output($this
                                                                                    ->model->edit($errors, $resBook[0],
                                                                                        $headings,
                                                                                        $publishers, $authors));
                                                                                exit();
                                                                            }
                                                                        } else {
                                                                            $errors[] = 'Не найдено фото для удаления.';
                                                                            $this->db->rollBack();
                                                                            $this->view->output($this
                                                                                ->model->edit($errors, $resBook[0],
                                                                                    $headings,
                                                                                    $publishers, $authors));
                                                                            exit();
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            $this->db->commit();
                                                            UtilsService::redirect("books");
                                                        } else {
                                                            $errors[] = 'Параметры элемента книги не должны быть пустыми.';
                                                        }
                                                    } else {
                                                        $errors[] = 'Присутствуют некорректные символы в данных.';
                                                    }
                                                } else {
                                                    $errors[] = 'Не заданы параметры элемента книги.';
                                                }
                                                $this->view->output($this->model->edit($errors, $resBook[0], $headings,
                                                    $publishers, $authors));
                                                exit();
                                            } else {
                                                $errors[] = 'Авторы книги не найдены.';
                                            }
                                        } else {
                                            $errors[] = 'Авторы книги не найдены.';
                                        }
                                        $this->db->rollBack();
                                    } catch (\Exception $ex) {
                                        $this->db->rollBack();
                                        $errors[] = $ex->getMessage();
                                    }
                                } else {
                                    $errors[] = 'Не найдена книга.';
                                }
                            } else {
                                $errors[] = 'Не найдена книга.';
                            }
                        } else {
                            $errors[] = 'Присутствуют некорректные символы в данных.';
                        }
                    } else {
                        $errors[] = 'Укажите не пустой id.';
                    }
                } else {
                    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                        if (!empty($id)) {
                            $id = filter_var($id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                            if ($id) {
                                $resBook = $book->newInstance($id);
                                if (null != $resBook) {
                                    if ($resBook[0] != "") {
                                        // Добавляем авторов книги
                                        $resBooksAuthors = $booksAuthors->getAuthorsOfBookJoin($resBook[0]['id']);
                                        if (null != $resBooksAuthors) {
                                            if ($resBooksAuthors[0] != "") {
                                                for ($i = 0; $i < count($resBooksAuthors); $i++) {
                                                    $resBook[0]['authors_id'][] = $resBooksAuthors[$i]['id'];
                                                }
                                                // Добавляем фото книги
                                                $resBooksPhotos = $booksPhotos->getPhotosOfBookJoin($resBook[0]['id'],
                                                    1000);
                                                $resArrayBooksPhotos = array();
                                                if (null != $resBooksPhotos) {
                                                    if ($resBooksPhotos[0] != "") {
                                                        for ($i = 0; $i < count($resBooksPhotos); $i++) {
                                                            $resArrayBooksPhotos[] = [
                                                                'id' => $resBooksPhotos[$i]['id'],
                                                                'path' => $resBooksPhotos[$i]['path']
                                                            ];
                                                        }
                                                    }
                                                }
                                                $resBook[0]['photos'] = $resArrayBooksPhotos;
                                                $this->view->output($this->model->edit($errors, $resBook[0], $headings,
                                                    $publishers, $authors));
                                                exit();
                                            } else {
                                                $errors[] = 'Авторы книги не найдены.';
                                            }
                                        } else {
                                            $errors[] = 'Авторы книги не найдены.';
                                        }
                                    } else {
                                        $errors[] = 'Не найдена книга.';
                                    }
                                } else {
                                    $errors[] = 'Не найдена книга.';
                                }
                            } else {
                                $errors[] = 'Присутствуют некорректные символы в данных.';
                            }
                        } else {
                            $errors[] = 'Укажите не пустой id.';
                        }
                    }
                }
            } catch (\Exception $ex) {
                $errors[] = $ex->getMessage();
            }
            $this->view->output($this->model->edit($errors, null, $headings, $publishers, $authors));
        }
    }
}
