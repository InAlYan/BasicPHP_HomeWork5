<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Render;
use Geekbrains\Application1\Models\User;
use Geekbrains\Application1\Application;

class UserController {
    public function actionIndex() {
        $users = User::getAllUsersFromStorage();

        $render = new Render();

        if (!$users) {
            return $render->renderPage(
                'user-empty.tpl',
                [
                    'title' => 'Список пользователей в хранилище',
                    'message' => 'Список пуст или не найден'
                ]
                );
        } else {
            return $render->renderPage(
                'user-index.tpl',
                [
                    'title' => 'Список пользователей в хранилище',
                    'users' => $users
                ]
                );
        }
    }

    public function actionSave($params) {

        $dataArr = Application::parseRequestDataToArray($params);

        $render = new Render();

        if ((isset($dataArr['name']) && $dataArr['name']) && (isset($dataArr['birthday']) && $dataArr['birthday'])) {
            $userName = urldecode($dataArr['name']);
            $birthday = $dataArr['birthday'];
        } else {
            // Показать окно сообщений, что не правильные параметры
            return $render->renderPage(
                'user-save.tpl',
                [
                    'title' => 'Список пользователей в хранилище',
                    'message' => 'Заданы некорректные параметры запроса'
                ]
                );
        }

        $result = User::saveUserToStorage($userName, $birthday);

        if ($result) {
            return $render->renderPage(
                'user-save.tpl',
                [
                    'title' => 'Список пользователей в хранилище',
                    'message' => 'Пользователь ' . $userName . ', ' . $birthday . ' был сохранен в хранилище'
                ]
                );
        } else {
            return $render->renderPage(
                'user-save.tpl',
                [
                    'title' => 'Список пользователей в хранилище',
                    'message' => 'Пользователь ' . $userName . ', ' . $birthday . ' не был сохранен в хранилище'
                ]
                );
        }
    }
}