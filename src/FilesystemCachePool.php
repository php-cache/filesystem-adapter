<?php

/*
 * This file is part of php-cache\filesystem-adapter package.
 *
 * (c) 2015-2015 Aaron Scherer <aequasi@gmail.com>, Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cache\Adapter\Filesystem;

use Cache\Adapter\Common\AbstractCachePool;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use Psr\Cache\CacheItemInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class FilesystemCachePool extends AbstractCachePool
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->filesystem->createDir('cache');
    }

    protected function fetchObjectFromCache($key)
    {
        return $this->filesystem->read($key);
    }

    protected function clearAllObjectsFromCache()
    {
        $this->filesystem->deleteDir('cache');
        $this->filesystem->createDir('cache');

        return true;
    }

    protected function clearOneObjectFromCache($key)
    {
        try {
            return $this->filesystem->delete($key);
        } catch (FileNotFoundException $e) {
            return true;
        }
    }

    protected function storeItemInCache($key, CacheItemInterface $item, $ttl)
    {
        return $this->filesystem->write($key, $item);
    }
}
