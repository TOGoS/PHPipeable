<?php

class TOGoS_PHPipeable_FilteryBoiTest extends TOGoS_SimplerTest_TestCase
{
	public function testAFilter() {
		$collector = new TOGoS_PHPipeable_Collector();
		$filter = new TOGoS_PHPipeable_Filter(function($thing) {
			if( $thing == 'foo' ) return null; // No foos allowed!
			return "prefix:$thing";
		}, true);
		$filter->pipe($collector);
		$filter->item("foo");
		$filter->item("bar");
		$filter->item("baz");
		$result = $filter->close();
		
		$this->assertEquals( array(
			'openInfo' => array(),
			'items' => array('prefix:bar','prefix:baz'),
			'closeInfo' => array(),
		), $result );
	}
}
