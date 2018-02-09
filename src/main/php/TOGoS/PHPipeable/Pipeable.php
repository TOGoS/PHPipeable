<?php

/**
 * Anything that can be told at runtime
 * (after construction) to pipe to somewhere.
 * If pipe is called multiple times on a data source,
 * any emitted things should be sent to all destinations.
 */
interface TOGoS_PHPipeable_Pipeable
{
	/** @return an identifier for this connection, which can be used to disconnect later */
	function pipe(TOGoS_PHPipeable_Sink $sink);
	function unpipe($connectionId);
}
