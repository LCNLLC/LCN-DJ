<?php

use App\Models\Referral;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\City;
use App\Models\Shop;
use App\Models\User;
use App\Models\Addon;
use App\Models\Coupon;
use App\Models\Seller;
use App\Models\Upload;
use App\Models\Wallet;
use App\Models\Address;
use App\Models\Carrier;
use App\Models\Country;
use App\Models\Product;
use App\Models\Currency;
use App\Models\CouponUsage;
use App\Models\Translation;
use App\Models\ProductStock;
use App\Models\CombinedOrder;
use App\Models\SellerPackage;
use App\Models\BusinessSetting;
use App\Models\CustomerPackage;
use App\Utility\SendSMSUtility;
use App\Utility\CategoryUtility;
use App\Models\SellerPackagePayment;
use App\Utility\NotificationUtility;
use App\Http\Resources\V2\CarrierCollection;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\ClubPointController;
use App\Http\Controllers\CommissionController;
use AizPackages\ColorCodeConverter\Services\ColorCodeConverter;
use App\Mail\EmailManager;
use App\Models\AffiliateConfig;
use App\Models\EmailLanguage;
use App\Models\AuctionProductBid;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Subscriber;
use App\Models\JobProfile;
use App\Models\JobsCategory;
use App\Models\ShippingCostSize;
use App\Models\DiscountDay;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\FlashDeal;
use App\Models\Language;
use App\Models\PreorderProduct;
use App\Models\Tax;
use App\Models\PaymentMethod;
use App\Models\TaxValue;

//function getParents($userid ){
//
//    echo ($userid)
//
//}
//


if (!function_exists("userAffiliateRules")) {

    function userAffiliateRules($id=null)
    {
    //    $total_deposit = Deposit::whereUserId(Auth::id())->whereStatus(1);
    //    $total_withdraw = Withdraw::whereUserId(Auth::id())->whereStatus(1);
    //    $total_trans = Transection::whereUserId(Auth::id());
    //    $total_ref_com = Transection::whereUserId(Auth::id())->where('type', 11);
    //    $investes = Invest::whereUserId(Auth::id())->get();
    //    $trans = Invest::where('user_id', Auth::id())->sum('return_rec_time');
    //    $gn_setting = GeneralSettings::first();

        $user_id = $id ? $id : auth()->id();
        $user_referral = User::where('referred_by',  $user_id)->count();


        $user_total_referral = User::where('referred_by',  $user_id)->count();

        $level = Referral::where('people', '>=', $user_total_referral)->first();


        if (!$level && $level == null && $user_total_referral > 0) {

            $level = Referral::select('*')->orderBy('id', 'desc')->first();

        }

        $gn_setting['user_level'] = $level;
        $gn_setting['user_referral'] = $user_referral;
    //    $gn_setting['total_trans'] = $total_trans;
    //    $gn_setting['trans'] = $trans;

        return $gn_setting;
    }

}

if(! function_exists("printAboveReferralTree"))  {

    function printAboveReferralTree($userId, $level = 0)
    {
        $user = User::find($userId);


        echo  $user->id ;

        // Get the user who referred the current user
        $referrer = User::find($user->referred_by);

        // If there is a referrer, print it recursively
        if ($referrer) {
            // Recursive call for the referrer
            printAboveReferralTree($referrer->id, $level + 1);

        }


    }

}
if (!function_exists("printReferralTree")) {
    function printReferralTree($userId, $level = 0)
    {
        $user = User::find($userId);


        echo '<ul>';
        echo '<li >';
        echo '<span  style="text-decoration: underline dotted">
        <i class="fas fa-user px-1"></i>User ID: ' . $user->id . '</span>';

        // Get the referrals of the current user
        $referrals = $user->referrals;

        // If there are referrals, print them recursively
        if ($referrals->count() > 0) {
            echo '<ul>';
            foreach ($referrals as $referral) {
                echo '<li class="pt-4">';
                // Recursive call for each referral
                printReferralTree($referral->id, $level + 1);
                echo '</li>';
            }
            echo '</ul>';
        }

        echo '</li>';
        echo '</ul>';
    }
}
if (!function_exists('get_active_flash_deals')) {
    function get_active_flash_deals()
    {
        $activated_flash_deal_query = FlashDeal::query();
        $activated_flash_deal_query = $activated_flash_deal_query->where("status", 1);

        return $activated_flash_deal_query->get();
    }
}
if (!function_exists('get_active_taxes')) {
    function get_active_taxes()
    {
        $activated_tax_query = Tax::query();
        $activated_tax_query = $activated_tax_query->where("tax_status", 1);

        return $activated_tax_query->get();
    }
}



// Get all brands
if (!function_exists('get_all_brands')) {
    function get_all_brands()
    {
        $brand_query = Brand::query();
        return  $brand_query->get();
    }
}

// Get single brands
if (!function_exists('get_brands')) {
    function get_brands($brand_ids)
    {
        $brand_query = Brand::query();
        $brands = $brand_query->whereIn('id', $brand_ids)->get();
        return $brands;
    }
}

// Get single brands
if (!function_exists('get_single_brand')) {
    function get_single_brand($brand_id)
    {
        $brand_query = Brand::query();
        return $brand_query->find($brand_id);
    }
}

// Get Brands by products
if (!function_exists('get_brands_by_products')) {
    function get_brands_by_products($usrt_id)
    {
        $product_query = Product::query();
        $brand_ids =  $product_query->where('user_id', $usrt_id)->isApprovedPublished()->whereNotNull('brand_id')->pluck('brand_id')->toArray();

        $brand_query = Brand::query();
        return $brand_query->whereIn('id', $brand_ids)->get();
    }
}

if (!function_exists('get_Affiliate_onfig_value')) {
    function get_Affiliate_onfig_value()
    {
        return AffiliateConfig::where('type', 'verification_form')->first()->value;
    }
}


if (!function_exists('get_all_sellers')) {
    function get_all_sellers()
    {
        $seller_query = User::query();
        $seller_query->where('user_type', 'seller');

        return $seller_query->get();
    }
}

// Get category
if (!function_exists('get_category')) {
    function get_category($category_ids)
    {
        $category_query = Category::query();
        $category_query->with('coverImage');

        $category_query->whereIn('id', $category_ids);

        $categories = $category_query->get();
        return $categories;
    }
}

// Get single category
if (!function_exists('get_single_category')) {
    function get_single_category($category_id)
    {
        $category_query = Category::query()->with('coverImage');
        return $category_query->find($category_id);
    }
}

// Get categories by level zero
if (!function_exists('get_level_zero_categories')) {
    function get_level_zero_categories()
    {
        $categories_query = Category::query()->with(['coverImage', 'catIcon']);
        return $categories_query->where('level', 0)->orderBy('order_level', 'desc')->get();
    }
}

// Get categories by products
if (!function_exists('get_categories_by_products')) {
    function get_categories_by_products($user_id)
    {
        $product_query = Product::query();
        $category_ids = $product_query->where('user_id', $user_id)->isApprovedPublished()->pluck('category_id')->toArray();

        $category_query = Category::query();
        return $category_query->whereIn('id', $category_ids)->get();
    }
}
// Get categories by products
if (!function_exists('get_categories_by_preorder_products')) {
    function get_categories_by_preorder_products($user_id)
    {
        $product_query = PreorderProduct::query();
        $category_ids = $product_query->where('user_id', $user_id)->where('is_published', 1)->pluck('category_id')->toArray();

        $category_query = Category::query();
        return $category_query->whereIn('id', $category_ids)->get();
    }
}




if (!function_exists('referrals')) {
    function referrals()   {


        $userID = 3;
        $user = User::findOrFail($userID);
        $allReferrals = $user->allReferrals;
        dd($allReferrals->toArray());
    }
}


//  to make unique referral_code
if (!function_exists('unique_reference_link')) {

   function unique_reference_link()
   {

    $reference_link = mt_rand(10000000000, 99999999999);
    if (User::where('referral_code',$reference_link)->count() == 0) {
        return $reference_link;
    }
    unique_reference_link();
}
}



if (!function_exists('get_system_language')) {

    function get_system_language()
    {
        $language_query = Language::query();

        $locale = 'en';
        if (Session::has('locale')) {
            $locale = Session::get('locale', Config::get('app.locale'));
        }

        $language_query->where('code',  $locale);

        return $language_query->first();
    }
}


if (!function_exists('parseFile')) {
    function parseFile($filePath = 'resources/views/frontend/policies/faq.blade.php')
    {
        // Read the file content
        $fileContent = file_get_contents($filePath);

        // Define the regular expression pattern to match {{translate()}}
        $pattern = '/{{\s*translate\(\s*(.*?)\s*\)\s*}}/';

        // Initialize an array to store matched translations
        $translations = [];

        // Use preg_match_all to find all matches in the file content
        preg_match_all($pattern, $fileContent, $matches);

        // Extract the matched translations
        foreach ($matches[1] as $match) {
            // Add the extracted translation to the translations array
            $translations[] = $match;
        }


        $translations[] = count($translations);

        return $translations;
    }
}
//get bussines_settings for referl
if (!function_exists('referlSetting')) {
    function referlSetting($name)
    {
        $setting = $name ? BusinessSetting::where('type', $name)->first() : null;
        return $setting ? $setting->value : null;

    }
}
//sensSMS function for OTP
if (!function_exists('sendSMS')) {
    function sendSMS($to, $from, $text, $template_id)
    {
        return SendSMSUtility::sendSMS($to, $from, $text, $template_id);
    }
}

// sendPreviewEmail
if (!function_exists('sendPreviewEmail')) {
    function sendPreviewEmail($emailId)
    {

        $type = 'general_email_template';
        $general_email = BusinessSetting::where('type', $type)->value('value');
        $emailLanguage = EmailLanguage::where('id', $emailId)->first();

        $array['view'] = 'emails.newsletter';
        $array['subject'] = $emailLanguage->subject;
        $array['from'] = env('MAIL_FROM_ADDRESS');

        $message = $emailLanguage->message;
        $general_email = str_replace("{{message}}", $message, $general_email);
        $array['content'] = $general_email;

        try {
            Mail::to('16.bscs.0963@gmail.com')->queue(new EmailManager($array));
        } catch (\Exception $e) {
            dd($e);
        }
        return 1;
    }


}

// low stock notification

if (!function_exists('lowStockNotification')) {
    function lowStockNotification($productId)
    {
        $product = Product::find($productId);
        $seller = User::find($product->user_id);
        $seller_name   = $seller->name;
        $seller_email  = $seller->email;
        $seller_lang   = $seller->language;
        $product_name  = $product->name;

        $type = 'general_email_template';
        $general_email = BusinessSetting::where('type', $type)->value('value');

        $lang = $seller_lang;

        $code = 'POOS';


        $emailLanguage = EmailLanguage::where('lang', $lang)->where('code', $code)->first();

        $array['view'] = 'emails.newsletter';
        $array['subject'] = $emailLanguage->subject;
        $array['from'] = env('MAIL_FROM_ADDRESS');

        $message = $emailLanguage->message;

        $general_email = str_replace("{{name}}", $seller_name, $general_email);
        $general_email = str_replace("{{message}}", $message, $general_email);
        $general_email = str_replace("{{product_name}}", $product_name, $general_email);
        $array['content'] = $general_email;

        try {
             //Mail::to($seller_email)->queue(new EmailManager($array));
            Mail::to('16.bscs.0963@gmail.com')->queue(new EmailManager($array));
        } catch (\Exception $e) {
            dd($e);
        }
        return 1;
    }


}

if (!function_exists('subscribeNotification')) {
    function subscribeNotification($productName, $productDescription, $productPrice)
    {

        $product_name  = $productName;
        $product_description  = $productDescription;
        $product_price  = $productPrice;

        $type = 'general_email_template';
        $general_email = BusinessSetting::where('type', $type)->value('value');

        $lang = 'en';

        $code = 'NPA';


        $emailLanguage = EmailLanguage::where('lang', $lang)->where('code', $code)->first();

        $array['view'] = 'emails.newsletter';
        $array['subject'] = $emailLanguage->subject;
        $array['from'] = env('MAIL_FROM_ADDRESS');

        $message = $emailLanguage->message;

        $general_email = str_replace("{{name}}",'Dear Subscriber', $general_email);
        $general_email = str_replace("{{message}}", $message, $general_email);
        $general_email = str_replace("{{product_name}}", $product_name, $general_email);
        $general_email = str_replace("{{product_description}}", $product_description, $general_email);
        $general_email = str_replace("{{product_price}}", $product_price, $general_email);
        $array['content'] = $general_email;

        $subscribers = Subscriber::all();

        foreach ($subscribers as $subscriber) {
            try {
                Mail::to($subscriber->email)->queue(new EmailManager($array));
            } catch (\Exception $e) {
                dd($e);
            }
        }
        return 1;
    }


}


