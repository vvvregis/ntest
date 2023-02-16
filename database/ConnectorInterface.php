<?php

namespace database;

use PDO;

/**
 * Этот интерфейс смогут реализовать классы для коннекта к другим типам БД, использующим PDO
 */
interface ConnectorInterface
{
    /**
     * @return PDO
     */
    public function getConnection(): PDO;
}
