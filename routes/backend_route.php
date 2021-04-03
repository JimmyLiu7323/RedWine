<?php
Route::get('admin/login','AdminPageEnterController@LoginIndex');
Route::match(['get','post'],'admin/verifyLogin','AdminPageEnterController@VerifyLogin');
Route::group(['middleware'=>['verifyAdmin'],'prefix'=>'admin'],function(){
    Route::get('/','AdminPageEnterController@loginIndex');
    Route::get('dashboard','AdminPageEnterController@adminDashboard');
});
Route::get('/admin/logout','AdminPageEnterController@logout');

Route::group(['middleware'=>['verifyAdmin'],'prefix'=>'admin/products'],function(){
    Route::get('/basic_category','Backend\AdminProductsController@basic_category');
    Route::match(['get','post'],'/basic_category/edit','Backend\AdminProductsController@edit_category');

    Route::get('/','Backend\AdminProductsController@products');
    Route::match(['get','post'],'add','Backend\AdminProductsController@add_product');
    Route::match(['get','post'],'mod/{id}','Backend\AdminProductsController@edit_product');
    Route::get('/delete/{id}','Backend\AdminProductsController@delete_product');

    Route::post('/import','Backend\AdminProductsController@import_product');

    Route::get('/varieties','Backend\AdminProductSetsController@varieties');
    Route::match(['get','post'],'/varieties/add','Backend\AdminProductSetsController@add_variety');
    Route::match(['get','post'],'/varieties/mod/{id}','Backend\AdminProductSetsController@edit_variety');
    Route::get('/variety/delete/{id}','Backend\AdminProductSetsController@delete_variety');

    Route::get('/colours','Backend\AdminProductSetsController@colours');
    Route::match(['get','post'],'/colours/add','Backend\AdminProductSetsController@add_colour');
    Route::match(['get','post'],'/colours/mod/{id}','Backend\AdminProductSetsController@edit_colour');
    Route::get('/colours/delete/{id}','Backend\AdminProductSetsController@delete_colour');   

    Route::get('/regions','Backend\AdminProductSetsController@regions');
    Route::match(['get','post'],'/regions/add','Backend\AdminProductSetsController@add_region');
    Route::match(['get','post'],'/regions/mod/{id}','Backend\AdminProductSetsController@edit_region');
    Route::get('/regions/delete/{id}','Backend\AdminProductSetsController@delete_region');   

    Route::get('/closures','Backend\AdminProductSetsController@closures');
    Route::match(['get','post'],'/closures/add','Backend\AdminProductSetsController@add_closure');
    Route::match(['get','post'],'/closures/mod/{id}','Backend\AdminProductSetsController@edit_closure');
    Route::get('/closures/delete/{id}','Backend\AdminProductSetsController@delete_closure');   

    Route::get('/styles','Backend\AdminProductSetsController@styles');
    Route::match(['get','post'],'/styles/add','Backend\AdminProductSetsController@add_style');
    Route::match(['get','post'],'/styles/mod/{id}','Backend\AdminProductSetsController@edit_style');
    Route::get('/styles/delete/{id}','Backend\AdminProductSetsController@delete_style');  

    Route::get('/caterings','Backend\AdminProductSetsController@caterings');
    Route::match(['get','post'],'/caterings/add','Backend\AdminProductSetsController@add_catering');
    Route::match(['get','post'],'/caterings/mod/{id}','Backend\AdminProductSetsController@edit_catering');
    Route::get('/caterings/delete/{id}','Backend\AdminProductSetsController@delete_catering');       

    Route::group(['prefix'=>'/mix'],function(){
    	Route::get('/','Backend\AdminProductsController@sales_mix');
        Route::match(['get','post'],'/add','Backend\AdminProductsController@add_salesMix');
    	Route::match(['get','post'],'/mod','Backend\AdminProductsController@edit_salesMix');
    	Route::get('/status/{id}','Backend\AdminProductsController@change_salesMix_status');
    	Route::get('/delete/{id}','Backend\AdminProductsController@delete_salesMix');
    });

    Route::group(['prefix'=>'/cases'],function(){
        Route::get('/','Backend\AdminProductsController@cases');
        Route::match(['get','post'],'/add','Backend\AdminProductsController@add_case');
        Route::match(['get','post'],'/mod','Backend\AdminProductsController@edit_case');
        Route::get('/delete','Backend\AdminProductsController@delete_case');
    });

    Route::group(['prefix'=>'/options'],function(){
        Route::get('/','Backend\AdminProductOptionsController@options');
        Route::match(['get','post'],'/add','Backend\AdminProductOptionsController@add_option');
        Route::match(['get','post'],'/edit/{optionId}','Backend\AdminProductOptionsController@edit_option');
        Route::get('/delete/{optionId}','Backend\AdminProductOptionsController@delete_option');
        Route::get('/ajaxProduct','Backend\AdminProductOptionsController@ajaxProduct');
        Route::get('/ajaxTopic','Backend\AdminProductOptionsController@ajaxTopic');
    });
});

