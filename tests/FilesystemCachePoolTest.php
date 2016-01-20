<?php

/*
 * This file is part of php-cache\filesystem-adapter package.
 *
 * (c) 2015-2015 Aaron Scherer <aequasi@gmail.com>, Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cache\Adapter\Filesystem\Tests;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class FilesystemCachePoolTest extends \PHPUnit_Framework_TestCase
{
    use CreatePoolTrait;

    /**
     * @expectedException \Psr\Cache\InvalidArgumentException
     */
    public function testInvalidKey()
    {
        $pool = $this->createCachePool();

        $pool->getItem('test%string')->get();
    }
}
