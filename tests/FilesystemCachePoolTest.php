<?php

namespace Cache\Adapter\Filesystem\tests;

use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class FilesystemCachePoolTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Psr\Cache\InvalidArgumentException
     */
    public function testInvalidKey()
    {
        $pool = new FilesystemCachePool(new Filesystem(new Local(__DIR__.'/')));

        $pool->getItem('test%string');
    }
}