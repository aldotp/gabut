<?php

namespace App\Services;

use App\Repositories\LinkRepository;

class LinkService {
    private $linkRepository;

    public function __construct(LinkRepository $linkRepository)
    {
        $this->linkRepository = $linkRepository;
    }

    public function getAllLink()
    {
        return $this->linkRepository->getAllLink();
    }

    public function getLink($id)
    {
        return $this->linkRepository->getLink($id, auth()->user()->id);
    }

    public function createLink($data)
    {
        $slug = isset($data['slug']) ? $data['slug'] : \Str::random(10);

        return $this->linkRepository->createLink([
            'statistic' => 0,
            'slug' => $slug,
            'url' => $data['url'],
            'user_id' => auth()->user()->id
        ]);
    }

    public function updateLink($id, $data)
    {
        return $this->linkRepository->updateLink($id, $data);
    }

    public function deleteLink($id)
    {
        return $this->linkRepository->deleteLink($id);
    }

    public function getLinkBySlug($slug)
    {
        return $this->linkRepository->getLinkBySlug($slug);
    }

    public function getLinksByUserID()
    {
        $id = auth()->user()->id;
        return $this->linkRepository->getLinksByUserID($id);
    }
}