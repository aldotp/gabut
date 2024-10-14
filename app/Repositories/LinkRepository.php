<?php

namespace App\Repositories;

use App\Models\Link;

class LinkRepository {

    public function getAllLink()
    {
        return Link::all();
    }

    public function getLink($id, $userID)
    {
        // return Link::where('id', $id)->where('user_id', $userID)->first();

        return Link::where('user_id and id', $userID, $id)->get();
    }

    public function createLink($data)
    {
        return Link::create($data); 
    }

    public function updateLink($id, $data)
    {
        $link = Link::find($id);
        $link->update($data);
        return $link;
    }

    public function deleteLink($id)
    {
        $link = Link::find($id);
        $link->delete();
        return $link;
    }

    public function getLinkBySlug($slug)
    {
        $link = Link::where('slug', $slug)->first();
        if (!$link) {
            return null;
        }

        $link->increment('statistic');

        return Link::where('slug', $slug)->first();
    }

    public function getLinksByUserID($id)
    {
        return Link::where('user_id', $id)->get();
    }
}