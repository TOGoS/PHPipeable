<?php

class TOGoS_PHPipeable_BasePipeable implements TOGoS_PHPipeable_Pipeable
{
	protected $nextSinkNumber = 1;
	protected $sinks = array();
	
	protected function emitOpen(array $info=array()) {
		foreach( $this->sinks as $sink ) {
			$sink->open($info);
		}
	}
	protected function emitItem($item, array $metadata=array()) {
		foreach( $this->sinks as $sink ) {
			$sink->item($item, $metadata);
		}
	}
	protected function emitClose(array $info=array()) {
		$res = array();
		foreach( $this->sinks as $sink ) {
			$res = array_merge_recursive($res, $sink->close($info));
		}
		return $res;
	}
	
	public function pipe(TOGoS_PHPipeable_Sink $sink) {
		$sinkNumber = $this->nextSinkNumber++;
		$this->sinks[$sinkNumber] = $sink;
		return $sinkNumber;
	}
	
	public function unpipe($sinkNumber) {
		unset($this->sinks[$sinkNumber]);
	}
}
