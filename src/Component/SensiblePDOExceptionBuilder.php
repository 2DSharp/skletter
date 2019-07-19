<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Component;


use Skletter\Exception\PDOExceptionWrapper\UniqueConstraintViolation;

class SensiblePDOExceptionBuilder
{
    /**
     * @var \PDOException $exception
     */
    private $exception;

    private $map =
        ["23000" => UniqueConstraintViolation::class];

    /**
     * Maps generic PDO Exception message errors to sensible exceptions
     * SensiblePDOException constructor.
     * @param \PDOException $exception
     */
    public function __construct(\PDOException $exception, $exceptionMap = null)
    {
        $this->exception = $exception;
    }

    /**
     * @throws \PDOException specific exception based on mysql error code
     */
    public function throw(): void
    {
        $exception = $this->createException($this->exception);
        throw new $exception;
    }

    private function createException(\PDOException $exception): \PDOException
    {
        $code = $exception->getCode();
        if (array_key_exists($this->map, $code))
            return new $this->map[$code];

        else return $exception;
    }
}