<?php

class TOGoS_PHPipeable_BasePipeableTest extends TOGoS_SimplerTest_TestCase
{
	public function testPipePipePipe() {
		$collector = new TOGoS_PHPipeable_Collector();
		
		$filter1 = new TOGoS_PHPipeable_Filter(function($thing) {
			return "foo:$thing";
		}, true);
		$filter2 = new TOGoS_PHPipeable_Filter(function($thing) {
			return "bar:$thing";
		}, true);
		$filter3 = new TOGoS_PHPipeable_Filter(function($thing) {
			return "baz:$thing";
		}, true);

		$filter1->pipe($filter2)->pipe($filter3)->pipe($collector);
		$filter1->item("Hello, world!");
		
		$this->assertEquals( array(
			"baz:bar:foo:Hello, world!"
		), $collector->items );
	}
}
