<?php

namespace eContext\File\Format;

class Csv implements FormatInterface {
	
	private $delimiter;
	private $enclosure;
	
	public function __construct($delimiter=",", $enclosure='"') {
		$this->delimiter = $delimiter;
		$this->enclosure = $enclosure;
	}
	
	public function write($file_handler, $data, $length=null) {
		if($this->enclosure === null) {
			fwrite($file_handler, implode($this->delimiter, $data).PHP_EOL);
		} else {
			fputcsv($file_handler, $data, $this->delimiter, $this->enclosure);
		}
	}
	
	public function readline($file_handler, $length=null) {
		if($length === null) {
			$length = 0;
		}
		if($this->enclosure === null) {
			return fgetcsv($file_handler, $length, $this->delimiter);
		} else {
			return fgetcsv($file_handler, $length, $this->delimiter, $this->enclosure);
		}
	}
}
