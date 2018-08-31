<?php
/**
 * PathBuilder
 */
class PathBuilder
{
	/**
	 * @var String
	 */
	private $pathStr = '';

	/**
	 * @var boolean
	 */
	private $autoMakeDir = true;

	/**
	 * @var String
	 */
	private $separator = '/';

	/**
	 * PathBuilder
	 * @param String $path
	 * @param boolean $autoMakeDir
	 * @return PathBuilder
	 */
	function PathBuilder($path='', $autoMakeDir=true) {
		$this->pathStr = str_replace('\\', $this->separator, $path);
		$this->autoMakeDir = $autoMakeDir;
		if ($this->autoMakeDir) $this->mkDirAll();
	}

	/**
	 * append
	 * @param String $dir
	 */
	public function append($dir) {
		if ($this->pathStr != '') $this->pathStr .= $this->separator;
		$this->pathStr .= $dir;
		if ($this->autoMakeDir) $this->mkDir();
	}

	/**
	 * isDir
	 * @return boolean
	 */
	public function isDir() {
		return $this->_isDir($this->pathStr);
	}

	/**
	 * isFile
	 * @return boolean
	 */
	public function isFile() {
		return $this->_isFile($this->pathStr);
	}

	/**
	 * toString
	 * @return String
	 */
	public function toString() {
		return $this->pathStr;
	}

	/**
	 * mkDir
	 * @return boolean
	 */
	public function mkDir() {
		if ($this->_isDir($this->pathStr) === false) {
			return mkdir($this->pathStr);
		}
		return true;
	}

	/**
	 * mkDirAll
	 * @return boolean
	 */
	public function mkDirAll() {
		$pathAry = explode($this->separator, $this->pathStr);
		$len = count($pathAry);
		$path = '';
		for ($i=0; $i<$len; $i++) {
			$dir = $pathAry[$i];
			$path .= $dir.$this->separator;
			if ($i == 0 || $dir == '') continue;
			if ($this->_isDir($path) === false) {
				if (mkdir($path) == false) return false;
			}
		}
		return true;
	}

	/**
	 * _isDir
	 * @param String $path
	 * @return boolean
	 */
	private function _isDir($path) {
		return (file_exists($path) && is_dir($path));
	}

	/**
	 * _isFile
	 * @param String $path
	 * @return boolean
	 */
	private function _isFile($path) {
		return (file_exists($path) && is_file($path));
	}
}
?>