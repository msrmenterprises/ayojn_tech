<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Clear application cache:
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Route::get('/', 'UserAuth\userController@index');

//Route::get('/profile/{id}', 'UserAuth\userController@userprofile');
//Route::get('/getprofile/{id?}', 'UserAuth\userController@getprofile')->name('getprofile');

Route::get('/profile/{referCode}', 'UserAuth\userController@index');

Route::get('/partner-refer/{referCode}', 'UserAuth\userController@partner');

Route::any('cron/', 'UserAuth\BidController@cronForDailyMail');


Route::get('/connect', function () {
    return view('connect');
});
Route::get('/expertise', function () {
    return view('expertise');
});
Route::get('/partners', function () {
    return view('partners');
});
Route::get('/solutions', function () {
    return view('solutions');
});
Route::get('/focus', function () {
    return view('focus');
});
Route::get('/stories', function () {
    return view('stories');
});
Route::get('/thankyou', function () {
    return view('thankyou');
});
Route::get('/DOOH', function () {
    return view('event');
});
Route::get('/share/{id?}', 'UserAuth\BidController@shareBid')->name('share-bid');
Route::get('/share-opportunity/{id?}', 'UserAuth\OppertunityController@shareBid')->name('share-opportunity');

Route::post('opp-transaction', 'UserAuth\OppertunityController@opp_transaction')
            ->name('opp-transaction');

Route::get('/offer-share/{id?}', 'UserAuth\OppertunityController@shareOffer')->name('offer-share');
Route::get('/share-event/{id?}', 'UserAuth\EventController@shareEvent')->name('share-event');
Route::get('/share-col/{id?}', 'UserAuth\CollaborateController@shareCol')->name('share-col');

Route::get('/pending', 'UserAuth\userController@pending')->name('pending');
Route::get('/suspend', 'UserAuth\userController@suspend')->name('suspend');
Route::get('/review', 'UserAuth\userController@review')->name('review');
Route::post('/referAFriend', 'UserAuth\userController@referAFriend')->name('referAFriend');
Route::get('/getReferFriends', 'UserAuth\userController@getReferFriendsNew')->name('getReferFriends');
//Route::get('/getReferFriends', 'UserAuth\userController@getReferFriends')->name('getReferFriends');
Route::post('/get-city', 'UserAuth\userController@getCity')->name('get-city');

Route::get('/sitemap', function () {
    return view('sitemap');
});
Route::get('/testtest', 'UserAuth\OppertunityController@shareIdStore');
Route::get('/test', function () {

});
Route::get('/home', function () {
    return redirect(url('/index'));
});

Route::get('/cron', function () {
    \Artisan::call('cron:collaboration');
});

Route::get('/partner-pending', function () {
    return view('partner/partner-pending');
});


