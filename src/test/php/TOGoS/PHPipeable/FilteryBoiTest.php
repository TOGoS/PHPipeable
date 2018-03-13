<?php

class TOGoS_PHPipeable_FilteryBoiTest extends TOGoS_SimplerTest_TestCase
{
	public function testAFilter() {
		$collector = new TOGoS_PHPipeable_Collector(array(
			TOGoS_PHPipeable_Collector::OPT_COLLECT_ITEM_METADATA => true
		));
		$filter = new TOGoS_PHPipeable_Filter(function($thing) {
			if( $thing == 'foo' ) return null; // No foos allowed!
			return "prefix:$thing";
		}, true);
		$filter->pipe($collector);
		$filter->item("foo", array('lineNumber'=>11));
		call_user_func($filter, "bar", array('lineNumber'=>22)); // Make sure #__invoke works as an alias to #item!
		$filter->item("baz", array('lineNumber'=>33));
		$result = $filter->close();
		
		$this->assertEquals( array(
			'openInfo' => array(),
			'items' => array('prefix:bar','prefix:baz'),
			'itemMetadata' => array(array('lineNumber'=>22),array('lineNumber'=>33)),
			'closeInfo' => array(),
		), $result );
	}
}
