<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\Debug;

/**
 * The value-object of variable dump.
 */
class Dump
{
    /**
     * The string representation of variable dump.
     *
     * @var string
     */
    protected $representation;

    /**
     * The file, that initializes debugging.
     *
     * @var string
     */
    protected $file;

    /**
     * The line of file, that initializes debugging.
     *
     * @var string
     */
    protected $line;

    /**
     * Constructor.
     *
     * @param string $representation The string representation of variable dump
     * @param string $file           The file, that initializes debugging
     * @param int    $line           The line of file, that initializes debugging
     */
    public function __construct($representation, $file, $line)
    {
        $this->representation = $representation;

        $this->file = $file;
        $this->line = $line;
    }

    /**
     * Gets the file, that initializes debugging.
     *
     * @return string The path to file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Gets the line of file, that initializes debugging.
     *
     * @return int The line of file
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Gets the string representation of variable dump.
     *
     * @return string The string representation of variable dump
     */
    public function __toString()
    {
        return $this->representation;
    }
}
