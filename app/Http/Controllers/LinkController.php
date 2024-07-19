<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetLinksRequest;
use App\Http\Requests\GetMaxClickRequest;
use App\Http\Requests\MakeLinkRequest;
use App\Services\LinkService;
use App\Traits\ApiResponder;
use Symfony\Component\HttpFoundation\Response;

class LinkController extends Controller
{
    use ApiResponder;

    public function __construct(protected LinkService $linkService)
    {
    }

    public function make(MakeLinkRequest $request)
    {
        $data = $request->validated();
        $response = $this->linkService->make($data);
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed(message: $response['message'], statusCode: $response['status']);
        }
        return $this->success(data: $response['data'], message: $response['message']);
    }

    public function getMaxClicks(GetMaxClickRequest $request)
    {
        $data = $request->validated();
        $response = $this->linkService->getMaxClicks($data);
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed(message: $response['message'], statusCode: $response['status']);
        }
        return $this->success(data: $response['data']);
    }

    public function get(GetLinksRequest $request)
    {
        $data = $request->validated();
        $response = $this->linkService->get($data);
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed(message: $response['message'], statusCode: $response['status']);
        }
        return $this->success(data: $response['data']);
    }

    public function getLink(string $shortLink)
    {
        $response = $this->linkService->getLink($shortLink);
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed(message: $response['message'], statusCode: $response['status']);
        }
        return $this->success(data: $response['data']);
    }
}
