<?php

class TOGoS_PHPipeable_File
{
	const MINUS_MEANS_STDIO = "minusMeansStdio";

	protected static function getOpt(array $options, $k, $defaultValue) {
		return isset($options[$k]) ? $options[$k] : $defaultValue;
	}
	
	public static function sourceFile($sourceFile, TOGoS_PHPipeable_Sink $sink, array $options=array()) {
		if( $sourceFile == '-' and self::getOpt($options, self::MINUS_MEANS_STDIO, false) ) {
			$sourceFile = "php://stdin";
		}
		
		$stream = @fopen($sourceFile, "rb");
		if( $stream === false ) {
			$err = error_get_last();
			throw new Exception("Failed to open $sourceFile: {$err['message']}");
		}

		$fileInfo = array(
			TOGoS_PHPipeable_Sink::MD_FILENAME => $sourceFile
		);
		
		$sink->open($fileInfo);
		while( ($data = fread($stream, 65536)) !== false && strlen($data) > 0 ) {
			$sink->item($data);
		}
		
		fclose($stream);
		return $sink->close($fileInfo);
	}
	
	public static function pipeToFile(TOGoS_PHPipeable_Pipeable $source, $destFile, array $options=array()) {
		if( $destFile == '-' and self::getOpt($options, self::MINUS_MEANS_STDIO, false) ) {
			$destFile = "php://stdout";
		}
		
		$source->pipe(new TOGoS_PHPipeable_SingleFileWriter($destFile, $options));
	}
}
