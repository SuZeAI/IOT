<?php
class Database {
    private static $dbName = 'thltm'; // Example: private static $dbName = 'myDB';
    private static $dbHost = 'localhost'; // Example: private static $dbHost = 'localhost';
    private static $dbUsername = 'tuyen'; // Example: private static $dbUsername = 'myUserName';
    private static $dbUserPassword = '0896023142@Tt'; // // Example: private static $dbUserPassword = 'myPassword';
     
    private static $cont  = null;

    public function __construct() {
        die('Init function is not allowed');
    }

    public static function connect() {
        // Một kết nối duy nhất cho toàn bộ ứng dụng
        if (null == self::$cont) {     
            try {
                self::$cont = new PDO("mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword); 
            } catch(PDOException $e) {
                die($e->getMessage()); 
            }
        }
        return self::$cont;
    }

    public static function disconnect() {
        self::$cont = null;
    }
}
?>