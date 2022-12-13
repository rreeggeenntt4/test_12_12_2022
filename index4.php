<?php

/**
 * # Работа с DOM
 * Написать скрипт закачивания страницы www.bills.ru,
 * из страницы извлечь даты, заголовки,
 * ссылки в блоке "события на долговом рынке",
 * сохранить в таблицу bills_ru_events,
 * имеющей такую структуру:
 * 
 * id — целое, автоинкрементарное
 * date — в формате год-месяц-день часы:минуты:секунды
 * title — строковое, не более 230 символов
 * url — строковое, не более 240 символов, уникальное
 *
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
setlocale(LC_ALL, 'ru_RU');
date_default_timezone_set('Europe/Moscow');

require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bdconnect.php";



/**
 * Создает базу данных bills_ru_events
 *
 * @return void
 */
function create()
{
    global $mysqli;

    mysqli_query($mysqli, "CREATE TABLE IF NOT EXISTS `bills_ru_events` (
        id int(10) unsigned NOT NULL AUTO_INCREMENT,
        date datetime DEFAULT NULL,
        title text(230) DEFAULT NULL,
        url text(240) DEFAULT NULL,
        PRIMARY KEY (id)
        )");
}
create();

/**
 * addNews добавляет в базу данных новости
 *
 * @param  mixed $date
 * @param  mixed $title
 * @param  mixed $url
 * @return void
 */
function addNews($date, $title, $url)
{
    global $mysqli;

    $date = $mysqli->real_escape_string($date);
    $title = $mysqli->real_escape_string($title);
    $url = $mysqli->real_escape_string($url);

    $sql = "INSERT INTO `bills_ru_events` (`date`,`title`,`url`) VALUES ('" . $date . "','" . $title . "','" . $url . "')";
    $mysqli->query($sql);
}


// -------------------------------------------------------------
/**
 * get_web_page Рабочий для поисковиков яндекс и гугл так же
 * получает страницу сайта переданную в url
 *
 * @param  mixed $url
 * @return void
 */
function get_web_page($url)
{
    $uagent = "Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.14";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу
    curl_setopt($ch, CURLOPT_HEADER, 0);           // не возвращает заголовки
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);   // переходит по редиректам
    curl_setopt($ch, CURLOPT_ENCODING, "");        // обрабатывает все кодировки
    curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // таймаут соединения
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);        // таймаут ответа
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);       // останавливаться после 10-ого редиректа

    $content = curl_exec($ch);
    $err     = curl_errno($ch);
    $errmsg  = curl_error($ch);
    $header  = curl_getinfo($ch);
    curl_close($ch);

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header;
}


/**
 * saveToFile записывает в файл содержание переданной переменной
 * вспомогательная функция для понимания содержания dom в текстовом формате
 * без изменения браузером
 *
 * @return void
 */
function saveToFile($html)
{
    $file = 'html.txt';
    // Открываем файл для получения существующего содержимого
    $current = file_get_contents($file);
    // Добавляем нового человека в файл
    $current = "$html\n";
    // Пишем содержимое обратно в файл
    file_put_contents($file, $current);
}


$result = get_web_page("https://www.bills.ru/");
$page = "";
if (($result['errno'] != 0) || ($result['http_code'] != 200)) {
    echo $result['errmsg'];
} else {
    $page = $result['content'];
}
// -------------------------------------------------------------



// Производим манипуляции с полученными данными и приводим содержание
// к более читабельному виду html.txt, что при работе с редактором
// становится в нормльный вид temp.html
// -------------------------------------------------------------
$html = $page;

$findme = '<!--Центральная колонка-->';
// Определяем позицию
$pos = strpos($html, $findme);

/* Коректиовка */
$corect = 390;
// Получаем строку начиная с номера pos
$pos = $pos - $corect;
$html = mb_substr($html, $pos);

$findme = '</table>';
$pos = strpos($html, $findme);
$pos = $pos - $corect + 40;
$html = mb_substr($html, 0, $pos); // Начиная с 0 берем pos символов

// Записываем последнее значение в файл
saveToFile($html);
// -------------------------------------------------------------


$dom = new DOMDocument();
/* Добавляем '<?xml encoding="utf-8" ?>' иначе будут иероглифы */
$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);


$trs = $dom->getElementsByTagName('tr');
foreach ($trs as $tr) {

    $date = trim($tr->childNodes->item(1)->nodeValue);
    $title = trim($tr->childNodes->item(3)->childNodes->item(1)->nodeValue);
    $url = trim($tr->childNodes->item(3)->childNodes->item(1)->getAttribute('href'));

    $title = mb_substr($title, 0, 230);
    $url = mb_substr($url, 0, 240);

    $months = [
        'янв' => 'Jan',
        'фев' => 'Feb',
        'мар' => 'Mar',
        'апр' => 'Apr',
        'мая' => 'May',
        'июн' => 'Jun',
        'июл' => 'Jul',
        'авг' => 'Aug',
        'сен' => 'Sep',
        'окт' => 'Oct',
        'ноя' => 'Nov',
        'дек' => 'Dec',
    ];

    $date = str_ireplace(
        array_keys($months),
        array_values($months),
        $date
    );

    if (strlen($date) < 11) {
        $date .= ' ' . date('Y');
    }
    $timestamp = strtotime($date);
    $date = date('Y-m-d H:i:s', $timestamp);

    addNews($date, $title, $url);

    echo $dom->saveHTML($tr) . "<br>";
}
