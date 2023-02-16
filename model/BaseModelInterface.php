<?php

namespace model;

use PDO;
use PDOStatement;

interface BaseModelInterface
{
    /**
     * Предполагается, что коннект будет осуществлен в конструткторе
     */
    public function __construct();

    /**
     * Возвращает коннект
     * @return PDO
     */
    public function getConnection(): PDO;

    /**
     * Обрабатывает запрос
     * @param string $query
     * @return PDOStatement|null
     */
    public function query(string $query): ?PDOStatement;

    /**
     * Возвращает ассоциативный массив выборки
     * @param string $query
     * @return array
     */
    public function getAssocArray(string $query): array;
}