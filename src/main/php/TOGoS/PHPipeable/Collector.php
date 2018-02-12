<?php

/**
 * A sink that just collects everything sent to it
 * and returns it all in 'items' from close().
 */
class TOGoS_PHPipeable_Collector implements TOGoS_PHPipeable_Sink
{
	const OPT_COLLECT_ITEM_METADATA = 'collectItemMetadata';
	
	public $openInfo;
	public $items;
	public $closeInfo;
	
	protected $collectItemMetadata;
	
	public function __construct(array $options=array()) {
		$this->collectItemMetadata = !empty($options[self::OPT_COLLECT_ITEM_METADATA]);
	}
	
	public function open(array $whatever=array()) {
		$this->openInfo = $whatever;
		$this->items = array();
		$this->itemMetadata = array();
	}
	
	public function item($item, array $whatever=array()) {
		$this->items[] = $item;
		if( $this->collectItemMetadata ) $this->itemMetadata[] = $whatever;
	}
	
	public function close(array $whatever=array()) {
		$this->closeInfo = $whatever;
		$ret = array(
			'openInfo' => $this->openInfo,
			'closeInfo' => $this->closeInfo,
			'items' => $this->items
		);
		if( $this->collectItemMetadata ) $ret['itemMetadata'] = $this->itemMetadata;
		return $ret;
	}
}
