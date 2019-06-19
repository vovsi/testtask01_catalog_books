<?php

namespace Controllers {

    use database\PDO\DbPDO;
    use database\PDO\Entities\Book;
    use database\PDO\Entities\Publisher;
    use Models\PublisherModel;
    use MVC\Controllers\BaseController;
    use MVC\Services\UtilsService;

    class PublishersController extends BaseController
    {

        private $db;

        public function __construct($init)
        {
            parent::__construct($init);
            // создаем модель
            $this->model = new PublisherModel();
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
            $publishers = null;
            try {
                $publisher = new Publisher($this->db);
                $publishers = $publisher->find(1000);
            } catch (\Exception $ex) {
                $errors[] = $ex->getMessage();
            }
            $this->view->output($this->model->index($errors, $publishers));
        }

        protected function add()
        {
            $errors = array();
            try {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($_POST['name']) &&
                        isset($_POST['address']) &&
                        isset($_POST['phone'])) {
                        $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        $address = filter_var($_POST['address'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        $phone = filter_var($_POST['phone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        if ($name && $address && $phone) {
                            if (!empty($name) && !empty($address) && !empty($phone)) {
                                try {
                                    $this->db->beginTransaction();
                                    $publisher = new Publisher($this->db);
                                    $publisher->setName($name);
                                    $publisher->setAddress($address);
                                    $publisher->setPhone($phone);
                                    $publisher->save();
                                    $this->db->commit();
                                    UtilsService::redirect("publishers");
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
                if (!empty($id)) {
                    $publisher = new Publisher($this->db);
                    $resPublisher = $publisher->newInstance($id);
                    if (null != $resPublisher) {
                        if ($resPublisher[0] != "") {
                            $book = new Book($this->db);
                            $booksOfPublisher = $book->getBooksByPublisherId($id, 1000);
                            if ($booksOfPublisher[0] == "") {
                                try {
                                    $this->db->beginTransaction();
                                    if ($publisher->delete($id)) {
                                        $this->db->commit();
                                        UtilsService::redirect("publishers");
                                    } else {
                                        $this->db->rollBack();
                                        $errors[] = 'Ошибка удаления.';
                                    }
                                } catch (\Exception $ex) {
                                    $this->db->rollBack();
                                    $errors[] = $ex->getMessage();
                                }
                            } else {
                                $errors[] = 'Это издательство используется в книгах. Удалите сначала книги.';
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
            } catch (\Exception $ex) {
                $errors[] = $ex->getMessage();
            }
            $this->view->output($this->model->remove($errors));
        }

        protected function edit($id)
        {
            $errors = array();
            try {
                $publisher = new Publisher($this->db);
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($_POST['id'])) {
                        $id = filter_var($_POST['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        $resPublisher = $publisher->newInstance($id);
                        if (null != $resPublisher) {
                            if ($resPublisher[0] != "") {
                                if (isset($_POST['name']) &&
                                    isset($_POST['address']) &&
                                    isset($_POST['phone'])) {
                                    $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                                    $address = filter_var($_POST['address'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                                    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                                    if ($name && $address && $phone) {
                                        if (!empty($name) && !empty($address) && !empty($phone)) {
                                            try {
                                                $this->db->beginTransaction();
                                                $publisherUpdate = $publisher->newEmptyInstance();
                                                $publisherUpdate->setId($_POST['id']);
                                                $publisherUpdate->setName($name);
                                                $publisherUpdate->setAddress($address);
                                                $publisherUpdate->setPhone($phone);
                                                $publisherUpdate->save();
                                                $this->db->commit();
                                                UtilsService::redirect("publishers");
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
                                $this->view->output($this->model->edit($errors, $resPublisher[0]));
                                exit();
                            } else {
                                $errors[] = 'Не найден издатель.';
                            }
                        } else {
                            $errors[] = 'Не найден издатель.';
                        }
                    } else {
                        $errors[] = 'Укажите не пустой id.';
                    }
                } else {
                    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                        $id = filter_var($id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        if (!empty($id)) {
                            $resPublisher = $publisher->newInstance($id);
                            if (null != $resPublisher) {
                                if ($resPublisher[0] != "") {
                                    $this->view->output($this->model->edit($errors, $resPublisher[0]));
                                    exit();
                                } else {
                                    $errors[] = 'Не найден издатель.';
                                }
                            } else {
                                $errors[] = 'Не найден издатель.';
                            }
                        } else {
                            $errors[] = 'Укажите не пустой id.';
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