//Route::group(['middleware' => ['auth:web','activeuser']], function () {
Route::group(['middleware' => ['auth:web']], function () {
    Route::get('/offer/faq', function () {
        return view('offer-faqs');
    });
    Route::get('/receive/faq', function () {
        return view('receive-faqs');
    });
    Route::get('/pending-faq', function () {
        return view('pending-faqs');
    });
    Route::get('/partner-faqs', function () {
        return view('partner-faqs');
    });

    Route::get('/home2', 'UserAuth\userController@map')->name('home2');
    Route::post('/update-status-notification', 'UserAuth\userController@NotificationUpdate')->name('update-status-notification');
    Route::get('/index', 'UserAuth\userController@map2')->name('index');
    Route::get('/map', 'UserAuth\userController@mapDisplay')->name('map');
    Route::get('/redeem-request', 'UserAuth\userController@redeemRequest')->name('redeem-request');
    Route::post('/voucher-redeem', 'UserAuth\userController@voucher_redeem')->name('voucher-redeem');
    Route::post('/sent-request', 'UserAuth\userController@sentRedeemRequest')->name('sent-request');

    Route::post('/edituserprofile', 'UserAuth\userController@edituserprofile')->name('edituserprofile');
    Route::post('/update-offer-data', 'UserAuth\userController@updateOfferData')->name('update-offer-data');
    Route::post('/mybankget', 'UserAuth\userController@mybank_get')->name('mybankget');
    Route::post('/savebankform', 'UserAuth\userController@savebankform')->name('savebankform');
    
    Route::post('/mapdata', 'UserAuth\userController@mapDataPopup')->name('mapdata');
    Route::post('/mapdata2', 'UserAuth\MapController@mapDataPopup')->name('mapdata2');
    Route::post('/topcountriesdata', 'UserAuth\userController@topcountriesdata')->name('topcountriesdata');
    Route::post('/topdestinationdata', 'UserAuth\userController@topdestinationdata')->name('topdestinationdata');
    Route::post('/topindustriesdata', 'UserAuth\userController@topindustriesdata')->name('topindustriesdata');
    Route::post('/toprecieptdsdata', 'UserAuth\userController@toprecieptdsdata')->name('toprecieptdsdata');
    Route::get('/getUserCount/{id?}', 'UserAuth\userController@getUserCount')->name('getUserCount');
    Route::get('/bid', 'UserAuth\BidController@index')->name('bid');
    Route::get('/unpaid-bid', 'UserAuth\BidController@unpaid')->name('unpaid-opportunity');
    Route::post('/unpaid-bid-delete', 'UserAuth\BidController@unpaid_delete')->name('unpaid-bid-delete');
    Route::get('/spam-bids/{id?}', 'UserAuth\BidController@spamBid')->name('spam-bids');
    Route::get('/restore-spam/{id?}', 'UserAuth\BidController@RestoreSpamBid')->name('restore-spam');
    Route::get('/add-bid', 'UserAuth\BidController@addBid')->name('add-bid');
    Route::post('/add-bid', 'UserAuth\BidController@addNewBid');
    Route::post('/edit-bid', 'UserAuth\BidController@editNewBid');
    Route::post('/update-bid', 'UserAuth\BidController@updateNewBid');
    Route::post('/change-bid-status', 'UserAuth\BidController@changeBidStatus');
    Route::post('/change-opportunity-status', 'UserAuth\OppertunityController@OpportunityStatus');
    Route::post('/add-bid-response', 'UserAuth\BidController@addBidResponse');
    Route::get('/bid-response/{id?}', 'UserAuth\BidController@myBidResponse');
    Route::post('/book-negotiation', 'UserAuth\BidController@bookNegotiation')->name('book-negotiation');

    Route::get('/my-bids', 'UserAuth\BidController@myResponse');
    Route::get('/get-bid-response/{id?}', 'UserAuth\BidController@getBidResponse');
    Route::post('/bid-accept/{id?}', 'UserAuth\BidController@acceptBid');
    Route::get('/get-hashtags', 'UserAuth\OppertunityController@getHashtags');
    Route::get('export-vouch/{id?}', 'UserAuth\OppertunityController@vouchListExport')->name('export.file');

    //add by ram for paynow button 21 sep 2023
    Route::post('/paynow-page-user', 'UserAuth\BidController@payNow')->name('paynow-page-user');
    Route::get('/get-bid/{id?}', 'UserAuth\BidController@getBidbyid');


    //add by ram 07 jan 2024
    Route::post('/paynow-oportunity', 'UserAuth\BidController@payOportunity')->name('paynow-oportunity');
    Route::post('/paynow-bid-receiver', 'UserAuth\BidController@payOportunityrec')->name('paynow-bid-receiver');
    Route::post('/paynow-proof', 'UserAuth\BidController@paymentproof')->name('paynow-proof');
    Route::post('/paynow-proof-get', 'UserAuth\BidController@paymentproofget')->name('paynow-proof-get');

    Route::get('/opportunity', 'UserAuth\OppertunityController@index')->name('opportunity');

    Route::get('/reciept', 'UserAuth\userController@reciept')->name('reciept');

    Route::get('/invoice', 'UserAuth\userController@invoice')->name('invoice');

    Route::get('/build-opportunity', 'UserAuth\OppertunityController@buildOpportunity')->name('build-opportunity');
    Route::post('/add-new-opportunity', 'UserAuth\OppertunityController@addNewOpportunity')
        ->name('add-new-opportunity');

    Route::post('/hashtags-check', 'UserAuth\OppertunityController@hashtagscheck')
        ->name('hashtags-check');

    Route::post('/add-new-opportunity-paynow', 'UserAuth\OppertunityController@addNewOpportunitypaynow')
        ->name('add-new-opportunity-paynow'); 
        
    Route::post('/renew-opportunity-paynow', 'UserAuth\OppertunityController@renewOpportunitypaynow')
        ->name('renew-opportunity-paynow'); 

    Route::post('/add-new-vouch', 'UserAuth\OppertunityController@addNewVouch')->name('add-new-vouch');
    Route::get('/my-vouches', 'UserAuth\OppertunityController@myResponse');
    Route::post('/add-vouch', 'UserAuth\OppertunityController@addVouch');
    Route::get('/get-cities/{id}', 'UserAuth\OppertunityController@getCities');
    Route::get('/add-reason/{id}', 'UserAuth\BidController@addReason');
    Route::post('/add-new-vouch-code', 'UserAuth\BidController@addNewVouchCode')->name('add-new-vouch-code');
    Route::post('/add-reason', 'UserAuth\BidController@addReasonOfReason');
    Route::get('/add-new-vouch', 'UserAuth\OppertunityController@addNewVouch');
    Route::get('/vouches/{id}', 'UserAuth\OppertunityController@vouchList');

    Route::post('check-vouch-code', 'UserAuth\OppertunityController@check_vouch_code')->name('check-vouch-code');
    Route::post('/paynow-vouch', 'UserAuth\OppertunityController@paynow_vouch')->name('paynow-vouch');

    Route::get('/update-vouch/{id}', 'UserAuth\OppertunityController@updateVouch');
    Route::get('/receive-payment/{id}', 'PaypalController@getExpressCheckout');

    Route::get('paypal/ec-checkout-success', 'PaypalController@getExpressCheckoutSuccess');
    Route::get('/paypal/cancel', 'PaypalController@paymentCancel');
    Route::post('ipn/notify', 'PaypalController@notify');

    Route::get('/pay-now/{id}', 'PayController@getExpressCheckout');
    Route::get('pay-pal/ec-checkout-success', 'PayController@getExpressCheckoutSuccess');
    Route::get('/pay-pal/cancel', 'PayController@paymentCancel');

    Route::get('/bid-response-payment/{id}', 'BidResponsPaymentController@getExpressCheckout');
    Route::get('/response-paypal/success', 'BidResponsPaymentController@getExpressCheckoutSuccess');
    Route::get('/response-paypal/cancel', 'BidResponsPaymentController@paymentCancel');


    Route::get('/vouch-payment/{id}', 'VouchPaymentController@getExpressCheckout');
    Route::get('/vouch-paypal/success', 'VouchPaymentController@getExpressCheckoutSuccess');
    Route::get('/vouch-paypal/cancel', 'VouchPaymentController@paymentCancel');


    Route::get('/bids', 'UserAuth\BidController@index')->name('bids');
    Route::get('/offers', 'UserAuth\BidController@offers')->name('poffers');
    Route::get('/notification-settings', 'UserAuth\SettingController@getNotificationSettings')->name('notification-settings');
    Route::post('/set-notifications', 'UserAuth\SettingController@updateNotificationSettings')->name('set-notifications');
    Route::get('/exting-user', 'UserAuth\SettingController@exitingUser');

    // add by ram 24 sept cart
    Route::get('/cart', 'UserAuth\BidController@cart')->name('cart');
    Route::get('/add-to-cart/{id}', 'UserAuth\BidController@addToCart')->name('add_to_cart');
    Route::get('/wishlist', 'UserAuth\BidController@wishlist')->name('wishlist');
    Route::get('/add-to-wish/{id}', 'UserAuth\BidController@addToWish')->name('add_to_wish');
    
    Route::patch('update-cart', 'UserAuth\BidController@update')->name('update_cart');
    Route::delete('remove-from-cart', 'UserAuth\BidController@remove')->name('remove_from_cart');
    Route::post('/checkout', 'UserAuth\BidController@checkout')->name('checkout');
    Route::delete('remove_from_wishlist', 'UserAuth\BidController@removewishlist')->name('remove_from_wishlist');
    Route::get('/add-to-cart-wishlist/{id}', 'UserAuth\BidController@addToCartBywishlist')->name('add_to_cart_wishlist');
    
    Route::post('/add-reason-common', 'UserAuth\SettingController@addReasoncommon');
    Route::post('/save-reason-common', 'UserAuth\SettingController@saveReasoncommon');

    //add by ram 29 sep for offer history
    Route::get('/marketplace-history', 'UserAuth\SettingController@getmarketplace')->name('marketplace-history');
    Route::get('/wallet-history', 'UserAuth\SettingController@getwallet')->name('wallet-history');
    Route::post('/feedback-store', 'UserAuth\SettingController@feedback_store')->name('feedback-store');
    Route::post('/show-feedback-user', 'UserAuth\SettingController@show_feedback')->name('show-feedback-user');
    Route::post('/show-payment', 'UserAuth\SettingController@show_payment')->name('show-payment');
    Route::post('/query-store', 'UserAuth\SettingController@query_store')->name('query-store');
    Route::post('/query-get', 'UserAuth\SettingController@query_get')->name('query-get');
    Route::post('/cancel-offer-user', 'UserAuth\SettingController@cancel_offer_item')->name('cancel-offer-user');

    //add by ram 16 nov 2024
    Route::get('/offline-pay', 'UserAuth\XsquareController@offlinepay')->name('offline-pay');
    Route::post('/offline-payment', 'UserAuth\XsquareController@offlinepayment')->name('offline-payment');
    Route::get('/purposedropdown', 'UserAuth\XsquareController@purposedropdown')->name('purposedropdown');
    Route::get('/directpay', 'UserAuth\XsquareController@directpay')->name('directpay');
    Route::post('/xsquare-status-check', 'UserAuth\XsquareController@directpay_status')->name('xsquare-status-check');
    Route::post('/xsquare-payment-delete', 'UserAuth\XsquareController@directpay_delete')->name('xsquare-payment-delete');
    Route::get('search-username', 'UserAuth\XsquareController@searchUsername')->name('search-username');
    Route::get('/beneficiary_search/{id}', 'UserAuth\XsquareController@beneficiary_search')->name('beneficiary_search');


    Route::get('/ticket', 'TicketController@index')->name('ticket');
    Route::get('/tickets/create', 'TicketController@create')->name('tickets.create');
    Route::post('/tickets/store', 'TicketController@store')->name('tickets.store');
    Route::get('/tickets/show/{id}', 'TicketController@show')->name('tickets.show');
    //Route::post('tickets/{id}/assign', 'TicketController@assign')->name('tickets.assign');
    Route::post('tickets/{id}/comment', 'TicketController@addComment')->name('tickets.comment');
    Route::post('tickets/{id}/close', 'TicketController@close')->name('tickets.close');
    Route::post('tickets/{id}/reopen', 'TicketController@reopen')->name('tickets.reopen');


    Route::post('/upload-image-ticket-comment', 'TicketController@upload')->name('upload-image-ticket-comment');




    Route::get('/ticket', 'TicketController@index')->name('ticket');
    Route::get('/tickets/create', 'TicketController@create')->name('tickets.create');
    Route::post('/tickets/store', 'TicketController@store')->name('tickets.store');
    Route::get('/tickets/show/{id}', 'TicketController@show')->name('tickets.show');
    //Route::post('tickets/{id}/assign', 'TicketController@assign')->name('tickets.assign');
    Route::post('tickets/{id}/comment', 'TicketController@addComment')->name('tickets.comment');
    Route::post('tickets/{id}/close', 'TicketController@close')->name('tickets.close');
    Route::post('tickets/{id}/reopen', 'TicketController@reopen')->name('tickets.reopen');


    Route::post('/upload-image-ticket-comment', 'TicketController@upload')->name('upload-image-ticket-comment');




    Route::get('/ticket', 'TicketController@index')->name('ticket');
    Route::get('/tickets/create', 'TicketController@create')->name('tickets.create');
    Route::post('/tickets/store', 'TicketController@store')->name('tickets.store');
    Route::get('/tickets/show/{id}', 'TicketController@show')->name('tickets.show');
    //Route::post('tickets/{id}/assign', 'TicketController@assign')->name('tickets.assign');
    Route::post('tickets/{id}/comment', 'TicketController@addComment')->name('tickets.comment');
    Route::post('tickets/{id}/close', 'TicketController@close')->name('tickets.close');
    Route::post('tickets/{id}/reopen', 'TicketController@reopen')->name('tickets.reopen');


    Route::post('/upload-image-ticket-comment', 'TicketController@upload')->name('upload-image-ticket-comment');

    Route::get('/usernotification', 'UserAuth\userController@notificationall')->name('usernotification');
    Route::get('/success', 'UserAuth\XsquareController@success')->name('success');
    Route::get('/reciept_download/{id}', 'UserAuth\XsquareController@reciept_download')->name('reciept_download');
    Route::get('/failed', 'UserAuth\XsquareController@failed')->name('failed');

    Route::get('/beneficiary-list', 'UserAuth\XsquareController@beneficiary_list')->name('beneficiary-list');
    Route::post('/beneficiary-save', 'UserAuth\XsquareController@beneficiary_save')->name('beneficiary-save');
    Route::get('/beneficiary-add', 'UserAuth\XsquareController@beneficiary_add')->name('beneficiary-add');
    Route::get('/beneficiary-edit/{id}', 'UserAuth\XsquareController@beneficiary_edit')->name('beneficiary-edit');
    Route::post('/beneficiary-update', 'UserAuth\XsquareController@beneficiary_update')->name('beneficiary-update');
    Route::post('/beneficiary-delete', 'UserAuth\XsquareController@beneficiary_delete')->name('beneficiary-delete');

    Route::post('/offline-payment-direct', 'UserAuth\XsquareController@run')->name('offline-payment-direct');
    Route::post('/beneficiary_add_direct', 'UserAuth\XsquareController@beneficiary_add_direct')->name('beneficiary_add_direct');

    //event
    Route::get('/events', 'UserAuth\EventController@index');
    Route::post('/add-event', 'UserAuth\EventController@addEvent');
    Route::get('/attendees/{id?}', 'UserAuth\EventController@attendeesList')->name('attendees');
    Route::post('/change-attendee-status/{id?}', 'UserAuth\EventController@changeStatusAttendees')->name('attendees');
    Route::post('/attend-event/{id?}', 'UserAuth\EventController@attendEvent')->name('attend-event');
    Route::post('/cancel-event/{id?}', 'UserAuth\EventController@cancelEvent')->name('cancel-event');
    Route::get('export-attendees/{id?}', 'UserAuth\EventController@ExportAttendees')->name('export.attendee');

    //collaborate
    Route::get('/collaborate', 'UserAuth\CollaborateController@index')->name('collaborate');
    Route::post('/add-collborations', 'UserAuth\CollaborateController@addCollaboration');
    Route::post('/add-collaboration-response', 'UserAuth\CollaborateController@addCollaborationResponse');
    Route::get('/collaborator/{id?}', 'UserAuth\CollaborateController@collaboratorList')->name('collaborator');
    Route::get('export-collaborator/{id?}', 'UserAuth\CollaborateController@ExportCollaborator')->name('export.collaborator');
    Route::post('/change-collaboration-status/{id?}', 'UserAuth\CollaborateController@changeStatusCollaborator')->name('change-collaboration-status');


});

