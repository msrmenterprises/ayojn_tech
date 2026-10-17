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

Route::get('/', 'UserAuth\userController@index');
Route::get('/refer/{referCode}', 'UserAuth\userController@index');
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
    Route::post('/sent-request', 'UserAuth\userController@sentRedeemRequest')->name('sent-request');

    Route::post('/edituserprofile', 'UserAuth\userController@edituserprofile')->name('edituserprofile');
    Route::post('/update-offer-data', 'UserAuth\userController@updateOfferData')->name('update-offer-data');

    Route::post('/mapdata', 'UserAuth\userController@mapDataPopup')->name('mapdata');
    Route::post('/mapdata2', 'UserAuth\MapController@mapDataPopup')->name('mapdata2');
    Route::post('/topcountriesdata', 'UserAuth\userController@topcountriesdata')->name('topcountriesdata');
    Route::post('/topdestinationdata', 'UserAuth\userController@topdestinationdata')->name('topdestinationdata');
    Route::post('/topindustriesdata', 'UserAuth\userController@topindustriesdata')->name('topindustriesdata');
    Route::post('/toprecieptdsdata', 'UserAuth\userController@toprecieptdsdata')->name('toprecieptdsdata');
    Route::get('/getUserCount/{id?}', 'UserAuth\userController@getUserCount')->name('getUserCount');
    Route::get('/bid', 'UserAuth\BidController@index')->name('bid');
    Route::get('/unpaid-bid', 'UserAuth\BidController@unpaid')->name('unpaid-opportunity');
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
    Route::get('/my-bids', 'UserAuth\BidController@myResponse');
    Route::get('/get-bid-response/{id?}', 'UserAuth\BidController@getBidResponse');
    Route::get('/bid-accept/{id?}', 'UserAuth\BidController@acceptBid');
    Route::get('/get-hashtags', 'UserAuth\OppertunityController@getHashtags');
    Route::get('export-vouch/{id?}', 'UserAuth\OppertunityController@vouchListExport')->name('export.file');


    Route::get('/opportunity', 'UserAuth\OppertunityController@index')->name('opportunity');
    Route::get('/build-opportunity', 'UserAuth\OppertunityController@buildOpportunity')->name('build-opportunity');
    Route::post('/add-new-opportunity', 'UserAuth\OppertunityController@addNewOpportunity')
        ->name('add-new-opportunity');

    Route::post('/add-new-vouch', 'UserAuth\OppertunityController@addNewVouch')->name('add-new-vouch');
    Route::get('/my-vouches', 'UserAuth\OppertunityController@myResponse');
    Route::get('/add-vouch/{id}', 'UserAuth\OppertunityController@addVouch');
    Route::get('/get-cities/{id}', 'UserAuth\OppertunityController@getCities');
    Route::get('/add-reason/{id}', 'UserAuth\BidController@addReason');
    Route::post('/add-new-vouch-code', 'UserAuth\BidController@addNewVouchCode')->name('add-new-vouch-code');
    Route::post('/add-reason', 'UserAuth\BidController@addReasonOfReason');
    Route::get('/add-new-vouch', 'UserAuth\OppertunityController@addNewVouch');
    Route::get('/vouches/{id}', 'UserAuth\OppertunityController@vouchList');
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
    //Route::post('/home', 'ControlpanelAuth\LoginController@logout')->name('logout');
    Route::get('/home', function () {
        return redirect(url('/'));
    });
//    Route::get('/register', 'ControlpanelAuth\RegisterController@showRegistrationForm')->name('register');
//    Route::post('/register', 'ControlpanelAuth\RegisterController@register');

    Route::post('/password/email', 'ControlpanelAuth\ForgotPasswordController@sendResetLinkEmail')
        ->name('password.request');
    Route::post('/password/reset', 'ControlpanelAuth\ResetPasswordController@reset')->name('password.email');
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


        Route::get('/bid-list', 'ControlpanelAuth\BidController@index')->name('bid-list');
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
        Route::get('/edituser-type/{id?}', 'Controlpanel\UserController@editUserType');
        Route::post('/updateusertype', 'Controlpanel\UserController@updateUserType')->name('updateusertype');
        Route::post('/updateuser', 'Controlpanel\UserController@updateuser')->name('updateuser');
        Route::post('delete-user', 'Controlpanel\UserController@deleteUser')->name('delete-user');
        Route::post('delete-bid', 'ControlpanelAuth\BidController@deleteBid')->name('delete-bid');
        Route::post('delete-code', 'ControlpanelAuth\BidController@deleteCode')->name('delete-code');
        Route::post('delete-users', 'Controlpanel\UserController@deleteUsers')->name('delete-users');

        Route::get('/partners', 'ControlpanelAuth\PartnerController@partner')->name('partners');
        Route::post('/partner/list-users', 'ControlpanelAuth\PartnerController@listUser')->name('list-users');
        Route::get('/partner-review-list', 'ControlpanelAuth\PartnerController@userReviewList')
            ->name('partner-review-list');
        Route::post('partner-review-list-data', 'ControlpanelAuth\PartnerController@listReviewUser')
            ->name('partner-list-data');
        Route::get('/offers', 'ControlpanelAuth\PartnerController@Offers')->name('offers');
        Route::post('/delete-offer', 'ControlpanelAuth\PartnerController@DeleteOffers')->name('delete-offer');
        Route::post('/partner/list-offers', 'ControlpanelAuth\PartnerController@listOffer')->name('list-offers');
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

    });
});

//Route::group(['prefix' => 'user'], function () {
Route::get('/login', 'UserAuth\userController@index')->name('login');
Route::post('/login', 'UserAuth\LoginController@login');
Route::post('/logout', 'UserAuth\LoginController@logout')->name('logout');

//Route::get('/register', 'UserAuth\RegisterController@showRegistrationForm')->name('register');
Route::post('/register', 'UserAuth\RegisterController@register');
Route::get('verify/{verifyCode}', 'UserAuth\RegisterController@VerifyEmail');
Route::post('/half-register', 'UserAuth\RegisterController@halfRegister');
Route::post('/password/email', 'UserAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
Route::post('/password/reset', 'UserAuth\ResetPasswordController@reset')->name('password.email');
Route::get('/password/reset', 'UserAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
Route::get('/user/password/reset/{token}', 'UserAuth\ResetPasswordController@showResetForm');
Route::get('/country', 'UserAuth\userController@getCountry');
Route::get('/username/reset', 'UserAuth\userController@showLinkRequestForm');
Route::post('/sent-username', 'UserAuth\userController@sendUsername');


Route::get('/testtest', 'UserAuth\RegisterController@existingUser');
Route::group(['prefix' => 'partner'], function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('partner.login');
    Route::post('/register', 'Auth\RegisterController@register')->name('partner.register');
    Route::get('verify/{verifyCode}', 'Auth\RegisterController@VerifyEmail');
    Route::post('/login', 'Auth\LoginController@login');


    Route::group(['middleware' => ['auth:web']], function () {
        Route::get('/home', 'Partner\UserController@index')->name('partner.home');
        Route::get('/pending', 'Partner\UserController@pending')->name('pending');
        Route::get('/suspend', 'Partner\UserController@suspend')->name('suspend');
        Route::post('/update-userprofile', 'Partner\UserController@updateUserProfile')->name('updateuserprofile');
        Route::get('/new-offer', 'Partner\UserController@newOffer')->name('new-offer');
        Route::post('/store-offer', 'Partner\UserController@storeOffer')->name('store-offer');
        Route::post('/change-offer-status', 'Partner\UserController@changeOfferStatus');

    });
});
