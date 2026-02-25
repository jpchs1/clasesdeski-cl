jQuery(document).ready(function($) {
    "use strict";
    $('.cq-coverflow').each(function(index, el) {
        var _this = $(this);
        var _buttonbackground = $(this).data('buttonbackground');
        var _buttonhoverbackground = $(this).data('buttonhoverbackground');
        var _contentcolor = $(this).data('contentcolor');
        var _dotanimation = $(this).data('dotanimation');
        var _contentbackground = $(this).data('contentbackground');
        var _arrowcolor = $(this).data('arrowcolor');
        var _arrowhovercolor = $(this).data('arrowhovercolor');
        var _cover = $('.cq-coverflow-cover', _this);
        var _itemContainer = $('.cq-coverflow-itemcontainer', _this);
        var _isautoheight = $(this).data('isautoheight') == "yes" ? true : false;
        var _istooltip = $(this).data('istooltip') == "yes" ? true : false;
        var _autodelay = parseInt($(this).data('autodelay'), 10) || 0;
        var _itemWidth = _cover.width();
        var _itemNum = 0;
        var _index = index;

        var _autodelayObj = false;
        if(_autodelay > 0){
            _autodelayObj = {
                pauseOnMouseEnter: true,
                disableOnInteraction: false,
                delay: 1000,
            }
        } else {
            _autodelayObj = false;
        }


        var _swiper = new Swiper($(".cq-coverflow-swiper", _this).get(0), {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: false,
            },
            autoplay: _autodelayObj,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            on: {
            init: function () {

            },
          },
        });

        $(document).on('lity:open', function(event, instance) {
            if(_swiper && (_autodelay > 0)) _swiper.autoplay.stop();
            event.preventDefault();
        });
        $(document).on('lity:remove', function(event, instance) {
            if(_swiper && (_autodelay > 0)) _swiper.autoplay.start();
            event.preventDefault();
        });

        $('.swiper-button-next, .swiper-button-prev', _this).on('mouseover', function(event) {
                if(_arrowhovercolor != "") $(this).css('color', _arrowhovercolor);
        }).on('mouseleave', function(event) {
                if(_arrowcolor != "") $(this).css('color', _arrowcolor);
        });


        $('.cq-coverflow-item', _this).each(function(index, el) {
            $(this).data('index', index);
            $(this).on('click', function(event) {
                var _index = $(this).data('index');
                if (_index == _swiper.activeIndex) {

                } else{
                   _swiper.slideTo(_index);
                }
                event.preventDefault();
            });

            if($(this).attr('title') != ""){
                var _tooltip = $(this).tooltipster({
                    position: 'top',
                    delay: 200,
                    interactive: true,
                    speed: 300,
                    touchDevices: true,
                    animation: 'grow',
                    theme: 'tooltipster-shadow',
                    contentAsHTML: true
                });

            }
        });



    });
});
