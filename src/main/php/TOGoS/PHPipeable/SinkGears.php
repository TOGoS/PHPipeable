<?php

trait TOGoS_PHPipeable_SinkGears
{
	public function __invoke($item, array $metadata=array()) {
		$this->item($item, $metadata);
	}
}
