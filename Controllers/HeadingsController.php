<?php

namespace Controllers {

    use Database\DbConfig;
    use Database\PDO\DbPDO;
    use Database\PDO\Entities\Heading;
    use Models\HeadingModel;
    use MVC\Controllers\BaseController;
    use MVC\Services\UtilsService;

    class HeadingsController extends BaseController
    {

        private $db;

        public function __construct($init)
        {
            parent::__construct($init);
            // создаем модель
            $this->model = new HeadingModel();
            try {
                $this->db = new DbPDO();
            } catch (\Exception $ex) {
                UtilsService::redirect();
            }
        }

        // действие по умолчанию
        protected function index($id)
        {
            $errors = array();
            $headings = null;
            try {
                $heading = new Heading($this->db);
                $id = filter_var($id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if (!empty($id)) {
                    $headings = $heading->getHeadingsByParentId($id, 1000);
                } else {
                    $headings = $heading->getHeadingsWithoutParent(1000);
                }
            } catch (\Exception $ex) {
                $errors[] = $ex->getMessage();
            }
            $this->view->output($this->model->index($errors, $headings));
        }

        protected function add()
        {
            $errors = array();
            $headings = null;
            try {
                $heading = new Heading($this->db);
                $headings = $heading->find(1000);
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($_POST['name']) &&
                        isset($_POST['parent_heading_id'])) {
                        $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        if ($name) {
                            $parentHeadingId = ($_POST['parent_heading_id'] == '-') ? null :
                                filter_var($_POST['parent_heading_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                            if (strlen($name) >= DbConfig::MIN_SYMBOLS_HEADING_NAME &&
                                strlen($name) <= DbConfig::MAX_SYMBOLS_HEADING_NAME) {
                                if (!empty($name)) {
                                    try {
                                        $this->db->beginTransaction();
                                        $heading->setName($name);
                                        $heading->setParentHeadingId($parentHeadingId);
                                        $heading->save();
                                        $this->db->commit();
                                        UtilsService::redirect("headings");
                                    } catch (\Exception $ex) {
                                        $this->db->rollBack();
                                        $errors[] = $ex->getMessage();
                                    }
                                } else {
                                    $errors[] = 'Все параметры элемента рубрики не должны быть пустыми.';
                                }
                            } else {
                                $errors[] = 'Нарушены правила кол-ва символов в данных. Допустимо: Name('
                                    . DbConfig::MIN_SYMBOLS_HEADING_NAME . '-' . DbConfig::MAX_SYMBOLS_HEADING_NAME .
                                    ')';
                            }
                        } else {
                            $errors[] = 'Присутствуют некорректные символы в данных.';
                        }
                    } else {
                        $errors[] = 'Не найдены параметры элемента рубрики.';
                    }
                }
            } catch (\Exception $ex) {
                $errors[] = $ex->getMessage();
            }
            $this->view->output($this->model->add($errors, $headings));
        }

