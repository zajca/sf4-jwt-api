<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;

class UserAttributesDTO
{
    /**
     * @var array
     */
    public $riasec;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $a12;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $a08;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $a04;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $a07;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $a11;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $a06;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $a10;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $a01;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $a02;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $a05;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $a15;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $a03;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $a09;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $a14;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $a13;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $b06;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $b02;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $b07;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $b08;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $b03;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $b04;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $b05;

    /**
     * @var int
     * @Assert\Range(min="0",max="10")
     */
    public $b01;
}
