<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\Debug\Test;

use Es\Debug\Debug;
use Es\Debug\Dump;

class DebugTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        require_once 'DebugTestHelper.php';

        DebugTestHelper::resetDebug();
    }

    public function testDump()
    {
        $dump = Debug::dump($this);
        $line = __LINE__ - 1;

        $this->assertInstanceOf(Dump::CLASS, $dump);
        $this->assertSame($dump->getLine(), $line);
        $this->assertSame($dump->getFile(), __FILE__);
    }

    public function testGetDumpInstances()
    {
        Debug::dump($this);
        $line = __LINE__ - 1;

        $instances = Debug::getDumpInstances();
        $this->assertInternalType('array', $instances);

        $this->assertSame(1, count($instances));
        $this->assertInstanceOf(Dump::CLASS, $instances[0]);

        $dump = $instances[0];
        $this->assertSame($dump->getLine(), $line);
        $this->assertSame($dump->getFile(), __FILE__);
    }
}
