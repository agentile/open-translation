<?php # vim:ts=4:sw=4:et:
class OT_Database {
    static $db = null;

    public static function get()
    {
        if (self::$db == null) {
            $config = include 'config.php';
            $dsn = $config['database']['type'] 
                . ':dbname=' . $config['database']['name'] 
                . ';host=' . $config['database']['host'];
            $db = new PDO($dsn, $config['database']['user'], $config['database']['pass']);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$db = $db;
        }

        return self::$db;
    }
}
