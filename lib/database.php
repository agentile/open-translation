<?php # vim:ts=4:sw=4:et:
class OT_DB {
    static $db = null;

    public function __construct()
    {
        self::get();
    }

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

    public function fetchPageTranslation($page, $locale_code, $text)
    {
        $db = self::$db;
        $sql = 'SELECT ott.translation_id, ott.locale_code, ott.page, ott.original, ott.translation,
                otv.vote_id, otv.result
                FROM ot_translations as ott
                LEFT JOIN ot_votes as otv ON (ott.translation_id = otv.translation_id)
                WHERE page = :page AND locale_code = :lc AND original = :text';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':page', $page);
        $stmt->bindValue(':lc', $locale_code);
        $stmt->bindValue(':text', $text);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
