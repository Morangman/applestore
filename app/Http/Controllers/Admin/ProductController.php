<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Product;
use App\Category;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use McMatters\Helpers\Helpers\ModelHelper;
use Spatie\MediaLibrary\Models\Media;
use Throwable;

class ProductController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): ViewContract
    {
        return View::make('admin.product.index');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        return View::make('admin.product.create', [
            'categories' => Category::all(),
            'recommended' => Product::query()->get(['id', 'title']),
        ]);
    }

    /**
     * @param \App\Http\Requests\Admin\Product\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function store(StoreRequest $request): JsonResponse
    {
        /** @var \App\Product $product */
        $product = Product::create($request->all());

        $this->handleDocuments($request, $product);

        Session::flash(
            'success',
            Lang::get('admin/product.messages.create')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Product $product
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Product $product): ViewContract
    {
        return View::make(
            'admin.product.edit',
            [
                'product' => $product,
                'categories' => Category::all(),
                'recommended' => Product::query()->get(['id', 'title']),
            ]
        );
    }

    /**
     * @param \App\Http\Requests\Admin\Product\UpdateRequest $request
     * @param \App\Product $product
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function update(UpdateRequest $request, Product $product): JsonResponse
    {
        $product->update($request->all());

        $this->handleDocuments($request, $product);

        Session::flash(
            'success',
            Lang::get('admin/product.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Product $product
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(Product $product): JsonResponse
    {
        $product->delete();

        Session::flash(
            'success',
            Lang::get('admin/product.messages.delete')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \InvalidArgumentException
     */
    public function getAll(Request $request): JsonResponse
    {
        $products = Product::query()
            ->when(
                $request->get('search'),
                function ($query, $search) {
                    $keyword = "%{$search}%";

                    $query->where('title', 'like', $keyword);
                }
            )
            ->when(
                $request->get('by'),
                function ($q, $sort) use ($request) {
                    $q->orderBy($sort, $request->get('dir', 'asc'));
                }
            )
            ->paginate(20);

        return $this->json()->ok($products);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Product $product
     *
     * @return void
     *
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    protected function handleDocuments(Request $request, Product $product): void
    {
        $images = $request->file('product_images', []);

        foreach ($images as $image) {
            $product->addMedia($image)
                ->toMediaCollection(Product::MEDIA_COLLECTION_PRODUCT);
        }

        if ($productPreviewImage = $request->file('image')) {
            $media = $product->addMedia($productPreviewImage)
                ->toMediaCollection(Product::MEDIA_COLLECTION_PRODUCT);

            $product->update(['image' => $media->getUrl()]);
        } elseif ($product->getAttribute('image') !== $image = $request->get('image')) {
            $media = $product->addMediaFromUrl($image)
                ->toMediaCollection(Product::MEDIA_COLLECTION_PRODUCT);

            $product->update(['image' => $media->getUrl()]);
        }
    }

    /**
     * @param \App\Product $product
     * @param \Spatie\MediaLibrary\Models\Media $media
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMedia(Product $product, Media $media): JsonResponse
    {
        try {
            if ($media->getAttribute('collection_name') === Product::MEDIA_COLLECTION_PRODUCT
                && ModelHelper::doesMorphedBelongToParent($media, $product, 'model')
            ) {
                $product->deleteMedia($media->getKey());
            }
        } catch (Throwable $e) {
            return $this->json()->badRequest(['message' => $e->getMessage()]);
        }

        return $this->json()->noContent();
    }
}
