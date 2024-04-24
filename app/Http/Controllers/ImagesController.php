<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Services\AuthorImageService;
use App\Services\AuthorService;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;
use Throwable;

class ImagesController extends Controller
{
    public function index()
    {
        return response()->json();
    }

    public function store(
        StoreImageRequest  $request,
        ImageService       $imageService,
        AuthorService      $authorService,
        AuthorImageService $authorImageService,
    )
    {
        DB::beginTransaction();
        try {
            $parentImage = $imageService->create($request->getImageFile());
            $author = $authorService->getOrCreate(
                $request->input('data.owner.name'),
                $request->input('data.owner.email'),
            );
            $authorImageService->bind($author, $parentImage);

            DB::commit();
        } catch (Throwable $throwable) {
            $rolledBack = $imageService->rollback($request->getImageFile());
            DB::rollBack();

            return response()->json()->setStatusCode($rolledBack ? 400 : 500);
        }

        return response()->json();
    }
}
