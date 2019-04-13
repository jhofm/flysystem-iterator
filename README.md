# flysystem-iterator

Provides an Iterator to recursively iterate over paths in a Flysystem\FileSystem.

Iteration is more memory-efficient than Flysystem's recursive listContents lookup 
($filesystem->listContents('', true)), since only directory contents that are part
of the current item's ancestry are kept in memory.\
Execution time is also decreased for many usecases, since files can be processed without waiting for 
the whole directory recursion to complete.

The Iterator is seekable, but will need to iterate to the target position without shortcuts, possibly rewinding 
first if it's current position is higher that the desired one. 

By default, the iterator will return a numerical index as the key and the file information array returned 
by listContents() for the current item.\
Returned keys and values can be configured by passing a config array as the third constructor parameter:

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
    ['key' => 'path', 'value' => 'info']
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
