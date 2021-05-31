/**
 * Created by No Fear on 09.02.2021.
 * E-mail: g0th1c097@gmail.com
 */

$(document).ready(function () {

    $('.colors-view').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        adaptiveHeight: true,
        arrows: false,
        draggable: false,
        swipe: false,
        touchMove: false,
        infinite: false,
        fade: true,
        initialSlide: 25
    });

    $('.colors-nav img').click(function () {
        var _t = $(this);
        $('.colors-view .slick-slide').each(function () {
            if($(this).find('img').attr('alt') === _t.attr('alt')) {
                $('.colors-view').slick('slickGoTo', $(this).index());
            }
        });
        $('#za').text(_t.attr('alt'));
        document.getElementById('color').value=_t.attr('alt');
        $('.colors-nav img').removeClass('active');
        _t.addClass('active');
        // yaCounter22695421.reachGoal('ORDERcolor');
    });

    $('#color').change(function () {
        var _this = $(this).val();
        $('.colors-nav img').each(function () {
            $(this).attr('alt') === _this && $(this).click();
        });
    }).click(function () {
        // yaCounter22695421.reachGoal('ORDERcolor2');
    });

    if($(window).width() < 767.5) {
        $('.order-product').appendTo('.order-product-mob');
    }

});

$(window).resize(function () {
    if($(window).width() < 767.5) {
        $('.order-product').appendTo('.order-product-mob');
    }
    else {
        $('.order-product').appendTo('.order-product-desc');
    }
});
