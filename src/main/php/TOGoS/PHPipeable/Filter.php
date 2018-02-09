<?php

class TOGoS_PHPipeable_Filter
extends TOGoS_PHPipeable_BasePipeable
implements TOGoS_PHPipeable_Sink
{
	use TOGoS_PHPipeable_SinkGears;

	protected $filterCallback;
	protected $dropNulls;
	/**
	 * If $dropNulls is true,
	 * then nulls returned by $filterCallback
	 * will be 'dropped on the floor' rather than emitted.
	 */
	public function __construct($filterCallback, $dropNulls=false) {
		$this->filterCallback = $filterCallback;
		$this->dropNulls = $dropNulls;
	}
	public function item($item, array $metadata=array()) {
		$value = call_user_func($this->filterCallback,$item,$metadata);
		if( $value === null && $this->dropNulls ) return;
		$this->emitItem($value);
	}
	public function open(array $fileInfo=array()) {
		$this->emitOpen($fileInfo);
	}
	public function close(array $metadata=array()) {
		return $this->emitClose($metadata);
	}
}
