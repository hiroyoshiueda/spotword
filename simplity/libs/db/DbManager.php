<?php
/**
 * DbManager
 */
class DbManager {
	/**
	 * @var mysqli
	 */
	private $_con = null;
	private $_logger = null;
	private $_server = '';
	private $_schema = '';
	private $_user = '';
	private $_password = '';
	private $_charset = 'utf8';
	private $_lastQuery = '';
	private $_updateLines = 0;
	private $_clientCharset = '';
	function DbManager($logger=null) {
		$this->_logger = $logger;
	}
	public function setInfo($server, $schema, $user, $password, $charset=null)
	{
		$this->_server = $server;
		$this->_schema = $schema;
		$this->_user = $user;
		$this->_password = $password;
		if ($charset !== null) $this->_charset = $charset;
	}
	public function connect($iscon=true)
	{
		if ($iscon && $this->isConnection()) return;
		$this->_con = @new mysqli($this->_server, $this->_user, $this->_password, $this->_schema);
		if (mysqli_connect_error()) throw new DbException();
		// MySQL 5.0.7
		//if (is_resource($this->_con) && function_exists('mysql_set_charset')) mysql_set_charset($this->_charset, $this->_con);
		$this->_con->set_charset($this->_charset);
		$this->_logger->debug("'".$this->_schema."' is connection.");
	}
	public function connectConf($conf)
	{
		$this->setInfo($conf['db_server'], $conf['db_schema'], $conf['db_user'], $conf['db_password']);
		$this->connect();
	}
	public function setSchema($schema)
	{
		$this->_schema = $schema;
	}
	public function isConnection()
	{
		return ($this->_con !== null);
	}
	public function closeConnection() {
		if ($this->isConnection()) {
			$this->_con->close();
			$this->_logger->debug("'".$this->_schema."' is close connection.");
		}
		$this->_con = null;
	}
	public function executeQuery($query) {
		return $this->_query($query);
	}
	public function executeUpdate($query) {
		$i=0;
		while ($i<5) {
			try {
				$this->_query($query);
				break;
			} catch(Exception $e) {
				if (preg_match("/Lost connection to MySQL/i", $e->getMessage())) {
					$this->connect(false);
				} else {
					throw new DbException($query);
				}
			}
			$i++;
		}
		$this->_updateLines = $this->_con->affected_rows;
		$this->_logger->query("--> ".$this->_updateLines." rows");
		if ($this->_updateLines == -1) throw new DbException($query);
		return true;
	}
	public function executeToFile($filename)
	{
		$query = file_get_contents($filename);
		$qs = preg_split("/;\r?\n/", $query);
		foreach ($qs as $q) {
			$q = ltrim($q);
			if ($q!='') $this->_query($q);
		}
		return true;
	}
	public function getUpdateLines() {
		return $this->_updateLines;
	}
	public function getInt($query) {
		$result = $this->_query($query);
		$row = $result->fetch_row();
		$result->free();
		if ($row === null) return 0;
		return (int)$row[0];
	}
	public function getString($query) {
		$result = $this->_query($query);
		$row = $result->fetch_row();
		$result->free();
		if ($row === null) return null;
		return (string)$row[0];
	}
	public function getKeyValue($query) {
		$result = $this->_query($query);
		$ary = array();
		while ($row = $result->fetch_row()) {
			$ary[$row[0]] = $row[1];
		}
		$result->free();
		return $ary;
	}
	public function quote($str) {
		if ($str === null || $str === '') return $str;
		//return str_replace(array("\\", "'", "\0"), array("\\\\", "\\'", "\\\0"), $str);
		return $this->_con->real_escape_string($str);
	}
	public function getClientCharset() {
		$charset = $this->_con->character_set_name();
		switch ($charset) {
		case 'utf8': return 'UTF-8';
		case 'ujis': return 'EUC-JP';
		case 'sjis': return 'SJIS';
		}
		return $charset;
	}
	public function getLastQuery() {
		return $this->_lastQuery;
	}
	public function autoCommit($isauto=true) {
		$commit = ($isauto) ? 1 : 0;
		$this->connect();
		return $this->_query('SET AUTOCOMMIT='.$commit);
	}
	public function beginTransaction() {
		$this->connect();
		return $this->_query('BEGIN');
	}
	public function commit() {
		$this->connect();
		return $this->_query('COMMIT');
	}
	public function rollback() {
		$this->connect();
		return $this->_query('ROLLBACK');
	}
	public function renameTable($oldname,$newname) {
		return $this->_query("ALTER TABLE ${oldname} RENAME ${newname}");
	}
	public function addColumn($table,$colname,$dtype) {
		return $this->_query("ALTER TABLE ${table} ADD ${colname} ${dtype}");
	}
	public function delColumn($table,$colname) {
		return $this->_query("ALTER TABLE ${table} DROP ${colname}");
	}
	/**
	 * SHOW TABLES LIKE 'a%';
	 * +---------------------------+
	 * | Tables_in_catalog (a%)    |
	 * +---------------------------+
	 * | address_book              |
	 * | address_book_to_customers |
	 * | address_format            |
	 * +---------------------------+
	 * @param $like
	 * @return unknown_type
	 */
	public function getTables($like=null) {
		$sql = "SHOW TABLES";
		if ($like!='') $sql .= " LIKE '".$like."'";
		return $this->executeQuery($sql);
	}
	/**
	 * SHOW COLUMNS FROM address_book LIKE 'a%';
	 * +-----------------+------------------+-----------+-----+---------+----------------+
	 * | Field           | Type             | Null      | Key | Default | Extra          |
	 * +-----------------+------------------+-----------+-----+---------+----------------+
	 * | address_book_id | int(11) unsigned | YES or NO | PRI | 1       | auto_increment |
	 * +-----------------+------------------+-----------+-----+---------+----------------+
	 * @param $table
	 * @param $like
	 * @return unknown_type
	 */
	public function getColumns($table,$like=null) {
		$sql = "SHOW COLUMNS FROM ${table}";
		if ($like!='') $sql .= " LIKE '".$like."'";
		return $this->executeQuery($sql);
	}
	private function _query($query) {
		if (substr($query, -1) === ';') {
			$query = substr($query, 0, -1);
		}
		$this->_lastQuery = $query;
		$this->_updateLines = 0;
		$this->_logger->query($this->_lastQuery);
		$result = $this->_con->query($query);
		if ($result === false) throw new DbException($this->_lastQuery);
		$this->_logger->queryResult();
		return $result;
	}
}
?>