# flysystem-iterator

Provides an Iterator to iterate over paths in a Flysystem\FileSystem.

The iterator can traverse subdirectories recursively if the recursion option is enabled. 
Recursive iteration is more memory-efficient than Flysystem's recursive listContents lookup 
($filesystem->listContents('', true)), since only directory contents that are part
of the current item's ancestry are kept in memory.\
Execution time is also decreased for many usecases, since files can be processed without waiting for 
the whole directory recursion to complete.

The Iterator is seekable, but will need to iterate to the target position without shortcuts, possibly rewinding 
first if it's current position is higher that the desired one. 

The second constructor parameter is the starting directory within the filesystem.
Pass '/' or nothing to iterate over the root path the filesystem was created with. 

Iterator behaviour can be changed controlled by passing a key/value configuration array as the third
construction parameter (or the second argument if using the plugin).
Constants exist in the Options\Options class for all available option keys and string values.

Iterator recursion is disabled by default, enable it by passing the following option:
```
['recursive' => true]
```

By default, the iterator will return a numerical index as the key and the file information array returned 
by listContents() for the current item.\
The keys and values returned can be configured like this:

```
['key' => <value>, 'value' => <value>]
``` 

Possible values:

* path: path relative to the filesystem's initial directory, directory paths will have a trailing slash 
* index: numerical index of the item in the list
* info: info array from FileSystem::listContents (only for values)

## Usage example (direct) ##

 ```
use Jhofm\FlysystemIterator\FilesystemIterator;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem;

$fs = new Filesystem(
    new LocalAdapter(
        '/home/user',
        LOCK_EX,
        LocalAdapter::SKIP_LINKS
    ),
    '/',
    ['key' => 'path', 'value' => 'info', 'recursive' => true]
);

$iterator = new FilesystemIterator($fs);
foreach ($iterator as $index => $info) {
    echo $key . ': ' . $info['path'] . "\n";
} 
``` 

## Usage example (as plugin) ##

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

$iterator = $fs->createIterator();
$iterator->seek(99);
$fileinfo = $iterator->current();
echo $iterator->key() . ': ' . $fileinfo['path'] . "\n";

```
