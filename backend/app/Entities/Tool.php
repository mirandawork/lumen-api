<?php

namespace App\Entities;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Illuminate\Contracts\Support\Arrayable;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="tools")
 * @OA\Schema(schema="tool")
 */
class Tool implements Arrayable
{
    /**
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="id", type="integer")
     * @OA\Property(readOnly=true)
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(name="title", type="string", unique=true)
     * @OA\Property()
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(name="link", type="string")
     * @OA\Property()
     * @var string
     */
    protected $link;

    /**
     * @ORM\Column(name="description", type="text")
     * @OA\Property()
     * @var string
     */
    protected $description;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="tools", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="tools_tags")
     * @OA\Property(type="array", @OA\Items(type="string"))
     * @var App\Entities\Tag[]
     */
    protected $tags;

    /**
     * @param string $title
     * @param string $link
     * @param string $description
     */
    public function __construct(string $title, string $link, string $description)
    {
        $this->title = $title;
        $this->link = $link;
        $this->description = $description;
        $this->tags = new ArrayCollection();
    }

    /**
     * @param Tag[] $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags->clear();
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }

    /**
     * @param Tag $tag
     */
    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addTool($this);
        }
    }

    /**
     * @return array
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param bool $listTags
     * @return array
     */
    public function toArray(bool $listTags = True): array
    {
        $tags = [];
        if ($listTags) {
            foreach ($this->tags as $tag) {
                $tags[] = $tag->getName();
            }
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'link' => $this->link,
            'description' => $this->description,
            'tags' => $tags,
        ];
    }

}
