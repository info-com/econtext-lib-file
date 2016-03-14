<?php

namespace eContext\File;
use eContext\File\Format\FormatInterface;

/**
 * An OO named temp file
 * 
 * @author Jonathan Spalink <jspalink@info.com>
 */
class Temp extends File {
	
	protected $temp_dir;
	private $auto_delete;
	
	/**
	 * Create a new eContext File Temp object.
	 * 
	 * @param string $mode File mode to open temp file with
	 * @param string $temp_dir Location of the temporary directory
	 * @param string $prefix Filename prefix for temp files
	 * @param bool $auto_delete Automatically delete temp files
	 */
	public function __construct(FormatInterface $format=null, $mode="c+", $temp_dir=null, $prefix="zenya", $auto_delete=false) {
		if($temp_dir == null) {
			$temp_dir = sys_get_temp_dir();
		}
		$this->auto_delete = $auto_delete;
		$this->temp_dir = $temp_dir;
		$filepath = tempnam($temp_dir, $prefix);
		return parent::__construct($filepath, $format, $mode);
	}
	
	/**
	 * Delete the file if we're autodeleting
	 */
	public function __destruct() {
		parent::__destruct();
		if($this->auto_delete) {
			$this->unlink();
		}
	}
	
}
