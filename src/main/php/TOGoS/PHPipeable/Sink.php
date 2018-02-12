<?php

interface TOGoS_PHPipeable_Sink
{
	// Standard metadata keys
	const MD_FILENAME = 'filename';
	const MD_LINE_NUMBER = 'lineNumber';
	const MD_COLUMN_NUMBER = 'columnNumber';
	const MD_ERRORS = 'errors'; // List of error objects that may be returned by close(...)

	/**
	 * Will be called before item()
	 */
	function open(array $fileInfo=array());
	/**
	 * Process a data item.  Return value is ignored.
	 * Non-fatal errors should be logged internally and returned
	 * from close() rather than thrown, as throwing an exception will usually crash
	 * the whole pipeline.
	 */
	function item($item, array $metadata=array());
	/**
	 * Commit any changes, as item will not be called again.
	 * @return array of arbitrary information about processing that has been done since open()
	 */
	function close(array $info=array());
	// __invoke(...) should act as an alias for item(...)
}
