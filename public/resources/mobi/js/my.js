
$(function(){

    var web = {
        window : $(window),
        body : $('body,html'),
        slide : $('#slide'),
        hamburger : $('#hamburger'),
        menu : $('#menu'),
        baguetteBox : $(".baguetteBox")
    }
    var navAct = function(){
        web.hamburger.on('click',function(){
            var that = $(this);
            if(!that.hasClass('active')){
                that.addClass('active');
                web.menu.addClass('open');
            }else{
                that.removeClass('active');
                web.menu.removeClass('open');
        }
        })
        web.menu.on('click',function(e){
            var _con = $('.menu-nav');
            if(!_con.is(event.target) && _con.has(event.target).length === 0){
                web.menu.removeClass('open');
                web.hamburger.removeClass('active');
            }
        })
    }

    var slideAct = function(){
        if(web.slide.length > 0){
            new Swiper ('#slide .swiper-container', {
                direction: 'horizontal',
                loop:true,
                autoplayDisableOnInteraction : false,
                speed: 500,
                autoplay: 5000,
                pagination: '.swiper-pagination',
                paginationClickable:true
            })
        }
    }

    var imgAct = function(){
        if(web.baguetteBox.length > 0){
            baguetteBox.run('.baguetteBox');
        }
    }


    var eventInit = function(){
        navAct();
        slideAct();
        imgAct();
    }

    eventInit();

})