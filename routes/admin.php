<?php

use App\Http\Controllers\AddonController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AizUploadController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BusinessSettingsController;
use App\Http\Controllers\CarrierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\CustomerProductController;
use App\Http\Controllers\DigitalProductController;
use App\Http\Controllers\FlashDealController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PickupPointController;
use App\Http\Controllers\ProductBulkUploadController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductQueryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SellerWithdrawRequestController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TaxValueController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\EmailLanguageController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\ShippingCostSizeController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\CourierWithdrawRequestController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\OrderExportController;



/*
  |--------------------------------------------------------------------------
  | Admin Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register admin routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
//Update Routes
  Route::controller(UpdateController::class)->group(function () {
    Route::post('/update', 'step0')->name('update');
    Route::get('/update/step1', 'step1')->name('update.step1');
    Route::get('/update/step2', 'step2')->name('update.step2');
    Route::get('/update/step3', 'step3')->name('update.step3');
    Route::post('/purchase_code', 'purchase_code')->name('update.code');
});

  Route::get('/admin', [AdminController::class, 'admin_dashboard'])->name('admin.dashboard')->middleware(['auth', 'admin', 'prevent-back-history']);
//cron job url *******************
  Route::get('/admin/delete-ticket-reply', [SupportTicketController::class, 'delete_ticket_reply'])->name('admin.delete.ticket.reply');
//********************************
  Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin', 'prevent-back-history']], function() {

    // category
    Route::resource('categories', CategoryController::class);
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories/edit/{id}', 'edit')->name('categories.edit');
        Route::get('/categories/destroy/{id}', 'destroy')->name('categories.destroy');
        Route::post('/categories/featured', 'updateFeatured')->name('categories.featured');
    });

    Route::controller(SearchController::class)->group(function () {
       Route::get('/customer/searching-for', 'customerSearch')->name('customer.search');
   });
    // Brand
    Route::resource('brands', BrandController::class);
    Route::controller(BrandController::class)->group(function () {
        Route::get('/brands/edit/{id}', 'edit')->name('brands.edit');
        Route::get('/brands/destroy/{id}', 'destroy')->name('brands.destroy');
    });

    // Products
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products/admin', 'admin_products')->name('products.admin');
        Route::get('/products/seller', 'seller_products')->name('products.seller');
        Route::get('/products/all', 'all_products')->name('products.all');
        Route::get('/products/create', 'create')->name('products.create');
        Route::post('/products/store/', 'store')->name('products.store');
        Route::get('/products/admin/{id}/edit', 'admin_product_edit')->name('products.admin.edit');

        Route::get('/products/admin/{id}/jan', 'get_product_by_jan')->name('products.admin.jan');
        Route::get('/products/check-by-jan', 'product_check_by_jan_code')->name('products.check_by_jan');
        Route::post('/products/store/jan', 'store_product_by_jan')->name('products.store.jan');

        Route::get('/products/seller/{id}/edit', 'seller_product_edit')->name('products.seller.edit');
        Route::post('/products/update/{product}', 'update')->name('products.update');
        Route::post('/products/todays_deal', 'updateTodaysDeal')->name('products.todays_deal');
        Route::post('/products/featured', 'updateFeatured')->name('products.featured');

        Route::post('/products/published', 'updatePublished')->name('products.published');
        Route::post('/products/approved', 'updateProductApproval')->name('products.approved');
        Route::post('/products/get_products_by_subcategory', 'get_products_by_subcategory')->name('products.get_products_by_subcategory');
        Route::get('/products/duplicate/{id}', 'duplicate')->name('products.duplicate');
        Route::get('/products/destroy/{id}', 'destroy')->name('products.destroy');
        Route::post('/bulk-product-delete', 'bulk_product_delete')->name('bulk-product-delete');

        Route::post('/products/sku_combination', 'sku_combination')->name('products.sku_combination');
        Route::post('/products/sku_combination_edit', 'sku_combination_edit')->name('products.sku_combination_edit');
        Route::post('/products/add-more-choice-option', 'add_more_choice_option')->name('products.add-more-choice-option');
        Route::post('/product-search', 'product_search')->name('product.search');
        Route::post('/get-selected-products', 'get_selected_products')->name('get-selected-products');
        Route::post('/set-product-discount', 'setProductDiscount')->name('set_product_discount');
        Route::get('/products/admin/donation', 'admin_donated_product')->name('products.admin.donate');
        Route::get('/products/sellers/donation', 'seller_donated_product')->name('products.seller.donate');

        Route::get('/products/boosting', 'all_boosting_product')->name('products.boost');
        Route::get('/products/{id}', 'remove_boost_product')->name('product.boost.plan.remove');
        Route::get('/fetch-products','fetchProducts')->name('products.fetch');

    });

    // Digital Product
    Route::resource('digitalproducts', DigitalProductController::class);
    Route::controller(DigitalProductController::class)->group(function () {
        Route::get('/digitalproducts/edit/{id}', 'edit')->name('digitalproducts.edit');
        Route::get('/digitalproducts/destroy/{id}', 'destroy')->name('digitalproducts.destroy');
        Route::get('/digitalproducts/download/{id}', 'download')->name('digitalproducts.download');
    });

    Route::controller(ProductBulkUploadController::class)->group(function () {
        //Product Export
        Route::get('/product-bulk-export', 'export')->name('product_bulk_export.index');
        Route::get('auction/product-bulk-export', 'auction_product_export')->name('auction_product_export');
        //Product Bulk Upload
        Route::get('/product-bulk-upload/index', 'index')->name('product_bulk_upload.index');
        Route::get('auction/product-bulk-upload', 'auction_product_bulk_import')->name('auction_product_bulk_import');
        Route::post('/bulk-product-upload', 'bulk_upload')->name('bulk_product_upload');
        Route::get('/product-csv-download/{type}', 'import_product')->name('product_csv.download');
        Route::get('/vendor-product-csv-download/{id}', 'import_vendor_product')->name('import_vendor_product.download');
        Route::group(['prefix' => 'bulk-upload/download'], function() {
            Route::get('/category', 'pdf_download_category')->name('pdf.download_category');
            Route::get('/brand', 'pdf_download_brand')->name('pdf.download_brand');
            Route::get('/seller', 'pdf_download_seller')->name('pdf.download_seller');
        });
    });

    // Seller
    Route::resource('sellers', SellerController::class);
    Route::controller(SellerController::class)->group(function () {
        Route::get('sellers_ban/{id}', 'ban')->name('sellers.ban');
        Route::get('/sellers/destroy/{id}', 'destroy')->name('sellers.destroy');
        Route::post('/bulk-seller-delete', 'bulk_seller_delete')->name('bulk-seller-delete');
        Route::get('/sellers/view/{id}/verification', 'show_verification_request')->name('sellers.show_verification_request');
        Route::get('/sellers/approve/{id}', 'approve_seller')->name('sellers.approve');
        Route::get('/sellers/reject/{id}', 'reject_seller')->name('sellers.reject');
        Route::get('/sellers/login/{id}', 'login')->name('sellers.login');
        Route::post('/sellers/payment_modal', 'payment_modal')->name('sellers.payment_modal');
        Route::post('/sellers/profile_modal', 'profile_modal')->name('sellers.profile_modal');
        Route::post('/sellers/approved', 'updateApproved')->name('sellers.approved');
    });

    // Seller Payment
    Route::controller(PaymentController::class)->group(function () {
        Route::get('/seller/payments', 'payment_histories')->name('sellers.payment_histories');
        Route::get('/seller/payments/show/{id}', 'show')->name('sellers.payment_history');
        Route::get('/courier/payments', 'courier_payment_histories')->name('couriers.payment_histories');
        Route::get('/courier/payments/show/{id}', 'courier_show')->name('couriers.payment_history');
        Route::get('/customer/payments', 'customer_payment_histories')->name('customers.payment_histories');
        Route::get('/customer/payments/show/{id}', 'customer_show')->name('customers.payment_history');
    });

    // Seller Withdraw Request
    Route::resource('/withdraw_requests', SellerWithdrawRequestController::class);
    Route::controller(SellerWithdrawRequestController::class)->group(function () {
        Route::get('/withdraw_requests_all', 'index')->name('withdraw_requests_all');
        Route::post('/withdraw_request/payment_modal', 'payment_modal')->name('withdraw_request.payment_modal');
        Route::post('/withdraw_request/message_modal', 'message_modal')->name('withdraw_request.message_modal');
    });

    // Seller Withdraw Request
    Route::resource('/courier_withdraw_requests', CourierWithdrawRequestController::class);
    Route::controller(CourierWithdrawRequestController::class)->group(function () {
        Route::get('/courier_withdraw_requests_all', 'index')->name('courier_withdraw_requests_all');
        Route::post('/courier/withdraw_request/payment_modal', 'payment_modal')->name('courier_withdraw_request.payment_modal');
        Route::post('/courier/withdraw_request/message_modal', 'message_modal')->name('courier_withdraw_request.message_modal');
    });
    Route::controller(CustomerPaymentController::class)->group(function () {
     Route::get('/customer_withdraw_requests_all', 'withdraw_request')->name('customer_withdraw_requests_all');
     Route::post('/customer/withdraw_request/payment_modal', 'payment_modal')->name('customer_withdraw_request.payment_modal');
     Route::post('/customer/withdraw_request/message_modal', 'message_modal')->name('customer_withdraw_request.message_modal');

 });
    // Customer
    Route::resource('customers', CustomerController::class);
    Route::controller(CustomerController::class)->group(function () {
        Route::get('customers_ban/{customer}', 'ban')->name('customers.ban');
        Route::get('/customers/login/{id}', 'login')->name('customers.login');
        Route::get('/customers/destroy/{id}', 'destroy')->name('customers.destroy');
        Route::post('/bulk-customer-delete', 'bulk_customer_delete')->name('bulk-customer-delete');
    });

      // Candidate
    Route::resource('candidates', CandidateController::class);
    Route::controller(CandidateController::class)->group(function () {
        Route::get('candidates_ban/{customer}', 'ban')->name('candidates.ban');
        Route::get('/candidates/login/{id}', 'login')->name('candidates.login');
        Route::get('/candidates/destroy/{id}', 'destroy')->name('candidates.destroy');
        Route::post('/bulk-candidate-delete', 'bulk_candidate_delete')->name('bulk-candidate-delete');
    });

    //couriers
    Route::resource('couriers', CourierController::class);
    Route::controller(CourierController::class)->group(function () {
        Route::get('couriers_ban/{id}', 'ban')->name('couriers.ban');
        Route::get('/couriers/destroy/{id}', 'destroy')->name('courier.destroy');
        Route::post('/bulk-courier-delete', 'bulk_courier_delete')->name('bulk-courier-delete');
        Route::get('/sellers/view/{id}/verification', 'show_verification_request')->name('sellers.show_verification_request');
        Route::get('/couriers/approve/{id}', 'approve_courier')->name('couriers.approve');
        Route::get('/couriers/reject/{id}', 'reject_courier')->name('couriers.reject');
        Route::get('/couriers/login/{id}', 'login')->name('couriers.login');
        Route::post('/couriers/payment_modal', 'payment_modal')->name('couriers.payment_modal');
        Route::post('/couriers/profile_modal', 'profile_modal')->name('couriers.profile_modal');
        Route::post('/couriers/approved', 'updateApproved')->name('couriers.approved');
    });


    // Newsletter
    Route::controller(NewsletterController::class)->group(function () {
        Route::get('/newsletter', 'index')->name('newsletters.index');
        Route::post('/newsletter/send', 'send')->name('newsletters.send');
        Route::post('/newsletter/test/smtp', 'testEmail')->name('test.smtp');
    });

    Route::resource('profile', ProfileController::class);

    // Business Settings
    Route::controller(BusinessSettingsController::class)->group(function () {
        Route::post('/business-settings/update', 'update')->name('business_settings.update');
        Route::post('/business-settings/update/activation', 'updateActivationSettings')->name('business_settings.update.activation');
        Route::get('/general-setting', 'general_setting')->name('general_setting.index');
        Route::get('/activation', 'activation')->name('activation.index');
        Route::get('/payment-method', 'payment_method')->name('payment_method.index');
        Route::get('/file_system', 'file_system')->name('file_system.index');
        Route::get('/social-login', 'social_login')->name('social_login.index');
        Route::get('/smtp-settings', 'smtp_settings')->name('smtp_settings.index');
        Route::get('/google-analytics', 'google_analytics')->name('google_analytics.index');
        Route::get('/google-recaptcha', 'google_recaptcha')->name('google_recaptcha.index');
        Route::get('/google-map', 'google_map')->name('google-map.index');
        Route::get('/google-firebase', 'google_firebase')->name('google-firebase.index');

        //Facebook Settings
        Route::get('/facebook-chat', 'facebook_chat')->name('facebook_chat.index');
        Route::post('/facebook_chat', 'facebook_chat_update')->name('facebook_chat.update');
        Route::get('/facebook-comment', 'facebook_comment')->name('facebook-comment');
        Route::post('/facebook-comment', 'facebook_comment_update')->name('facebook-comment.update');
        Route::post('/facebook_pixel', 'facebook_pixel_update')->name('facebook_pixel.update');

        Route::post('/env_key_update', 'env_key_update')->name('env_key_update.update');
        Route::post('/payment_method_update', 'payment_method_update')->name('payment_method.update');
        Route::post('/google_analytics', 'google_analytics_update')->name('google_analytics.update');
        Route::post('/google_recaptcha', 'google_recaptcha_update')->name('google_recaptcha.update');
        Route::post('/google-map', 'google_map_update')->name('google-map.update');
        Route::post('/google-firebase', 'google_firebase_update')->name('google-firebase.update');

        // Route::get('/verification/form', 'seller_verification_form')->name('seller_verification_form.index');
        Route::post('/verification/form', 'seller_verification_form_update')->name('seller_verification_form.update');
        Route::get('/vendor_commission', 'vendor_commission')->name('business_settings.vendor_commission');
        Route::post('/vendor_commission_update', 'vendor_commission_update')->name('business_settings.vendor_commission.update');


        Route::get('/courier_commission', 'courier_commission')->name('business_settings.courier_commission');
        Route::post('/courier_commission_update', 'courier_commission_update')->name('business_settings.courier_commission.update');

        //Shipping Configuration
        Route::get('/shipping_configuration', 'shipping_configuration')->name('shipping_configuration.index');
        Route::post('/shipping_configuration/update', 'shipping_configuration_update')->name('shipping_configuration.update');

        // Order Configuration
        Route::get('/order-configuration', 'order_configuration')->name('order_configuration.index');

        //product_price_adjustment
        Route::get('/product-price-adjustment', 'product_price_adjustment')->name('product_price_adjustment.index');
        Route::post('/product-price-adjustment/update', 'product_price_adjustment_update')->name('business_settings.product_price_adjustment.update');

        //product_price_adjustment
        Route::get('/shipping-cost-adjustment', 'shipping_cost_adjustment')->name('shipping_cost_adjustment.index');
        Route::post('/shipping-cost-adjustment/update', 'shipping_cost_adjustment_update')->name('business_settings.shipping_cost_adjustment.update');

    });


    //Currency
    Route::controller(CurrencyController::class)->group(function () {
        Route::get('/currency', 'currency')->name('currency.index');
        Route::post('/currency/update', 'updateCurrency')->name('currency.update');
        Route::post('/your-currency/update', 'updateYourCurrency')->name('your_currency.update');
        Route::get('/currency/create', 'create')->name('currency.create');
        Route::post('/currency/store', 'store')->name('currency.store');
        Route::post('/currency/currency_edit', 'edit')->name('currency.edit');
        Route::post('/currency/update_status', 'update_status')->name('currency.update_status');
    });

    //Tax
    Route::resource('tax', TaxController::class);
    Route::controller(TaxController::class)->group(function () {
        Route::get('/tax/edit/{id}', 'edit')->name('tax.edit');
        Route::get('/tax/destroy/{id}', 'destroy')->name('tax.destroy');
        Route::post('tax-status', 'change_tax_status')->name('taxes.tax-status');
    });
     //Tax
    Route::resource('tax-value', TaxValueController::class);
        Route::controller(TaxValueController::class)->group(function () {
        Route::get('/tax-value/edit/{id}', 'edit')->name('tax-value.edit');
        Route::get('/tax-value/destroy/{id}', 'destroy')->name('tax-value.destroy');
        Route::post('tax-value-status', 'change_tax_status')->name('tax-values.tax-status');
    });


    // Language
    Route::resource('/languages', LanguageController::class);
    Route::controller(LanguageController::class)->group(function () {
        Route::post('/languages/{id}/update', 'update')->name('languages.update');
        Route::get('/languages/destroy/{id}', 'destroy')->name('languages.destroy');
        Route::post('/languages/update_rtl_status', 'update_rtl_status')->name('languages.update_rtl_status');
        Route::post('/languages/update-status', 'update_status')->name('languages.update-status');
        Route::post('/languages/key_value_store', 'key_value_store')->name('languages.key_value_store');

        //App Trasnlation
        Route::post('/languages/app-translations/import', 'importEnglishFile')->name('app-translations.import');
        Route::get('/languages/app-translations/show/{id}', 'showAppTranlsationView')->name('app-translations.show');
        Route::post('/languages/app-translations/key_value_store', 'storeAppTranlsation')->name('app-translations.store');
        Route::get('/languages/app-translations/export/{id}', 'exportARBFile')->name('app-translations.export');
    });


    // website setting
    Route::group(['prefix' => 'website'], function() {
        Route::controller(WebsiteController::class)->group(function () {
            Route::get('/footer', 'footer')->name('website.footer');
            Route::get('/header', 'header')->name('website.header');
            Route::get('/appearance', 'appearance')->name('website.appearance');
            Route::get('/pages', 'pages')->name('website.pages');
        });

        // Custom Page
        Route::resource('custom-pages', PageController::class);
        Route::controller(PageController::class)->group(function () {
            Route::get('/custom-pages/edit/{id}', 'edit')->name('custom-pages.edit');
            Route::get('/custom-pages/destroy/{id}', 'destroy')->name('custom-pages.destroy');
        });
    });

    //faqs_Points
    Route::resource('faq', FAQController::class);
    Route::controller(FAQController::class)->group(function () {
        Route::get('/faq/edit/{id}', 'edit')->name('faq.edit');
        Route::get('/faq/destroy/{id}', 'destroy')->name('faq.destroy');
        Route::post('/faq/status', 'updateStatus')->name('faq.status');
    });
    // Staff Roles
    Route::resource('roles', RoleController::class);
    Route::controller(RoleController::class)->group(function () {
        Route::get('/roles/edit/{id}', 'edit')->name('roles.edit');
        Route::get('/roles/destroy/{id}', 'destroy')->name('roles.destroy');
         Route::post('/bulk-rolet-delete', 'bulk_role_delete')->name('bulk-role-delete');

        // Add Permissiom
        Route::post('/roles/add_permission', 'add_permission')->name('roles.permission');
    });

    // Staff
    Route::resource('staffs', StaffController::class);
    Route::get('/staffs/destroy/{id}', [StaffController::class, 'destroy'])->name('staffs.destroy');

    // Flash Deal
    Route::resource('flash_deals', FlashDealController::class);
    Route::controller(FlashDealController::class)->group(function () {
        Route::get('/flash_deals/edit/{id}', 'edit')->name('flash_deals.edit');
        Route::get('/flash_deals/destroy/{id}', 'destroy')->name('flash_deals.destroy');
        Route::post('/flash_deals/update_status', 'update_status')->name('flash_deals.update_status');
        Route::post('/flash_deals/update_featured', 'update_featured')->name('flash_deals.update_featured');
        Route::post('/flash_deals/product_discount', 'product_discount')->name('flash_deals.product_discount');
        Route::post('/flash_deals/product_discount_edit', 'product_discount_edit')->name('flash_deals.product_discount_edit');
    });

    //Subscribers
    Route::controller(SubscriberController::class)->group(function () {
        Route::get('/subscribers', 'index')->name('subscribers.index');
        Route::get('/subscribers/destroy/{id}', 'destroy')->name('subscriber.destroy');
    });

    // Order
    Route::resource('orders', OrderController::class);
    Route::controller(OrderController::class)->group(function () {
        // All Orders
        Route::get('/all_orders', 'all_orders')->name('all_orders.index');
        Route::get('/all_orders/export', 'all_orders_export')->name('all_orders.export');
        Route::get('/sale/tax/report', 'sale_tax_report')->name('sale.tax.report');

        Route::get('/inhouse-orders', 'all_orders')->name('inhouse_orders.index');
        Route::get('/seller_orders', 'all_orders')->name('seller_orders.index');
        Route::get('orders_by_pickup_point', 'all_orders')->name('pick_up_point.index');

        Route::get('/orders/{id}/show', 'show')->name('all_orders.show');
        Route::get('/inhouse-orders/{id}/show', 'show')->name('inhouse_orders.show');

        Route::get('/seller_orders/{id}/show', 'show')->name('seller_orders.show');

        Route::get('/orders_by_pickup_point/{id}/show', 'show')->name('pick_up_point.order_show');

        Route::post('/bulk-order-status', 'bulk_order_status')->name('bulk-order-status');

        Route::get('/orders/destroy/{id}', 'destroy')->name('orders.destroy');
        Route::post('/bulk-order-delete', 'bulk_order_delete')->name('bulk-order-delete');

        Route::get('/orders/destroy/{id}', 'destroy')->name('orders.destroy');
        Route::post('/orders/details', 'order_details')->name('orders.details');
        Route::post('/orders/update_delivery_status', 'update_delivery_status')->name('orders.update_delivery_status');
        Route::post('/orders/update_payment_status', 'update_payment_status')->name('orders.update_payment_status');
        Route::post('/orders/update_tracking_code', 'update_tracking_code')->name('orders.update_tracking_code');

        //Delivery Boy Assign
        Route::post('/orders/delivery-boy-assign', 'assign_delivery_boy')->name('orders.delivery-boy-assign');
    });

    // Route::controller(OrderExportController::class)->group(function () {
    //   Route::get('/excel/export', 'excel')->name('excel.export.excel');
    // });

    Route::post('/pay_to_seller', [CommissionController::class, 'pay_to_seller'])->name('commissions.pay_to_seller');
    Route::post('/pay_to_coureir', [CommissionController::class, 'pay_to_courier'])->name('commissions.pay_to_courier');
    Route::post('/pay_to_customer', [CommissionController::class, 'pay_to_customer'])->name('commissions.pay_to_customer');
    //Reports
    Route::controller(ReportController::class)->group(function () {
        Route::get('/in_house_sale_report', 'in_house_sale_report')->name('in_house_sale_report.index');
        Route::get('/seller_sale_report', 'seller_sale_report')->name('seller_sale_report.index');
        Route::get('/stock_report', 'stock_report')->name('stock_report.index');
        Route::get('/wish_report', 'wish_report')->name('wish_report.index');
        Route::get('/user_search_report', 'user_search_report')->name('user_search_report.index');
        Route::get('/commission-log', 'commission_history')->name('commission-log.index');
        Route::get('/wallet-history', 'wallet_transaction_history')->name('wallet-history.index');
    });

    //Blog Section
    //Blog cateory
    Route::resource('blog-category', BlogCategoryController::class);
    Route::get('/blog-category/destroy/{id}', [BlogCategoryController::class, 'destroy'])->name('blog-category.destroy');

    // Blog
    Route::resource('blog', BlogController::class);
    Route::controller(BlogController::class)->group(function () {
        Route::get('/blog/destroy/{id}', 'destroy')->name('blog.destroy');
        Route::post('/blog/change-status', 'change_status')->name('blog.change-status');
    });

    //Coupons
    Route::resource('coupon', CouponController::class);
    Route::controller(CouponController::class)->group(function () {
        Route::get('/coupon/destroy/{id}', 'destroy')->name('coupon.destroy');

        //Coupon Form
        Route::post('/coupon/get_form', 'get_coupon_form')->name('coupon.get_coupon_form');
        Route::post('/coupon/get_form_edit', 'get_coupon_form_edit')->name('coupon.get_coupon_form_edit');
        Route::post('/coupon/status', 'updateStatus')->name('coupon.status');
    });

    //Reviews
    Route::controller(ReviewController::class)->group(function () {
        Route::get('/reviews', 'index')->name('reviews.index');
        Route::post('/reviews/published', 'updatePublished')->name('reviews.published');
    });

    //Support_Ticket
    Route::controller(SupportTicketController::class)->group(function () {
        Route::get('support_ticket/', 'admin_index')->name('support_ticket.admin_index');
        Route::get('support_ticket/{id}/show', 'admin_show')->name('support_ticket.admin_show');
        Route::post('support_ticket/reply', 'admin_store')->name('support_ticket.admin_store');
    });

    //Idea
    Route::controller(IdeaController::class)->group(function () {
        Route::get('idea/', 'admin_index')->name('idea.admin_index');
        Route::get('idea/{id}/show', 'admin_show')->name('idea.admin_show');
        Route::post('idea/reply', 'admin_store')->name('idea.admin_store');
    });

    //Pickup_Points


    //conversation of seller customer
    Route::controller(ConversationController::class)->group(function () {
        Route::get('conversations', 'admin_index')->name('conversations.admin_index');
        Route::get('conversations/{id}/show','admin_show')->name('conversations.admin_show');
    });

    // product Queries show on Admin panel
    Route::controller(ProductQueryController::class)->group(function () {
        Route::get('/product-queries', 'index')->name('product_query.index');
        Route::get('/product-queries/{id}', 'show')->name('product_query.show');
        Route::put('/product-queries/{id}', 'reply')->name('product_query.reply');
    });

    // Product Attribute
    Route::resource('attributes', AttributeController::class );
    Route::controller(AttributeController::class)->group(function () {
        Route::get('/attributes/edit/{id}', 'edit')->name('attributes.edit');
        Route::get('/attributes/destroy/{id}', 'destroy')->name('attributes.destroy');

        //Attribute Value
        Route::post('/store-attribute-value', 'store_attribute_value')->name('store-attribute-value');
        Route::get('/edit-attribute-value/{id}', 'edit_attribute_value')->name('edit-attribute-value');
        Route::post('/update-attribute-value/{id}', 'update_attribute_value')->name('update-attribute-value');
        Route::get('/destroy-attribute-value/{id}', 'destroy_attribute_value')->name('destroy-attribute-value');

        //Colors
        Route::get('/colors', 'colors')->name('colors');
        Route::post('/colors/store', 'store_color')->name('colors.store');
        Route::get('/colors/edit/{id}', 'edit_color')->name('colors.edit');
        Route::post('/colors/update/{id}', 'update_color')->name('colors.update');
        Route::get('/colors/destroy/{id}', 'destroy_color')->name('colors.destroy');
    });

    // Addon
    Route::resource('addons', AddonController::class);
    Route::post('/addons/activation', [AddonController::class, 'activation'])->name('addons.activation');

    //Customer Package
    Route::resource('customer_packages', CustomerPackageController::class);
    Route::controller(CustomerPackageController::class)->group(function () {
        Route::get('/customer_packages/edit/{id}', 'edit')->name('customer_packages.edit');
        Route::get('/customer_packages/destroy/{id}', 'destroy')->name('customer_packages.destroy');
    });

    //Classified Products
    Route::controller(CustomerProductController::class)->group(function () {
        Route::get('/classified_products', 'customer_product_index')->name('classified_products');
        Route::post('/classified_products/published', 'updatePublished')->name('classified_products.published');
        Route::get('/classified_products/destroy/{id}', 'destroy_by_admin')->name('classified_products.destroy');
    });

    // Countries
    Route::resource('countries', CountryController::class);
    Route::post('/countries/status', [CountryController::class, 'updateStatus'])->name('countries.status');

    // States
    Route::resource('states', StateController::class);
    Route::post('/states/status', [StateController::class, 'updateStatus'])->name('states.status');

    // Carriers
    Route::resource('carriers', CarrierController::class);
    Route::controller(CarrierController::class)->group(function () {
        // Route::get('/carriers/destroy/{id}', 'destroy')->name('carriers.destroy');
        // Route::post('/carriers/update_status', 'updateStatus')->name('carriers.update_status');
    });


    // Zones
    Route::resource('zones', ZoneController::class);
    Route::get('/zones/destroy/{id}', [ZoneController::class, 'destroy'])->name('zones.destroy');

    Route::resource('cities', CityController::class);
    Route::controller(CityController::class)->group(function () {
        Route::get('/cities/edit/{id}', 'edit')->name('cities.edit');
        Route::get('/cities/destroy/{id}', 'destroy')->name('cities.destroy');
        Route::post('/cities/status', 'updateStatus')->name('cities.status');
    });

    Route::resource('shipping_cost_size', ShippingCostSizeController::class);
    Route::controller(ShippingCostSizeController::class)->group(function () {
        Route::get('/shipping_cost_size/edit/{id}', 'edit')->name('shipping_cost_size.edit');
        Route::get('/shipping_cost_size/destroy/{id}', 'destroy')->name('shipping_cost_size.destroy');
        Route::post('/shipping_cost_size/status', 'updateStatus')->name('shipping_cost_size.status');
    });

    Route::resource('discount_day', DiscountController::class);
    Route::controller(DiscountController::class)->group(function () {
        Route::get('/discount-day/edit/{id}', 'edit')->name('discount_day.edit');
        Route::get('/discount-day/destroy/{id}', 'destroy')->name('discount_day.destroy');
        Route::post('/discount-day/status', 'updateStatus')->name('discount_day.status');
    });


    Route::view('/system/update', 'backend.system.update')->name('system_update');
    Route::view('/system/server-status', 'backend.system.server_status')->name('system_server');
    Route::get('/cache', [AdminController::class, 'systemCache'])->name('system.cache');
    Route::get('/system/cache', [AdminController::class, 'systemClearCache'])->name('system.clearcache');
    Route::get('/system/view', [AdminController::class, 'systemClearView'])->name('system.clearview');
    Route::get('/system/config', [AdminController::class, 'systemClearConfig'])->name('system.clearconfig');
    Route::get('/system/route', [AdminController::class, 'systemClearRoute'])->name('system.clearroute');
    Route::get('/system/information', [AdminController::class, 'systemInformation'])->name('system.information');

    // uploaded files
    Route::resource('/uploaded-files', AizUploadController::class);
    Route::controller(AizUploadController::class)->group(function () {
        Route::any('/uploaded-files/file-info', 'file_info')->name('uploaded-files.info');
        Route::get('/uploaded-files/destroy/{id}', 'destroy')->name('uploaded-files.destroy');
        Route::post('/bulk-uploaded-files-delete', 'bulk_uploaded_files_delete')->name('bulk-uploaded-files-delete');
        Route::get('/all-file', 'all_file');
    });


    Route::controller(PackageController::class)->group(function () {
        Route::any('/pacakge/create', 'create')->name('package.create');
        Route::get('/package/view', 'show')->name('package.show');
        Route::post('/package/add', 'store')->name('package.add');

        Route::get('/package/destroy/{id}', 'destroy')->name('package.destroy');
        Route::get('/package/edit/{id}', 'edit')->name('package.edit');
        Route::post('/package/update/{id}', 'update')->name('package.update');
        Route::post('/package/change-status', 'change_status')->name('package.change-status');
    });


    Route::controller(EmailLanguageController::class)->group(function () {
        Route::get('email/template', 'index')->name('email.language.index');
        Route::get('create/email/template', 'create')->name('create.lang.template');
        Route::post('/add/email/lang', 'store')->name('add.email.language');
        Route::get('email/template/edit/{id}', 'edit')->name('email.language.edit');
        Route::post('/email/template/preview/send', 'sendPreviewEmail')->name('email.template.preview.send');
        Route::post('email/template/update/{id}', 'update')->name('email.language.update');
        Route::get('/general/email', 'GenSettingEmail')->name('general.email');
        Route::post('/business/settings', 'UpdateGenEmail')->name('general.email.update');



    });

    Route::controller(SliderController::class)->group(function () {
        Route::get('/website/slider', 'index')->name('website.slider');
        Route::get('/website/slider/create', 'create')->name('website.slider.create');
        Route::put('/website/slider/update/{id}', 'update')->name('website.slider.update');
        Route::get('/website/slider/edit/{id}', 'edit')->name('website.slider.edit');
        Route::post('/website/slider/add', 'store')->name('website.slider.store');
        Route::get('/website/slider/destroy/{id}', 'destroy')->name('website.slider.destroy');
        Route::post('/website/slider/status', 'status')->name('website.slider.status');
        Route::get('/website/slider/export', 'export')->name('slider-export');
    });



    Route::get('/all-notification', [NotificationController::class, 'index'])->name('admin.all-notification');

    Route::get('/clear-cache', [AdminController::class, 'clearCache'])->name('cache.clear');

    Route::get('/admin-permissions', [RoleController::class, 'create_admin_permissions']);


    Route::get('referral',[AdminController::class,'refIndex'])->name('referral.index');
    Route::post('/referral', [AdminController::class,'refStore'])->name('store.refer');
    Route::post('/referral/business/settings', [BusinessSettingsController::class,'referral_withdraw_settings'])->name('general.store');
    Route::get('/referral/edit/{id}', [AdminController::class,'refEdit'])->name('refer.edit');
    Route::post('/referral/update/{id}', [AdminController::class,'refUpdate'])->name('refer.update');
    Route::get('/referral/delete/{id}', [AdminController::class,'refDestroy'])->name('refer.delete');

});

