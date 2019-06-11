<?php

namespace App\Services;


use App\Entities\Tag;
use App\Entities\Tool;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use EntityManager;


final class VuttrService implements VuttrServiceInterface
{

    /**
     * @var EntityRepository
     */
    private $toolRepository;

    /**
     * @var EntityRepository
     */
    private $tagRepository;

    /**
     * VuttrService constructor.
     *
     * @param EntityRepository $toolRepository
     * @param EntityRepository $tagRepository
     */
    public function __construct(EntityRepository $toolRepository, EntityRepository $tagRepository)
    {
        $this->toolRepository = $toolRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param string|null $filterTag
     * @return Collection
     */
    public function tools(string $filterTag = null): array
    {
        if (empty($filterTag)) {
            return $this->toolRepository->findAll();
        }

        $tag = $this->tagRepository->findOneByName($filterTag);
        if (empty($tag)) {
            return [];
        }

        return $tag->getTools()->toArray();
    }

    /**
     * @param int $id
     * @return Tool|null
     */
    public function getTool(int $id): ?Tool
    {
        return $this->toolRepository->findOneById($id);
    }

    /**
     * @param string $title
     * @param string $link
     * @param string $description
     * @param array $tags
     * @return Tool
     */
    public function addTool(string $title, string $link, string $description, array $tags): Tool
    {
        $tags = array_unique($tags);

        $tool = new Tool($title, $link, $description);
        foreach ($tags as $tagName) {
            $tag = $this->tagRepository->findOneByName($tagName);
            if (empty($tag)) {
                $tag = new Tag($tagName);
            }
            $tool->addTag($tag);
        }

        EntityManager::persist($tool);
        EntityManager::flush();

        return $tool;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteTool(int $id): bool
    {
        $tool = $this->toolRepository->findOneById($id);
        if (empty($tool)) {
            return false;
        }

        foreach ($tool->getTags() as $tag) {
            if ($tag->getTools()->count() == 1) {
                EntityManager::remove($tag);
            }
        }

        EntityManager::remove($tool);
        EntityManager::flush();

        return true;
    }

}