Route::group(['prefix' => 'controlpanel'], function () {

    Route::get('/login', 'ControlpanelAuth\LoginController@showLoginForm')->name('controlpanel.login');
    Route::post('/login', 'ControlpanelAuth\LoginController@login');
    Route::post('/logout', 'ControlpanelAuth\LoginController@logout')->name('logout');

    Route::get('/directpayadmin', 'ControlpanelAuth\CollaboraterController@directpay')->name('controlpanel.directpayadmin');
    Route::get('/all-beneficiary', 'ControlpanelAuth\CollaboraterController@all_beneficiary')->name('controlpanel.all-beneficiary');
    Route::get('/all-beneficiary-payment/{id}', 'ControlpanelAuth\CollaboraterController@all_beneficiary_payment')->name('controlpanel.all-beneficiary-payment');
    Route::post('/xsquare-status-check-admin', 'ControlpanelAuth\CollaboraterController@directpay_status')->name('xsquare-status-check-admin');
    Route::post('/query-get-admin', 'ControlpanelAuth\CollaboraterController@query_get_admin')->name('query-get-admin');
    Route::post('/show-feedback-admin', 'ControlpanelAuth\CollaboraterController@show_feedback_admin')->name('show-feedback-admin');

    //Route::post('/home', 'ControlpanelAuth\LoginController@logout')->name('logout');
    Route::get('/home', function () {
        return redirect(url('/'));
    });
//    Route::get('/register', 'ControlpanelAuth\RegisterController@showRegistrationForm')->name('register');
//    Route::post('/register', 'ControlpanelAuth\RegisterController@register');

    Route::post('/password/email', 'ControlpanelAuth\ForgotPasswordController@sendResetLinkEmail')
        ->name('password.request');
    Route::post('/password/reset', 'ControlpanelAuth\ResetPasswordController@reset')->name('password.email');
    Route::get('/mfa', 'ControlpanelAuth\LoginController@mfa')->name('password.mfa');
    Route::post('/verify_admin_mfa','ControlpanelAuth\LoginController@verify_admin_mfa')->name('verify_admin_mfa');

    Route::get('/resent_admin_mfa','ControlpanelAuth\LoginController@resent_admin_mfa')->name('resent_admin_mfa');

    Route::get('/password/reset', 'ControlpanelAuth\ForgotPasswordController@showLinkRequestForm')
        ->name('password.reset');
    Route::get('/password/reset/{token}', 'ControlpanelAuth\ResetPasswordController@showResetForm');
    Route::get('/editprofile', 'ControlpanelAuth\UserController@editprofile')->name('editprofile');
    Route::post('/getspecify', 'UserAuth\userController@getspecify')->name('getspecify');
    Route::post('/getspecifyedit', 'UserAuth\userController@getspecifyedit')->name('getspecifyedit');
    Route::group(['middleware' => ['auth:controlpanel']], function () {
    Route::get('/opportunity-list', 'ControlpanelAuth\OpportunityController@index')->name('opportunity-list');
    Route::get('/vouchlist/{id?}', 'ControlpanelAuth\OpportunityController@vouchList');
    Route::any('list-opportunity', 'ControlpanelAuth\OpportunityController@listOpportunity');
    Route::any('list-vouch/{id?}', 'ControlpanelAuth\OpportunityController@listVouch');
    Route::post('delete-opportunity', 'ControlpanelAuth\OpportunityController@deleteOpportunity')
            ->name('delete-opportunity');

    Route::post('opportunity-transaction', 'ControlpanelAuth\OpportunityController@opportunity_transaction')
            ->name('opportunity-transaction');
    
    Route::get('/bid-list', 'ControlpanelAuth\BidController@index')->name('bid-list');

    Route::post('/flags-activity-admin-opportunity', 'ControlpanelAuth\OpportunityController@flagsactivity')->name('flags-activity-admin-opportunity');

     Route::post('/flags-activity-admin-bid', 'ControlpanelAuth\BidController@flagsactivity')->name('flags-activity-admin-bid');


        //add by ram 23 sep
        Route::post('/addper', 'ControlpanelAuth\BidController@addper')->name('addper');

        Route::post('/request-change-status', 'ControlpanelAuth\BidController@RequestChangeStatus')
            ->name('request-change-status');
        Route::get('/codes', 'ControlpanelAuth\BidController@codes')->name('codes');
        Route::get('/redeem-request', 'ControlpanelAuth\BidController@redeemRequests')->name('redeem-request');
        Route::post('/list-redeem-request', 'ControlpanelAuth\BidController@listRedeemRequest')
            ->name('list-redeem-request');
       
        Route::post('/list-code', 'ControlpanelAuth\BidController@listCode')->name('list-code');
        Route::get('/vouch-create', 'ControlpanelAuth\BidController@VouchCreate')->name('vouch-create');
        Route::get('/edit-code/{id?}', 'ControlpanelAuth\BidController@VouchEdit')->name('vouch-edit');
        Route::post('/vouch-add', 'ControlpanelAuth\BidController@VouchAdd')->name('vouch-add');
        Route::post('/vouch-update', 'ControlpanelAuth\BidController@VouchUpdate')->name('vouch-update');
        Route::get('/response/{id?}', 'ControlpanelAuth\BidController@response');
        Route::any('list-bid', 'ControlpanelAuth\BidController@listUser');
        Route::any('list-response/{id?}', 'ControlpanelAuth\BidController@listResponse');

        Route::post('/paynow-page', 'ControlpanelAuth\BidController@payNow')->name('paynow-page');
        Route::get('/bulk-vouch-upload', 'ControlpanelAuth\BidController@bulkvouchupload')->name('bulk-vouch-upload');
        Route::post('/bulk-vouch-upload-store', 'ControlpanelAuth\BidController@bulkVouchUploadStore')->name('bulk-vouch-upload-store');

        Route::get('/import-users', 'ControlpanelAuth\UserController@importUser')->name('import-users');
        Route::post('/import-excel', 'ControlpanelAuth\UserController@importExcel')->name('import-excel');
        Route::post('/exportUser', 'ControlpanelAuth\UserController@exportUser')->name('exportUser');
        Route::post('/exportReferUser', 'ControlpanelAuth\UserController@exportReferUser')->name('exportReferUser');
        Route::get('/sampleData', 'ControlpanelAuth\UserController@dataExport')->name('sampleData');
        Route::get('/user-list', 'ControlpanelAuth\UserController@userList')->name('user-list');
        Route::get('/review-list', 'ControlpanelAuth\UserController@userReviewList')->name('review-list');
        Route::post('review-list-data', 'ControlpanelAuth\UserController@listReviewUser')->name('review-list-data');
        Route::post('/updateprofile', 'ControlpanelAuth\UserController@updateprofile')->name('updateprofile');
        Route::get('/changepassword', 'ControlpanelAuth\UserController@changepassword')->name('changepassword');

        Route::post('/changepassword', 'ControlpanelAuth\UserController@updatepassword')->name('changepassword');
        Route::post('list/users/{type?}', 'Controlpanel\UserController@listUser');
        Route::get('/wallet-history-admin/{type}/{id}', 'Controlpanel\UserController@getwalletbyadmin')->name('wallet-history-admin');
        Route::resource('user', 'Controlpanel\UserController');
        Route::post('/user-change-status', 'Controlpanel\UserController@userChangeStatus')->name('user-change-status');
        Route::post('/user-change-status-suspand', 'Controlpanel\UserController@userChangeStatusSuspend')
            ->name('user-change-status-suspand');
        Route::post('user-suspand-comment', 'Controlpanel\UserController@userChangeStatusSuspendWithComment')
            ->name('user-suspand-comment');
        Route::post('/opportunity-change-status', 'ControlpanelAuth\OpportunityController@opportunityChangeStatus')
            ->name('opportunity-change-status');
        Route::get('/user-change-status-suspand/{id}', 'Controlpanel\UserController@userChangeStatusSuspendForm');
        Route::post('/users-change-status', 'Controlpanel\UserController@usersChangeStatus')
            ->name('users-change-status');
        Route::post('/user-change-type', 'Controlpanel\UserController@userType')->name('user-change-type');
        Route::get('/editusers/{id?}', 'Controlpanel\UserController@editusers')->name('editusers');
        Route::get('/showbankuser/{id?}', 'Controlpanel\UserController@showbankuser')->name('showbankuser');
        Route::get('/beneficiary-edit-admin/{id?}', 'Controlpanel\UserController@benefeciaryeditadmin')->name('beneficiary-edit-admin');
        Route::post('/beneficiary-update-admin', 'Controlpanel\UserController@beneficiaryupdateadmin')->name('beneficiary-update-admin');
        Route::get('/edituser-type/{id?}', 'Controlpanel\UserController@editUserType');
        Route::post('/updateusertype', 'Controlpanel\UserController@updateUserType')->name('updateusertype');
        Route::post('/updateuser', 'Controlpanel\UserController@updateuser')->name('updateuser');
        Route::post('delete-user', 'Controlpanel\UserController@deleteUser')->name('delete-user');
        Route::post('delete-bid', 'ControlpanelAuth\BidController@deleteBid')->name('delete-bid');
        Route::post('delete-code', 'ControlpanelAuth\BidController@deleteCode')->name('delete-code');
        Route::post('delete-users', 'Controlpanel\UserController@deleteUsers')->name('delete-users');

        Route::get('/partners', 'ControlpanelAuth\PartnerController@partner')->name('partners');
        Route::get('/partnersbank', 'ControlpanelAuth\PartnerController@partnersbank')->name('partnersbank');
        Route::post('/partner/list-users', 'ControlpanelAuth\PartnerController@listUser')->name('list-users');
        Route::get('/partner-review-list', 'ControlpanelAuth\PartnerController@userReviewList')
            ->name('partner-review-list');
        Route::post('partner-review-list-data', 'ControlpanelAuth\PartnerController@listReviewUser')
            ->name('partner-list-data');
        Route::get('/offers', 'ControlpanelAuth\PartnerController@Offers')->name('offers');
        Route::post('/delete-offer', 'ControlpanelAuth\PartnerController@DeleteOffers')->name('delete-offer');
        Route::post('/partner/list-offers', 'ControlpanelAuth\PartnerController@listOffer')->name('list-offers');

        Route::get('/offer-users/{type}/{id}', 'ControlpanelAuth\PartnerController@Offerusers')->name('offer-users');
        Route::post('/offer-buy-status', 'ControlpanelAuth\PartnerController@offerbuyStatus')
            ->name('offer-buy-status');

            Route::post('/voucher-share', 'ControlpanelAuth\PartnerController@voucher_share')
            ->name('voucher-share');

        Route::post('/bulk-voucher-share', 'ControlpanelAuth\PartnerController@bulk_voucher_share')
            ->name('bulk-voucher-share');
           

        Route::post('/offer-change-status', 'Controlpanel\UserController@offerChangeStatus')
            ->name('offer-change-status');

        Route::post('/bid-change-status', 'ControlpanelAuth\BidController@bidChangeStatus')->name('bid-change-status');

        Route::get('/events', 'ControlpanelAuth\EventController@Events')->name('events');
        Route::post('/list-event', 'ControlpanelAuth\EventController@ListEvents')->name('list-event');
        Route::post('/event-change-status', 'ControlpanelAuth\EventController@eventChangeStatus')
            ->name('event-change-status');
        Route::post('/update-event', 'ControlpanelAuth\EventController@updateEvent')->name('update-event');
        Route::get('/edit-event/{id?}', 'ControlpanelAuth\EventController@editEvent')->name('edit-event');
        Route::get('/attendees/{id?}', 'ControlpanelAuth\EventController@attendeesList')->name('attendees.list');

        Route::get('/collaborates', 'ControlpanelAuth\CollaboraterController@Collaborators')->name('collaborates');
        Route::post('/list-collaborates', 'ControlpanelAuth\CollaboraterController@ListCollaborators')->name('list-collaborates');
        Route::post('/collaboration-change-status', 'ControlpanelAuth\CollaboraterController@collChangeStatus')
            ->name('collaboration-change-status');
        Route::post('/delete-colloborate', 'ControlpanelAuth\CollaboraterController@DeleteColloborate')->name('delete-colloborate');
        Route::get('/collaborator/{id?}', 'ControlpanelAuth\CollaboraterController@ColloborateList')->name('collaborator.list');
        Route::get('/offers-opt', 'ControlpanelAuth\PartnerController@OffersOpt')->name('offers-opt');
        Route::post('/show-feedback', 'UserAuth\SettingController@show_feedback')->name('show-feedback');
        Route::post('/store-payment', 'UserAuth\SettingController@store_payment')->name('store-payment');
        Route::get('/controlpanel-refund', 'ControlpanelAuth\PartnerController@refund')->name('controlpanel.refund');
        Route::get('/setting-button', 'ControlpanelAuth\AdminSettingController@buttons_setting')->name('setting.button');
        Route::get('/configuration', 'ControlpanelAuth\AdminSettingController@configuration')
            ->middleware('can:isAdmin')->name('configuration');
        Route::post('/configuration-update', 'ControlpanelAuth\AdminSettingController@configuration_update')
            ->middleware('can:isAdmin')->name('configuration-update');
        Route::post('/outbound-email-update', 'ControlpanelAuth\AdminSettingController@outbound_email_update')
            ->middleware('can:isAdmin')->name('outbound.email.update');
        Route::post('/mfa-demo/settings', 'ControlpanelAuth\AdminSettingController@updateMfaDemoSettings')
            ->middleware('can:isAdmin')->name('mfa-demo.settings.update');
        Route::post('/mfa-demo/users', 'ControlpanelAuth\AdminSettingController@storeMfaDemoUser')
            ->middleware('can:isAdmin')->name('mfa-demo.users.store');
        Route::post('/mfa-demo/users/{id}/toggle', 'ControlpanelAuth\AdminSettingController@toggleMfaDemoUser')
            ->middleware('can:isAdmin')->name('mfa-demo.users.toggle');
        Route::post('/mfa-demo/users/{id}/remove', 'ControlpanelAuth\AdminSettingController@removeMfaDemoUser')
            ->middleware('can:isAdmin')->name('mfa-demo.users.remove');
        Route::post('/view-button-setting/{id?}', 'ControlpanelAuth\AdminSettingController@view_buttons_setting')->name('view-button-setting');
        Route::post('/configuration-user-update', 'ControlpanelAuth\AdminSettingController@configuration_user_update')
            ->middleware('can:isAdmin')->name('configuration-user-update');
        Route::post('/update-button-setting', 'ControlpanelAuth\AdminSettingController@update_buttons_setting')->name('update-button-setting');
        Route::get('/faqs', 'ControlpanelAuth\FaqController@index')->name('faqs');
        Route::post('/faqs/store', 'ControlpanelAuth\FaqController@store')->name('faqs.store');
        Route::post('/faqs/delete/{id}', 'ControlpanelAuth\FaqController@delete')->name('faqs.delete');
        Route::post('/faqs/update', 'ControlpanelAuth\FaqController@update')->name('faqs.update');

        Route::get('tickets/adminindex', 'TicketController@adminindex')->name('tickets.adminindex');
        Route::get('/tickets/adminshow/{id}', 'TicketController@adminshow')->name('tickets.adminshow');
        Route::post('tickets/{id}/admincomment', 'TicketController@adminaddComment')->name('tickets.admincomment');
        Route::post('tickets/{id}/assign', 'TicketController@assign')->name('tickets.assign');
        // Route::get('tickets/create', 'ControlpanelAuth\TicketController@create')->name('tickets.create');
        Route::post('upload-image-ticket-comment-admin', 'TicketController@upload')->name('upload-image-ticket-comment-admin');
        // Route::get('tickets', 'ControlpanelAuth\TicketController@index')->name('tickets');
        // Route::post('tickets/{id}/assign', 'ControlpanelAuth\TicketController@assign')->name('tickets.assign');
        // Route::post('tickets/{id}/comment', 'ControlpanelAuth\TicketController@addComment')->name('tickets.comment');
        // Route::post('tickets/{id}/resolve', 'ControlpanelAuth\TicketController@resolve')->name('tickets.resolve');
         Route::post('tickets/{id}/close', 'TicketController@close')->name('tickets.close');
        // Route::post('tickets/{id}/reopen', 'ControlpanelAuth\TicketController@reopen')->name('tickets.reopen');
        // Route::get('tickets/show/{id}', 'ControlpanelAuth\TicketController@show')->name('tickets.show');


        
        //agents
        Route::get('agents/index', 'Controlpanel\AgentsController@index')->name('agents.index');
        Route::get('agents/show/{id}', 'Controlpanel\AgentsController@show')->name('agents.show');
        Route::post('/agent/tickets/{id}/reply', 'Controlpanel\AgentsController@reply')->name('agent.tickets.reply');
    });
});

