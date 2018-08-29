<?php

namespace App\Entity;

use App\Base\Enum\ArrayEnumAnnotation as ArrayEnum;
use App\Enum\EQF;
use App\Enum\RIASEC;
use App\Enum\WorkUnitType;
use Consistence\Doctrine\Enum\EnumAnnotation as Enum;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nsp_work_unit")
 * @ORM\Entity(repositoryClass="App\Repository\WorkUnitRepository")
 */
class WorkUnit extends \Consistence\ObjectPrototype
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $urlSlug;

    /**
     * @ORM\Column(type="integer")
     */
    private $legacyId;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $nspCode;

    /**
     * @ORM\Column(type="integer")
     */
    private $code;

    /**
     * @Enum(WorkUnitType::class)
     * @ORM\Column(type="integer_enum", nullable=false)
     *
     * @var WorkUnitType
     */
    private $type;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $minWage;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $maxWage;

    /**
     * @ORM\Column(type="text")
     */
    private $characteristics;

    /**
     * @Enum(EQF::class)
     * @ORM\Column(type="integer_enum", nullable=false)
     *
     * @var EQF
     */
    private $eqf;

    /**
     * @ArrayEnum(class=RIASEC::class)
     * @ORM\Column(type="array_enum",nullable=true)
     *
     * @var RIASEC[]
     */
    private $riasec = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getUrlSlug(): ?string
    {
        return $this->urlSlug;
    }

    public function setUrlSlug(string $urlSlug): self
    {
        $this->urlSlug = $urlSlug;

        return $this;
    }

    public function getLegacyId(): ?int
    {
        return $this->legacyId;
    }

    public function setLegacyId(int $legacyId): self
    {
        $this->legacyId = $legacyId;

        return $this;
    }

    public function getNspCode(): ?string
    {
        return $this->nspCode;
    }

    public function setNspCode(?string $nspCode): self
    {
        $this->nspCode = $nspCode;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code): void
    {
        $this->code = $code;
    }

    public function getType(): WorkUnitType
    {
        return $this->type;
    }

    public function setType(WorkUnitType $type): void
    {
        $this->type = $type;
    }

    public function getEqf(): EQF
    {
        return $this->eqf;
    }

    public function setEqf(EQF $eqf): void
    {
        $this->eqf = $eqf;
    }

    public function getMinWage(): ?int
    {
        return $this->minWage;
    }

    public function setMinWage(int $minWage): self
    {
        $this->minWage = $minWage;

        return $this;
    }

    public function getMaxWage(): ?int
    {
        return $this->maxWage;
    }

    public function setMaxWage(int $maxWage): self
    {
        $this->maxWage = $maxWage;

        return $this;
    }

    public function getCharacteristics(): ?string
    {
        return $this->characteristics;
    }

    public function setCharacteristics(string $characteristics): self
    {
        $this->characteristics = $characteristics;

        return $this;
    }

    public function getRiasec(): array
    {
        return $this->riasec;
    }

    public function setRiasec(array $riasec): void
    {
        $this->riasec = $riasec;
    }
}
