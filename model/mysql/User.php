<?php

namespace model\mysql;

/**
 * Модель обеспечивающая работу с таблицей Users
 */
class User extends BaseMysqlModel
{
    /**
     * Лимит поиска юзеров
     */
    private const SELECT_USERS_LIMIT = 10;
    /**
     * В БД были указаны поля, но данные в них не приходили, добавил дефолтные константы
     */
    private const DEFAULT_CITY = 'Moscow';
    private const DEFAULT_SETTINGS = '{"key" : "some_key"}';

    /**
     * Возвращает юзеров старше определенного возраста
     * @param int $ageFrom
     * @return array
     */
    public function getUsersOldestAge(int $ageFrom): array
    {
        $query = "SELECT id, name, lastName, city, age, settings FROM Users WHERE age > {$ageFrom} LIMIT "
            . self::SELECT_USERS_LIMIT;

        $resultRows = $this->getAssocArray($query);

        return $this->getArrayWithDecodedSettings($resultRows);
    }

    /**
     * Возвращает юзеров по имени
     * @param string $name
     * @return array
     */
    public function getUsersByName(string $name): array
    {
        $query = "SELECT id, name, lastName, city, age, settings FROM Users WHERE name = '{$name}'";

        $resultRows = $this->getAssocArray($query);
        return $this->getArrayWithDecodedSettings($resultRows);
    }

    /**
     * В изначальном коде название поля БД было from, посколько данное слово является ключевым в SQL название поля
     * лучше изменить, изменил на city (можно было например address)
     *
     * Так же неверно обрабатывался результат декодирования json
     *
     * @param array $resultRows
     * @return array
     */
    protected function getArrayWithDecodedSettings(array $resultRows): array
    {
        $users = [];

        if (!$resultRows) {
            return $users;
        }

        foreach ($resultRows as $row) {
            $settings = json_decode($row['settings']);
            $users[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'lastName' => $row['lastName'],
                'from' => $row['city'],
                'age' => $row['age'],
                'key' => $settings->key,
            ];
        }

        return $users;
    }

    /**
     * Добавляет нового юзера
     * @param string $name
     * @param string $lastName
     * @param int $age
     * @return string|null
     */
    public function addUser(string $name, string $lastName, int $age): ?string
    {
        $preparedQuery = $this->mysqlConnection->prepare(
            "INSERT INTO Users (name, lastName, age, city, settings) VALUES 
                                                            (:name, :lastName, :age, :city, :settings)"
        );
        $preparedQuery->execute([
            'name' => $name,
            'age' => $age,
            'lastName' => $lastName,
            'city' => self::DEFAULT_CITY,
            'settings' => self::DEFAULT_SETTINGS]
        );

        if (!$lastInsertId = $this->mysqlConnection->lastInsertId()) {
            return null;
        }

        return $lastInsertId;
    }
}