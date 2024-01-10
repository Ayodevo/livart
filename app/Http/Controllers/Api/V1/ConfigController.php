<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Branch;
use App\Model\BusinessSetting;
use App\Model\Category;
use App\Model\Product;
use App\Model\Currency;
use App\Model\SocialMedia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ConfigController extends Controller
{
    public function configuration()
    {
        //addon settings publish status
        $published_status = 0; // Set a default value
        $payment_published_status = config('get_payment_publish_status');
        if (isset($payment_published_status[0]['is_published'])) {
            $published_status = $payment_published_status[0]['is_published'];
        }
        $active_addon_payment_lists = $published_status == 1 ? $this->getPaymentMethods() : $this->getDefaultPaymentMethods();
        //$gateway_image_url = $published_status == 1 ? asset('storage/app/public/payment_modules/gateway_image') : asset('public/assets/admin/img/payment');
        $digital_payment_status = BusinessSetting::where(['key' => 'digital_payment'])->first()->value;
        $digital_payment_status_value = json_decode($digital_payment_status, true);


        $currency_symbol = Currency::where(['currency_code' => Helpers::currency_code()])->first()->currency_symbol;
        $cod = json_decode(BusinessSetting::where(['key' => 'cash_on_delivery'])->first()->value, true);
        $dp = json_decode(BusinessSetting::where(['key' => 'digital_payment'])->first()->value, true);

        $dm_config = Helpers::get_business_settings('delivery_management');
        $delivery_management = array(
            "status" => (int) $dm_config['status'],
            "min_shipping_charge" => (float) $dm_config['min_shipping_charge'],
            "shipping_per_km" => (float) $dm_config['shipping_per_km'],
        );

        $cookies_config = Helpers::get_business_settings('cookies');
        $cookies_management = array(
            "status" => (int) $cookies_config['status'],
            "text" => $cookies_config['text'],
        );

        return response()->json([
            'ecommerce_name' => BusinessSetting::where(['key' => 'restaurant_name'])->first()->value,
            'ecommerce_logo' => BusinessSetting::where(['key' => 'logo'])->first()->value,
            'app_logo' => BusinessSetting::where(['key' => 'app_logo'])->first()->value,
            'ecommerce_address' => BusinessSetting::where(['key' => 'address'])->first()->value,
            'ecommerce_phone' => BusinessSetting::where(['key' => 'phone'])->first()->value,
            'ecommerce_email' => BusinessSetting::where(['key' => 'email_address'])->first()->value,
            'ecommerce_location_coverage' => Branch::where(['id' => 1])->first(['longitude', 'latitude', 'coverage']),
            'minimum_order_value' => (float)BusinessSetting::where(['key' => 'minimum_order_value'])->first()->value,
            'self_pickup' => (int)BusinessSetting::where(['key' => 'self_pickup'])->first()->value,
            'base_urls' => [
                'product_image_url' => asset('storage/app/public/product'),
                'customer_image_url' => asset('storage/app/public/profile'),
                'banner_image_url' => asset('storage/app/public/banner'),
                'category_image_url' => asset('storage/app/public/category'),
                'category_banner_image_url' => asset('storage/app/public/category/banner'),
                'review_image_url' => asset('storage/app/public/review'),
                'notification_image_url' => asset('storage/app/public/notification'),
                'ecommerce_image_url' => asset('storage/app/public/ecommerce'),
                'delivery_man_image_url' => asset('storage/app/public/delivery-man'),
                'chat_image_url' => asset('storage/app/public/conversation'),
                'flash_sale_image_url' => asset('storage/app/public/flash-sale'),
                'gateway_image_url' => asset('storage/app/public/payment_modules/gateway_image'),
            ],
            'currency_symbol' => $currency_symbol,
            'delivery_charge' => (float) BusinessSetting::where(['key' => 'delivery_charge'])->first()->value,
            'delivery_management' => $delivery_management,
            'cash_on_delivery' => $cod['status'] == 1 ? 'true' : 'false',
            'digital_payment' => $dp['status'] == 1 ? 'true' : 'false',
            'branches' => Branch::all(['id', 'name', 'email', 'longitude', 'latitude', 'address', 'coverage']),
            'terms_and_conditions' => BusinessSetting::where(['key' => 'terms_and_conditions'])->first()->value,
            'privacy_policy' => BusinessSetting::where(['key' => 'privacy_policy'])->first()->value,
            'about_us' => BusinessSetting::where(['key' => 'about_us'])->first()->value,
            'email_verification' => (boolean)Helpers::get_business_settings('email_verification') ?? 0,
            'phone_verification' => (boolean)Helpers::get_business_settings('phone_verification') ?? 0,
            'currency_symbol_position' => Helpers::get_business_settings('currency_symbol_position') ?? 'right',
            'maintenance_mode' => (boolean)Helpers::get_business_settings('maintenance_mode') ?? 0,
            'country' => Helpers::get_business_settings('country') ?? 'BD',
            'play_store_config' => [
                "status"=> (boolean)Helpers::get_business_settings('play_store_config')['status'],
                "link"=> Helpers::get_business_settings('play_store_config')['link'],
                "min_version"=> Helpers::get_business_settings('play_store_config')['min_version'],
            ],
            'app_store_config' => [
                "status"=> (boolean)Helpers::get_business_settings('app_store_config')['status'],
                "link"=> Helpers::get_business_settings('app_store_config')['link'],
                "min_version"=> Helpers::get_business_settings('app_store_config')['min_version'],
            ],
            'social_media_link' => SocialMedia::orderBy('id', 'desc')->active()->get(),
            'software_version' => (string)env('SOFTWARE_VERSION')??null,
            'footer_text' => Helpers::get_business_settings('footer_text'),
            'dm_self_registration' => (int) Helpers::get_business_settings('dm_self_registration'),
            'otp_resend_time' => Helpers::get_business_settings('otp_resend_time') ?? 60,
            'cookies_management' => $cookies_management,
            'social_login' => [
                'google' => (integer)BusinessSetting::where(['key' => 'google_social_login'])->first()->value,
                'facebook' => (integer)BusinessSetting::where(['key' => 'facebook_social_login'])->first()->value,
            ],
            'whatsapp' => json_decode(BusinessSetting::where(['key' => 'whatsapp'])->first()->value, true),
            'telegram' => json_decode(BusinessSetting::where(['key' => 'telegram'])->first()->value, true),
            'messenger' => json_decode(BusinessSetting::where(['key' => 'messenger'])->first()->value, true),
            'digital_payment_status' => (integer)$digital_payment_status_value['status'],
            'active_payment_method_list' => (integer)$digital_payment_status_value['status'] == 1 ? $active_addon_payment_lists : [],
        ]);
    }

    private function getPaymentMethods()
    {
        // Check if the addon_settings table exists
        if (!Schema::hasTable('addon_settings')) {
            return [];
        }

        $methods = DB::table('addon_settings')->where('settings_type', 'payment_config')->get();
        $env = env('APP_ENV') == 'live' ? 'live' : 'test';
        $credentials = $env . '_values';

        $data = [];
        foreach ($methods as $method) {
            $credentialsData = json_decode($method->$credentials);
            $additional_data = json_decode($method->additional_data);
            if ($credentialsData->status == 1) {
                $data[] = [
                    'gateway' => $method->key_name,
                    'gateway_title' => $additional_data?->gateway_title,
                    'gateway_image' => $additional_data?->gateway_image
                ];
            }
        }
        return $data;
    }

    private function getDefaultPaymentMethods()
    {
        // Check if the addon_settings table exists
        if (!Schema::hasTable('addon_settings')) {
            return [];
        }

        $methods = DB::table('addon_settings')
            ->whereIn('settings_type', ['payment_config'])
            ->whereIn('key_name', ['ssl_commerz','paypal','stripe','razor_pay','senang_pay','paystack','paymob_accept','flutterwave','bkash','mercadopago'])
            ->get();

        $env = env('APP_ENV') == 'live' ? 'live' : 'test';
        $credentials = $env . '_values';

        $data = [];
        foreach ($methods as $method) {
            $credentialsData = json_decode($method->$credentials);
            $additional_data = json_decode($method->additional_data);
            if ($credentialsData->status == 1) {
                $data[] = [
                    'gateway' => $method->key_name,
                    'gateway_title' => $additional_data?->gateway_title,
                    'gateway_image' => $additional_data?->gateway_image
                ];
            }
        }
        return $data;
    }
}
