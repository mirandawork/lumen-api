<?php

namespace App\Services;


use App\Entities\Tool;
use Doctrine\Common\Collections\Collection;


interface VuttrServiceInterface
{
    /**
     * @param string|null $filterTag
     * @return Collection
     */
    public function tools(string $filterTag = null): array;

    /**
     * @param int $id
     * @return Tool|null
     */
    public function getTool(int $id): ?Tool;

    /**
     * @param string $title
     * @param string $link
     * @param string $description
     * @param array $tags
     * @return Tool
     */
    public function addTool(string $title, string $link, string $description, array $tags): Tool;

    /**
     * @param int $id
     * @return bool
     */
    public function deleteTool(int $id): bool;

}
