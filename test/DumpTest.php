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

use Es\Debug\Dump;

class DumpTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $representation = 'foo';

        $file = 'bar';
        $line = 100;

        $dump = new Dump($representation, $file, $line);
        $this->assertSame($representation, (string) $dump);
        $this->assertSame($file, $dump->getFile());
        $this->assertSame($line, $dump->getLine());
    }
}
