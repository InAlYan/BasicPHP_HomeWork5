
# Урок 5. Разработка каркаса приложения

## Задание 1:

    Добавьте к шаблону подключение файлов стилей так, чтобы в дальнейшем можно было дорабатывать внешний вид системы.
## Решение:
    стиль подключен в разделе head файла /src/Views/main.tpl строкой <link rel="stylesheet" href="/src/css/main.css"> Сам файл стиля находится по адресу /src/css/main.css

---

## Задание 2
Сформируйте еще три подключаемых к скелету блока – шапку сайта (она всегда будет одинаковой по стилю и располагаться в самой верхней части), подвал сайта (также одинаковый, но в нижней части) и sidebar (боковая колонка, которую можно наполнять новыми элементами).
## Решение:
    добавил 3 шаблона:
        - site-header.tpl,
        - site-footer.tpl,
        - site-sidebar.tpl
    в папку шаблонов по адресу /src/Views/,

    также добавил в функцию renderPage класса Render такой код:

```
$templateVariables['content_template_header'] = 'site-header.tpl'; // Шапка
$templateVariables['content_template_footer'] = 'site-footer.tpl'; // Подвал
$templateVariables['content_template_sidebar'] = 'site-sidebar.tpl'; // Sidebar
```

    а в шаблон main.tpl по адресу /src/Views/ такой код:

```
{% include content_template_header %}
...
{% include content_template_sidebar %}
{% include content_template_footer %}
```
---
## Задание 3:
    Средствами TWIG выводите на экран текущее время.

## Решение:
    создал класс:
```
namespace Geekbrains\Application1\Models;

class Time {
    public static function getCurrentTime() {
        return date('m/d/Y h:i:s a', time());
    }
}
```
    также добавил метод renderPage в класс Render такой код:
```
use Geekbrains\Application1\Models\Time;
    ...
$templateVariables['content_template_cur_time'] = Time::getCurrentTime(); // Текущее время
```
    а в шаблон main.tpl по адресу /src/Views/ такой код:
```
<p> {{ content_template_cur_time }} </p>
```
---

## Задание 4:
    Создайте обработку страницы ошибки. Например, если контроллер на найден, то нужно вызывать специальный метод рендеринга, который сформирует специальную страницу ошибок.
## Решение:
    Создал шаблон страницы ошибки error-rendering.tpl
    добавил в класс Render такой метод
```
public function renderError(string $contentTemplateName = 'error-rendering.tpl', array $templateVariables = []) {
    $template = $this->environment->load('error-rendering.tpl');

    return $template->render($templateVariables);
}
```
    также добавил в метод run() класса Application в конце такой код:
```
run() {
    ...
        } else { // Такого метода не существует
            $render = new Render();
            return $render->renderError('error-rendering.tpl', []);
        }
    } else { // Такого контроллера не существует
        $render = new Render();
        return $render->renderError('error-rendering.tpl', []);
    }
}
```
---
## Задание 5:
    Для страницы ошибок формируйте HTTP-ответ 404. Это можно сделать при помощи функции header.
## Решение:
    в метод run() класса Application в конце добавлена функция header("HTTP/1.0 404 Not Found");:
```
run() {
    ...
        } else { // Такого метода не существует
            header("HTTP/1.0 404 Not Found");
            $render = new Render();
            return $render->renderError('error-rendering.tpl', []);
        }
    } else { // Такого контроллера не существует
        header("HTTP/1.0 404 Not Found");
        $render = new Render();
        return $render->renderError('error-rendering.tpl', []);
    }
}
```
---
## Задание 6:
    Реализуйте функционал сохранения пользователя в хранилище. Сохранение будет происходить при помощи GET-запроса.
    /user/save/?name=Иван&birthday=05-05-1991
## Решение:
    Добавил в класс User такой метод
```
public static function saveUserToStorage(string $userName, string $birthday) : bool {

    $address = $_SERVER['DOCUMENT_ROOT'] . User::$storageAddress;
    if (file_exists($address) && is_readable($address)) {
        $file = fopen($address, 'a');

        $userStringToSave = $userName . ', ' . $birthday . PHP_EOL;
        fputs($file, $userStringToSave);

        fclose($file);
        return true;
    } else {
        return false;
    }
}
```
    а в класс UserController такой метод
```
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
```
    В методе actionSave($params) класса UserController испозьзуется строка
    $dataArr = Application::parseRequestDataToArray($params) так как суперглобальный массив $_GET почему то пуст.

    Данный метод Application::parseRequestDataToArray($params) реализован в классе Application как статический и возвращает ассоциативный массив параметров,которые должны были быть в $_GET:
```
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
```
---