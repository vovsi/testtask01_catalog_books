<?php

namespace database\PDO {

    use database\DbConfig;
    use PDO;

    class DbPDO extends PDO
    {
        public function __construct($options = [])
        {
            $default_options = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ];
            $options = array_replace($default_options, $options);
            parent::__construct('mysql:host=' . DbConfig::HOST . ';dbname=' . DbConfig::DATABASE . ';charset=utf8',
                DbConfig::USERNAME, DbConfig::PASSWORD, $options);
        }

        public function run($sql, $args = null)
        {
            if (!$args) {
                return $this->query($sql);
            }
            $stmt = $this->prepare($sql);
            $stmt->execute($args);
            return $stmt;
        }
    }
}