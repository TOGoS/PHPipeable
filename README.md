# PHPipeable

Provides a lightweight framework for push-based stream processing.

```
$someDataSource = new SomethingThatImplementsPipeable();
$someDataSink = new SomethingThatImplementsSink();
$someDataSource->pipe($someDataSink);

// Our pipe system is now in place,
// so shove some data through:

$someDataSource->emitSomeStuff(); // Use imagination here
$results = $someDataSource->emitClose(); // Let everyone know that's all,
                                         // and collect feedback from sinks
```

## Philosophical notes about the Sink interface

For reference:

```
interface TOGoS_PHPipeable_Sink
{
	function open(array $fileInfo=array());
	function item($item, array $metadata=array());
	function close(array $info=array());
	// __invoke(...) should be like item(...)
}
```

An alterntive approach would be to have an even simpler definition for the sink interface such as 'a 1-ary function'.
Open and close events would be passed to the function using the same mechanism as data items
(this is how <a href="https://github.com/EarthlingInteractive/PHPLogging">EIT's logging framework</a> is defined).

A disadvantage to that approach is that your data items then need to be wrapped in some kind of object
to distinguish them from stream metadata, which adds complexity to the code that implements and
uses the sink.

The more complex sink interface maps more nicely to the use cases that I'm concerned with today,
which are importing and transforming CSV files.
Breaking out open(...) item(...) and end(...) calls gives some guidance about the
meaning of metadata and the order in which they should be called,
freeing implementing classes from having to define those things.

```close(...)``` marks a point at which to commit any changes and is a convenient way to
get information from deeply nested sinks back to the caller.
