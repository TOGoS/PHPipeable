<?php

/**
 * item(...) accepts chunks of data
 * but breaks them into lines.
 */
class TOGoS_PHPipeable_Lineifier
extends TOGoS_PHPipeable_AbstractFilter
{
	protected $lineNumber;

	public function __construct() {
		$this->reset();
	}
	
	protected function reset() {
		$this->lineNumber = 1;
		$this->buffer = "";
	}
	
	public function open(array $metadata=array()) {
		$this->reset();
		parent::open($metadata);
	}
	
	protected function emitLine($line, array $info) {
		$info['lineNumber'] = $this->lineNumber++;
		$this->emitItem($line, $info);
	}
	
	protected $buffer = "";
	public function item($bytes, array $info=array()) {
		$this->buffer .= $bytes;
		$this->buffer = str_replace("\r\n", "\n", $this->buffer);

		// In case this file uses bare CRs to delimit lines, any remaining CRs
		// (unless they are the last in the buffer, since there may be a following LF)
		// need to be translated to LF:
		$crCheckPos = 0;
		while( $crCheckPos < strlen($this->buffer)-1 ) {
			$nextCr = strpos($this->buffer, "\r", $crCheckPos);
			if( $nextCr === false or $nextCr == strlen($this->buffer)-1 ) break;
			
			$this->buffer[$nextCr] = "\n";
			$crCheckPos = $nextCr+1;
		}
		
		$lineStart = 0;
		while( ($nextLf = strpos($this->buffer,"\n",$lineStart)) !== false ) {
			$line = substr($this->buffer, $lineStart, $nextLf-$lineStart);
			$this->emitLine($line, $info);
			$lineStart = $nextLf+1;
		}
		$this->buffer = substr($this->buffer, $lineStart);
	}
	
	public function close(array $info=array()) {
		if( !empty($this->buffer) ) $this->emitLine($this->buffer, $info);
		$this->reset();
		return $this->emitClose();
	}
}
