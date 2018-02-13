<?php

class TOGoS_PHPipeable_PipelineTest extends TOGoS_SimplerTest_TestCase
{
	public function testSomeFilters() {
		$collector = new TOGoS_PHPipeable_Collector();
		
		$filter1 = new TOGoS_PHPipeable_Filter(function($thing) {
			if( $thing == 'foo' ) return null; // No foos allowed!
			return "prefix1:$thing";
		}, true);
		$filter2 = new TOGoS_PHPipeable_Filter(function($thing) {
			return "prefix2:$thing";
		}, true);
		$filter3 = new TOGoS_PHPipeable_Filter(function($thing) {
			return "prefix3:$thing";
		}, true);
		$pipeline = TOGoS_PHPipeable_Pipeline::create(array(
			$filter1, $filter2, $filter3
		), true);

		$pipeline->pipe($collector);
		$pipeline->open(array('oscar'=>'I love trash!'));
		$pipeline->item("foo");
		call_user_func($pipeline, "bar"); // Make sure #__invoke works as an alias to #item!
		$pipeline->item("baz");
		$result = $pipeline->close(array('fonzie'=>'Ehhh!'));
		
		$this->assertEquals( array(
			'openInfo' => array('oscar'=>'I love trash!'),
			'items' => array('prefix3:prefix2:prefix1:bar','prefix3:prefix2:prefix1:baz'),
			'closeInfo' => array('fonzie'=>'Ehhh!'),
		), $result );
	}

	public function testNoop() {
		$collector = new TOGoS_PHPipeable_Collector();
		
		$pipeline = TOGoS_PHPipeable_Pipeline::create(array(
		), true);

		$pipeline->pipe($collector);
		$pipeline->open(array('oscar'=>'I love trash!'));
		$pipeline->item("foo");
		call_user_func($pipeline, "bar"); // Make sure #__invoke works as an alias to #item!
		$pipeline->item("baz");
		$result = $pipeline->close(array('fonzie'=>'Ehhh!'));
		
		$this->assertEquals( array(
			'openInfo' => array('oscar'=>'I love trash!'),
			'items' => array('foo','bar','baz'),
			'closeInfo' => array('fonzie'=>'Ehhh!'),
		), $result );
	}
}
