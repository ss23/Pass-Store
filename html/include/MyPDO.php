<?
class MyPDO extends PDO {
    public function __construct() {
        $dns = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;
       
        return parent::__construct($dns, DB_USER, DB_PASS);
    }
	
	public function prepare($sql, $driveropts = FALSE) {
		$hash = hash('sha1', $sql.$driveropts);
		if (isset($GLOBALS['cached_stmt'][$hash])) {
			return $GLOBALS['cached_stmt'][$hash];
		} else {
			if ($driveropts) {
				return $GLOBALS['cached_stmt'][$hash] = parent::prepare($sql, $driveropts);
			} else {
				return $GLOBALS['cached_stmt'][$hash] = parent::prepare($sql);
			}
		}
	}
}
