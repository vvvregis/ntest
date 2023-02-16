<?php

namespace database\mysql;

use database\ConnectorInterface;
use PDO;

/**
 * Коннект с БД вынес в отдельный класс
 */
class Connector implements ConnectorInterface
{
    private static ?Connector $instance = null;
    private \PDO $connection;

    /**
     * Сделано для наглядности, в реальном проекте доступы в БД должны быть вынесены в отдельный конфиг файл,
     * который игнорируется гитом
     */
    private const MYSQL_DSN = 'mysql:dbname=db;host=127.0.0.1';
    private const MYSQL_USER = 'dbuser';
    private const MYSQL_PASS = 'dbpass';

    /**
     * Конструктор делаем приватным
     * Можно обойтись без try catch, но светить mysql креды в ошибке не стоит
     */
    private function __construct()
    {
        try {
            $this->connection = new PDO(self::MYSQL_DSN, self::MYSQL_USER, self::MYSQL_PASS);
        } catch (\Throwable $e) {
            die('Connect Error');
        }

    }

    /**
     * Возвращает коннект к БД
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Синглтон
     * @return Connector
     */
    public static function getInstance(): Connector
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Запрещаем клонировать
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Запрещаем сериализацию
     * @return void
     */
    private function __wakeup()
    {
    }
}