<?php

namespace service\user;

use model\mysql\User;

/**
 * Сервис добавления юзеров в БД
 */
class AddUser
{
    /**
     * Добавляет массив юзеров в БД
     * @param array $users
     * @return array
     */
    public function addUsers(array $users): array
    {
        $userModel = new User();
        $connection = $userModel->getConnection();


        try {
            $connection->beginTransaction();
            $ids = [];
            foreach ($users as $user) {
                $userModel->addUser($user['name'], $user['lastName'], $user['age']);
                $ids[] = $connection->lastInsertId();
            }
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
        }

        return $ids;
    }
}