if (!function_exists('jobNotification')) {
    function jobNotification($jobName, $jobDescription, $jobId)
    {

        $job_name  = $jobName;
        $job_description  = $jobDescription;
        $job_id  = $jobId;

        $type = 'general_email_template';
        $general_email = BusinessSetting::where('type', $type)->value('value');

        $lang = 'en';

        $code = 'EJOAY';


        $emailLanguage = EmailLanguage::where('lang', $lang)->where('code', $code)->first();

        $array['view'] = 'emails.newsletter';
        $array['subject'] = $emailLanguage->subject;
        $array['from'] = env('MAIL_FROM_ADDRESS');

        $message = $emailLanguage->message;



        $job_profiles = JobProfile::all();

        foreach ($job_profiles as $job_profile) {
            $general_email = str_replace("{{name}}",$job_profile->user->name, $general_email);
            $general_email = str_replace("{{message}}", $message, $general_email);
            $general_email = str_replace("{{job_name}}", $job_name, $general_email);
            $general_email = str_replace("{{job_description}}", $job_description, $general_email);

            $array['content'] = $general_email;
            // dd($job_profile->user->id);
            $matching_jobs_categories = JobsCategory::whereIn('category_id', $job_profile->categories)
            ->where('job_id', $job_id)
            ->get();

            if ($matching_jobs_categories->isNotEmpty()) {
                try {
                    Mail::to('16.bscs.0963@gmail.com')->queue(new EmailManager($array));
                    // Mail::to($job_profile->user->email)->queue(new EmailManager($array));
                } catch (\Exception $e) {
                    dd($e);
                }
            }
        }
        return 1;
    }


}
//sensEmail

if (!function_exists('sendProductApprovedEmail')) {
    function sendProductApprovedEmail($productId = null, $approved = null)
    {
        $product = Product::find($productId);
        $seller = User::find($product->user_id);
        $seller_name   = $seller->name;
        $seller_email  = $seller->email;
        $seller_lang   = $seller->language;
        $product_name  = $product->name;

        $type = 'general_email_template';
        $general_email = BusinessSetting::where('type', $type)->value('value');

        $lang = $seller_lang;
        if($product->approved === 1 )
        {
            $code = 'PAPP';
        }
        else
        {
         $code = 'PDAPP';
     }

     $emailLanguage = EmailLanguage::where('lang', $lang)->where('code', $code)->first();

     $array['view'] = 'emails.newsletter';
     $array['subject'] = $emailLanguage->subject;
     $array['from'] = env('MAIL_FROM_ADDRESS');

     $message = $emailLanguage->message;

     $general_email = str_replace("{{name}}", $seller_name, $general_email);
     $general_email = str_replace("{{message}}", $message, $general_email);
     $general_email = str_replace("{{product_name}}", $product_name, $general_email);
     $array['content'] = $general_email;

     try {
             //Mail::to($seller_email)->queue(new EmailManager($array));
        Mail::to('16.bscs.0963@gmail.com')->queue(new EmailManager($array));
    } catch (\Exception $e) {
        dd($e);
    }
    return 1;
}
}


//auction End Notification Email

if (!function_exists('auctionEndNotification')) {
    function auctionEndNotification($productId = null)
    {
        $product = Product::find($productId);
        $seller = User::find($product->user_id);
        $seller_name   = $seller->name;
        $seller_email  = $seller->email;
        $seller_lang   = $seller->language;
        $product_name  = $product->name;

        $type = 'general_email_template';
        $general_email = BusinessSetting::where('type', $type)->value('value');

        $lang = $seller_lang;

        $code = 'PAEN';

        $emailLanguage = EmailLanguage::where('lang', $lang)->where('code', $code)->first();

        $array['view'] = 'emails.newsletter';
        $array['subject'] = $emailLanguage->subject;
        $array['from'] = env('MAIL_FROM_ADDRESS');

        $message = $emailLanguage->message;

        $general_email = str_replace("{{name}}", $seller_name, $general_email);
        $general_email = str_replace("{{message}}", $message, $general_email);
        $general_email = str_replace("{{product_name}}", $product_name, $general_email);
        $array['content'] = $general_email;

        try {
            //  Mail::to($seller_email)->queue(new EmailManager($array));
            // Mail::to('16.bscs.0963@gmail.com')->queue(new EmailManager($array));
        } catch (\Exception $e) {
            dd($e);
        }
        return 1;
    }
}


//Order status change Notification Email

if (!function_exists('orderStatusNotification')) {
    function orderStatusNotification($userId = null,$status = null,$orderCode = null)
    {
        $customer = User::find($userId);
        $customer_name   = $customer->name;
        $customer_email  = $customer->email;
        $customer_lang   = $customer->language;



        $type = 'general_email_template';
        $general_email = BusinessSetting::where('type', $type)->value('value');

        $lang = $customer_lang;

        $status = translate($status,$lang);

        $code = 'UOYO';

        $emailLanguage = EmailLanguage::where('lang', $lang)->where('code', $code)->first();

        $array['view'] = 'emails.newsletter';
        $array['subject'] = $emailLanguage->subject;
        $array['from'] = env('MAIL_FROM_ADDRESS');

        $message = $emailLanguage->message;

        $general_email = str_replace("{{name}}", $customer_name, $general_email);
        $general_email = str_replace("{{message}}", $message, $general_email);
        $general_email = str_replace("{{order_code}}", $orderCode, $general_email);
        $general_email = str_replace("{{order_status}}", $status, $general_email);
        $array['content'] = $general_email;

        try {
             //Mail::to($seller_email)->queue(new EmailManager($array));
            Mail::to('16.bscs.0963@gmail.com')->queue(new EmailManager($array));
        } catch (\Exception $e) {
            dd($e);
        }
        return 1;
    }
}

//Seller Account Status Notification

if (!function_exists('SellerAccountStatusNotification')) {
    function SellerAccountStatusNotification($userId = null,$status = null)
    {
        $customer = User::find($userId);
        $customer_name   = $customer->name;
        $customer_email  = $customer->email;
        $customer_lang   = $customer->language;



        $type = 'general_email_template';
        $general_email = BusinessSetting::where('type', $type)->value('value');

        $lang = $customer_lang;

        $status = translate($status,$lang);
        if($status == 1)
        {
            $code = 'SAA';
        }
        else
        {
            $code = 'SADA';
        }

        $emailLanguage = EmailLanguage::where('lang', $lang)->where('code', $code)->first();

        $array['view'] = 'emails.newsletter';
        $array['subject'] = $emailLanguage->subject;
        $array['from'] = env('MAIL_FROM_ADDRESS');

        $message = $emailLanguage->message;

        $general_email = str_replace("{{name}}", $customer_name, $general_email);
        $general_email = str_replace("{{message}}", $message, $general_email);
        $array['content'] = $general_email;

        try {
             //Mail::to($seller_email)->queue(new EmailManager($array));
            Mail::to('16.bscs.0963@gmail.com')->queue(new EmailManager($array));
        } catch (\Exception $e) {
            dd($e);
        }
        return 1;
    }
}

if (!function_exists('SellerAccountStatusBanUnbendDeleteNotification')) {
    function SellerAccountStatusBanUnbendDeleteNotification($userId = null,$account_status = null)
    {
        $customer = User::find($userId);
        $customer_name   = $customer->name;
        $customer_email  = $customer->email;
        $customer_lang   = $customer->language;



        $type = 'general_email_template';
        $general_email = BusinessSetting::where('type', $type)->value('value');

        $lang = $customer_lang;

        $account_status = translate($account_status,$lang);


        $code = 'UOYSASBUAD';


        $emailLanguage = EmailLanguage::where('lang', $lang)->where('code', $code)->first();

        $array['view'] = 'emails.newsletter';
        $array['subject'] = $emailLanguage->subject;
        $array['from'] = env('MAIL_FROM_ADDRESS');

        $message = $emailLanguage->message;

        $general_email = str_replace("{{name}}", $customer_name, $general_email);
        $general_email = str_replace("{{message}}", $message, $general_email);
        $general_email = str_replace("{{status}}", $account_status, $general_email);
        $array['content'] = $general_email;

        try {
             //Mail::to($seller_email)->queue(new EmailManager($array));
            Mail::to('16.bscs.0963@gmail.com')->queue(new EmailManager($array));
        } catch (\Exception $e) {
            dd($e);
        }
        return 1;
    }
}


if (!function_exists('CustomerBanUnbendNotification')) {
    function CustomerBanUnbendNotification($userId = null,$account_status = null,$value =null)
    {
        $customer = User::find($userId);
        $customer_name   = $customer->name;
        $customer_email  = $customer->email;
        $customer_lang   = $customer->language;



        $type = 'general_email_template';
        $general_email = BusinessSetting::where('type', $type)->value('value');

        $lang = $customer_lang;

        $account_status = translate($account_status,$lang);

        if($value == 0)
        {
            $code = 'CAUB';
        }
        else
        {
            $code = 'CAB';
        }


        $emailLanguage = EmailLanguage::where('lang', $lang)->where('code', $code)->first();

        $array['view'] = 'emails.newsletter';
        $array['subject'] = $emailLanguage->subject;
        $array['from'] = env('MAIL_FROM_ADDRESS');

        $message = $emailLanguage->message;

        $general_email = str_replace("{{name}}", $customer_name, $general_email);
        $general_email = str_replace("{{message}}", $message, $general_email);
        $general_email = str_replace("{{status}}", $account_status, $general_email);
        $array['content'] = $general_email;

        try {
             //Mail::to($seller_email)->queue(new EmailManager($array));
            Mail::to('16.bscs.0963@gmail.com')->queue(new EmailManager($array));
        } catch (\Exception $e) {
            dd($e);
        }
        return 1;
    }
}


/// Bid Status Notification
if (!function_exists('BidStatusNotification')) {
    function BidStatusNotification($userId = null,$account_status = null,$product_name =null)
    {
        $customer = User::find($userId);
        $customer_name   = $customer->name;
        $customer_email  = $customer->email;
        $customer_lang   = $customer->language;



        $type = 'general_email_template';
        $general_email = BusinessSetting::where('type', $type)->value('value');

        $lang = $customer_lang;

        $account_status = translate($account_status,$lang);


        $code = 'BSNF';



        $emailLanguage = EmailLanguage::where('lang', $lang)->where('code', $code)->first();

        $array['view'] = 'emails.newsletter';
        $array['subject'] = $emailLanguage->subject;
        $array['from'] = env('MAIL_FROM_ADDRESS');

        $message = $emailLanguage->message;

        $general_email = str_replace("{{name}}", $customer_name, $general_email);
        $general_email = str_replace("{{message}}", $message, $general_email);
        $general_email = str_replace("{{status}}", $account_status, $general_email);
        $general_email = str_replace("{{product_name}}", $product_name, $general_email);
        $array['content'] = $general_email;

        try {
             //Mail::to($seller_email)->queue(new EmailManager($array));
            Mail::to('16.bscs.0963@gmail.com')->queue(new EmailManager($array));
        } catch (\Exception $e) {
            dd($e);
        }
        return 1;
    }
}



if (!function_exists('messageToSeller')) {
    function messageToSeller($productId = null)
    {
        $product = Product::find($productId);
        $seller = User::find($product->user_id);
        $seller_name   = $seller->name;
        $seller_email  = $seller->email;
        $seller_lang   = $seller->language;
        $product_name  = $product->name;

        $type = 'general_email_template';
        $general_email = BusinessSetting::where('type', $type)->value('value');

        $lang = $seller_lang;

        $code = 'PM';


        $emailLanguage = EmailLanguage::where('lang', $lang)->where('code', $code)->first();

        $array['view'] = 'emails.newsletter';
        $array['subject'] = $emailLanguage->subject;
        $array['from'] = env('MAIL_FROM_ADDRESS');

        $message = $emailLanguage->message;

        $general_email = str_replace("{{name}}", $seller_name, $general_email);
        $general_email = str_replace("{{message}}", $message, $general_email);
        $general_email = str_replace("{{product_name}}", $product_name, $general_email);
        $array['content'] = $general_email;

        try {
             //Mail::to($seller_email)->queue(new EmailManager($array));
            Mail::to('16.bscs.0963@gmail.com')->queue(new EmailManager($array));
        } catch (\Exception $e) {
            dd($e);
        }
        return 1;

    }
}

