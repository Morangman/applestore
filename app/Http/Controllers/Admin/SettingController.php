<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Setting;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\StoreRequest;
use App\Http\Requests\Admin\Setting\UpdateRequest;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use McMatters\Helpers\Helpers\ModelHelper;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class SettingController
 *
 * @package App\Http\Controllers\Admin
 */
class SettingController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function index(): ViewContract
    {
        $setting = Setting::latest('updated_at')->first() ?? (object)[];

        return View::make('admin.setting.index', [
            'settings' => $setting,
            'slimages' => $setting->getMedia(Setting::MEDIA_COLLECTION_SLIDER)
                ->map(static function (Media $media) {
                    return [
                        'id' => $media->getKey(),
                        'url' => $media->getFullUrl(),
                        'title' => $media->getCustomProperty('title'),
                    ];
                })->toArray(),
            'creditimages' => $setting->getMedia(Setting::MEDIA_COLLECTION_CREIT_SLIDER)
                ->map(static function (Media $media) {
                    return [
                        'id' => $media->getKey(),
                        'url' => $media->getFullUrl(),
                        'title' => $media->getCustomProperty('title'),
                    ];
                })->toArray(),
            'content' => json_decode(Storage::disk('file')->get('content.json'), true)
        ]);
    }

    /**
     * @param \App\Http\Requests\Admin\Setting\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $settingData = $request->except(
                [
                    'code_insert',
                ]
            ) + [
                'code_insert' => $request->get('code_insert') ?? '',
            ];

        $setting = Setting::create($settingData);

        $this->handleDocuments($request, $setting);

        Session::flash(
            'success',
            Lang::get('admin/setting.messages.create')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Http\Requests\Admin\Setting\UpdateRequest $request
     * @param \App\Setting $setting
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function update(UpdateRequest $request, Setting $setting): JsonResponse
    {
        $settingData = $request->except(
                [
                    'code_insert',
                ]
            ) + [
                'code_insert' => $request->get('code_insert') ?? '',
            ];

        if ($files = $request->file('slimages', [])) {
            $reqFiles = $request->get('slimages');
            foreach($files as $key => $file) {
                $setting->addMedia($file['file'])
                    ->withCustomProperties(['title' => isset($reqFiles[$key]['title']) ? $reqFiles[$key]['title'] : '', 'target_url' => isset($reqFiles[$key]['target_url']) ? $reqFiles[$key]['target_url'] : ''])
                    ->toMediaCollection(Setting::MEDIA_COLLECTION_SLIDER);
            }
        }

        if ($files = $request->file('creditimages', [])) {
            $reqFiles = $request->get('creditimages');
            foreach($files as $key => $file) {
                $setting->addMedia($file['file'])
                    ->withCustomProperties(['title' => isset($reqFiles[$key]['title']) ? $reqFiles[$key]['title'] : '', 'target_url' => isset($reqFiles[$key]['target_url']) ? $reqFiles[$key]['target_url'] : ''])
                    ->toMediaCollection(Setting::MEDIA_COLLECTION_CREIT_SLIDER);
            }
        }

        $setting->update($settingData);

        $this->handleDocuments($request, $setting);

        if ($content = $request->get('content')) {
            //Storage::disk('public')->delete('content.json');

            Storage::disk('file')->put('content.json', json_encode($content));
        }

        Session::flash(
            'success',
            Lang::get('admin/setting.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function modify(Request $request): JsonResponse
    {
        $data = [
            'attribute' => $request->get('attribute'),
            'value' => $request->get('value'),
        ];

        Storage::disk('public')->put('content.json', json_encode($data));

        return $this->json()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Setting $setting
     *
     * @return void
     *
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    protected function handleDocuments(Request $request, Setting $setting): void
    {
        if ($settingSeoImage = $request->file('general_settings')['seo_image']) {
            $media = $setting->addMedia($settingSeoImage)
                ->toMediaCollection(Setting::MEDIA_COLLECTION_SETTING);

            $setting->update(['general_settings' => [
                'email' => $request->get('general_settings')['email'] ?? null,
                'contact_email' => $request->get('general_settings')['contact_email'] ?? null,
                'phone' => $request->get('general_settings')['phone'] ?? null,
                'seo_meta' => $request->get('general_settings')['seo_meta'] ?? null,
                'seo_title' => $request->get('general_settings')['seo_title'] ?? null,
                'seo_keywords' => $request->get('general_settings')['seo_keywords'] ?? null,
                'seo_image' => $media->getFullUrl(),
                'iframe_map' => $request->get('general_settings')['iframe_map'] ?? null,
            ]]);
        } elseif (
            $request->has('general_settings')['seo_image']
            && $setting->getAttribute('general_settings')['seo_image']
            !== $image = $request->get('general_settings')['seo_image']
        ) {
            $setting->update(['general_settings' => [
                'email' => $request->get('general_settings')['email'] ?? null,
                'contact_email' => $request->get('general_settings')['contact_email'] ?? null,
                'phone' => $request->get('general_settings')['phone'] ?? null,
                'seo_meta' => $request->get('general_settings')['seo_meta'] ?? null,
                'seo_title' => $request->get('general_settings')['seo_title'] ?? null,
                'seo_keywords' => $request->get('general_settings')['seo_keywords'] ?? null,
                'seo_image' => $setting->getAttribute('general_settings')['seo_image'] ?? '',
                'iframe_map' => $request->get('general_settings')['iframe_map'] ?? null,
            ]]);
        }
    }

    /**
     * @param \Spatie\MediaLibrary\Models\Media $media
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMedia(Media $media): JsonResponse
    {
        $setting = Setting::latest('updated_at')->first() ?? (object)[];

        try {
            if ($media->getAttribute('collection_name') === Setting::MEDIA_COLLECTION_SLIDER
                || $media->getAttribute('collection_name') === Setting::MEDIA_COLLECTION_CREIT_SLIDER
                && ModelHelper::doesMorphedBelongToParent($media, $setting, 'model')
            ) {
                $setting->deleteMedia($media->getKey());
            }
        } catch (\Throwable $e) {
            return $this->json()->badRequest(['message' => $e->getMessage()]);
        }

        return $this->json()->noContent();
    }
}
