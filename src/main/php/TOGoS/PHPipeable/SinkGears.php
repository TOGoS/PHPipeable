<?php

trait TOGoS_PHPipeable_SinkGears
{
	public function __invoke() {
		$args = func_get_args();
		call_user_func_array(array($this,$this->item), $args);
	}
}
