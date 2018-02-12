<?php

/**
 * Abstract base class for filters.
 * Provides default implementations for close, open, item
 * that just forward to piped-to sinks.
 */
abstract class TOGoS_PHPipeable_AbstractFilter
extends TOGoS_PHPipeable_BasePipeable
implements TOGoS_PHPipeable_Sink
{
	use TOGoS_PHPipeable_SinkGears;

	public function item($item, array $metadata=array()) {
		$this->emitItem($value, $metadata);
	}
	public function open(array $fileInfo=array()) {
		$this->emitOpen($fileInfo);
	}
	public function close(array $metadata=array()) {
		return $this->emitClose($metadata);
	}
}
