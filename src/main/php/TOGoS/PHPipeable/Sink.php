<?php

interface TOGoS_PHPipeable_Sink
{
	function open(array $fileInfo=array());
	function item($item, array $metadata=array());
	function close(array $info=array());
	// __invoke(...) should be like item(...)
}