        protected function remove($id)
        {
            $errors = array();
            try {
                $id = filter_var($id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if (!empty($id)) {
                    if (strlen($id) >= DbConfig::MIN_SYMBOLS_ID &&
                        strlen($id) <= DbConfig::MAX_SYMBOLS_ID) {
                        $heading = new Heading($this->db);
                        $resHeading = $heading->newInstance($id);
                        if (null != $resHeading) {
                            if ($resHeading[0] != "") {
                                try {
                                    $this->db->beginTransaction();
                                    if ($heading->delete($id)) {
                                        $this->db->commit();
                                        UtilsService::redirect("headings");
                                    } else {
                                        $this->db->rollBack();
                                        $errors[] = 'Ошибка удаления.';
                                    }
                                } catch (\Exception $ex) {
                                    if ($ex->getCode() == 23000) {
                                        $errors[] = "Рубрика, или её подразделы используются в книгах. Сначала нужно удалить 
                                    книги.";
                                    } else {
                                        $errors[] = $ex->getMessage();
                                    }
                                    $this->db->rollBack();
                                }
                            } else {
                                $errors[] = 'Не найден элемент.';
                            }
                        } else {
                            $errors[] = 'Не найден элемент.';
                        }
                    } else {
                        $errors[] = 'Нарушены правила кол-ва символов в данных. Допустимо: Id(' .
                            DbConfig::MIN_SYMBOLS_ID . '-' . DbConfig::MAX_SYMBOLS_ID .
                            ')';
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
            $headings = null;
            try {
                $heading = new Heading($this->db);
                $headings = $heading->find(1000);
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($_POST['id'])) {
                        $id = filter_var($_POST['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        $resHeading = $heading->newInstance($id);
                        if (null != $resHeading) {
                            if ($resHeading[0] != "") {
                                if (isset($_POST['name']) &&
                                    isset($_POST['parent_heading_id'])) {
                                    $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                                    if ($name) {
                                        $parentHeadingId = ($_POST['parent_heading_id'] == '-') ? null :
                                            filter_var($_POST['parent_heading_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                                        if (strlen($name) >= DbConfig::MIN_SYMBOLS_HEADING_NAME &&
                                            strlen($name) <= DbConfig::MAX_SYMBOLS_HEADING_NAME) {
                                            // Если подрубрика ссылается на себя
                                            if ($parentHeadingId == $resHeading[0]['id']) {
                                                $errors[] = 'Нельзя выбрать подрубрику ссылающуюся на себя.';
                                                $this->view->output($this->model->edit($errors, $headings,
                                                    $resHeading[0]));
                                                exit();
                                            }
                                            if (!empty($name)) {
                                                try {
                                                    $this->db->beginTransaction();
                                                    $headingUpdate = $heading->newEmptyInstance();
                                                    $headingUpdate->setId($resHeading[0]['id']);
                                                    $headingUpdate->setName($name);
                                                    $headingUpdate->setParentHeadingId($parentHeadingId);
                                                    $headingUpdate->save();
                                                    $this->db->commit();
                                                    UtilsService::redirect("headings");
                                                } catch (\Exception $ex) {
                                                    $this->db->rollBack();
                                                    $errors[] = $ex->getMessage();
                                                }
                                            } else {
                                                $errors[] = 'Все параметры элемента рубрики не должны быть пустыми.';
                                            }
                                        } else {
                                            $errors[] = 'Нарушены правила кол-ва символов в данных. Допустимо: Name('
                                                . DbConfig::MIN_SYMBOLS_HEADING_NAME . '-' . DbConfig::MAX_SYMBOLS_HEADING_NAME .
                                                ')';
                                        }
                                    } else {
                                        $errors[] = 'Присутствуют некорректные символы в данных.';
                                    }
                                } else {
                                    $errors[] = 'Не найдены параметры элемента рубрики.';
                                }
                                $this->view->output($this->model->edit($errors, $headings, $resHeading[0]));
                                exit();
                            } else {
                                $errors[] = 'Не найдена рубрика.';
                            }
                        } else {
                            $errors[] = 'Не найдена рубрика.';
                        }
                    } else {
                        $errors[] = 'Укажите не пустой id.';
                    }
                } else {
                    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                        $id = filter_var($id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        if (!empty($id)) {
                            if (strlen($id) >= DbConfig::MIN_SYMBOLS_ID &&
                                strlen($id) <= DbConfig::MAX_SYMBOLS_ID) {
                                $resHeading = $heading->newInstance($id);
                                if (null != $resHeading) {
                                    if ($resHeading[0] != "") {
                                        $this->view->output($this->model->edit($errors, $headings, $resHeading[0]));
                                        exit();
                                    } else {
                                        $errors[] = 'Не найдена рубрика.';
                                    }
                                } else {
                                    $errors[] = 'Не найдена рубрика.';
                                }
                            } else {
                                $errors[] = 'Нарушены правила кол-ва символов в данных. Допустимо: Id(' .
                                    DbConfig::MIN_SYMBOLS_ID . '-' . DbConfig::MAX_SYMBOLS_ID .
                                    ')';
                            }
                        } else {
                            $errors[] = 'Укажите не пустой id.';
                        }
                    }
                }
            } catch (\Exception $ex) {
                $errors[] = $ex->getMessage();
            }
            $this->view->output($this->model->edit($errors, $headings, null));
        }
    }
}
