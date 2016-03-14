<?php

namespace eContext\File\Format;

class Json implements FormatInterface {
	
	private $assoc;
	private $depth;
	private $read_options;
	private $write_options;
	
	/**
	 * Create a new JSON Format object.  JSON Format works on a line-by-line 
	 * basis, meaning that each line is a distinct JSON object, but the file as 
	 * a whole is not valid JSON.
	 * 
	 * @param bool $assoc When TRUE, returned objects will be converted into associative arrays.
	 * @param int $depth User specified recursion depth.
	 * @param int $read_options Bitmask of JSON decode options. Currently only JSON_BIGINT_AS_STRING is supported (default is to cast large integers as floats)
	 * @param int $write_options Bitmask consisting of JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_NUMERIC_CHECK, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT, JSON_UNESCAPED_UNICODE. The behaviour of these constants is described on the JSON constants page.
	 */
	public function __construct($assoc=false, $depth=512, $read_options=0, $write_options=0) {
		$this->assoc = $assoc;
		$this->depth = $depth;
		$this->read_options = $read_options;
		$this->write_options = $write_options;
	}
	
	public function write($file_handler, $data, $length=null) {
		fwrite($file_handler, json_encode($data).PHP_EOL);
	}
	
	public function readline($file_handler, $length=null) {
		$json = ($length == null) ? fgets($file_handler) : fgets($file_handler, $length);
		return json_decode($json, $this->assoc, $this->depth, $this->read_options);
	}
}
