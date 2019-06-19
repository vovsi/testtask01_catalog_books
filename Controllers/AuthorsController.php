<?php

namespace Controllers {

    use database\PDO\DbPDO;
    use database\PDO\Entities\Author;
    use database\PDO\Entities\BookAuthor;
    use Models\AuthorModel;
    use MVC\Controllers\BaseController;
    use MVC\Services\UtilsService;

    class AuthorsController extends BaseController
    {

        private $db;

        public function __construct($init)
        {
            parent::__construct($init);
            // создаем модель
            $this->model = new AuthorModel();
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
            $authors = null;
            try {
                $authorPdo = new Author($this->db);
                $authors = $authorPdo->find(1000);
            } catch (\Exception $ex) {
                $errors[] = $ex->getMessage();
            }
            $this->view->output($this->model->index($errors, $authors));
        }

        public function add()
        {
            $errors = array();
            try {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($_POST['first_name']) &&
                        isset($_POST['last_name']) &&
                        isset($_POST['middle_name'])) {
                        $firstName = filter_var($_POST['first_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        $lastName = filter_var($_POST['last_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        $middleName = filter_var($_POST['middle_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        if ($firstName && $lastName && $middleName) {
                            if (!empty($firstName) && !empty($lastName) && !empty($middleName)) {
                                try {
                                    $this->db->beginTransaction();
                                    $author = new Author($this->db);
                                    $author->setFirstName($firstName);
                                    $author->setLastName($lastName);
                                    $author->setMiddleName($middleName);
                                    $author->save();
                                    $this->db->commit();
                                    UtilsService::redirect("authors");
                                } catch (\Exception $ex) {
                                    $this->db->rollBack();
                                    $errors[] = $ex->getMessage();
                                }
                            } else {
                                $errors[] = 'Все параметры элемента издательства не должны быть пустыми.';
                            }
                        } else {
                            $errors[] = 'Присутствуют некорректные символы в данных.';
                        }
                    } else {
                        $errors[] = 'Не найдены параметры элемента издательства.';
                    }
                }
            } catch (\Exception $ex) {
                $errors[] = $ex->getMessage();
            }
            $this->view->output($this->model->add($errors));
        }

        protected function remove($id)
        {
            $errors = array();
            try {
                $id = filter_var($id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if ($id) {
                    if (!empty($id)) {
                        $author = new Author($this->db);
                        $resAuthor = $author->newInstance($id);
                        if (null != $resAuthor) {
                            if ($resAuthor[0] != "") {
                                $bookAuthor = new BookAuthor($this->db);
                                $booksOfAuthor = $bookAuthor->getBooksByAuthorId($id, 1000);
                                if (null == $booksOfAuthor || $booksOfAuthor[0] == "") {
                                    try {
                                        $this->db->beginTransaction();
                                        if ($author->delete($id)) {
                                            $this->db->commit();
                                            UtilsService::redirect("authors");
                                        } else {
                                            $this->db->rollBack();
                                            $errors[] = 'Ошибка удаления.';
                                        }
                                    } catch (\Exception $ex) {
                                        $this->db->rollBack();
                                        $errors[] = $ex->getMessage();
                                    }
                                } else {
                                    $errors[] = 'Этот автор используется в книгах. Удалите сначала книги.';
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
                } else {
                    $errors[] = 'Присутствуют некорректные символы в данных.';
                }
            } catch (\Exception $ex) {
                $errors[] = $ex->getMessage();
            }
            $this->view->output($this->model->remove($errors));
        }

        protected function edit($id)
        {
            $errors = array();
            try {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($_POST['id']) &&
                        isset($_POST['first_name']) &&
                        isset($_POST['last_name']) &&
                        isset($_POST['middle_name'])) {
                        $id = filter_var($_POST['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        $firstName = filter_var($_POST['first_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        $lastName = filter_var($_POST['last_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        $middleName = filter_var($_POST['middle_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        if ($id && $firstName && $lastName && $middleName) {
                            if (!empty($id) && !empty($firstName) && !empty($lastName) && !empty($middleName)) {
                                $author = new Author($this->db);
                                $resAuthor = $author->newInstance($id);
                                if (null != $resAuthor) {
                                    if ($resAuthor[0] != "") {
                                        try {
                                            $this->db->beginTransaction();
                                            $authorUpdate = $author->newEmptyInstance();
                                            $authorUpdate->setId($resAuthor[0]['id']);
                                            $authorUpdate->setFirstName($firstName);
                                            $authorUpdate->setLastName($lastName);
                                            $authorUpdate->setMiddleName($middleName);
                                            $authorUpdate->save();
                                            $this->db->commit();
                                            UtilsService::redirect("authors");
                                        } catch (\Exception $ex) {
                                            $this->db->rollBack();
                                            $errors[] = $ex->getMessage();
                                        }
                                    } else {
                                        $errors[] = 'Не найден автор.';
                                    }
                                } else {
                                    $errors[] = 'Не найден автор.';
                                }

                            } else {
                                $errors[] = 'Все параметры элемента издательства не должны быть пустыми.';
                            }
                        } else {
                            $errors[] = 'Присутствуют некорректные символы в данных.';
                        }
                    } else {
                        $errors[] = 'Не найдены параметры элемента издательства.';
                    }
                } else {
                    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                        $id = filter_var($id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        if ($id) {
                            if (!empty($id)) {
                                $author = new Author($this->db);
                                $resAuthor = $author->newInstance($id);
                                if (null != $resAuthor) {
                                    if ($resAuthor[0] != "") {
                                        $this->view->output($this->model->edit($errors, $resAuthor[0]));
                                        exit();
                                    } else {
                                        $errors[] = 'Не найден автор.';
                                    }
                                } else {
                                    $errors[] = 'Не найден автор.';
                                }
                            } else {
                                $errors[] = 'Укажите не пустой id.';
                            }
                        } else {
                            $errors[] = 'Присутствуют некорректные символы в данных.';
                        }
                    }
                }
            } catch (\Exception $ex) {
                $errors[] = $ex->getMessage();
            }
            $this->view->output($this->model->edit($errors, null));
        }
    }
}
