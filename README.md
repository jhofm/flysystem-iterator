# flysystem-iterator

Provides a plugin that creates an Iterator to iterate over paths in a Flysystem\FileSystem,
supporting recursion and custom filters.

Recursive iteration is more memory-efficient than Flysystem's recursive listContents lookup 
(`$filesystem->listContents('', true)`), since only directory contents that are part
of the current item's ancestry are kept in memory.

The returned iterator is seekable, countable and jsonserializable. Using these functions will often require
a complete recursion over all items in the filesystem. 
 
## Configuration options ##
Iterator behaviour can be controlled by passing a key/value configuration array to the plugin.
Constants exist in the Options\Options class for all available option keys and string values.

Iterator recursion is enabled by default, and can be disabled by passing:
```
'recursive' => false
```

When recursion is enabled, the first value returned by the iterator will be the directory that is iterated over.
To ignore the root directory and start iteration with the directories contents you can pass the parameter

```
'skip-root' => true
```
This parameter has no effect if recursion is disabled.

The iterator will return a numerical index as the key and the file information array returned 
by listContents() for the current item.

Additional filesystem metadata can be added to the items by passing an array of additional properties to the configuration array.
Allowed property names are the same as in Flysystem's ListWith plugin.

```
'list-with' => 'mimetype'
```

Alternatively, the path of the current item, relative to the directory being iterated,
may be returned as the value instead of the info array. Unlike the paths in filesystem's info array directories 
will have a trailing slash, so it is possible to distinguish files from directories without the type information.  

```
'value' => 'path'
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
            FilterFactory::isDirectory(),
            FilterFactory::pathContainsString('foo')
        )
```    

A subdirectory can be specified as an optional second parameter to the createIterator() function.
If omitted, the iterator will use the directory set by the filesystem adapter.

## Usage example ##

```
use Jhofm\FlysystemIterator\Plugin\IteratorPlugin;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem;
use Jhofm\FlysystemIterator\Filter\FilterFactory;

$fs = new Filesystem(
    new LocalAdapter(
        '/home/user',
        LOCK_EX,
        LocalAdapter::SKIP_LINKS
    )
);
$fs->addPlugin(new IteratorPlugin());

$iterator = $fs->createIterator(
    ['filter' => FilterFactory::isFile(), 'skip-root' => true],
    'subdirectory'
);

foreach ($iterator as $key => $item) {
    echo $i . ' ' . $item['path'] . "\n";
}
var_dump(json_encode($iterator));
```