<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\ValueObject;


class Result
{
    private $errors = [];
    private $success;
    private $vo;

    public function __construct(bool $success, array $errors = [])
    {
        $this->success = $success;
        $this->errors = $errors;
    }

    public function setValueObject($vo)
    {
        $this->vo = $vo;
    }

    public function getValueObject()
    {
        return $this->vo;
    }
    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

}