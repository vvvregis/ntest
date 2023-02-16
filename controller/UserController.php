<?php

namespace controller;

use model\mysql\User;
use service\user\AddUser;

/**
 * Контроллер работающий с юзерами
 */
class UserController
{
    /**
     * Возвращает массив юзеров старше определенного возраста
     * @param int $ageFrom
     * @return array
     */
    public function getUsersOldestAge(int $ageFrom): array
    {
        return (new User)->getUsersOldestAge($ageFrom);
    }

    /**
     * Возвращает массив юзеров по заданным именам
     * GET заменил на POST дабы избежать SQL инъекций
     * @return array
     */
    public function getUsersByNames(): array
    {
        $users = [];

        foreach ($_POST['names'] as $name) {
            if ($user = (new User)->getUsersByName($name)) {
                $users[] = $user;
            }
        }

        return $users;
    }

    /**
     * Добавляет массив юзеров в таблицу
     * Оставлять логику добавления тут мне показалось слишком FAT, вынес в сервис
     * @param array $users
     * @return array
     */
    public function addUser(array $users): array
    {
        return (new AddUser())->addUsers($users);
    }
}
