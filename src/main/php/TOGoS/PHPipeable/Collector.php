<?php

/**
 * A sink that just collects everything sent to it
 * and returns it all in 'items' from close().
 */
class TOGoS_PHPipeable_Collector implements TOGoS_PHPipeable_Sink
{
	public $openInfo;
	public $items;
	public $closeInfo;
	
	public function open(array $whatever=array()) {
		$this->openInfo = $whatever;
	}
	
	public function item($item, array $whatever=array()) {
		$this->items[] = $item;
	}
	
	public function close(array $whatever=array()) {
		$this->closeInfo = $whatever;
		return array(
			'openInfo' => $this->openInfo,
			'closeInfo' => $this->closeInfo,
			'items' => $this->items
		);
	}
}
