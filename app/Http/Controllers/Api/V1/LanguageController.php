<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Model\BusinessSetting;

class LanguageController extends Controller
{
    public function __construct(
        private BusinessSetting $business_setting
    ){}

    /**
     * @return JsonResponse
     */
    public function get(): JsonResponse
    {
        $languages = json_decode($this->business_setting->where(['key' => 'language'])->first()->value, true);

        $languages = array_map(function ($lang) {
            return array(
                'key' => $lang,
                'value'=> \App\CentralLogics\Helpers::get_language_name($lang)
            );
        }, $languages);

        return response()->json($languages, 200);
    }
}
