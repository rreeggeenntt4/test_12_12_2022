<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bdconnect.php";

final class Init
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->create();
        $this->fill();
    }

    /**
     * create создает таблицу
     *
     * @return void
     */
    private function create()
    {
        global $mysqli;

        mysqli_query($mysqli, "CREATE TABLE IF NOT EXISTS `test` (
            id int(10) unsigned NOT NULL AUTO_INCREMENT,
            script_name char(25) DEFAULT NULL,
            start_time integer DEFAULT NULL,
            end_time integer DEFAULT NULL,
            result text DEFAULT NULL,
            PRIMARY KEY (id)
            )");
    }

    /**
     * fill заполняет таблицу случайными данными
     *
     * @return void
     */
    private function fill()
    {
        global $mysqli;
        // Количество добавляемых строк в таблицу
        $rows = 5;
        // Длина генерируемой строки
        $n = 25;

        $z = array("normal", "illegal", "failed", "success");

        $i = 0;
        while ($i < $rows) {
            $script_name = substr(md5(mt_rand()), 0, $n);
            $t = new DateTime();
            $start_time = $t->getTimestamp();
            $end_time = $t->getTimestamp();
            $result = $z[mt_rand(0, 3)];

            $sql = "INSERT INTO `test` (`script_name`, `start_time`,`end_time`,`result`) VALUES ('" . $script_name . "','" . $start_time . "','" . $end_time . "','" . $result . "')";
            $mysqli->query($sql);

            $i++;
        }
    }

    /**
     * get выбирает из таблицы test данные
     *
     * @return array
     */
    public function get()
    {
        global $mysqli;

        $sql = "SELECT * FROM `test` where `result` in ('normal', 'success')";
        $arr = $mysqli->query($sql);

        while ($rez = $arr->fetch_assoc()) {
            $all[] = $rez;
        }
        
        return $all;
    }
}

$test = new Init();

echo "<pre>";
print_r($test->get());
echo "</pre>";
