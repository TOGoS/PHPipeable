# PHPipeable

Pipe things to each other and then sit back while your data flows.

```
$someDataSource = new SomethingThatImplementsPipeable();
$someDataSink = new SomethingThatImplementsSink();
$someDataSource->pipe($someDataSink);

// Our pipe system is now in place,
// so shove some data through:

$someDataSource->emitSomeStuff(); // Use imagination here
$results = $someDataSource->close(); // Let everyone know that's all
```
