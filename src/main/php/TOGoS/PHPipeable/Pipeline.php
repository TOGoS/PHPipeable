<?php

/**
 * A sink&pipeable composed of a series of sink&pipeables ('stages').
 * sinking data into the pipeline pushes it into the first stage,
 * and piping pipes from the last stage.
 */
class TOGoS_PHPipeable_Pipeline
implements TOGoS_PHPipeable_Sink, TOGoS_PHPipeable_Pipeable
{
	protected $firstStage;
	protected $lastStage;
	
	protected function __construct( array $stages, $autoConnect=true ) {
		if( count($stages) == 0 ) {
			throw new Exception("Trying to create zero-stage pipeline");
		}
		$isFirst = true;
		$prevStage = null;
		foreach( $stages as $stage ) {
			if( $isFirst ) {
				$this->firstStage = $stage;
				$isFirst = false;
			} else {
				if( $autoConnect ) $prevStage->pipe($stage);
			}
			$prevStage = $stage;
		}
		$this->lastStage = $prevStage;
	}
	
	public static function create( array $stages, $autoPipe=true ) {
		if( count($stages) == 0 ) return new TOGoS_PHPipeable_Pipe();
		
		return new self($stages, $autoPipe);	
	}

	//// Pipeable
	
	public function pipe(TOGoS_PHPipeable_Sink $sink) {
		$this->lastStage->pipe($sink);
	}
	
	public function unpipe($sinkNumber) {
		$this->lastStage->unpipe($sink);
	}
	
	//// Sink
	
	function open(array $fileInfo=array()) {
		return $this->firstStage->open($fileInfo);
	}
	function item($item, array $metadata=array()) {
		return $this->firstStage->item($item, $metadata);
	}
	function close(array $info=array()) {
		return $this->firstStage->close($info);
	}
	public function __invoke( $item, array $metadata=array() ) {
		$this->item($item, $metadata);
	}
}
