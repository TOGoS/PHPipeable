<?php

class TOGoS_PHPipeable_LineifierTest extends TOGoS_SimplerTest_TestCase
{
	public function testLineifier() {
		$collector = new TOGoS_PHPipeable_Collector(array(
			TOGoS_PHPipeable_Collector::OPT_COLLECT_ITEM_METADATA=>true
		));
		$lineifier = new TOGoS_PHPipeable_Lineifier();
		$lineifier->pipe($collector);
		
		$lineifier->open();
		$lineifier->item("line ");
		$lineifier->item("1\n");
		$lineifier->item("line 2\r");
		$lineifier->item("\nline 3\r");
		$lineifier->item("line 4\rline 5");
		$result1 = $lineifier->close();

		$lineifier->open();
		$lineifier->item("fine 1\nfine 2\r");
		$lineifier->item("fine 3\r");
		$lineifier->item("\nfine 4\r");
		$lineifier->item("\nfine 5\n");
		$result2 = $lineifier->close();
		
		$lineifier->open();
		$lineifier->item("nine 1\rnine 2\nnine 3\rnine 4\r\nnine 5\r\n");
		$result3 = $lineifier->close();
		
		foreach( array(
			'line'=>$result1,
			'fine'=>$result2,
			'nine'=>$result3,
		) as $word=>$result ) {
			$this->assertEquals( 5, count($result['items']) );
			$this->assertEquals( 5, count($result['itemMetadata']) );
			for( $i=0; $i<5; ++$i ) {
				$lineNumber = $i+1;
				$this->assertEquals("$word $lineNumber", $result['items'][$i]);
				$this->assertEquals($lineNumber, $result['itemMetadata'][$i]['lineNumber']);
			}
		}
	}
}
