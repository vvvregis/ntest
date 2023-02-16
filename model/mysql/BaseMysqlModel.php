<?php

namespace model\mysql;

use database\mysql\Connector;
use model\BaseModelInterface;
use \PDO;
use \PDOStatement;

/**
 * Базовая модель, содержит в себе методы, которые могут быть использованы в дочерних моделях
 */
abstract class BaseMysqlModel implements BaseModelInterface
{
    /**
     * Хранит подключение к БД
     * @var PDO
     */
    protected PDO $mysqlConnection;

    /**
     * Осуществляем коннект к БД, записываем в переменную
     */
    public function __construct()
    {
        $instance = Connector::getInstance();
        $this->mysqlConnection = $instance->getConnection();
    }

    /**
     * Немного упростил запросы, но писать тут отдельную ОРМ не стал
     * @param string $query
     * @return PDOStatement|null
     */
    public function query(string $query): ?PDOStatement
    {
        if (!$preparedQuery = $this->mysqlConnection->prepare($query)) {
            return null;
        }

        if (!$preparedQuery->execute()) {
            return null;
        }

        return $preparedQuery;
    }

    /**
     * Возвращает ассоциативный массив выборки
     * @param string $query
     * @return array
     */
    public function getAssocArray(string $query): array
    {
        try {
            $preparedQuery = $this->query($query);
            return $preparedQuery->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            die('SQL Error');
        }

    }

    /**
     * Возвращает коннект к БД
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->mysqlConnection;
    }
}