if (!function_exists('message')) {
    function message($userId = null)
    {

        $seller = User::find($userId);
        $seller_name   = $seller->name;
        $seller_email  = $seller->email;
        $seller_lang   = $seller->language;

        $type = 'general_email_template';
        $general_email = BusinessSetting::where('type', $type)->value('value');

        $lang = $seller_lang;
        $code = 'SMS';


        $emailLanguage = EmailLanguage::where('lang', $lang)->where('code', $code)->first();

        $array['view'] = 'emails.newsletter';
        $array['subject'] = $emailLanguage->subject;
        $array['from'] = env('MAIL_FROM_ADDRESS');

        $message = $emailLanguage->message;

        $general_email = str_replace("{{name}}", $seller_name, $general_email);
        $general_email = str_replace("{{message}}", $message, $general_email);
        $array['content'] = $general_email;

        try {
             //Mail::to($seller_email)->queue(new EmailManager($array));
            Mail::to('16.bscs.0963@gmail.com')->queue(new EmailManager($array));
        } catch (\Exception $e) {
            dd($e);
        }
        return 1;

    }
}


//highlights the selected navigation on admin panel
if (!function_exists('areActiveRoutes')) {
    function areActiveRoutes(array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }
    }
}

//highlights the selected navigation on frontend
if (!function_exists('areActiveRoutesHome')) {
    function areActiveRoutesHome(array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }
    }
}

//highlights the selected navigation on frontend
if (!function_exists('default_language')) {
    function default_language()
    {
        return env("DEFAULT_LANGUAGE");
    }
}

/**
 * Save JSON File
 * @return Response
 */
if (!function_exists('convert_to_usd')) {
    function convert_to_usd($amount)
    {
        $currency = Currency::find(get_setting('system_default_currency'));
        return (floatval($amount) / floatval($currency->exchange_rate)) * Currency::where('code', 'USD')->first()->exchange_rate;
    }
}

if (!function_exists('convert_to_kes')) {
    function convert_to_kes($amount)
    {
        $currency = Currency::find(get_setting('system_default_currency'));
        return (floatval($amount) / floatval($currency->exchange_rate)) * Currency::where('code', 'KES')->first()->exchange_rate;
    }
}

//filter products based on vendor activation system
if (!function_exists('filter_products')) {
    function filter_products($products)
    {
        $verified_sellers = verified_sellers_id();
        if (get_setting('vendor_system_activation') == 1) {
            return $products->where('approved', '1')
            ->where('published', '1')
            ->where('auction_product', 0)
            ->where(function ($p) use ($verified_sellers) {
                $p->where('added_by', 'admin')->orWhere(function ($q) use ($verified_sellers) {
                    $q->whereIn('user_id', $verified_sellers);
                });
            });
        } else {
            return $products->where('published', '1')->where('auction_product', 0)->where('added_by', 'admin');
        }
    }
}

//cache products based on category
if (!function_exists('get_cached_products')) {
    function get_cached_products($category_id = null)
    {
        $products = \App\Models\Product::where('published', 1)->where('approved', '1')->where('auction_product', 0);
        $verified_sellers = verified_sellers_id();
        if (get_setting('vendor_system_activation') == 1) {
            $products = $products->where(function ($p) use ($verified_sellers) {
                $p->where('added_by', 'admin')->orWhere(function ($q) use ($verified_sellers) {
                    $q->whereIn('user_id', $verified_sellers);
                });
            });
        } else {
            $products = $products->where('added_by', 'admin');
        }

        if ($category_id != null) {
            return Cache::remember('products-category-' . $category_id, 86400, function () use ($category_id, $products) {
                $category_ids = CategoryUtility::children_ids($category_id);
                $category_ids[] = $category_id;
                return $products->whereIn('category_id', $category_ids)->latest()->take(12)->get();
            });
        } else {
            return Cache::remember('products', 86400, function () use ($products) {
                return $products->latest()->take(12)->get();
            });
        }
    }
}

if (!function_exists('verified_sellers_id')) {
    function verified_sellers_id()
    {
        return Cache::rememberForever('verified_sellers_id', function () {
            return App\Models\Shop::where('verification_status', 1)->pluck('user_id')->toArray();
        });
    }
}

if (!function_exists('get_system_default_currency')) {
    function get_system_default_currency()
    {
        return Cache::remember('system_default_currency', 86400, function () {
            return Currency::findOrFail(get_setting('system_default_currency'));
        });
    }
}

//converts currency to home default currency
if (!function_exists('convert_price')) {
    function convert_price($price)
    {
        if (Session::has('currency_code') && (Session::get('currency_code') != get_system_default_currency()->code)) {
            $price = floatval($price) / floatval(get_system_default_currency()->exchange_rate);
            $price = floatval($price) * floatval(Session::get('currency_exchange_rate'));
        }

        if (
            request()->header('Currency-Code') &&
            request()->header('Currency-Code') != get_system_default_currency()->code
        ) {
            $price = floatval($price) / floatval(get_system_default_currency()->exchange_rate);
            $price = floatval($price) * floatval(request()->header('Currency-Exchange-Rate'));
        }
        return $price;
    }
}

//gets currency symbol
if (!function_exists('currency_symbol')) {
    function currency_symbol()
    {
        if (Session::has('currency_symbol')) {
            return Session::get('currency_symbol');
        }
        if (request()->header('Currency-Code')) {
            return request()->header('Currency-Code');
        }
        return get_system_default_currency()->symbol;
    }
}

//formats currency
if (!function_exists('format_price')) {
    function format_price($price, $isMinimize = false)
    {
        if (get_setting('decimal_separator') == 1) {
            $fomated_price = number_format($price, get_setting('no_of_decimals'));
            if (floor($price) == $price) {
                // The number has no decimal places
                $fomated_price = number_format($price, get_setting('no_of_decimals'));
            } else {
                // The number has decimal places
                $fomated_price = number_format($price, 0, '.', ',');
                // $fomated_price = number_format($price, 2, '.', ',');
            }
        } else {
            $fomated_price = number_format($price, get_setting('no_of_decimals'), ',', '.');
        }


        // Minimize the price
        if ($isMinimize) {
            $temp = number_format($price / 1000000000, get_setting('no_of_decimals'), ".", "");

            if ($temp >= 1) {
                $fomated_price = $temp . "B";
            } else {
                $temp = number_format($price / 1000000, get_setting('no_of_decimals'), ".", "");
                if ($temp >= 1) {
                    $fomated_price = $temp . "M";
                }
            }
        }

        if (get_setting('symbol_format') == 1) {
            return currency_symbol() . $fomated_price;
        } else if (get_setting('symbol_format') == 3) {
            return currency_symbol() . ' ' . $fomated_price;
        } else if (get_setting('symbol_format') == 4) {
            return $fomated_price . ' ' . currency_symbol();
        }
        return $fomated_price . currency_symbol();
    }
}


//formats price to home default price with convertion
if (!function_exists('single_price')) {
    function single_price($price)
    {
        return format_price(convert_price($price));
    }
}

if (!function_exists('discount_in_percentage')) {
    function discount_in_percentage($product)
    {
        $base = home_base_price($product, false);
        $reduced = home_discounted_base_price($product, false);
        $discount = $base - $reduced;
        $dp = ($discount * 100) / ($base > 0 ? $base : 1);
        return round($dp);
    }
}

if (!function_exists('discount_day')) {
    function discount_day($user_id)
    {
     $today = Carbon::today();
     $discont = DiscountDay::where('user_id', $user_id)
     ->where('status', 1)
     ->whereDate('discount_date', $today)
     ->value('discount');
     return $discont;
 }
}

//Shows Price on page based on carts
if (!function_exists('cart_product_price')) {
    function cart_product_price($cart_product, $product, $formatted = true, $tax = true)
    {
        //dd($cart_product);
        if ($product->product_type != 3) {
            $str = '';
            if ($cart_product['variation'] != null) {
                $str = $cart_product['variation'];
            }
            $price = 0;
            $product_stock = $product->stocks->where('variant', $str)->first();
            if ($product_stock) {
                $price = $product_stock->price;
            }

            if ($product->wholesale_product) {
                $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $cart_product['quantity'])->where('max_qty', '>=', $cart_product['quantity'])->first();
                if ($wholesalePrice) {
                    $price = $wholesalePrice->price;
                }
            }

            //discount calculation
            $discount_applicable = false;

            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            } elseif (
                strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
            ) {
                $discount_applicable = true;
            }

            if($cart_product->warranty_status == 1)
            {
                $price += ($price * $cart_product->warranty_cost) / 100;
            }

            if($product->product_type == 1)
            {
                $discount_day = discount_day($product->user_id);
                if ($discount_day > 0) {
                    $price -= ($price * $discount_day) / 100;
                } elseif ($discount_day < 0) {
                    $price += ($price * $discount_day) / 100;
                } else {
                           // echo "The value is zero.";
                }
            }


            if(get_setting('product_pr_adjustment_active') == 1) {
                $priceAdjustment = get_setting('product_price_adjustment');

                if ($priceAdjustment > 0) {
                    $price -= ($price * $priceAdjustment) / 100;

                } elseif ($priceAdjustment < 0) {
                    $price += ($price * $priceAdjustment) / 100;

                } else {
               // echo "The value is zero.";
                }
            }

            if ($discount_applicable) {
                if ($product->discount_type == 'percent') {
                    $price -= ($price * $product->discount) / 100;
                } elseif ($product->discount_type == 'amount') {
                    $price -= $product->discount;
                }
            }
        } else {
            $user_id = Auth::user()->id;
            $bid = AuctionProductBid::where('user_id', $user_id)
            ->where('product_id', $product->id)->where('status','Accepted')
            ->first();

            $price = $bid->amount; //$product->bids->max('amount');
        }

        //calculation of taxes
        if ($tax) {
            $taxAmount = 0;
            foreach ($product->taxes as $product_tax) {

                    $tax_data = TaxValue::where('product_type', 1)->latest()->first();
                    $tax_Amount = $tax_data ? $tax_data->tax_value : 0;
                    $tax_Type = $tax_data ? $tax_data->tax_type : null;

                    if ($tax_Type === 'amount')
                    {
                         $taxAmount += $tax_Amount;
                    }
                    elseif ($tax_Type === 'percent')
                    {
                        $taxAmount += ($price * $tax_Amount) / 100;
                    }

                // if ($product_tax->tax_type == 'percent') {
                //     $taxAmount += ($price * $product_tax->tax) / 100;
                // } elseif ($product_tax->tax_type == 'amount') {
                //     $taxAmount += $product_tax->tax;
                // }
            }
            $price += $taxAmount;
        }

        if ($formatted) {
            return format_price(convert_price($price));
        } else {
            return $price;
        }
    }
}

if (!function_exists('cart_product_tax')) {
    function cart_product_tax($cart_product, $product, $formatted = true)
    {
        $str = '';
        if ($cart_product['variation'] != null) {
            $str = $cart_product['variation'];
        }
        $product_stock = $product->stocks->where('variant', $str)->first();
        $price = $product_stock->price;

        //discount calculation
        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        //calculation of taxes
        $tax = 0;
        foreach ($product->taxes as $product_tax) {

            $tax_data = TaxValue::where('product_type', 1)->latest()->first();
            $tax_Amount = $tax_data ? $tax_data->tax_value : 0;
            $tax_Type = $tax_data ? $tax_data->tax_type : null;

            if ($tax_Type === 'amount')
            {
                 $tax += $tax_Amount;
            }
            elseif ($tax_Type === 'percent')
            {
                $tax += ($price * $tax_Amount) / 100;
            }


            // if ($product_tax->tax_type == 'percent') {
            //     $tax += ($price * $product_tax->tax) / 100;
            // } elseif ($product_tax->tax_type == 'amount') {
            //     $tax += $product_tax->tax;
            // }
        }

        if ($formatted) {
            return format_price(convert_price($tax));
        } else {
            return $tax;
        }
    }
}

