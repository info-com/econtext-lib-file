<?php

/**
 * Defines an abstract class for creating File handlers.
 */

namespace eContext\File\Format;

interface FormatInterface {
	
	/**
	 * Writes a single line (assumes a linebreak at the end).  Depending on the
	 * specific implementation, $data could be an array or a string.
	 * 
	 * @var resource $file_handler A file handler to write to
	 * @var mixed $data A string or array to write to the file
	 * @var int $length Total data we should write
	 */
	public function write($file_handler, $data, $length=null);
	
	/**
	 * Read a single line (assumes a linebreak at the end).
	 * 
	 * @var resource $file_handler A file handler to read from
	 * @var int $length Total data we should read
	 */
	public function readline($file_handler, $length=null);
}
