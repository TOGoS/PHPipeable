<?php

class TOGoS_PHPipeable_Filter
extends TOGoS_PHPipeable_AbstractFilter
{
	protected $filterCallback;
	protected $dropNulls;
	/**
	 * If $dropNulls is true,
	 * then nulls returned by $filterCallback
	 * will be 'dropped on the floor' rather than emitted.
	 */
	public function __construct($filterCallback, $options=false) {
		$this->filterCallback = $filterCallback;
		if( is_bool($options) ) $options = array(
			'dropNulls' => $options
		);
		$this->dropNulls = isset($options['dropNulls']) ? $options['dropNulls'] : false;
		$this->onError = isset($options['onError']) ? $options['onError'] : 'throw';
	}
	public function item($item, array $metadata=array()) {
		try {
			$value = call_user_func($this->filterCallback,$item,$metadata);
		} catch( Exception $e ) {
			switch( $this->onError ) {
			case 'skip':
				return;
			case 'pass':
				$this->emitItem(null, array(
					'errors' => array(
						array('message'=>"Exception while transforming", 'exception' => $e)
					),
				) + $metadata);
				return;
			default: // 'throw'
				throw $e;
			}
		}
		if( $value === null && $this->dropNulls ) return;
		$this->emitItem($value,$metadata);
	}
}
