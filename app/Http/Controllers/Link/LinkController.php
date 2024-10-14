<?php

namespace App\Http\Controllers\Link;

use Illuminate\Http\Request;
use App\Response\ResponseJson;
use App\Services\LinkService;

 class LinkController
{
    private $response;
    private $linkService;

    public function __construct(ResponseJson $response, LinkService $linkService) {
        $this->response = $response;
        $this->linkService = $linkService;
    }
    public function createLink(Request $request){
       $link = $this->linkService->createLink($request->all());
       if ($link == null){
           return $this->response->responseError("Link already exist", 400);
       }
       return $this->response->responseSuccess($link, 'Link created successfully', 201);
    }

    public function getAllLinks(){
        $links = $this->linkService->getAllLink();

        return $this->response->responseSuccess($links, 'Get All Link successfully', 201);
    }


    public function deleteLink($id){
        $link = $this->linkService->deleteLink($id);
        if (!$link) {
            return $this->response->responseError('Link not found', 404);
        } else {
            return $this->response->responseSuccess($link, 'Link deleted successfully', 200);
        }
    }

    public function updateLink($id, Request $request){
        $link = $this->linkService->updateLink($id, $request->all());
        if (!$link) {
            return $this->response->responseError('Link not found', 404);
        } else {
            return $this->response->responseSuccess($link, 'Link updated successfully', 200);
        }
    }

    public function getLink($id){
        $link = $this->linkService->getLink($id);
        if (!$link) {
            return $this->response->responseError('Link not found', 404);
        } else {
            return $this->response->responseSuccess($link, 'Link fetched successfully', 200);
        }
    }

    public function getLinkBySlug($slug){
        $link = $this->linkService->getLinkBySlug($slug);
        if (!$link) {
            return $this->response->responseError('Link not found', 404);
        } else {
            return response()->redirectTo($link->url);
        }
    }


    public function getLinksByUserID (){
        $link = $this->linkService->getLinksByUserID();
        if (!$link) {
            return $this->response->responseError('Link not found', 404);
        } else {
            return $this->response->responseSuccess($link, 'Link fetched successfully', 200);
        }
    }
}