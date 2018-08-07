<?php

class TOGoS_PHPipeable_NestedOpenIgnorer
extends TOGoS_PHPipeable_AbstractFilter
{
	protected $nestedness = 0;

	public function open(array $fileInfo=array()) {
		if( $this->nestedness++ == 0 ) {
			$this->emitOpen($fileInfo);
		}
	}
	public function close(array $metadata=array()) {
		if( --$this->nestedness == 0 ) {
			return $this->emitClose($metadata);
		}
		if( $this->nestedness < 0 ) {
			throw new Exception("Uh oh, close() got called more times than open()");
		}
		return array();
	}	
}
