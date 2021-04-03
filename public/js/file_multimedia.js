var checkFirst = true;

function page_init() {
    //�鱓���崕蝡贝身摰𡄯�𨧣oading蝯鞉�笔��嘑銵�
    //�嘑銵屸�摨�3
    console.log("page_init");
    //閮剖�颹anner
    if ($(".slider-for div").length > 1) {
        $('.slider-for').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            fade: true,
            asNavFor: '.slider-nav'
        });
        $('.slider-nav').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            asNavFor: '.slider-for',
            centerMode: true,
            arrows: false,
            focusOnSelect: true,
            responsive: [{
                    breakpoint: 980,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2
                    }
                }
            ]
        });
        $('.slider-for').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
            _obj = $(event.currentTarget).find(".main-file-multimedia-content").eq(currentSlide);
            if (_obj.find("video").get(0)) {
                _obj.find("video").get(0).pause();
            }
            /*if (checkFirst) {//��𧢲�毺蕃頧㗇��芣�芰�
                checkFirst = false;
                $('.slider-for').slick('slickSetOption', {
                    adaptiveHeight: true,
                });
            }*/
        });
        $('.slider-for').on('setPosition', function(event, slick) {
            changeImgSize();
        });
    }
    changeImgSize();
}

function page_resize() {
    //�鱓���崕蝡贝身摰𡄯�丯esize���嘑銵�
    console.log("page_resize");
}
//---------------------------------
function changeImgSize() {
    console.log("changeImgSize");
    $(".main-file-multimedia-img").attr('style', "");
    $(".main-file-multimedia-img").each(function(index) {
        $(this).height((595 * $(this).width()) / 892);
    });
}