if (!function_exists('cart_product_discount')) {
    function cart_product_discount($cart_product, $product, $formatted = false)
    {
        $str = '';
        if ($cart_product['variation'] != null) {
            $str = $cart_product['variation'];
        }
        $product_stock = $product->stocks->where('variant', $str)->first();
        $price = $product_stock->price;

        //discount calculation
        $discount_applicable = false;
        $discount = 0;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $discount = ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $discount = $product->discount;
            }
        }

        if ($formatted) {
            return format_price(convert_price($discount));
        } else {
            return $discount;
        }
    }
}

// all discount
if (!function_exists('carts_product_discount')) {
    function carts_product_discount($cart_products, $formatted = false)
    {
        $discount = 0;
        foreach ($cart_products as $key => $cart_product) {
            $str = '';
            $product = \App\Models\Product::find($cart_product['product_id']);
            if ($cart_product['variation'] != null) {
                $str = $cart_product['variation'];
            }
            $product_stock = $product->stocks->where('variant', $str)->first();
            $price = $product_stock->price;

            //discount calculation
            $discount_applicable = false;

            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            } elseif (
                strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
            ) {
                $discount_applicable = true;
            }

            if($product->product_type == 1)
            {
                $discount_day = discount_day($product->user_id);
                if ($discount_day > 0) {
                    $discount -= ($price * $discount_day) / 100;
                } elseif ($discount_day < 0) {
                    $discount += ($price * $discount_day) / 100;
                } else {
                           // echo "The value is zero.";
                }
            }
            // product adjustment
            if(get_setting('product_pr_adjustment_active') == 1) {
                $priceAdjustment = get_setting('product_price_adjustment');

                if ($priceAdjustment > 0) {
                    $discount -= ($price * $priceAdjustment) / 100;

                } elseif ($priceAdjustment < 0) {
                    $discount += ($price * $priceAdjustment) / 100;

                } else {
                   // echo "The value is zero.";
                }
            }

            if ($discount_applicable) {
                if ($product->discount_type == 'percent') {
                    $discount += ($price * $product->discount) / 100;
                } elseif ($product->discount_type == 'amount') {
                    $discount += $product->discount;
                }
            }
        }

        if ($formatted) {
            return format_price(convert_price($discount));
        } else {
            return $discount;
        }
    }
}