// 首頁 Banner 管理
Route::group(['middleware'=>['verifyAdmin'],'prefix'=>'admin/banners'],function(){
    Route::get('/','AdminBannersController@banners');
    Route::match(['get','post'],'/add','AdminBannersController@add_mod_banner');
    Route::match(['get','post'],'/mod','AdminBannersController@add_mod_banner');
    Route::get('/delete','AdminBannersController@delete_banner');
});

// 購買流程
Route::group(['middleware'=>['verifyAdmin'],'prefix'=>'admin/shopping'],function(){
    Route::get('/parameters','Backend\AdminShoppingController@parameters');
    Route::match(['get','post'],'/parameter/edit','Backend\AdminShoppingController@edit_parameter');
    Route::match(['get','post'],'/parameter/freight','Backend\AdminShoppingController@edit_freight');

    Route::get('/orders','Backend\AdminShoppingController@orders');
    Route::match(['get','post'],'/orders/edit','Backend\AdminShoppingController@maintain_order');
    Route::get('/orders/delete','Backend\AdminShoppingController@delete_order');
    Route::get('/orders/shipping','Backend\AdminShoppingController@quick_shipping');
});

// 系統管理
Route::group(['middleware'=>['verifyAdmin'],'prefix'=>'admin/system'],function(){
    Route::match(['get','post'],'/self','AdminAuthoritiesController@self_data');
    Route::get('/countries','AdminSystemController@countries');
    Route::match(['get','post'],'/countries/maintain/{countryId?}','AdminSystemController@maintain_country');
    Route::get('/countries/delete/{countryId}','AdminSystemController@delete_country');
});

// 網站資訊
Route::group(['middleware'=>['verifyAdmin'],'prefix'=>'admin/webinfo'],function(){
    Route::match(['get','post'],'/basic_seo','AdminWebInfoController@basic_seo');
    Route::match(['get','post'],'/company','AdminWebInfoController@company');
});

// Blogs
Route::group(['middleware'=>['verifyAdmin'],'prefix'=>'admin/blog'],function(){
    Route::get('/category','AdminBlogController@category');
    Route::match(['get','post'],'/category/maintain','AdminBlogController@maintain_category');
    Route::get('/category/delete','AdminBlogController@delete_category');
    Route::get('/articles','AdminBlogController@articles');
    Route::match(['get','post'],'/articles/maintain','AdminBlogController@maintain_articles');
    Route::get('/articles/delete','AdminBlogController@delete_article');
});

// 群組管理
Route::group(['middleware'=>['verifyAdmin'],'prefix'=>'admin/groups'],function(){
    Route::get('/','AdminGroupsController@group_list');
    Route::match(['get','post'],'/add','AdminGroupsController@add_group');
    Route::match(['get','post'],'/mod/{groupId}','AdminGroupsController@edit_group');
    Route::get('/delete/{groupId}','AdminGroupsController@delete_group');
    Route::match(['get','post'],'people/{groupId}','AdminGroupsController@group_members');
});

Route::group(['middleware'=>['verifyAdmin'],'prefix'=>'admin/accounts'],function(){
    Route::get('/','AdminAuthoritiesController@accounts');
    Route::match(['get','post'],'add','AdminAuthoritiesController@add_account');
    Route::match(['get','post'],'mod/{id}','AdminAuthoritiesController@mod_account');
    Route::get('delete/{id}','AdminAuthoritiesController@delete_account');
});

// 單位管理
Route::group(['middleware'=>['verifyAdmin'],'prefix'=>'admin/departments'],function(){
    Route::get('/','AdminAuthoritiesController@departments');
    Route::match(['get','post'],'/add','AdminAuthoritiesController@add_department');
    Route::match(['get','post'],'/mod/{id}','AdminAuthoritiesController@edit_department');
    Route::get('/setOrder','AdminAuthoritiesController@setDepartmentOrder');
    Route::get('/del/{id}','AdminAuthoritiesController@delete_department');
});

