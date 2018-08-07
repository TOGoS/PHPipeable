<?php

/**
 * A sink that requires open(), item()..., close() to be called in exactly that order,
 * if at all.
 */
class TOGoS_PHPipeable_ModalSink
implements TOGoS_PHPipeable_Sink
{
	protected $state = 'unopened';
	protected $errors = array();
	
	protected function ensureState($expectedState, $method) {
		if( $this->state !== $expectedState ) {
			throw new Exception(
				get_class($this)."#$method called when in {$this->state} state; ".
				"must be in $expectedState state first"
			);
		}
	}
	
	protected function changeState($toState, $fromState, $method) {
		if( $this->state !== $fromState ) {
			throw new Exception(
				get_class($this)."#$method attempted invalid transition from {$this->currentState} to $toState; ".
				"must be in $fromState first"
			);
		}
		$this->state = $toState;
	}
	
	protected function errored(array $errors) {
		$this->errors = $errors;
		$this->state = "errored";
	}
	
	/** @override */
	public function open(array $metadata=array()) {
		$this->changeState('open', 'unopened', 'open');
	}
	
	/** @override */
	public function item($item, array $metadata=array()) {
		$this->ensureState('open', 'item');
	}

	/** @override */
	public function close(array $metadata=array()) {
		$this->changeState('closed', 'open', 'close');
		return array();
	}
}
