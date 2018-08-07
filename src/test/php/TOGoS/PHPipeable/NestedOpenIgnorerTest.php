<?php

class TOGoS_PHPipeable_NestedOpenIgnorerTest
extends TOGoS_SimplerTest_TestCase
{
	public function testThatModalSinkDoesntExcept() {
		$modalSink = new TOGoS_PHPipeable_ModalSink();
		$nestedOpenIgnorer = new TOGoS_PHPipeable_NestedOpenIgnorer();
		$nestedOpenIgnorer->pipe($modalSink);
		$nestedOpenIgnorer->open();
		$nestedOpenIgnorer->open();
		$nestedOpenIgnorer->open();
		$nestedOpenIgnorer->item("Hi");
		$nestedOpenIgnorer->close();
		$nestedOpenIgnorer->open();
		$nestedOpenIgnorer->item("Hi again");
		$nestedOpenIgnorer->close();
		$nestedOpenIgnorer->close();
		$nestedOpenIgnorer->close();
	}
}
