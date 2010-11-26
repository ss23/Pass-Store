<?php

/**
 * MyPDO class file
 */

/**
 * The MyPDO class.
 *
 * Provides a caching of prepared statements, along with a helper connect method
 */
class MyPDO extends PDO {
	/**
	 * Construct the class
	 */
	public function __construct() {
		$dns = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;
		return parent::__construct($dns, DB_USER, DB_PASS);
	}

	/**
	 * Prepare a prepared statement with caching
	 *
	 * @param string $sql The SQL for the statment
	 * @param string $driveropts Driver Options
	 *
	 * @return PDOStatement The resulting prepared PDO Statement object
	 */
	public function prepare($sql, $driveropts = false) {
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

?>
