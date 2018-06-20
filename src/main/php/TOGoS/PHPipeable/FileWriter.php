<?php

abstract class TOGoS_PHPipeable_FileWriter
extends TOGoS_PHPipeable_ModalSink
{
	/** Return name of the file that should be written-to */
	protected abstract function getFile(array $metadata);

	protected $writeStream = null;
	
	protected function mkParentDirs($filename) {
		$dirname = dirname($filename);
		if( !is_dir($dirname) ) mkdir($dirname, 0777, true);
	}
	
	protected $filename;
	
	/** @override */
	public function open(array $metadata=array()) {
		$this->filename = $filename = $this->getFile($metadata);
		$this->mkParentDirs($filename);
		$this->writeStream = @fopen($filename, "wb");
		if( $this->writeStream === false ) {
			$error = error_get_last();
			$message = "Error opening $filename for writing: ".$error['message'];
			$this->errored(array(array('message' => $message)));
			throw new Exception($message);
		}
		parent::open($metadata);
	}
	
	/** @override */
	public function item($data, array $metadata=array()) {
		parent::item($data, $metadata);
		fwrite($this->writeStream, $data);
	}

	/** @override */
	public function close(array $metadata=array()) {
		parent::close($metadata);
		fclose($this->writeStream);
		return array(
			'targetFilename' => $this->filename
		);
	}
}
