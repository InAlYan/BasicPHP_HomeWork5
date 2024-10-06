<?php

namespace Geekbrains\Application1;

class Application {
    private const APP_NAMESPACE = 'Geekbrains\Application1\Controllers\\';

    private string $controllerName;
    private string $methodName;

    // Использую так как суперглобальный массив $_GET почему то пуст
    public static function parseRequestDataToArray($pass_params) : array {
        // Так выглядят сырые данные string(102) "?name=Ivan&lastname=Ivanov&birthday=03-04-2002"
        $pass_params = trim($pass_params, "?\""); // => name=Ivan&lastname=Ivanov&birthday=03-04-2002
        $pairKeyValue = explode('&', $pass_params); // => ['name=Ivan', 'lastname=Ivanov', 'birthday=03-04-2002'];
        $result = [];
        foreach ($pairKeyValue as $key => $value) {
            $temp = explode("=" , $value);
            $result[$temp[0]] = $temp[1];
        }
        return $result; // => ['name' => 'Ivan', 'lastname' => 'Ivanov', 'birthday=03-04-2002'];
    }

    public function run() : string {

        // разбиваем адрес по имени слэша
        $routeArray = explode('/', $_SERVER['REQUEST_URI']);

        if (isset($routeArray[1]) && $routeArray[1] != '') {
            $controllerName = $routeArray[1];
        } else {
            $controllerName = 'page';
        }

        // определяем имя контроллера
        $this->controllerName = Application::APP_NAMESPACE . ucfirst($controllerName) . 'Controller';

        // проверяем контроллер на существование
        if (class_exists($this->controllerName)) {
            if (isset($routeArray[2]) && $routeArray[2] != '') {
                $methodName = $routeArray[2];
            } else {
                $methodName = 'index';
            }
            $this->methodName = 'action' . ucfirst($methodName);

            // проверяем метод на существование
            if (method_exists($this->controllerName, $this->methodName)) {

                // создаем экземпляр контроллера, если класс существует
                $controllerInstance = new $this->controllerName();
                // вызываем метод, если он существует
                // Использую $parametrs так как суперглобальный массив $_GET почему то пуст при отладке
                $parametrs = array($routeArray[3]);
                return call_user_func_array([$controllerInstance, $this->methodName], $parametrs);

            } else {
                header("HTTP/1.0 404 Not Found");
                header("Location: /error-page.html");
                die;
                // $render = new Render();
                // return $render->renderError('error-rendering.tpl', []);
            }

        } else {
            header("HTTP/1.0 404 Not Found");
            header("Location: /error-page.html");
            die;
            // $render = new Render();
            // return $render->renderError('error-rendering.tpl', []);

        }

    }
}
