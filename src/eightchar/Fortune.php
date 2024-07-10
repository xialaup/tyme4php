<?php

namespace com\tyme\eightchar;


use com\tyme\AbstractTyme;
use com\tyme\lunar\LunarYear;
use com\tyme\sixtycycle\SixtyCycle;

/**
 * 小运
 * @author 6tail
 * @package com\tyme\eightchar
 */
class Fortune extends AbstractTyme
{
    /**
     * @var ChildLimit 童限
     */
    protected ChildLimit $childLimit;

    /**
     * @var int 序号
     */
    protected int $index;

    protected function __construct(ChildLimit $childLimit, int $index)
    {
        $this->childLimit = $childLimit;
        $this->index = $index;
    }

    static function fromChildLimit(ChildLimit $childLimit, int $index): static
    {
        return new static($childLimit, $index);
    }

    /**
     * 年龄
     *
     * @return int 年龄
     */
    function getAge(): int
    {
        return $this->childLimit->getYearCount() + 1 + $this->index;
    }

    /**
     * 农历年
     *
     * @return LunarYear 农历年
     */
    function getLunarYear(): LunarYear
    {
        return $this->childLimit->getEndTime()->getLunarHour()->getLunarDay()->getLunarMonth()->getLunarYear()->next($this->index);
    }

    /**
     * 干支
     *
     * @return SixtyCycle 干支
     */
    function getSixtyCycle(): SixtyCycle
    {
        $n = $this->getAge();
        return $this->childLimit->getEightChar()->getHour()->next($this->childLimit->isForward() ? $n : -$n);
    }

    function getName(): string
    {
        return $this->getSixtyCycle()->getName();
    }

    function next(int $n): static
    {
        return self::fromChildLimit($this->childLimit, $this->index + $n);
    }

}