//Route::group(['prefix' => 'user'], function () {
Route::get('/login', 'UserAuth\userController@index')->name('login');
Route::post('/login', 'UserAuth\LoginController@login');
Route::post('/logout', 'UserAuth\LoginController@logout')->name('logout');
Route::post('/resent_mfa_user','UserAuth\LoginController@resent_mfa_user')->name('resent_mfa_user');
Route::get('/direct_login', 'UserAuth\LoginController@direct_login')->name('direct_login');
//Route::get('/register', 'UserAuth\RegisterController@showRegistrationForm')->name('register');
Route::post('/register', 'UserAuth\RegisterController@register');
Route::get('verify/{verifyCode}', 'UserAuth\RegisterController@VerifyEmail');
Route::post('/half-register', 'UserAuth\RegisterController@halfRegister');
Route::post('/password/email', 'UserAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
Route::post('/password/reset', 'UserAuth\ResetPasswordController@reset')->name('password.email');
Route::get('/password/reset', 'UserAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
Route::get('/user/password/reset/{token}', 'UserAuth\ResetPasswordController@showResetForm');
Route::get('/direct_login_partner', 'Auth\LoginController@direct_login_partner')->name('direct_login_partner');
Route::get('/country', 'UserAuth\userController@getCountry');
Route::get('/username/reset', 'UserAuth\userController@showLinkRequestForm');
Route::post('/sent-username', 'UserAuth\userController@sendUsername');


Route::get('/testtest', 'UserAuth\RegisterController@existingUser');
Route::group(['prefix' => 'partner'], function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('partner.login');
    Route::post('/register', 'Auth\RegisterController@register')->name('partner.register');
    Route::get('verify/{verifyCode}', 'Auth\RegisterController@VerifyEmail');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('/mfa', 'Auth\LoginController@mfa')->name('mfa');
    Route::post('/verify_mfa','Auth\LoginController@verify_mfa')->name('verify_mfa');
    Route::get('/resent_mfa','Auth\LoginController@resent_mfa')->name('resent_mfa');

    Route::group(['middleware' => ['auth:web']], function () {
        Route::get('/home', 'Partner\UserController@index')->name('partner.home');
        Route::get('/offer-details/{id?}', 'Partner\UserController@offer_details')->name('offer-details');
        Route::get('/pending', 'Partner\UserController@pending')->name('pending');
        Route::get('/suspend', 'Partner\UserController@suspend')->name('suspend');
        Route::post('/update-userprofile', 'Partner\UserController@updateUserProfile')->name('updateuserprofile');
        Route::get('/new-offer', 'Partner\UserController@newOffer')->name('new-offer');
        Route::post('/store-offer', 'Partner\UserController@storeOffer')->name('store-offer');
        Route::post('/paynow-partner-offer-create', 'Partner\UserController@paynowstoreOffer')->name('paynow-partner-offer-create');
        
        Route::post('/change-offer-status', 'Partner\UserController@changeOfferStatus');

        Route::post('/change-status-user', 'Partner\UserController@changeOfferUser')->name('change-status-user');



    });
});