// 禮物管理
Route::group(['middleware'=>['verifyAdmin'],'prefix'=>'admin/gifts'],function(){
    Route::get('/banners','Backend\AdminGiftsController@banners');
    Route::match(['get','post'],'/banners/add','Backend\AdminGiftsController@add_mod_banner');
    Route::match(['get','post'],'/banners/edit','Backend\AdminGiftsController@add_mod_banner');
    Route::get('/banners/delete','Backend\AdminGiftsController@delete_banner');

    Route::match(['get','post'],'/business-image','Backend\AdminGiftsController@business_image');

    Route::get('/categories','Backend\AdminGiftsController@categories');
    Route::match(['get','post'],'/categories/edit','Backend\AdminGiftsController@edit_category');

    Route::get('/list','Backend\AdminGiftsController@gifts');

    Route::match(['get','post'],'/add','Backend\AdminGiftsController@add_gift');
    Route::match(['get','post'],'/edit','Backend\AdminGiftsController@edit_gift');
    Route::get('/delete','Backend\AdminGiftsController@delete_gift');
});

// 模板資料管理
Route::group(['middleware'=>['verifyAdmin'],'prefix'=>'admin/template'],function(){
    Route::match(['get','post'],'/license','Backend\AdminTemplateController@maintain_license');
    Route::match(['get','post'],'/global_notice','Backend\AdminTemplateController@global_notice');
    Route::match(['get','post'],'/m_feature','Backend\AdminTemplateController@m_feature');
    Route::match(['get','post'],'/index_featured_image','Backend\AdminTemplateController@index_featured_image');
    Route::match(['get','post'],'/index_featured_image/edit/{SN}','Backend\AdminTemplateController@edit_index_featured_image');
    Route::match(['get','post'],'/index_featured_video','Backend\AdminTemplateController@index_featured_video');
    Route::match(['get','post'],'/index_slogan','Backend\AdminTemplateController@index_slogan');
    Route::match(['get','post'],'/index_guarantee','Backend\AdminTemplateController@index_guarantee');

    Route::match(['get','post'],'/case-deals','Backend\AdminTemplateController@case_deals');
});

Route::group(['middleware'=>['verifyAdmin'],'prefix'=>'admin/footer_info'],function(){
    Route::match(['get','post'],'/guarantee','Backend\AdminFooterInfoController@guarantee');
    Route::match(['get','post'],'/privacy_policy','Backend\AdminFooterInfoController@privacy_policy');
    Route::match(['get','post'],'/corporate_services','Backend\AdminFooterInfoController@corporate_services');
    Route::match(['get','post'],'/awards','Backend\AdminFooterInfoController@awards');
});

Route::group(['middleware'=>['verifyAdmin'],'prefix'=>'admin/exhibition'],function(){
    Route::match(['get','post'],'/image','Backend\AdminExhibitionController@image');
    Route::get('/events','Backend\AdminExhibitionController@events');
    Route::match(['get','post'],'/events/add','Backend\AdminExhibitionController@add_event');
    Route::match(['get','post'],'/events/edit','Backend\AdminExhibitionController@edit_event');
    Route::get('/events/delete','Backend\AdminExhibitionController@delete_event');

    Route::group(['prefix'=>'/visits'],function(){
        Route::get('/','Backend\AdminExhibitionController@visits');
        Route::match(['get','post'],'/add','Backend\AdminExhibitionController@add_visit');
        Route::match(['get','post'],'/edit','Backend\AdminExhibitionController@edit_visit');
        Route::get('/delete','Backend\AdminExhibitionController@delete_visit');
    });

    Route::get('/{eventType}/attendees','Backend\AdminExhibitionController@attendees');
});

// 會員管理
Route::group(['middleware'=>['verifyAdmin'],'prefix'=>'admin/members'],function(){
    Route::get('/','Backend\AdminMembersController@index');
    Route::get('/delete','Backend\AdminMembersController@delete_member');
});

Route::post('/admin/tinymce/upload_image',['middleware'=>['verifyAdmin'],'uses'=>'TinymceController@upload_image']);
Route::get('/admin/build_filter','Backend\AdminProductsController@build_filter')->middleware(['verifyAdmin']);
?>