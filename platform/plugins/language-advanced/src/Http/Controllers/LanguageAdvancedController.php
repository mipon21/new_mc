<?php

namespace Botble\LanguageAdvanced\Http\Controllers;

use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Http\Controllers\BaseController;
use Botble\LanguageAdvanced\Http\Requests\LanguageAdvancedRequest;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Slug\Events\UpdatedSlugEvent;
use Botble\Slug\Facades\SlugHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class LanguageAdvancedController extends BaseController
{
    public function save(int|string $id, LanguageAdvancedRequest $request)
    {
        $model = $request->input('model');

        abort_unless(class_exists($model), 404);

        $data = (new $model())->findOrFail($id);

        LanguageAdvancedManager::save($data, $request);

        $request->merge([
            'is_slug_editable' => 0,
        ]);

        do_action(LANGUAGE_ADVANCED_ACTION_SAVED, $data, $request);

        event(new UpdatedContentEvent('', $request, $data));

        $slugId = $request->input('slug_id');

        $language = $request->input('language');

        if ($slugId && $language) {
            $table = 'slugs_translations';

            $condition = [
                'lang_code' => $language,
                'slugs_id' => $slugId,
            ];

            $slugData = array_merge($condition, [
                'key' => $request->input('slug'),
                'prefix' => SlugHelper::getPrefix($model),
            ]);

            $translate = DB::table($table)->where($condition)->exists();

            if ($translate) {
                DB::table($table)->where($condition)->update($slugData);
            } else {
                DB::table($table)->insert($slugData);
            }

            UpdatedSlugEvent::dispatch($data, $data->slugable);
        }

        $form = $request->input('form');

        if (class_exists($form) && is_subclass_of($form, FormAbstract::class)) {
            $form = $form::createFromModel($data);

            $form->saveMetadataFields();
        }

        // Determine the correct redirect URL based on the model type
        $redirectUrl = $this->getRedirectUrl($data, $language, $request);

        return $this
            ->httpResponse()
            ->setPreviousUrl($redirectUrl)
            ->withUpdatedSuccessMessage();
    }

    protected function getRedirectUrl($data, $language = null, $request = null): string
    {
        // Map model classes to their edit routes
        $modelRouteMap = [
            'Botble\Page\Models\Page' => 'pages.edit',
            'Botble\Blog\Models\Post' => 'posts.edit',
            'Botble\Ecommerce\Models\Product' => 'products.edit',
            // Add more mappings as needed
        ];

        $modelClass = get_class($data);
        $routeName = $modelRouteMap[$modelClass] ?? null;

        if ($routeName && \Route::has($routeName)) {
            $url = route($routeName, $data->getKey());
            
            // Add language parameter if not default language
            if ($language && $language !== \Botble\Language\Facades\Language::getDefaultLocaleCode()) {
                $url .= '?ref_lang=' . $language;
            }
            
            return $url;
        }

        // Fallback to previous URL or admin dashboard
        return $request ? $request->header('referer') ?? route('dashboard.index') : route('dashboard.index');
    }
}
