<?php
/**
 * DbException
 * @see SpException
 */
class DbException extends SpException
{
	protected $sql = '';
    public function __construct($sql='') {
        $this->sql = $sql;
        $this->traceMessage = $this->getTraceMessage();
        $errsql = ($sql!='') ? ' ['.$sql.']' : '';
        parent::__construct(mysqli_connect_error().$errsql, mysqli_connect_errno());
    }
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
    public function getStackTrace() {
		$msg = "ErrorMessage : ".$this->message."\n";
		if ($this->code != 0) {
			$msg .= "ErrorCode : ".$this->code."\n";
		}
		if ($this->sql != '') $msg .= "Sql : ".$this->sql."\n";
		$msg .= $this->traceMessage;
		return $msg;
    }
}
?>