if (!function_exists('carts_coupon_discount')) {
    function carts_coupon_discount($code, $formatted = false)
    {
        $coupon = Coupon::where('code', $code)->first();
        $coupon_discount = 0;
        if ($coupon != null) {
            if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date) {
                if (CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first() == null) {
                    $coupon_details = json_decode($coupon->details);

                    $carts = Cart::where('user_id', Auth::user()->id)
                    ->where('owner_id', $coupon->user_id)
                    ->get();

                    if ($coupon->type == 'cart_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach ($carts as $key => $cartItem) {
                            $product = Product::find($cartItem['product_id']);
                            $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                            $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                            $shipping += $cartItem['shipping_cost'];
                        }
                        $sum = $subtotal + $tax + $shipping;

                        if ($sum >= $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }
                        }
                    } elseif ($coupon->type == 'product_base') {
                        foreach ($carts as $key => $cartItem) {
                            $product = Product::find($cartItem['product_id']);
                            foreach ($coupon_details as $key => $coupon_detail) {
                                if ($coupon_detail->product_id == $cartItem['product_id']) {
                                    if ($coupon->discount_type == 'percent') {
                                        $coupon_discount += (cart_product_price($cartItem, $product, false, false) * $coupon->discount / 100) * $cartItem['quantity'];
                                    } elseif ($coupon->discount_type == 'amount') {
                                        $coupon_discount += $coupon->discount * $cartItem['quantity'];
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($coupon_discount > 0) {
                Cart::where('user_id', Auth::user()->id)
                ->where('owner_id', $coupon->user_id)
                ->update(
                    [
                        'discount' => $coupon_discount / count($carts),
                    ]
                );
            } else {
                Cart::where('user_id', Auth::user()->id)
                ->where('owner_id', $coupon->user_id)
                ->update(
                    [
                        'discount' => 0,
                        'coupon_code' => null,
                    ]
                );
            }
        }

        if ($formatted) {
            return format_price(convert_price($coupon_discount));
        } else {
            return $coupon_discount;
        }
    }
}

//Shows Price on page based on low to high
if (!function_exists('home_price')) {
    function home_price($product, $formatted = true)
    {
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        if ($product->variant_product) {
            foreach ($product->stocks as $key => $stock) {
                if ($lowest_price > $stock->price) {
                    $lowest_price = $stock->price;
                }
                if ($highest_price < $stock->price) {
                    $highest_price = $stock->price;
                }
            }
        }

        foreach ($product->taxes as $product_tax) {

            $tax_data = TaxValue::where('product_type', 1)->latest()->first();
            $tax_Amount = $tax_data ? $tax_data->tax_value : 0;
            $tax_Type = $tax_data ? $tax_data->tax_type : null;

            if ($tax_Type === 'amount')
            {
                $lowest_price  += $tax_Amount;
                $highest_price += $tax_Amount;
            }
            elseif ($tax_Type === 'percent')
            {
                $lowest_price += ($lowest_price * $tax_Amount) / 100;
                $highest_price += ($highest_price * $tax_Amount) / 100;
            }

            // if ($product_tax->tax_type == 'percent') {
            //     $lowest_price += ($lowest_price * $product_tax->tax) / 100;
            //     $highest_price += ($highest_price * $product_tax->tax) / 100;
            // } elseif ($product_tax->tax_type == 'amount') {
            //     $lowest_price += $product_tax->tax;
            //     $highest_price += $product_tax->tax;
            // }
        }

        if ($formatted) {
            if ($lowest_price == $highest_price) {
                return format_price(convert_price($lowest_price));
            } else {
                return format_price(convert_price($lowest_price)) . ' - ' . format_price(convert_price($highest_price));
            }
        } else {
            return $lowest_price . ' - ' . $highest_price;
        }
    }
}

//Shows Price on page based on low to high with discount
if (!function_exists('home_discounted_price')) {
    function home_discounted_price($product, $formatted = true)
    {


     $lowest_price = $product->unit_price;
     $highest_price = $product->unit_price;

     if ($product->variant_product) {
        foreach ($product->stocks as $key => $stock) {
            if ($lowest_price > $stock->price) {
                $lowest_price = $stock->price;
            }
            if ($highest_price < $stock->price) {
                $highest_price = $stock->price;
            }
        }
    }

    $discount_applicable = false;

    if ($product->discount_start_date == null) {
        $discount_applicable = true;
    } elseif (
        strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
        strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
    ) {
        $discount_applicable = true;
    }

    //discount day
    if($product->product_type == 1)
    {
        $discount_day = discount_day($product->user_id);
        if ($discount_day > 0) {
            $lowest_price -= ($lowest_price * $discount_day) / 100;
            $highest_price -= ($highest_price * $discount_day) / 100;
        } elseif ($discount_day < 0) {
            $lowest_price += ($lowest_price * $discount_day) / 100;
            $highest_price += ($highest_price * $discount_day) / 100;
        } else {
                   // echo "The value is zero.";
        }
    }

    //price adjustment

    if(get_setting('product_pr_adjustment_active') == 1 && $product->product_type == 1) {
        $priceAdjustment = get_setting('product_price_adjustment');

        if ($priceAdjustment > 0) {
            $lowest_price -= ($lowest_price * $priceAdjustment) / 100;
            $highest_price -= ($highest_price * $priceAdjustment) / 100;
        } elseif ($priceAdjustment < 0) {
            $lowest_price += ($lowest_price * $priceAdjustment) / 100;
            $highest_price += ($highest_price * $priceAdjustment) / 100;
        } else {
               // echo "The value is zero.";
        }
    }

    if ($discount_applicable) {
        if ($product->discount_type == 'percent') {
            $lowest_price -= ($lowest_price * $product->discount) / 100;
            $highest_price -= ($highest_price * $product->discount) / 100;
        } elseif ($product->discount_type == 'amount') {
            $lowest_price -= $product->discount;
            $highest_price -= $product->discount;
        }
    }

    foreach ($product->taxes as $product_tax) {

          $tax_data = TaxValue::where('product_type', 1)->latest()->first();
            $tax_Amount = $tax_data ? $tax_data->tax_value : 0;
            $tax_Type = $tax_data ? $tax_data->tax_type : null;

            if ($tax_Type === 'amount')
            {
                $lowest_price  += $tax_Amount;
                $highest_price += $tax_Amount;
            }
            elseif ($tax_Type === 'percent')
            {
                $lowest_price += ($lowest_price * $tax_Amount) / 100;
                $highest_price += ($highest_price * $tax_Amount) / 100;
            }
        // if ($product_tax->tax_type == 'percent') {
        //     $lowest_price += ($lowest_price * $product_tax->tax) / 100;
        //     $highest_price += ($highest_price * $product_tax->tax) / 100;
        // } elseif ($product_tax->tax_type == 'amount') {
        //     $lowest_price += $product_tax->tax;
        //     $highest_price += $product_tax->tax;
        // }
    }

    if ($formatted) {
        if ($lowest_price == $highest_price) {
            return format_price(convert_price($lowest_price));
        } else {
            return format_price(convert_price($lowest_price)) . ' - ' . format_price(convert_price($highest_price));
        }
    } else {
        return $lowest_price . ' - ' . $highest_price;
    }
}
}

//Shows Base Price
if (!function_exists('home_base_price_by_stock_id')) {
    function home_base_price_by_stock_id($id)
    {
        $product_stock = ProductStock::findOrFail($id);
        $price = $product_stock->price;
        $tax = 0;

        foreach ($product_stock->product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;
        return format_price(convert_price($price));
    }
}


if (!function_exists('home_base_price')) {
    function home_base_price($product, $formatted = true)
    {
        $price = $product->unit_price;
        $tax = 0;

        foreach ($product->taxes as $product_tax) {

            $tax_data = TaxValue::where('product_type', 1)->latest()->first();
            $tax_Amount = $tax_data ? $tax_data->tax_value : 0;
            $tax_Type = $tax_data ? $tax_data->tax_type : null;

            if ($tax_Type === 'amount')
            {
                 $tax += $tax_Amount;
            }
            elseif ($tax_Type === 'percent')
            {
                $tax += ($price * $tax_Amount) / 100;
            }
            // if ($product_tax->tax_type == 'percent') {
            //     $tax += ($price * $product_tax->tax) / 100;
            // } elseif ($product_tax->tax_type == 'amount') {
            //     $tax += $product_tax->tax;
            // }
        }
        $price += $tax;
        return $formatted ? format_price(convert_price($price)) : convert_price($price);
    }
}

//Shows Base Price with discount
if (!function_exists('home_discounted_base_price_by_stock_id')) {
    function home_discounted_base_price_by_stock_id($id)
    {
        $product_stock = ProductStock::findOrFail($id);
        $product = $product_stock->product;
        $price = $product_stock->price;
        $tax = 0;

        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }


        if($product->product_type == 1)
        {
            $discount_day = discount_day($product->user_id);
            if ($discount_day > 0) {
                $price -= ($price * $discount_day) / 100;
            } elseif ($discount_day < 0) {
                $price += ($price * $discount_day) / 100;
            } else {
                           // echo "The value is zero.";
            }
        }
        // adjustment price
        if(get_setting('product_pr_adjustment_active') == 1) {
            $priceAdjustment = get_setting('product_price_adjustment');

            if ($priceAdjustment > 0) {
                $price -= ($price * $priceAdjustment) / 100;
            } elseif ($priceAdjustment < 0) {
                $price += ($price * $priceAdjustment) / 100;
            } else {
               // echo "The value is zero.";
            }
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        foreach ($product->taxes as $product_tax) {

            $tax_data = TaxValue::where('product_type', 1)->latest()->first();
            $tax_Amount = $tax_data ? $tax_data->tax_value : 0;
            $tax_Type = $tax_data ? $tax_data->tax_type : null;

            if ($tax_Type === 'amount')
            {
                 $tax += $tax_Amount;
            }
            elseif ($tax_Type === 'percent')
            {
                $tax += ($price * $tax_Amount) / 100;
            }
            // if ($product_tax->tax_type == 'percent') {
            //     $tax += ($price * $product_tax->tax) / 100;
            // } elseif ($product_tax->tax_type == 'amount') {
            //     $tax += $product_tax->tax;
            // }
        }
        $price += $tax;

        return format_price(convert_price($price));
    }
}


//Shows Base Price with discount
if (!function_exists('home_discounted_base_price')) {
    function home_discounted_base_price($product, $formatted = true)
    {
        $price = $product->unit_price;
        $tax = 0;

        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }

        if($product->product_type == 1)
        {
            $discount_day = discount_day($product->user_id);
            if ($discount_day > 0) {
                $price -= ($price * $discount_day) / 100;
            } elseif ($discount_day < 0) {
                $price += ($price * $discount_day) / 100;
            } else {
                           // echo "The value is zero.";
            }
        }
        // adjustment price
        if(get_setting('product_pr_adjustment_active') == 1) {
            $priceAdjustment = get_setting('product_price_adjustment');

            if ($priceAdjustment > 0) {
                $price -= ($price * $priceAdjustment) / 100;
            } elseif ($priceAdjustment < 0) {
                $price += ($price * $priceAdjustment) / 100;
            } else {
               // echo "The value is zero.";
            }
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        foreach ($product->taxes as $product_tax) {

            $tax_data = TaxValue::where('product_type', 1)->latest()->first();
            $tax_Amount = $tax_data ? $tax_data->tax_value : 0;
            $tax_Type = $tax_data ? $tax_data->tax_type : null;

            if ($tax_Type === 'amount')
            {
                 $tax += $tax_Amount;
            }
            elseif ($tax_Type === 'percent')
            {
                $tax += ($price * $tax_Amount) / 100;
            }
            // if ($product_tax->tax_type == 'percent') {
            //     $tax += ($price * $product_tax->tax) / 100;
            // } elseif ($product_tax->tax_type == 'amount') {
            //     $tax += $product_tax->tax;
            // }
        }
        $price += $tax;
        // echo "<br>";
        // print_r(convert_price($price));
           //     print_r($price);
        // echo "<br>f=";
        // print_r(format_price(convert_price($price)));
        return $formatted ? format_price(convert_price($price)) : convert_price($price);
    }
}

if (!function_exists('renderStarRating')) {
    function renderStarRating($rating, $maxRating = 5)
    {
        $fullStar = "<i class = 'las la-star active'></i>";
        $halfStar = "<i class = 'las la-star half'></i>";
        $emptyStar = "<i class = 'las la-star'></i>";
        $rating = $rating <= $maxRating ? $rating : $maxRating;

        $fullStarCount = (int)$rating;
        $halfStarCount = ceil($rating) - $fullStarCount;
        $emptyStarCount = $maxRating - $fullStarCount - $halfStarCount;

        $html = str_repeat($fullStar, $fullStarCount);
        $html .= str_repeat($halfStar, $halfStarCount);
        $html .= str_repeat($emptyStar, $emptyStarCount);
        echo $html;
    }
}


if (!function_exists('get_activate_payment_methods')) {
    function get_activate_payment_methods()
    {
        $payment_methods = PaymentMethod::where('active', 1)
                                        ->Where(function($query){
                                            $query->whereNull('addon_identifier')
                                            ->orWhere(function($q){
                                                if(addon_is_activated('paytm')){
                                                    $q->where('addon_identifier', 'paytm');
                                                }
                                            })
                                            ->orWhere(function($q){
                                                if(addon_is_activated('african_pg')){
                                                    $q->where('addon_identifier', 'african_pg');
                                                }
                                            });
                                        });
        return $payment_methods->get();
    }
}

function translate($key, $lang = null, $addslashes = false)
{
    if ($lang == null) {
        $lang = App::getLocale();
    }


    $lang_key = preg_replace('/[^A-Za-z0-9\_]/', '', str_replace(' ', '_', strtolower($key)));

    $translations_en = Cache::rememberForever('translations-en', function () {
        return Translation::where('lang', 'en')->pluck('lang_value', 'lang_key')->toArray();
    });
    // if($key !== 'Privacy Policy')
    // {
    //     echo $lang_key;
    //     dd($translations_en);

    //     return  $translations_en;
    // }
    if (!isset($translations_en[$lang_key])) {
        $translation_def = new Translation;
        $translation_def->lang = 'en';
        $translation_def->lang_key = $lang_key;
        $translation_def->lang_value = str_replace(array("\r", "\n", "\r\n"), "", $key);
        $translation_def->save();
        Cache::forget('translations-en');
    }

    // return user session lang
    $translation_locale = Cache::rememberForever("translations-{$lang}", function () use ($lang) {
        return Translation::where('lang', $lang)->pluck('lang_value', 'lang_key')->toArray();
    });
    if (isset($translation_locale[$lang_key])) {
        return $addslashes ? addslashes(trim($translation_locale[$lang_key])) : trim($translation_locale[$lang_key]);
    }

    // return default lang if session lang not found
    $translations_default = Cache::rememberForever('translations-' . env('DEFAULT_LANGUAGE', 'en'), function () {
        return Translation::where('lang', env('DEFAULT_LANGUAGE', 'en'))->pluck('lang_value', 'lang_key')->toArray();
    });
    if (isset($translations_default[$lang_key])) {
        return $addslashes ? addslashes(trim($translations_default[$lang_key])) : trim($translations_default[$lang_key]);
    }

    // fallback to en lang
    if (!isset($translations_en[$lang_key])) {
        return trim($key);
    }
    return $addslashes ? addslashes(trim($translations_en[$lang_key])) : trim($translations_en[$lang_key]);
}

function remove_invalid_charcaters($str)
{
    $str = str_ireplace(array("\\"), '', $str);
    return str_ireplace(array('"'), '\"', $str);
}

if (!function_exists('translation_tables')) {
    function translation_tables($uniqueIdentifier)
    {
        $noTableAddons =  ['african_pg','paytm','pos_system'];
        if (!in_array($uniqueIdentifier, $noTableAddons)){
            $addons = [];
            $addons['affiliate'] = ['affiliate_options','affiliate_configs','affiliate_users','affiliate_payments', 'affiliate_withdraw_requests','affiliate_logs', 'affiliate_stats'];
            $addons['auction'] = ['auction_product_bids'];
            $addons['club_point'] = ['club_points','club_point_details'];
            $addons['delivery_boy'] = ['delivery_boys','delivery_histories','delivery_boy_payments','delivery_boy_collections'];
            $addons['offline_payment'] = ['manual_payment_methods'];
            $addons['otp_system'] = ['otp_configurations','sms_templates'];
            $addons['refund_request'] = ['refund_requests'];
            $addons['seller_subscription'] = ['seller_packages','seller_package_translations','seller_package_payments'];
            $addons['wholesale'] = ['wholesale_prices'];

            foreach($addons as $key => $addon_tables){
                if($key == $uniqueIdentifier){
                    foreach($addon_tables as $table)
                    {
                        Schema::dropIfExists($table);
                    }
                }
            }
        }
    }
}

function getShippingCost($carts, $index, $carrier = '')
{

    $shipping_type = get_setting('shipping_type');
    $admin_products = array();
    $seller_products = array();
    $admin_product_total_weight = 0;
    $admin_product_total_price = 0;
    $seller_product_total_weight = array();
    $seller_product_total_price = array();

    $cartItem = $carts[$index];
    $product = Product::find($cartItem['product_id']);

    if ($product->digital == 1) {
        return 0;
    }

    foreach ($carts as $key => $cart_item) {
        $item_product = Product::find($cart_item['product_id']);
        if ($item_product->added_by == 'admin') {
            array_push($admin_products, $cart_item['product_id']);

            // For carrier wise shipping
            if ($shipping_type == 'carrier_wise_shipping') {
                $admin_product_total_weight += ($item_product->weight * $cart_item['quantity']);
                $admin_product_total_price += (cart_product_price($cart_item, $item_product, false, false) * $cart_item['quantity']);
            }
        } else {
            $product_ids = array();
            $weight = 0;
            $price = 0;
            if (isset($seller_products[$item_product->user_id])) {
                $product_ids = $seller_products[$item_product->user_id];

                // For carrier wise shipping
                if ($shipping_type == 'carrier_wise_shipping') {
                    $weight += $seller_product_total_weight[$item_product->user_id];
                    $price += $seller_product_total_price[$item_product->user_id];
                }
            }

            array_push($product_ids, $cart_item['product_id']);
            $seller_products[$item_product->user_id] = $product_ids;

            // For carrier wise shipping
            if ($shipping_type == 'carrier_wise_shipping') {
                $weight += ($item_product->weight * $cart_item['quantity']);
                $seller_product_total_weight[$item_product->user_id] = $weight;

                $price += (cart_product_price($cart_item, $item_product, false, false) * $cart_item['quantity']);
                $seller_product_total_price[$item_product->user_id] = $price;
            }
        }
    }

    if ($shipping_type == 'flat_rate') {
        return get_setting('flat_rate_shipping_cost') / count($carts);
    } elseif ($shipping_type == 'seller_wise_shipping') {
        if ($product->added_by == 'admin') {
            return get_setting('shipping_cost_admin') / count($admin_products);
        } else {
            return Shop::where('user_id', $product->user_id)->first()->shipping_cost / count($seller_products[$product->user_id]);
        }
    } elseif ($shipping_type == 'area_wise_shipping') {
        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
        $city = City::where('id', $shipping_info->city_id)->first();
        if ($city != null) {
            if ($product->added_by == 'admin') {
                return $city->cost / count($admin_products);
            } else {
                return $city->cost / count($seller_products[$product->user_id]);
            }
        }
        return 0;
    } elseif ($shipping_type == 'carrier_wise_shipping') { // carrier wise shipping
        $user_zone = Address::where('id', $carts[0]['address_id'])->first()->country->zone_id;
        if ($carrier == null || $user_zone == 0) {
            return 0;
        }

        $carrier = Carrier::find($carrier);
        if ($carrier->carrier_ranges->first()) {
            $carrier_billing_type   = $carrier->carrier_ranges->first()->billing_type;
            if ($product->added_by == 'admin') {
                $itemsWeightOrPrice = $carrier_billing_type == 'weight_based' ? $admin_product_total_weight : $admin_product_total_price;
            } else {
                $itemsWeightOrPrice = $carrier_billing_type == 'weight_based' ? $seller_product_total_weight[$product->user_id] : $seller_product_total_price[$product->user_id];
            }
        }

        foreach ($carrier->carrier_ranges as $carrier_range) {
            if ($itemsWeightOrPrice >= $carrier_range->delimiter1 && $itemsWeightOrPrice < $carrier_range->delimiter2) {
                $carrier_price = $carrier_range->carrier_range_prices->where('zone_id', $user_zone)->first()->price;
                return $product->added_by == 'admin' ? ($carrier_price / count($admin_products)) : ($carrier_price / count($seller_products[$product->user_id]));
            }
        }
        return 0;
    } elseif ($product->is_quantity_multiplied && ($shipping_type == 'product_size_and_area_wise_shipping')) {

        $customer_address = Address::where('user_id', auth()->user()->id)->first();
        $to_city_id       = $customer_address->city_id;
        $to_state_id      = $customer_address->state_id;

        $seller_address = Shop::where('user_id', $product->user_id)->first();
        $from_city_id       = $seller_address->city_id;
        $from_state_id      = $seller_address->state_id;

        $size             = $product->size;

        $shippingCost = ShippingCostSize::where('to_state_id', $to_state_id)
        ->where('from_state_id', $from_state_id)
        ->where('size', '<=', $size)
        ->orderBy('size', 'desc')
        ->first();

        $cost = $shippingCost ? $shippingCost->cost : 0;
        return    $cost * $cartItem['quantity'];
    }

    else {

        if ($product->is_quantity_multiplied && ($shipping_type == 'product_size_and_area_wise_shipping')) {
            return  $product->shipping_cost * $cartItem['quantity'];
        }
        return $product->shipping_cost;
    }
}

//return carrier wise shipping cost against seller
if (!function_exists('carrier_base_price')) {
    function carrier_base_price($carts, $carrier_id, $owner_id)
    {
        $shipping = 0;
        foreach ($carts as $key => $cartItem) {
            if ($cartItem->owner_id == $owner_id) {
                $shipping_cost = getShippingCost($carts, $key, $carrier_id);
                $shipping += $shipping_cost;
            }
        }
        return $shipping;
    }
}

//return seller wise carrier list
if (!function_exists('seller_base_carrier_list')) {
    function seller_base_carrier_list($owner_id)
    {
        $carrier_list = array();
        $carts = Cart::where('user_id', auth()->user()->id)->get();
        if (count($carts) > 0) {
            $zone = $carts[0]['address'] ? Country::where('id', $carts[0]['address']['country_id'])->first()->zone_id : null;
            $carrier_query = Carrier::query();
            $carrier_query->whereIn('id', function ($query) use ($zone) {
                $query->select('carrier_id')->from('carrier_range_prices')
                ->where('zone_id', $zone);
            })->orWhere('free_shipping', 1);
            $carrier_list = $carrier_query->active()->get();
        }
        return (new CarrierCollection($carrier_list))->extra($owner_id);
    }
}

function timezones()
{
    return Array(
        '(GMT-12:00) International Date Line West' => 'Pacific/Kwajalein',
        '(GMT-11:00) Midway Island' => 'Pacific/Midway',
        '(GMT-11:00) Samoa' => 'Pacific/Apia',
        '(GMT-10:00) Hawaii' => 'Pacific/Honolulu',
        '(GMT-09:00) Alaska' => 'America/Anchorage',
        '(GMT-08:00) Pacific Time (US & Canada)' => 'America/Los_Angeles',
        '(GMT-08:00) Tijuana' => 'America/Tijuana',
        '(GMT-07:00) Arizona' => 'America/Phoenix',
        '(GMT-07:00) Mountain Time (US & Canada)' => 'America/Denver',
        '(GMT-07:00) Chihuahua' => 'America/Chihuahua',
        '(GMT-07:00) La Paz' => 'America/Chihuahua',
        '(GMT-07:00) Mazatlan' => 'America/Mazatlan',
        '(GMT-06:00) Central Time (US & Canada)' => 'America/Chicago',
        '(GMT-06:00) Central America' => 'America/Managua',
        '(GMT-06:00) Guadalajara' => 'America/Mexico_City',
        '(GMT-06:00) Mexico City' => 'America/Mexico_City',
        '(GMT-06:00) Monterrey' => 'America/Monterrey',
        '(GMT-06:00) Saskatchewan' => 'America/Regina',
        '(GMT-05:00) Eastern Time (US & Canada)' => 'America/New_York',
        '(GMT-05:00) Indiana (East)' => 'America/Indiana/Indianapolis',
        '(GMT-05:00) Bogota' => 'America/Bogota',
        '(GMT-05:00) Lima' => 'America/Lima',
        '(GMT-05:00) Quito' => 'America/Bogota',
        '(GMT-04:00) Atlantic Time (Canada)' => 'America/Halifax',
        '(GMT-04:00) Caracas' => 'America/Caracas',
        '(GMT-04:00) La Paz' => 'America/La_Paz',
        '(GMT-04:00) Santiago' => 'America/Santiago',
        '(GMT-03:30) Newfoundland' => 'America/St_Johns',
        '(GMT-03:00) Brasilia' => 'America/Sao_Paulo',
        '(GMT-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
        '(GMT-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
        '(GMT-03:00) Greenland' => 'America/Godthab',
        '(GMT-02:00) Mid-Atlantic' => 'America/Noronha',
        '(GMT-01:00) Azores' => 'Atlantic/Azores',
        '(GMT-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
        '(GMT) Casablanca' => 'Africa/Casablanca',
        '(GMT) Dublin' => 'Europe/London',
        '(GMT) Edinburgh' => 'Europe/London',
        '(GMT) Lisbon' => 'Europe/Lisbon',
        '(GMT) London' => 'Europe/London',
        '(GMT) UTC' => 'UTC',
        '(GMT) Monrovia' => 'Africa/Monrovia',
        '(GMT+01:00) Amsterdam' => 'Europe/Amsterdam',
        '(GMT+01:00) Belgrade' => 'Europe/Belgrade',
        '(GMT+01:00) Berlin' => 'Europe/Berlin',
        '(GMT+01:00) Bern' => 'Europe/Berlin',
        '(GMT+01:00) Bratislava' => 'Europe/Bratislava',
        '(GMT+01:00) Brussels' => 'Europe/Brussels',
        '(GMT+01:00) Budapest' => 'Europe/Budapest',
        '(GMT+01:00) Copenhagen' => 'Europe/Copenhagen',
        '(GMT+01:00) Ljubljana' => 'Europe/Ljubljana',
        '(GMT+01:00) Madrid' => 'Europe/Madrid',
        '(GMT+01:00) Paris' => 'Europe/Paris',
        '(GMT+01:00) Prague' => 'Europe/Prague',
        '(GMT+01:00) Rome' => 'Europe/Rome',
        '(GMT+01:00) Sarajevo' => 'Europe/Sarajevo',
        '(GMT+01:00) Skopje' => 'Europe/Skopje',
        '(GMT+01:00) Stockholm' => 'Europe/Stockholm',
        '(GMT+01:00) Vienna' => 'Europe/Vienna',
        '(GMT+01:00) Warsaw' => 'Europe/Warsaw',
        '(GMT+01:00) West Central Africa' => 'Africa/Lagos',
        '(GMT+01:00) Zagreb' => 'Europe/Zagreb',
        '(GMT+02:00) Athens' => 'Europe/Athens',
        '(GMT+02:00) Bucharest' => 'Europe/Bucharest',
        '(GMT+02:00) Cairo' => 'Africa/Cairo',
        '(GMT+02:00) Harare' => 'Africa/Harare',
        '(GMT+02:00) Helsinki' => 'Europe/Helsinki',
        '(GMT+02:00) Istanbul' => 'Europe/Istanbul',
        '(GMT+02:00) Jerusalem' => 'Asia/Jerusalem',
        '(GMT+02:00) Kyev' => 'Europe/Kiev',
        '(GMT+02:00) Minsk' => 'Europe/Minsk',
        '(GMT+02:00) Pretoria' => 'Africa/Johannesburg',
        '(GMT+02:00) Riga' => 'Europe/Riga',
        '(GMT+02:00) Sofia' => 'Europe/Sofia',
        '(GMT+02:00) Tallinn' => 'Europe/Tallinn',
        '(GMT+02:00) Vilnius' => 'Europe/Vilnius',
        '(GMT+03:00) Baghdad' => 'Asia/Baghdad',
        '(GMT+03:00) Kuwait' => 'Asia/Kuwait',
        '(GMT+03:00) Moscow' => 'Europe/Moscow',
        '(GMT+03:00) Nairobi' => 'Africa/Nairobi',
        '(GMT+03:00) Riyadh' => 'Asia/Riyadh',
        '(GMT+03:00) St. Petersburg' => 'Europe/Moscow',
        '(GMT+03:00) Volgograd' => 'Europe/Volgograd',
        '(GMT+03:30) Tehran' => 'Asia/Tehran',
        '(GMT+04:00) Abu Dhabi' => 'Asia/Muscat',
        '(GMT+04:00) Baku' => 'Asia/Baku',
        '(GMT+04:00) Muscat' => 'Asia/Muscat',
        '(GMT+04:00) Tbilisi' => 'Asia/Tbilisi',
        '(GMT+04:00) Yerevan' => 'Asia/Yerevan',
        '(GMT+04:30) Kabul' => 'Asia/Kabul',
        '(GMT+05:00) Ekaterinburg' => 'Asia/Yekaterinburg',
        '(GMT+05:00) Islamabad' => 'Asia/Karachi',
        '(GMT+05:00) Karachi' => 'Asia/Karachi',
        '(GMT+05:00) Tashkent' => 'Asia/Tashkent',
        '(GMT+05:30) Chennai' => 'Asia/Kolkata',
        '(GMT+05:30) Kolkata' => 'Asia/Kolkata',
        '(GMT+05:30) Mumbai' => 'Asia/Kolkata',
        '(GMT+05:30) New Delhi' => 'Asia/Kolkata',
        '(GMT+05:45) Kathmandu' => 'Asia/Kathmandu',
        '(GMT+06:00) Almaty' => 'Asia/Almaty',
        '(GMT+06:00) Astana' => 'Asia/Dhaka',
        '(GMT+06:00) Dhaka' => 'Asia/Dhaka',
        '(GMT+06:00) Novosibirsk' => 'Asia/Novosibirsk',
        '(GMT+06:00) Sri Jayawardenepura' => 'Asia/Colombo',
        '(GMT+06:30) Rangoon' => 'Asia/Rangoon',
        '(GMT+07:00) Bangkok' => 'Asia/Bangkok',
        '(GMT+07:00) Hanoi' => 'Asia/Bangkok',
        '(GMT+07:00) Jakarta' => 'Asia/Jakarta',
        '(GMT+07:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
        '(GMT+08:00) Beijing' => 'Asia/Hong_Kong',
        '(GMT+08:00) Chongqing' => 'Asia/Chongqing',
        '(GMT+08:00) Hong Kong' => 'Asia/Hong_Kong',
        '(GMT+08:00) Irkutsk' => 'Asia/Irkutsk',
        '(GMT+08:00) Kuala Lumpur' => 'Asia/Kuala_Lumpur',
        '(GMT+08:00) Perth' => 'Australia/Perth',
        '(GMT+08:00) Singapore' => 'Asia/Singapore',
        '(GMT+08:00) Taipei' => 'Asia/Taipei',
        '(GMT+08:00) Ulaan Bataar' => 'Asia/Irkutsk',
        '(GMT+08:00) Urumqi' => 'Asia/Urumqi',
        '(GMT+09:00) Osaka' => 'Asia/Tokyo',
        '(GMT+09:00) Sapporo' => 'Asia/Tokyo',
        '(GMT+09:00) Seoul' => 'Asia/Seoul',
        '(GMT+09:00) Tokyo' => 'Asia/Tokyo',
        '(GMT+09:00) Yakutsk' => 'Asia/Yakutsk',
        '(GMT+09:30) Adelaide' => 'Australia/Adelaide',
        '(GMT+09:30) Darwin' => 'Australia/Darwin',
        '(GMT+10:00) Brisbane' => 'Australia/Brisbane',
        '(GMT+10:00) Canberra' => 'Australia/Sydney',
        '(GMT+10:00) Guam' => 'Pacific/Guam',
        '(GMT+10:00) Hobart' => 'Australia/Hobart',
        '(GMT+10:00) Melbourne' => 'Australia/Melbourne',
        '(GMT+10:00) Port Moresby' => 'Pacific/Port_Moresby',
        '(GMT+10:00) Sydney' => 'Australia/Sydney',
        '(GMT+10:00) Vladivostok' => 'Asia/Vladivostok',
        '(GMT+11:00) Magadan' => 'Asia/Magadan',
        '(GMT+11:00) New Caledonia' => 'Asia/Magadan',
        '(GMT+11:00) Solomon Is.' => 'Asia/Magadan',
        '(GMT+12:00) Auckland' => 'Pacific/Auckland',
        '(GMT+12:00) Fiji' => 'Pacific/Fiji',
        '(GMT+12:00) Kamchatka' => 'Asia/Kamchatka',
        '(GMT+12:00) Marshall Is.' => 'Pacific/Fiji',
        '(GMT+12:00) Wellington' => 'Pacific/Auckland',
        '(GMT+13:00) Nuku\'alofa' => 'Pacific/Tongatapu'
    );
}

if (!function_exists('app_timezone')) {
    function app_timezone()
    {
        return config('app.timezone');
    }
}




if (!function_exists('get_featured_flash_deal')) {
    function get_featured_flash_deal()
    {
        $flash_deal_query = FlashDeal::query();
        $featured_flash_deal = $flash_deal_query->isActiveAndFeatured()
            ->where('start_date', '<=', strtotime(date('Y-m-d H:i:s')))
            ->where('end_date', '>=', strtotime(date('Y-m-d H:i:s')))
            ->first();

        return $featured_flash_deal;
    }
}


if (!function_exists('get_best_sellers')) {
    function get_best_sellers($limit = '')
    {
        return Cache::remember('best_selers', 86400, function () use ($limit) {
            return Shop::where('verification_status', 1)->orderBy('num_of_sale', 'desc')->take($limit)->get();
        });
    }
}

if (!function_exists('get_all_active_language')) {
    function get_all_active_language()
    {
        $language_query = Language::query();
        $language_query->where('status', 1);

        return $language_query->get();
    }
}


// this below code is  use for image handle, css and js file management Always in server side use this👇
// The code that is in the helpers.php file in the server is to be used.
// local one don't do that 👇

//return file uploaded via uploader
if (!function_exists('uploaded_asset')) {

    function uploaded_asset($id)
    {
        // dd($id);
        if (($asset = \App\Models\Upload::find($id)) != null) {
            return $asset->external_link == null ? my_asset($asset->file_name) : $asset->external_link;
        }
        return static_asset('assets/img/placeholder.jpg');
    }
}

if (!function_exists('my_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function my_asset($path, $secure = null)
    {
        if (env('FILESYSTEM_DRIVER') == 's3') {
            return Storage::disk('s3')->url($path);
        } else {
            return app('url')->asset('public/' . $path, $secure);
        }
    }
}

if (!function_exists('static_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function static_asset($path, $secure = null)
    {
        if (str_contains(url('/'), '.test') || str_contains(url('/'), 'http://127.0.0.1:')) {
            return app('url')->asset('/' . $path, $secure) . '?v=' . env('APP_VERSION');
        }
//        dd($path, $secure);
        return app('url')->asset('public/' . $path, $secure) . '?v=' . env('APP_VERSION');
            // return app('url')->asset('/' . $path, $secure);
    }
}


if (!function_exists('isHttps')) {
    function isHttps()
    {
        return !empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS']);
    }
}

if (!function_exists('getBaseURL')) {
    function getBaseURL()
    {
        $root = '//' . $_SERVER['HTTP_HOST'];

        $root .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

        return $root;
    }
}
if (!function_exists('getFileBaseURL')) {
    function getFileBaseURL()
    {
        if (env('FILESYSTEM_DRIVER') == 's3') {
            return env('AWS_URL') . 'public/';
        } else {
            return getBaseURL() . 'public/';
        }
    }
}


// this upper code is  use for image handle, css and js file management Always in server side use this👇
// The code that is in the helpers.php file in the server is to be used.
// local one don't do that 👇

if (!function_exists('isUnique')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function isUnique($email)
    {
        $user = \App\Models\User::where('email', $email)->first();

        if ($user == null) {
            return '1'; // $user = null means we did not get any match with the email provided by the user inside the database
        } else {
            return '0';
        }
    }
}

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null, $lang = false)
    {
        $settings = Cache::remember('business_settings', 86400, function () {
            return BusinessSetting::all();
        });

        if ($lang == false) {
            $setting = $settings->where('type', $key)->first();
        } else {
            $setting = $settings->where('type', $key)->where('lang', $lang)->first();
            $setting = !$setting ? $settings->where('type', $key)->first() : $setting;
        }
        return $setting == null ? $default : $setting->value;
    }
}

function hex2rgba($color, $opacity = false)
{
    return (new ColorCodeConverter())->convertHexToRgba($color, $opacity);
}

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        if (Auth::check() && (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff')) {
            return true;
        }
        return false;
    }
}

if (!function_exists('isSeller')) {
    function isSeller()
    {
        if (Auth::check() && Auth::user()->user_type == 'seller') {
            return true;
        }
        return false;
    }
}

if (!function_exists('isCustomer')) {
    function isCustomer()
    {
        if (Auth::check() && Auth::user()->user_type == 'customer') {
            return true;
        }
        return false;
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

// duplicates m$ excel's ceiling function
if (!function_exists('ceiling')) {
    function ceiling($number, $significance = 1)
    {
        return (is_numeric($number) && is_numeric($significance)) ? (ceil($number / $significance) * $significance) : false;
    }
}

//for api
if (!function_exists('get_images_path')) {
    function get_images_path($given_ids, $with_trashed = false)
    {
        $paths = [];
        foreach (explode(',', $given_ids) as $id) {
            $paths[] = uploaded_asset($id);
        }

        return $paths;
    }
}

//for api
if (!function_exists('checkout_done')) {
    function checkout_done($combined_order_id, $payment)
    {
        $combined_order = CombinedOrder::find($combined_order_id);

        foreach ($combined_order->orders as $key => $order) {
            $order->payment_status = 'paid';
            $order->payment_details = $payment;
            $order->save();

            try {
                NotificationUtility::sendOrderPlacedNotification($order);
                calculateCommissionAffilationClubPoint($order);
            } catch (\Exception $e) {
            }
        }
    }
}

//for api
if (!function_exists('wallet_payment_done')) {
    function wallet_payment_done($user_id, $amount, $payment_method, $payment_details)
    {
        $user = \App\Models\User::find($user_id);
        $user->balance = $user->balance + $amount;
        $user->save();

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $amount;
        $wallet->payment_method = $payment_method;
        $wallet->payment_details = $payment_details;
        $wallet->save();
    }
}

if (!function_exists('purchase_payment_done')) {
    function purchase_payment_done($user_id, $package_id)
    {
        $user = User::findOrFail($user_id);
        $user->customer_package_id = $package_id;
        $customer_package = CustomerPackage::findOrFail($package_id);
        $user->remaining_uploads += $customer_package->product_upload;
        $user->save();

        return 'success';
    }
}

if (!function_exists('seller_purchase_payment_done')) {
    function seller_purchase_payment_done($user_id, $seller_package_id, $amount, $payment_method, $payment_details)
    {
        $seller = Shop::where('user_id', $user_id)->first();
        $seller->seller_package_id = $seller_package_id;
        $seller_package = SellerPackage::findOrFail($seller_package_id);
        $seller->product_upload_limit = $seller_package->product_upload_limit;
        $seller->package_invalid_at = date('Y-m-d', strtotime($seller->package_invalid_at . ' +' . $seller_package->duration . 'days'));
        $seller->save();

        $seller_package = new SellerPackagePayment();
        $seller_package->user_id = $user_id;
        $seller_package->seller_package_id = $seller_package_id;
        $seller_package->payment_method = $payment_method;
        $seller_package->payment_details = $payment_details;
        $seller_package->approval = 1;
        $seller_package->offline_payment = 2;
        $seller_package->save();
    }
}

if (!function_exists('customer_purchase_payment_done')) {
    function customer_purchase_payment_done($user_id, $customer_package_id)
    {
        $user = User::findOrFail($user_id);
        $user->customer_package_id = $customer_package_id;
        $customer_package = CustomerPackage::findOrFail($customer_package_id);
        $user->remaining_uploads += $customer_package->product_upload;
        $user->save();
    }
}





//Commission Calculation
if (!function_exists('calculateCommissionAffilationClubPoint')) {
    function calculateCommissionAffilationClubPoint($order)
    {
        (new CommissionController)->calculateCommission($order);

        if (addon_is_activated('affiliate_system')) {
            (new AffiliateController)->processAffiliatePoints($order);
        }

        if (addon_is_activated('club_point')) {
            if ($order->user != null) {
                (new ClubPointController)->processClubPoints($order);
            }
        }

        $order->commission_calculated = 1;
        $order->save();
    }
}

// Addon Activation Check
if (!function_exists('addon_is_activated')) {
    function addon_is_activated($identifier, $default = null)
    {
        $addons = Cache::remember('addons', 86400, function () {
            return Addon::all();
        });

        $activation = $addons->where('unique_identifier', $identifier)->where('activated', 1)->first();
        return $activation == null ? false : true;
    }
}

// Addon Activation Check
if (!function_exists('seller_package_validity_check')) {
    function seller_package_validity_check($user_id = null)
    {
        $user = $user_id == null ? \App\Models\User::find(Auth::user()->id) : \App\Models\User::find($user_id);
        $shop = $user->shop;
        $package_validation = false;
        if (
            $shop->product_upload_limit > $shop->user->products()->count()
            && $shop->package_invalid_at != null
            && Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) >= 0
        ) {
            $package_validation = true;
        }

        return $package_validation;
        // Ture = Seller package is valid and seller has the product upload limit
        // False = Seller package is invalid or seller product upload limit exists.
    }
}

// Get URL params
if (!function_exists('get_url_params')) {
    function get_url_params($url, $key)
    {
        $query_str = parse_url($url, PHP_URL_QUERY);
        parse_str($query_str, $query_params);

        return $query_params[$key] ?? '';
    }
}

// Get URL params
if (!function_exists('admin_pay_to_courier')) {
    function admin_pay_to_courier($courier_id)
    {
        $orderDetails = Order::where('courier_id', $courier_id)->where('delivery_status','delivered')->get();

        $total = 0;
        foreach ($orderDetails as $key => $orderDetail)
        {
            $total += $orderDetail->courier_shipping_cost;
        }
        $amount_paid_by_admin = Courier::where('user_id', $courier_id)->first();

        $payable = $total - $amount_paid_by_admin->paid_by_admin ?? null;
        return $payable;
    }
}

if (!function_exists('admin_pay_to_customer')) {
    function admin_pay_to_customer($customer_id)
    {
        // Fetch orders where the customer referred by the given customer_id
        $orderDetails = OrderDetail::whereHas('order', function ($query) use ($customer_id) {
            $query->where('referred_by', $customer_id);
        })->where('delivery_status', 'delivered')->get();

        $total = 0;
        foreach ($orderDetails as $orderDetail) {
            $total += $orderDetail->price; // Assuming 'price' is the field for the order price
        }

        // Calculate 7% commission
        $commission = $total * 0.07;

        // Fetch the referred customer
        $referredCustomer = User::find($customer_id);

        if ($referredCustomer) {
            // Assuming there is a 'balance' column in the User model to hold the commission amount
            $referredCustomer->balance = $commission;
            $referredCustomer->save();
        }

        $amount_paid_by_admin = Customer::where('user_id', $customer_id)->first();
        $payable = $commission - $amount_paid_by_admin ?: null->paid_by_admin ?? null;
        return $payable;
    }
}

if (!function_exists('admin_pay_to_customer')) {
    function admin_pay_to_customer($referrer_id)
    {

       $gn_setting = userAffiliateRules($referrer_id);
       $level = isset($gn_setting['user_level']->level)?$gn_setting['user_level']->level: '0';

        // Fetch customers who were referred by the given referrer
        $referredCustomers = User::where('referred_by', $referrer_id)->get();

        $totalCommission = 0;

        foreach ($referredCustomers as $referredCustomer) {
            // Fetch delivered orders where the customer is the buyer
            $orderDetails = OrderDetail::whereHas('order', function ($query) use ($referredCustomer) {
                $query->where('user_id', $referredCustomer->id);
            })->where('delivery_status', 'delivered')->get();

            $total = 0;
            foreach ($orderDetails as $orderDetail) {
                $total += $orderDetail->price; // Assuming 'price' is the field for the order price
            }

            if($level !=0)
            {
                $commission = $total * ($gn_setting['user_level']->percent/100);
                $totalCommission += $commission;
            }

        }

        $amount_paid_by_admin = Customer::where('user_id', $referrer_id)->first();
        $payable = $totalCommission - $amount_paid_by_admin->paid_by_admin;
        return $payable;

    }
}

if (!function_exists('timezones')) {
    function timezones()
    {
        return array(
            '(GMT-12:00) International Date Line West' => 'Pacific/Kwajalein',
            '(GMT-11:00) Midway Island' => 'Pacific/Midway',
            '(GMT-11:00) Samoa' => 'Pacific/Apia',
            '(GMT-10:00) Hawaii' => 'Pacific/Honolulu',
            '(GMT-09:00) Alaska' => 'America/Anchorage',
            '(GMT-08:00) Pacific Time (US & Canada)' => 'America/Los_Angeles',
            '(GMT-08:00) Tijuana' => 'America/Tijuana',
            '(GMT-07:00) Arizona' => 'America/Phoenix',
            '(GMT-07:00) Mountain Time (US & Canada)' => 'America/Denver',
            '(GMT-07:00) Chihuahua' => 'America/Chihuahua',
            '(GMT-07:00) La Paz' => 'America/Chihuahua',
            '(GMT-07:00) Mazatlan' => 'America/Mazatlan',
            '(GMT-06:00) Central Time (US & Canada)' => 'America/Chicago',
            '(GMT-06:00) Central America' => 'America/Managua',
            '(GMT-06:00) Guadalajara' => 'America/Mexico_City',
            '(GMT-06:00) Mexico City' => 'America/Mexico_City',
            '(GMT-06:00) Monterrey' => 'America/Monterrey',
            '(GMT-06:00) Saskatchewan' => 'America/Regina',
            '(GMT-05:00) Eastern Time (US & Canada)' => 'America/New_York',
            '(GMT-05:00) Indiana (East)' => 'America/Indiana/Indianapolis',
            '(GMT-05:00) Bogota' => 'America/Bogota',
            '(GMT-05:00) Lima' => 'America/Lima',
            '(GMT-05:00) Quito' => 'America/Bogota',
            '(GMT-04:00) Atlantic Time (Canada)' => 'America/Halifax',
            '(GMT-04:00) Caracas' => 'America/Caracas',
            '(GMT-04:00) La Paz' => 'America/La_Paz',
            '(GMT-04:00) Santiago' => 'America/Santiago',
            '(GMT-03:30) Newfoundland' => 'America/St_Johns',
            '(GMT-03:00) Brasilia' => 'America/Sao_Paulo',
            '(GMT-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
            '(GMT-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
            '(GMT-03:00) Greenland' => 'America/Godthab',
            '(GMT-02:00) Mid-Atlantic' => 'America/Noronha',
            '(GMT-01:00) Azores' => 'Atlantic/Azores',
            '(GMT-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
            '(GMT) Casablanca' => 'Africa/Casablanca',
            '(GMT) Dublin' => 'Europe/London',
            '(GMT) Edinburgh' => 'Europe/London',
            '(GMT) Lisbon' => 'Europe/Lisbon',
            '(GMT) London' => 'Europe/London',
            '(GMT) UTC' => 'UTC',
            '(GMT) Monrovia' => 'Africa/Monrovia',
            '(GMT+01:00) Amsterdam' => 'Europe/Amsterdam',
            '(GMT+01:00) Belgrade' => 'Europe/Belgrade',
            '(GMT+01:00) Berlin' => 'Europe/Berlin',
            '(GMT+01:00) Bern' => 'Europe/Berlin',
            '(GMT+01:00) Bratislava' => 'Europe/Bratislava',
            '(GMT+01:00) Brussels' => 'Europe/Brussels',
            '(GMT+01:00) Budapest' => 'Europe/Budapest',
            '(GMT+01:00) Copenhagen' => 'Europe/Copenhagen',
            '(GMT+01:00) Ljubljana' => 'Europe/Ljubljana',
            '(GMT+01:00) Madrid' => 'Europe/Madrid',
            '(GMT+01:00) Paris' => 'Europe/Paris',
            '(GMT+01:00) Prague' => 'Europe/Prague',
            '(GMT+01:00) Rome' => 'Europe/Rome',
            '(GMT+01:00) Sarajevo' => 'Europe/Sarajevo',
            '(GMT+01:00) Skopje' => 'Europe/Skopje',
            '(GMT+01:00) Stockholm' => 'Europe/Stockholm',
            '(GMT+01:00) Vienna' => 'Europe/Vienna',
            '(GMT+01:00) Warsaw' => 'Europe/Warsaw',
            '(GMT+01:00) West Central Africa' => 'Africa/Lagos',
            '(GMT+01:00) Zagreb' => 'Europe/Zagreb',
            '(GMT+02:00) Athens' => 'Europe/Athens',
            '(GMT+02:00) Bucharest' => 'Europe/Bucharest',
            '(GMT+02:00) Cairo' => 'Africa/Cairo',
            '(GMT+02:00) Harare' => 'Africa/Harare',
            '(GMT+02:00) Helsinki' => 'Europe/Helsinki',
            '(GMT+02:00) Istanbul' => 'Europe/Istanbul',
            '(GMT+02:00) Jerusalem' => 'Asia/Jerusalem',
            '(GMT+02:00) Kyev' => 'Europe/Kiev',
            '(GMT+02:00) Minsk' => 'Europe/Minsk',
            '(GMT+02:00) Pretoria' => 'Africa/Johannesburg',
            '(GMT+02:00) Riga' => 'Europe/Riga',
            '(GMT+02:00) Sofia' => 'Europe/Sofia',
            '(GMT+02:00) Tallinn' => 'Europe/Tallinn',
            '(GMT+02:00) Vilnius' => 'Europe/Vilnius',
            '(GMT+03:00) Baghdad' => 'Asia/Baghdad',
            '(GMT+03:00) Kuwait' => 'Asia/Kuwait',
            '(GMT+03:00) Moscow' => 'Europe/Moscow',
            '(GMT+03:00) Nairobi' => 'Africa/Nairobi',
            '(GMT+03:00) Riyadh' => 'Asia/Riyadh',
            '(GMT+03:00) St. Petersburg' => 'Europe/Moscow',
            '(GMT+03:00) Volgograd' => 'Europe/Volgograd',
            '(GMT+03:30) Tehran' => 'Asia/Tehran',
            '(GMT+04:00) Abu Dhabi' => 'Asia/Muscat',
            '(GMT+04:00) Baku' => 'Asia/Baku',
            '(GMT+04:00) Muscat' => 'Asia/Muscat',
            '(GMT+04:00) Tbilisi' => 'Asia/Tbilisi',
            '(GMT+04:00) Yerevan' => 'Asia/Yerevan',
            '(GMT+04:30) Kabul' => 'Asia/Kabul',
            '(GMT+05:00) Ekaterinburg' => 'Asia/Yekaterinburg',
            '(GMT+05:00) Islamabad' => 'Asia/Karachi',
            '(GMT+05:00) Karachi' => 'Asia/Karachi',
            '(GMT+05:00) Tashkent' => 'Asia/Tashkent',
            '(GMT+05:30) Chennai' => 'Asia/Kolkata',
            '(GMT+05:30) Kolkata' => 'Asia/Kolkata',
            '(GMT+05:30) Mumbai' => 'Asia/Kolkata',
            '(GMT+05:30) New Delhi' => 'Asia/Kolkata',
            '(GMT+05:45) Kathmandu' => 'Asia/Kathmandu',
            '(GMT+06:00) Almaty' => 'Asia/Almaty',
            '(GMT+06:00) Astana' => 'Asia/Dhaka',
            '(GMT+06:00) Dhaka' => 'Asia/Dhaka',
            '(GMT+06:00) Novosibirsk' => 'Asia/Novosibirsk',
            '(GMT+06:00) Sri Jayawardenepura' => 'Asia/Colombo',
            '(GMT+06:30) Rangoon' => 'Asia/Rangoon',
            '(GMT+07:00) Bangkok' => 'Asia/Bangkok',
            '(GMT+07:00) Hanoi' => 'Asia/Bangkok',
            '(GMT+07:00) Jakarta' => 'Asia/Jakarta',
            '(GMT+07:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
            '(GMT+08:00) Beijing' => 'Asia/Hong_Kong',
            '(GMT+08:00) Chongqing' => 'Asia/Chongqing',
            '(GMT+08:00) Hong Kong' => 'Asia/Hong_Kong',
            '(GMT+08:00) Irkutsk' => 'Asia/Irkutsk',
            '(GMT+08:00) Kuala Lumpur' => 'Asia/Kuala_Lumpur',
            '(GMT+08:00) Perth' => 'Australia/Perth',
            '(GMT+08:00) Singapore' => 'Asia/Singapore',
            '(GMT+08:00) Taipei' => 'Asia/Taipei',
            '(GMT+08:00) Ulaan Bataar' => 'Asia/Irkutsk',
            '(GMT+08:00) Urumqi' => 'Asia/Urumqi',
            '(GMT+09:00) Osaka' => 'Asia/Tokyo',
            '(GMT+09:00) Sapporo' => 'Asia/Tokyo',
            '(GMT+09:00) Seoul' => 'Asia/Seoul',
            '(GMT+09:00) Tokyo' => 'Asia/Tokyo',
            '(GMT+09:00) Yakutsk' => 'Asia/Yakutsk',
            '(GMT+09:30) Adelaide' => 'Australia/Adelaide',
            '(GMT+09:30) Darwin' => 'Australia/Darwin',
            '(GMT+10:00) Brisbane' => 'Australia/Brisbane',
            '(GMT+10:00) Canberra' => 'Australia/Sydney',
            '(GMT+10:00) Guam' => 'Pacific/Guam',
            '(GMT+10:00) Hobart' => 'Australia/Hobart',
            '(GMT+10:00) Melbourne' => 'Australia/Melbourne',
            '(GMT+10:00) Port Moresby' => 'Pacific/Port_Moresby',
            '(GMT+10:00) Sydney' => 'Australia/Sydney',
            '(GMT+10:00) Vladivostok' => 'Asia/Vladivostok',
            '(GMT+11:00) Magadan' => 'Asia/Magadan',
            '(GMT+11:00) New Caledonia' => 'Asia/Magadan',
            '(GMT+11:00) Solomon Is.' => 'Asia/Magadan',
            '(GMT+12:00) Auckland' => 'Pacific/Auckland',
            '(GMT+12:00) Fiji' => 'Pacific/Fiji',
            '(GMT+12:00) Kamchatka' => 'Asia/Kamchatka',
            '(GMT+12:00) Marshall Is.' => 'Pacific/Fiji',
            '(GMT+12:00) Wellington' => 'Pacific/Auckland',
            '(GMT+13:00) Nuku\'alofa' => 'Pacific/Tongatapu'
        );
}
}
if (!function_exists('get_discount_day_alerts')) {
    /**
     * Get discount day alerts for start and end times.
     *
     * @return array
     */
    // app/Helpers/Helper.php

    if (!function_exists('get_today_discount_times')) {
        /**
         * Get the discount day's start and end time for today.
         *
         * @return array
         */
        function get_today_discount_times()
        {
            $now = now(); // Current time

            // Fetch discount days for today
            $discountDays = \App\Models\DiscountDay::whereDate('discount_date', $now->toDateString())
            ->where('status', 1)
                ->get();

            // Prepare an array with start and end times for today
            $todayTimes = [];

            foreach ($discountDays as $discount_day) {
                // Convert to Carbon instance and format the time
                $start_time = \Carbon\Carbon::parse($discount_day->start_time)->format('h:i A');
                $end_time = \Carbon\Carbon::parse($discount_day->end_time)->format('h:i A');

                $todayTimes[] = [
                    'discount_day' => $discount_day,
                    'start_time' => $start_time,
                    'end_time' => $end_time
                ];
            }

            return $todayTimes;
        }
    }




// Helper function to fetch running and upcoming flash deals
if (!function_exists('get_running_and_upcoming_flash_deals')) {
    /**
     * Get the running and upcoming flash deals for today and the next 2 days.
     *
     * @return array
     */
    function get_running_and_upcoming_flash_deals()
{
    $now = \Carbon\Carbon::now(); // Current time
    $nowTimestamp = $now->timestamp; // Get current timestamp

    // Fetch running deals where status is 1 (active), and current time is between start_date and end_date
    $runningDeals = \App\Models\FlashDeal::where('status', 1)
        ->where('start_date', '<=', $nowTimestamp)
        ->where('end_date', '>=', $nowTimestamp)
        ->get();

    // Fetch upcoming deals where status is 1 (active), and start_date is within the next 2 days
    $upcomingDeals = \App\Models\FlashDeal::where('status', 1)
        ->where('start_date', '>=', $nowTimestamp)
        ->where('start_date', '<=', $now->addDays(2)->timestamp) // Get timestamp for 2 days ahead
        ->get();

    return [
        'running' => $runningDeals,
        'upcoming' => $upcomingDeals
    ];
}

}
}
if (!function_exists('business_setting')) {
    /**
     * Retrieve a business setting value by type.
     *
     * @param string $type
     * @return string|null
     */
    function business_setting($type)
    {
        // Fetch the record where type matches the provided type
        $businessSetting = \App\Models\BusinessSetting::where('type', $type)->first();

        if ($businessSetting) {
            // Return the value column directly
            return $businessSetting->value;
        }

        return null; // Return null if no record is found
    }
}

if (!function_exists('get_payment_methods_widget')) {
    /**
     * Retrieve the payment methods from the database.
     *
     * @return array|null
     */
    function get_payment_methods_widget()
    {
        // Fetch the row where type = 'payment_methods_widget'
        $businessSetting = \App\Models\BusinessSetting::where('type', 'payment_methods_widget')->first();

        if ($businessSetting) {
            // Decode the JSON value column to an array
            return json_decode($businessSetting->value, true) ?: []; // Return an array
        }

        return []; // Return an empty array if no record is found
    }
}



