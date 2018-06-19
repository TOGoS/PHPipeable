<?php

class TOGoS_PHPipeable_FileTest
extends TOGoS_SimplerTest_TestCase
{
	public function testSourceFile() {
		$actualContent = file_get_contents(__FILE__);
		$this->assertTrue( strlen($actualContent) > 0 );
		$this->assertEquals( "<?php", substr($actualContent, 0, 5) );
		
		$collector = new TOGoS_PHPipeable_Collector();
		$result = TOGoS_PHPipeable_File::sourceFile(__FILE__, $collector);
		$sourcedContent = implode("", $result['items']);
		$this->assertEquals($actualContent, $sourcedContent);
	}
}
