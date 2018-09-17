<?php

/** Pipeable implementations can return one of these from pipe() so
 * that callers can pipe(x)->pipe(y)->... */
class TOGoS_PHPipeable_PipeHandle
{
	protected $id;
	protected $target;
	public function __construct($id, $target) {
		$this->id = $id;
		$this->target = $target;
	}
	public function pipe(TOGoS_PHPipeable_Sink $sink) {
		return $this->target->pipe($sink);
	}
	public function __toString() {
		return (string)$this->id;
	}
}
