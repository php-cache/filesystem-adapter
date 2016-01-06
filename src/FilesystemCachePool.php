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
    const CACHE_PATH = 'cache';
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
        $this->filesystem->createDir(self::CACHE_PATH);
    }

    protected function fetchObjectFromCache($key)
    {
        $file = $this->getFilePath($key);
        if (!$this->filesystem->has($file)) {
            return;
        }

        return unserialize($this->filesystem->read($file));
    }

    protected function clearAllObjectsFromCache()
    {
        $this->filesystem->deleteDir(self::CACHE_PATH);
        $this->filesystem->createDir(self::CACHE_PATH);

        return true;
    }

    protected function clearOneObjectFromCache($key)
    {
        try {
            return $this->filesystem->delete($this->getFilePath($key));
        } catch (FileNotFoundException $e) {
            return true;
        }
    }

    protected function storeItemInCache($key, CacheItemInterface $item, $ttl)
    {
        $file = $this->getFilePath($key);
        if ($this->filesystem->has($file)) {
            $this->filesystem->delete($file);
        }

        return $this->filesystem->write($file, serialize($item));
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    private function getFilePath($key)
    {
        return sprintf('%s/%s', self::CACHE_PATH, urlencode(base64_encode($key)));
    }
}
