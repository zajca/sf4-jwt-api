<?php declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;

class TestDTO
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=5)
     */
    public $title;
}
