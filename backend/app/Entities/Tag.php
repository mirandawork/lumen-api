<?php

namespace App\Entities;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Support\Arrayable;
use OpenApi\Annotations as OA;

use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="tags")
 * @OA\Schema(schema="tag")
 */
class Tag implements Arrayable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(type="integer")
     * @OA\Property(readOnly=true)
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @OA\Property()
     * @var string
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="Tool", mappedBy="tags")
     * @var App\Entities\Tool[]
     */
    protected $tools;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->tools = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection
     */
    public function getTools(): Collection
    {
        return $this->tools;
    }

    /**
     * @param Tool $tool
     */
    public function addTool(Tool $tool): void
    {
        if (!$this->tools->contains($tool)) {
            $this->tools->add($tool);
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $tools = [];
        foreach ($this->tools as $tool) {
            $tools[] = $tool->toArray(false);
        }

        return [
            "id" => $this->id,
            "name" => $this->name,
            "tools" => $tools,
        ];
    }
}
