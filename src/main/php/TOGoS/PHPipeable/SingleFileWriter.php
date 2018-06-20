<?php

class TOGoS_PHPipeable_SingleFileWriter
extends TOGoS_PHPipeable_FileWriter
{
	protected $filename;
	public function __construct($filename) {
		$this->filename = $filename;
	}
	
	/** @override */
	protected function getFile(array $metadata) {
		return $this->filename;
	}
}
