# flysystem-iterator

Provides a plugin that creates an Iterator to iterate over paths in a Flysystem\FileSystem,
supporting recursion and custom filters.

Recursive iteration is more memory-efficient than Flysystem's recursive listContents lookup 
(`$filesystem->listContents('', true)`), since only directory contents that are part
of the current item's ancestry are kept in memory.
 
## Configuration options ##
Iterator behaviour can be controlled by passing a key/value configuration array to the plugin.
Constants exist in the Options\Options class for all available option keys and string values.

Iterator recursion is enabled by default, and can be disabled by passing:
```
['recursive' => false]
```

The iterator will return a numerical index as the key and the file information array returned 
by listContents() for the current item.

The path of the current item, relative to the directory the filesystem was constructed with,
may also be returned as the value. Unlike the paths in filesysstem's info array directories 
will have a trailing slash.  

```
['value' => 'path']
``` 

The paths that the iterator returns can be filtered by passing a filter closure.
The current list item is passed to the filter. The item will be included in the result 
if the closure returns true.
The following example only returns files (directories are skipped) with a size of 1kb or more.

```
[
    'filter' =>
        function(array $item) {
            return $item['type'] === 'file' 
            && $item['size'] >= 1024;
        }
]    
```

A filter factory is included that provides a number of ready to use filter callbacks, 
including boolean wrappers.

```
[
    'filter' =>
        FilterFactory::and(
        [
            FilterFactory::isDirectory(),
            FilterFactory::pathContainsString('not')
        ]
    )
```    

## Usage example ##

```
use Jhofm\FlysystemIterator\Plugin\IteratorPlugin;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem;

$fs = new Filesystem(
    new LocalAdapter(
        '/home/user',
        LOCK_EX,
        LocalAdapter::SKIP_LINKS
    )
);
$fs->addPlugin(new IteratorPlugin());

$iterator = $fs->createIterator(['recursive' => false]);
foreach ($iterator as $key => $item) {
    echo $i . ' ' . $item['path'] . "\n";
}
```