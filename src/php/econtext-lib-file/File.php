<?php

namespace eContext\File;
use eContext\File\Format\FormatInterface;
use eContext\File\Format\Text;

/**
 * An OO file object
 * 
 * @author Jonathan Spalink <jspalink@info.com>
 */
class File {
	
	protected $filepath;
	protected $file;
	protected $format;
	
	private $open;
	private $mode;
	private $unlinked;
	
	/**
	 * Create a new File object.
	 * 
	 * @param string $filepath File path to open
	 * @param FormatInterface $format The format to use for reading/writing.  If null, a default Text formatter will be used
	 * @param string $mode File mode to open temp file with
	 */
	public function __construct($filepath, FormatInterface $format=null, $mode="c+") {
		$this->open = false;
		$this->unlinked = false;
		$this->filepath = $filepath;
		$this->file = null;
		$this->open($mode);
		$this->setFormat($format);
	}
	
	public function setFormat(FormatInterface $format=null) {
		$this->format = $format == null ? new Text() : $format;
	}
	
	/**
	 * Close the file when we're done with it
	 */
	public function __destruct() {
		if($this->isOpen()) {
			$this->close();
		}
	}
	
	/**
	 * Is the file open?
	 * 
	 * @return boolean
	 */
	public function isOpen() {
		return $this->open;
	}
	
	public function exists() {
		return !$this->unlinked;
	}
	
	/**
	 * Is the file writable?
	 * 
	 * @return boolean
	 */
	public function isWritable() {
		$m = substr($this->mode, 0, 1);
		return in_array($m, array("a", "c", "w", "x"));
	}
	
	/**
	 * Is the file readable?
	 * 
	 * @return boolean
	 */
	public function isReadable() {
		$m = substr($this->mode, 0, 1);
		$p = strlen($this->mode) > 1 ? substr($this->mode, 1, 2) : "";
		return ($m == "r" || $p == "+");
	}
	
	/**
	 * Throw an exception if the file is not open
	 * 
	 * @throws Exception
	 */
	private function assertOpen() {
		if(!$this->isOpen()) {
			throw new \Exception("File {$this->filepath} is not open");
		}
	}
	
	/**
	 * Throw an exception if the file is not writeable
	 * 
	 * @throws Exception
	 */
	private function assertWritable() {
		if(!$this->isOpen() || !$this->isWritable()) {
			throw new \Exception("File {$this->filepath} is not writable");
		}
	}
	
	/**
	 * Throw an exception if the file is not readable
	 *
	 * @throws Exception
	 */
	private function assertReadable() {
		if(!$this->isOpen() || !$this->isReadable()) {
			throw new \Exception("File {$this->filepath} is not readable");
		}
	}
	
	/**
	 * Return the path file
	 */
	public function getFilepath() {
		return $this->filepath;
	}
	
	/**
	 * Write the string to file
	 * 
	 * @param string $string Content to write to file
	 * @param mixed $length If the length argument is given, writing will stop after length bytes have been written or the end of string is reached, whichever comes first
	 * @see http://us3.php.net/manual/en/function.fwrite.php
	 */
	public function write($string, $length=null) {
		$this->assertWritable();
		return $this->format->write($this->file, $string, $length);
	}
	
	/**
	 * Read a line from the file
	 * 
	 * @param int $length If specified, read this many bytes
	 * @see http://www.php.net/manual/en/function.fgets.php
	 */
	public function readline($length=null) {
		$this->assertReadable();
		return $this->format->readline($this->file, $length);
	}
	
	/**
	 * Open a file
	 * 
	 * @param string $mode Filemode to use when opening the file.
	 * @see http://us3.php.net/manual/en/function.fopen.php
	 */
	public function open($mode="c") {
		$this->mode = $mode;
		$this->file = fopen($this->filepath, $mode);
		if($this->file === false) {
			$this->mode = null;
			$this->file = null;
			$this->open = false;
			throw new \Exception("Unable to open file {$this->filepath}");
		}
		$this->open = true;
		return true;
	}
	
	/**
	 * Close the file
	 * 
	 * @return boolean Successfully closed the file
	 */
	public function close() {
		$result = fclose($this->file);
		if($result == true) {
			$this->open = false;
			return true;
		}
		return false;
	}
	
	/**
	 * Return the pointer to the beginning of the file
	 * 
	 * @return boolean Successfully rewound the file
	 */
	public function rewind() {
		$this->close();
		return $this->open($this->mode);
	}
	
	/**
	 * Remove the file from the filesystem
	 * 
	 * @return boolean Successfully deleted the file
	 */
	public function unlink() {
		if($this->isOpen()) {
			$this->close();
		}
		if($this->exists()) {
			if(unlink($this->filepath)) {
				$this->unlinked = true;
			}
		}
	}
	
}
