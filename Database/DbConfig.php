<?php

namespace Database {

    class DbConfig
    {
        // CONFIG DB CONNECT
        const HOST = "localhost";
        const USERNAME = "root";
        const PASSWORD = "root";
        const DATABASE = "catalog_books";

        // PATHS
        const PATH_TO_DB_IMAGES = 'Database/dbStorage/bookImages/';
        const PATH_TO_DB_SQL_MIGRATIONS = 'Database/migrations/sqlFiles/';

        // MAX LIMIT
        const MAX_SYMBOLS_AUTHOR_FIRST_NAME = 50;
        const MAX_SYMBOLS_AUTHOR_LAST_NAME = 50;
        const MAX_SYMBOLS_AUTHOR_MIDDLE_NAME = 50;
        const MAX_SYMBOLS_BOOK_NAME = 150;
        const MAX_SYMBOLS_HEADING_NAME = 150;
        const MAX_SYMBOLS_PUBLISHER_NAME = 150;
        const MAX_SYMBOLS_PUBLISHER_ADDRESS = 150;
        const MAX_SYMBOLS_PUBLISHER_PHONE = 20;
        const MAX_PHOTOS_BOOK = 10;
        const MAX_SYMBOLS_ID = 100;
        const MAX_AUTHORS_BOOK = 10;

        // MIN LIMIT
        const MIN_SYMBOLS_AUTHOR_FIRST_NAME = 1;
        const MIN_SYMBOLS_AUTHOR_LAST_NAME = 1;
        const MIN_SYMBOLS_AUTHOR_MIDDLE_NAME = 1;
        const MIN_SYMBOLS_BOOK_NAME = 5;
        const MIN_SYMBOLS_HEADING_NAME = 3;
        const MIN_SYMBOLS_PUBLISHER_NAME = 3;
        const MIN_SYMBOLS_PUBLISHER_ADDRESS = 5;
        const MIN_SYMBOLS_PUBLISHER_PHONE = 1;
        const MIN_AUTHORS_BOOK = 1;
        const MIN_PHOTOS_BOOK = 0;
        const MIN_SYMBOLS_ID = 1;
    }
}