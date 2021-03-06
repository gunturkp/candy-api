<?php

namespace GetCandy\Api\Http\Controllers\Assets;

use GetCandy\Exceptions\InvalidServiceException;
use GetCandy\Api\Http\Controllers\BaseController;
use GetCandy\Api\Http\Requests\Assets\UpdateAllRequest;
use GetCandy\Api\Http\Requests\Assets\UploadRequest;
use GetCandy\Api\Http\Transformers\Fractal\Assets\AssetTransformer;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AssetController extends BaseController
{
    public function store(UploadRequest $request)
    {
        try {
            $parent = app('api')->{$request->parent}()->getByHashedId($request->parent_id);
        } catch (InvalidServiceException $e) {
            return $this->errorWrongArgs($e->getMessage());
        }
        $asset = app('api')->assets()->upload(
            $request->all(),
            $parent,
            $parent->assets()->count() + 1
        );
        return $this->respondWithItem($asset, new AssetTransformer);
    }

    public function destroy($id)
    {
        try {
            $result = app('api')->assets()->delete($id);
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithNoContent();
    }

    public function updateAll(UpdateAllRequest $request)
    {
        $result = app('api')->assets()->updateAll($request->assets);
        if (!$result) {
            $this->respondWithError();
        }
        return $this->respondWithComplete();
    }
}
