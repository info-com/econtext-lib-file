<?php

namespace eContext\File\Format;

class Text implements FormatInterface {
	
	public function write($file_handler, $data, $length=null) {
		if($length !== null) {
			return fwrite($file_handler, $data.PHP_EOL, $length);
		}
		return fwrite($file_handler, $data.PHP_EOL);
	}
	
	public function readline($file_handler, $length=null) {
		if($length == null) {
			return fgets($file_handler);
		}
		return fgets($file_handler, $length);
	}
}
