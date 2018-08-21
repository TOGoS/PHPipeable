<?php

class TOGoS_PHPipeable_File
{
	const MINUS_MEANS_STDIO = "minusMeansStdio";
	const RECURSE_INTO_DIRECTORIES = "recurseIntoDirectories";

	protected static function getOpt(array $options, $k, $defaultValue) {
		return isset($options[$k]) ? $options[$k] : $defaultValue;
	}
	
	public static function sourceFile($sourceFile, TOGoS_PHPipeable_Sink $sink, array $options=array()) {
		if( $sourceFile == '-' and self::getOpt($options, self::MINUS_MEANS_STDIO, false) ) {
			$sourceFile = "php://stdin";
		}
		
		if( is_dir($sourceFile) && self::getOpt($options, self::RECURSE_INTO_DIRECTORIES, false) ) {
			$filenames = @scandir($sourceFile);
			if( $filenames === false ) {
				$err = error_get_last();
				throw new Exception("Failed to scandir($sourceFile): {$err['message']}");				
			}
			natsort($filenames);
			$res = array();
			foreach( $filenames as $fn ) {
				if( $fn[0] == '.' ) continue;
				$res = array_merge_recursive($res, self::sourceFile("$sourceFile/$fn", $sink, $options));
			}
			return $res;
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
			$sink->item($data, $fileInfo);
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
