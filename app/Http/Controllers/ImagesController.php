<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest\CreateImageRequest;
use App\Http\Requests\ImageRequest\IndexImageRequest;
use App\Http\Requests\ImageRequest\StoreImageRequest;
use App\Services\AuthorImageService;
use App\Services\AuthorService;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ImagesController extends Controller
{
    public function index(IndexImageRequest $request, ImageService $imageService): View
    {
        $paginator = $imageService->listAll();

        return view('second-page', compact('paginator'));
    }

    public function create(CreateImageRequest $request): View
    {
        return view('first-page');
    }

    public function store(
        StoreImageRequest  $request,
        ImageService       $imageService,
        AuthorService      $authorService,
        AuthorImageService $authorImageService,
    ): JsonResponse
    {
        DB::beginTransaction();
        try {
            $parentImage = $imageService->create($request->getImageFile());
            $author = $authorService->getOrCreate(
                $request->input('name'),
                $request->input('surname'),
            );
            $authorImageService->bind($author, $parentImage);

            DB::commit();
        } catch (Throwable $throwable) {
            Log::error($throwable->getMessage());
            $rolledBack = $imageService->rollback($request->getImageFile());
            DB::rollBack();

            return response()->json()->setStatusCode($rolledBack ? 400 : 500);
        }

        return response()->json();
    }
}
