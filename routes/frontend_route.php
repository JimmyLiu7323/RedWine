<?php
Route::get('/','Frontend\IndexController@index');

// 酒類各種條件列表
Route::get('/wines/{basic_catg?}','Frontend\WinesController@wines');

Route::get('/wine/{WineId}','Frontend\WinesController@wine');
Route::get('/by-style/{styleId?}','Frontend\WinesController@wine_byStyle');
Route::get('/by-country/{countryShort?}','Frontend\WinesController@wine_byCountry');
Route::get('/by-region/{regionId?}','Frontend\WinesController@wine_byRegion');


// Need pass middleware
Route::group(['middleware'=>['verifyMember'],'prefix'=>'account'],function(){
	Route::get('/','Frontend\MemberController@account');
	Route::match(['get','post'],'/edit','Frontend\MemberController@edit');
});
Route::match(['get','post'],'/newsletter/manage','Frontend\MemberController@maintain_newsletter');
Route::group(['middleware'=>['verifyMember'],'prefix'=>'address'],function(){
	Route::get('/','Frontend\MemberController@addressBook');
	Route::match(['get','post'],'/new','Frontend\MemberController@new_address');
	Route::match(['get','post'],'/billing/edit','Frontend\MemberController@billing_address_edit');
	Route::match(['get','post'],'/shipping/edit','Frontend\MemberController@shipping_address_edit');
});

Route::match(['get','post'],'/login','Frontend\MemberController@login');
Route::match(['get','post'],'/account/register','Frontend\MemberController@register');

Route::get('/cases/{caseId?}','Frontend\CaseDealsController@index');
Route::get('/logout','Frontend\MemberController@logout');

Route::get('/case/{MixId}','Frontend\CaseDealsController@saleMixDetail');

Route::group(['prefix'=>'shopping'],function(){
	Route::post('/addItem','Frontend\ShoppingController@addItem');
	Route::post('/updateItem','Frontend\ShoppingController@updateItem');
	Route::post('/updateCart','Frontend\ShoppingController@updateCart');
	Route::get('/cart','Frontend\ShoppingController@cart');
	Route::get('/deleteItem/{itemId}','Frontend\ShoppingController@deleteItem');
	Route::get('/ajx_deleteItem/{itemId}','Frontend\ShoppingController@ajx_deleteItem');
	Route::match(['get','post'],'/checkout','Frontend\ShoppingController@checkout');

	Route::post('/estimateUpdatePost','Frontend\ShoppingController@estimateUpdatePost');
	Route::post('/chooseDeliveryMethod','Frontend\ShoppingController@chooseDeliveryMethod');
});

Route::group(['prefix'=>'/pay'],function(){
	Route::get('/fail','Frontend\PaymentController@fail');
	Route::get('/success','Frontend\PaymentController@succcess');

	Route::get('/QRCode_display','Frontend\PaymentController@QRCode_display');
	Route::match(['get','post'],'/notify_url','Frontend\PaymentController@notify_url');
});

Route::group(['prefix'=>'/exhibition'],function(){
	Route::get('/fail','Frontend\ExhibitionController@payment_fail');
	Route::get('/success','Frontend\ExhibitionController@payment_succcess');
});

Route::get('/blog','Frontend\BlogController@blog');
Route::get('/blog/tags/{tagText}','Frontend\BlogController@tagSearch');
Route::get('/blog/category/{category}','Frontend\BlogController@blog');
Route::get('/article/{articleId}','Frontend\BlogController@article');
Route::get('/lang/{lang}','Frontend\IndexController@changeLanguage');
Route::get('/confirmation18','Frontend\IndexController@confirmation18');

Route::get('/guarantee','Frontend\FooterPageController@guarantee');
Route::get('/privacy-policy','Frontend\FooterPageController@privacyPolicy');
Route::get('/corporate','Frontend\FooterPageController@corporate');
Route::get('/awards','Frontend\FooterPageController@awards');
Route::get('/orders-and-payment','Frontend\FooterPageController@orders_payment');
Route::get('/delivery-page','Frontend\FooterPageController@deliveryPage');
Route::get('/contacts','Frontend\FooterPageController@contacts');
Route::post('/send-contacts','Frontend\FooterPageController@sendContacts');
Route::get('/about-history','Frontend\FooterPageController@aboutHistory');

Route::get('/exhibitions','Frontend\ExhibitionController@exhibitions');
Route::get('/exhibitions/visit','Frontend\ExhibitionController@visits');
Route::get('/exhibition/{EventId}','Frontend\ExhibitionController@detail');
Route::post('/exhibition/{EventId}/Booking','Frontend\ExhibitionController@booking');

Route::group(['prefix'=>'/best-sell'],function(){
	Route::get('/year/{year}','Frontend\LatestDealsController@yearSelling');
	Route::get('/month','Frontend\LatestDealsController@monthSelling');
	Route::get('/week','Frontend\LatestDealsController@weekSelling');

	Route::group(['prefix'=>'/price'],function(){
		Route::get('{priceLevel}','Frontend\LatestDealsController@priceLevelSelling');
	});
});

Route::get('/gifts','Frontend\GiftsController@index');
Route::get('/gifts/{categoryName}','Frontend\GiftsController@category');
Route::get('/gift/{GiftId}','Frontend\GiftsController@giftDetail');

Route::get('/search','Frontend\IndexController@search');

Route::group(['prefix'=>'/wishlist','middleware'=>['verifyMember']],function(){
	// http://127.0.0.1:8000/wishlist/add/wine/vDjN1582730668yzBn
	Route::get('/','Frontend\WishlistController@index');
	Route::get('/add/{Ptype}/{Pid}','Frontend\WishlistController@addToWish');
	Route::post('/update','Frontend\WishlistController@updateWishlist');

	// http://127.0.0.1:8000/wishlist/add_to_order/vDjN1582730668yzBn
	Route::get('/add_to_order/{Pid}','Frontend\WishlistController@addOneProduct');

	// "url":"/wishlist/add_all_to_order",
	Route::get('/add_all_to_order','Frontend\WishlistController@addAllWishProducts');
});