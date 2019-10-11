/******Script du site***********/

///*functions ******************/

var slider;

function getById(id,arr)
{
    var elm = null;
    arr.forEach(function (item,index) {

        if(item.id == id)
        {
            elm =  item;
            return;
        }
    })
    if(elm!=null)
        return  elm;
    else
        return 0;
}


function setTotal() {
    var t=0;
    $('.list-group-item').each(function(){
            t+=parseFloat($(this).find('.montantdemande').text());
    })
    $('.total').text(t.toFixed(2));
}

function updateinfos(id,item)
{
    var infos = JSON.parse(localStorage.getItem('infos'));
    infos.forEach(function (item1,index) {
        if(item1.id==id)
        {
            infos[index] =item
        }
    })
    localStorage.setItem('infos',JSON.stringify(infos));
}

/******************/


$('.showpass').click(function(){
    if($('#fos_user_registration_form_plainPassword_first').attr('type')=='password')
    {
        $(this).removeClass('icon-eye');
        $(this).addClass('icon-eye-off');
        $('#fos_user_registration_form_plainPassword_first').attr('type','text');
    }
    else
    {
        $(this).addClass('icon-eye');
        $(this).removeClass('icon-eye-off');
        $('#fos_user_registration_form_plainPassword_first').attr('type','password');
    }
})


$('.nav.nav-tabs li').click(function () {
    $('.nav.nav-tabs li').removeClass('active');
    $(this).addClass('active');
})

$('.downloadcontrat').click(function (e) {
    if($(this).attr('href')=="#")
        e.preventDefault();

    if(!($('[name=checkbox]').is(':checked')))
    {
        e.preventDefault();
    }

})

$('.boxscpi').on('mouseenter',function () {
    var backcolor = $(this).css('border-bottom-color');
    if(!$(this).hasClass('choisit'))
        $(this).css('background-color',backcolor);
})

$('.boxscpi').on('mouseleave',function () {
    if(!$(this).hasClass('choisit'))
        $(this).css('background-color','unset');
})

$(".contart").on( 'scroll', function(){

    if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight && localStorage.getItem('scrolled')==null) {

        localStorage.setItem('scrolled',1);
        $('.icheckbox_minimal-green').removeClass('disabled');
        $('.accepter').removeAttr('disabled');
        $('.downloadcontrat').removeClass('disabled');
        $('.downloadcontrat').addClass('enabled');
        $('.downloadcontrat').attr('href','/files/cv.pdf');
        $('.downloadcontrat').attr('download','Contrat_SCPI_2019.pdf');

    }

});







$('.boxscpi').on('click',function () {

    var backcolor = $(this).css('border-bottom-color');
    var chosen = JSON.parse(localStorage.getItem('chosen'));
    var indexel = parseInt($(this).attr('data-index'));
    if($(this).hasClass('choisit'))
    {
        /***Suprimmer element*/
        if(chosen.includes(indexel))
        {
            var des = indexel;

            if($('.list-group-item').length==1)
            {
                $('.dropdown-menu.dropdown-menu-md').addClass('d-none');
            }

            if (chosen.indexOf(des) > -1) {
                chosen.splice(chosen.indexOf(des), 1);
                $('.list-group-item[data-index='+des+']').remove();
                setTotal();
            }
        }
        /***Suprimmer la selection*/
        $(this).removeClass('choisit');
        $(this).css('background-color','unset');

        /***Dimunier le nombre d elemsn sur le panier*/
        if($('.countitems').is(':visible'))
            $('.countitems span').text(parseInt($('.countitems span').text())-1);
    }
    else
    {
        if(!chosen.includes(indexel))
        {
            chosen.push(indexel);
        }

        $(this).addClass('choisit');
        $(this).css('background-color',backcolor);

        /******************/
        $('.dropdown-menu.dropdown-menu-md').removeClass('d-none');
        var infos = JSON.parse(localStorage.getItem('infos'));
        var index = indexel;
        var item = getById(index,infos);

        if(item!=0)
        {
            var cart = $('.shopping-cart');
            var imgtodrag = $(this).find("img").eq(0);

            var rendement = item.rendement;
            var montantdemande = item.montantdemande;
            var rente = item.rente;
            var nom = item.nom;
            var logo = item.logo;


            if (imgtodrag) {

                if($('.countitems').is(':visible'))
                {
                    $('.countitems span').text(parseInt($('.countitems span').text())+1);
                }
                else
                {
                    $('.countitems span').text(1);
                    $('.countitems').removeClass('d-none');
                }

                $('.monpanier').after('<a class="list-group-item" data-index="'+index+'" href="/reservation/details/'+index+'">' +
                    '<div class="d-flex align-items-center">' +
                    '<div class="icon icon-sm text-primary"><img src="' + logo + '" class="img-fluid logoscpi"></div>' +
                    '<div class=" ml-auto"> <h5 class="font-weight-bold text-uppercase text-primary mb-0 w-100">' + nom + '</h5>'+
                    '<p class="font-size-sm text-gray-700 mb-0 w-100">Rendement : <span class="rendement">' + rendement + '</span> %</p></div>'+
                    '</div>' +
                    '<div class="row no-gutters mt-2 mb-0">' +
                    '<div class="col-12"><b>Montant demandé : <span class="montantdemande colorbrand">'+ montantdemande + '</span> €</b></div> ' +
                    '<div class="col-12"><b>Rente moyenne annuelle  : <span class="rente colorbrand">' + rente + ' </span> €</b></div> ' +
                    '</div> '+
                    '</a>');

                /****Animation d image ******/
                if(cart != undefined && cart.is(':visible'))
                {
                    var imgclone = imgtodrag.clone()
                        .offset({
                            top: imgtodrag.offset().top,
                            left: imgtodrag.offset().left
                        })
                        .css({
                            'opacity': '0.5',
                            'position': 'absolute',
                            'height': '150px',
                            'width': '150px',
                            'z-index': '100'
                        })
                        .appendTo($('body'))
                        .animate({
                            'top': cart.offset().top + 10,
                            'left': cart.offset().left + 10,
                            'width': 75,
                            'height': 60
                        }, 1000, 'easeInOutExpo');

                    setTimeout(function () {
                        cart.effect("bounce", {
                            times: 2
                        }, 500);
                    }, 1500);

                    imgclone.animate({
                        'width': 0,
                        'height': 0
                    }, function () {
                        $(this).detach()
                    });
                }

            }

        }
        setTotal();

    }

    localStorage.setItem('chosen',JSON.stringify(chosen));


})



$('.nombreparts').on('change',function () {

    var index = 0;
    if($(this).closest('.swiped').length>0){
        index = parseInt($(this).closest('.swiped').attr('data-index'));
    }
    else
    {
        index = parseInt($(this).closest('.linechosen').attr('data-index'));
    }

    var infos =JSON.parse(localStorage.getItem('infos'));
    var item = getById(index,infos);

    if(item!=0)
    {

        var valeurpart  = item.valeurpart;
        var rendement  = item.rendement;
        var nombrepart = $(this).val();
        var element =
            {
                id:item.id,
                nom:item.nom,
                rendement:item.rendement,
                nombredepart:nombrepart,
                valeurdepart:item.valeurdepart,
                logo:item.logo,
                coleur:item.coleur,
                montantdemande:parseFloat(item.valeurdepart*nombrepart).toFixed(2),
                rente:parseFloat((parseFloat(item.valeurdepart)*parseInt(nombrepart))/(parseFloat(item.rendement)/100)).toFixed(2)
            };

            updateinfos(index,element)
            if($(this).closest('.scpichoisit').find('.montantdemande').length>0)
            {
                $(this).closest('.scpichoisit').find('.montantdemande').text(element.montantdemande);
                $(this).closest('.scpichoisit').find('.rente').text(element.rente);
            }
            else
            {
                $(this).closest('.linechosen').find('.montantdemande').text(element.montantdemande);
                $(this).closest('.linechosen').find('.rente').text(element.rente);
            }


            $('.list-group-item[data-index='+index+']').find('.montantdemande').text(element.montantdemande);
            $('.list-group-item[data-index='+index+']').find('.rente').text(element.rente);

            setTotal();

    }
})


$('#modalSigninHorizontal button').on('click',function () {
    var code = $('#codesms').val();
    var codestpred = localStorage.getItem('codegen');
    if(code == codestpred)
    {
        $(this).closest('.modal').modal('hide');
        $('.fos_user_registration_register').submit();
    }
    else
    {
            alert('Code non valide');
    }

})

$('a[href*=signup]').on('show.bs.tab', function (e) {

        $('.fos_user_registration_register input:not(input[type=button])').val('');
        $('.fos_user_registration_register input').removeClass('has-error');
        $('.fos_user_registration_register label').removeClass('has-error');

})



$.fn.extend({
    animateCss: function(animationName, callback) {
        var animationEnd = (function(el) {
            var animations = {
                animation: 'animationend',
                OAnimation: 'oAnimationEnd',
                MozAnimation: 'mozAnimationEnd',
                WebkitAnimation: 'webkitAnimationEnd',
            };

            for (var t in animations) {
                if (el.style[t] !== undefined) {
                    return animations[t];
                }
            }
        })(document.createElement('div'));

        this.addClass('animated ' + animationName).one(animationEnd, function() {
            $(this).removeClass('animated ' + animationName);

            if (typeof callback === 'function') callback();
        });

        return this;
    }
});




$('.scpichoisit .close').on('click',function(){

    var index =0;
    if($(this).closest('.swiped').length>0){
        $(this).closest('.swiped').fadeOut();
        index = parseInt($(this).closest('.swiped').attr('data-index'));
    }
    else{
        $(this).closest('.linechosen').fadeOut();
        index = parseInt($(this).closest('.linechosen').attr('data-index'));
    }

    var chosen = JSON.parse(localStorage.getItem('chosen'));

    if(chosen.includes(index))
    {

        chosen.filter(function(val,i,arr){
            return val==index;
        });

        var des = index;

        if($('.list-group-item').length==1)
        {
            $('.dropdown-menu.dropdown-menu-md').addClass('d-none');
        }

        if (chosen.indexOf(des) > -1) {
            chosen.splice(chosen.indexOf(des), 1);
            $('.list-group-item[data-index='+des+']').remove();
            setTotal();
        }

        /***Suprimmer la selection*/
        $('.boxscpi[data-index='+index+']').css('background-color','unset');
        $('.boxscpi[data-index='+index+']').removeClass('choisit');

        /***Dimunier le nombre d elemsn sur le panier*/
        if($('.countitems').is(':visible'))
            $('.countitems span').text(parseInt($('.countitems span').text())-1);

    }

    localStorage.setItem('chosen',JSON.stringify(chosen));

});


$( document ).ready(function() {

    if(localStorage.getItem('chosen')==null)
    {
        localStorage.setItem('chosen',JSON.stringify([0]));
    }
    else
    {
        var chosen = JSON.parse(localStorage.getItem('chosen'));

        if(chosen.length>1)
            $('.dropdown-menu.dropdown-menu-md').removeClass('d-none');


        $('.boxscpi').each(function () {

            chosen.forEach(function (item) {
                if(item!=0)
                {
                    if(!$("[data-index="+item+"]").hasClass('swiped'))
                        $("[data-index="+item+"]").addClass('swiped')
                    $(".swiped[data-index="+item+"]").show();
                }
            })

            if(chosen.includes(parseInt($(this).attr('data-index'))))
            {
                var backcolor = $(this).css('border-bottom-color');
                $(this).addClass('choisit');
                $(this).css('background-color',backcolor);
            }
        })
    }

    if($('.linechosen').length>0)
    {
        var chosen = JSON.parse(localStorage.getItem('chosen'));
        var infos = JSON.parse(localStorage.getItem('infos'));

        chosen.forEach(function (item , index) {
            if(item!=0) {

                $(".linechosen[data-index=" + item + "]").fadeIn();
                var  element = getById(item,infos);

                if(element!=0)
                {
                    $(".linechosen[data-index=" + item + "]").find('.nombreparts ').val(element.nombredepart)
                    $(".linechosen[data-index=" + item + "]").find('.montantdemande ').text(element.montantdemande)
                    $(".linechosen[data-index=" + item + "]").find('.rente ').text(element.rente)
                }

            }

        })
    }

    /*************Charger tous les scpis*****************/

    if(localStorage.getItem('infos')==null)
    {
        var items=[];
        $.ajax({
            url: "/getallscpi",
            data:{"token":"ea6b2efbdd4255a9f1b3bbc6399b58f4"},
            success:function(data){
                var d = JSON.parse(data);
                d.forEach(function (item,index) {
                    items.push({
                        id:item.id,
                        nom:item.nom,
                        rendement:item.rendementactuel,
                        nombredepart:1,
                        valeurdepart:item.valeurPart,
                        logo:item.logo,
                        coleur:item.coleur,
                        montantdemande:item.valeurPart,
                        rente:parseFloat(item.valeurPart/(item.rendementactuel/100)).toFixed(2)
                    })
                })
            }
        }).done(function() {

            if(items.length>0)
            {
                localStorage.setItem('infos',JSON.stringify(items));
            }

        });


    }

    if(localStorage.getItem('scrolled')!=null)
    {
        $('.icheckbox_minimal-green').removeClass('disabled');
        $('.accepter').removeAttr('disabled');
        $('.downloadcontrat').removeClass('disabled');
        $('.downloadcontrat').addClass('enabled');

        $('.downloadcontrat').attr('href','/files/cv.pdf');
        $('.downloadcontrat').attr('download','Contrat_SCPI_2019.pdf');
    }


    $('input').iCheck({
        checkboxClass: 'icheckbox_minimal-green',
        radioClass: 'iradio_minimal',
        increaseArea: '20%' // optional
    });

    var  defslide=0;

    if(localStorage.getItem('slider')==null)
    {
        localStorage.setItem('slider',0);
    }
    else
    {
        defslide=localStorage.getItem('slider');
        //defslide = localStorage.getItem('slider');

        if(defslide>0)
        {
            $('.slick-slide').removeClass('d-none');

            var chosen = JSON.parse(localStorage.getItem('chosen'));
            var infos = JSON.parse(localStorage.getItem('infos'));

            chosen.forEach(function (item , index) {
                if(item!=0) {

                    $(".linechosen[data-index=" + item + "]").fadeIn();
                    $(".linechosen[data-index=" + item + "]").addClass('swiped');
                    var  element = getById(item,infos);
                    if(element!=0)
                    {
                        $(".linechosen[data-index=" + item + "]").find('.nombreparts ').val(element.nombredepart)
                        $(".linechosen[data-index=" + item + "]").find('.montantdemande ').text(element.montantdemande)
                        $(".linechosen[data-index=" + item + "]").find('.rente ').text(element.rente)
                    }

                }

            })
        }

    }


     slider = $('.container-slick').slick({
        dots: false,
        infinite: false,
        speed: 500,
        fade: false,
        slidesToShow: 1,
        adaptiveHeight: false,
        autoplay: false,
        cssEase: 'ease-out',
        nextArrow: '.nexxt',
        prevArrow: '.prevee',
        draggable: false,
        swipe: false
    }).on('afterChange', function(event, slick, currentSlide){

         //$('.slick-track').css('height',(parseInt($('.slick-current.slick-active').height())+10)+"px");

        localStorage.setItem('slider',currentSlide);

        /*$(this).slick('slickAdd','<div><h3>45</h3></div>');
        $(this).slick('slickGoTo', 'slickCurrentSlide' + 1);*/

        if(currentSlide>0)
        {
            $('.slick-slide').removeClass('d-none');

            if (currentSlide == 1) {

                //$('.slick-slide.first').addClass('d-none');

                $(".linechosen[data-index]").hide();
                var chosen = JSON.parse(localStorage.getItem('chosen'));
                var infos = JSON.parse(localStorage.getItem('infos'));

                chosen.forEach(function (item , index) {

                    if(item!=0) {

                        $(".linechosen[data-index=" + item + "]").fadeIn();
                        $(".linechosen[data-index=" + item + "]").addClass('swiped');
                        var  element = getById(item,infos);
                        if(element!=0)
                        {
                            $(".linechosen[data-index=" + item + "]").find('.nombreparts ').val(element.nombredepart)
                            $(".linechosen[data-index=" + item + "]").find('.montantdemande ').text(element.montantdemande)
                            $(".linechosen[data-index=" + item + "]").find('.rente ').text(element.rente)
                        }

                    }

                })
            }

            $('.prevee').removeClass('d-none');

        }
        else
        {
            $('.slick-slide:not(.first)').addClass('d-none');
            $('.prevee').addClass('d-none');
        }

    });


    //$('.slick-slide:not(.slick-active.slick-current)').css('height',$('.slick-current.slick-active').css('height'));





    $('.container-slick').slick('slickGoTo', defslide,true);
    //$('.container-slick').slickGoTo(1,false);

    if($('.list-group-flush').is(':visible'))
    {
        var infos  = JSON.parse(localStorage.getItem('infos'));


        if($('.swiped.choisit').is(':visible'))
        {
            $('.swiped.choisit').each(function () {

                var index = parseInt($(this).attr('data-index'));
                var elment = getById(index,infos);

                var cart = $('.shopping-cart');
                var imgtodrag = $(this).find("img").eq(0);

                if (imgtodrag) {

                    var rendement =elment.rendement;
                    var nom = elment.nom;
                    var montantdemande = elment.montantdemande;
                    var rente = elment.rente;
                    var logo = elment.logo;


                    if ($('.countitems').is(':visible')) {

                        $('.countitems span').text(parseInt($('.countitems span').text()) + 1);

                    }

                    $('.monpanier').after('<a class="list-group-item" data-index="'+index+'" href="/reservation/details/'+index+'">' +
                        '<div class="d-flex align-items-center">' +
                        '<div class="icon icon-sm text-primary"><img src="' + logo + '" class="img-fluid logoscpi"></div>' +
                        '<div class=" ml-auto"> <h5 class="font-weight-bold text-uppercase text-primary mb-0 w-100">' + nom + '</h5>'+
                        '<p class="font-size-sm text-gray-700 mb-0 w-100">Rendement : <span class="rendement">' + rendement + '</span> %</p></div>'+
                        '</div>' +
                        '<div class="row no-gutters mt-2 mb-0">' +
                        '<div class="col-12"><b>Montant demandé : <span class="montantdemande colorbrand">'+ montantdemande + '</span> €</b></div> ' +
                        '<div class="col-12"><b>Rente moyenne annuelle  : <span class="rente colorbrand">' + rente + ' </span> €</b></div> ' +
                        '</div> '+
                        '</a>');


                }
                else
                {

                }

            })
        }
        else
        {
            var chosne  = JSON.parse(localStorage.getItem('chosen'));
            chosne.forEach(function (item,index) {

                if(item!=0)
                {
                    var index = parseInt(item);
                    var elment = getById(index,infos);



                    var cart = $('.shopping-cart');

                    //var imgtodrag = $('.').find("img").eq(0);


                    if (elment.logo) {
                        if ($('.countitems').is(':visible')) {

                            var rendement =elment.rendement;
                            var nom = elment.nom;
                            var montantdemande = elment.montantdemande;
                            var rente = elment.rente;
                            var logo = elment.logo;

                            $('.countitems span').text(parseInt($('.countitems span').text()) + 1);

                            $('.monpanier').after('<a class="list-group-item" data-index="'+index+'" href="/reservation/details/'+index+'">' +
                                '<div class="d-flex align-items-center">' +
                                '<div class="icon icon-sm text-primary"><img src="' + logo + '" class="img-fluid logoscpi"></div>' +
                                '<div class=" ml-auto"> <h5 class="font-weight-bold text-uppercase text-primary mb-0 w-100">' + nom + '</h5>'+
                                '<p class="font-size-sm text-gray-700 mb-0 w-100">Rendement : <span class="rendement">' + rendement + '</span> %</p></div>'+
                                '</div>' +
                                '<div class="row no-gutters mt-2 mb-0">' +
                                '<div class="col-12"><b>Montant demandé : <span class="montantdemande colorbrand">'+ montantdemande + '</span> €</b></div> ' +
                                '<div class="col-12"><b>Rente moyenne annuelle  : <span class="rente colorbrand">' + rente + ' </span> €</b></div> ' +
                                '</div> '+
                                '</a>');
                        }

                    }
                }

            })
        }
        setTotal();
    }
    else
    {
        if($(document).width()<=414)
        {
            var infos  = JSON.parse(localStorage.getItem('infos'));


            if($('.swiped.choisit').is(':visible'))
            {
                $('.swiped.choisit').each(function () {

                    var index = parseInt($(this).attr('data-index'));
                    var elment = getById(index,infos);

                    var cart = $('.shopping-cart');
                    var imgtodrag = $(this).find("img").eq(0);

                    if (imgtodrag) {

                        var rendement =elment.rendement;
                        var nom = elment.nom;
                        var montantdemande = elment.montantdemande;
                        var rente = elment.rente;
                        var logo = elment.logo;




                            $('.countitems span').text(parseInt($('.countitems span').text()) + 1);


                        $('.monpanier').after('<a class="list-group-item" data-index="'+index+'" href="/reservation/details/'+index+'">' +
                            '<div class="d-flex align-items-center">' +
                            '<div class="icon icon-sm text-primary"><img src="' + logo + '" class="img-fluid logoscpi"></div>' +
                            '<div class=" ml-auto"> <h5 class="font-weight-bold text-uppercase text-primary mb-0 w-100">' + nom + '</h5>'+
                            '<p class="font-size-sm text-gray-700 mb-0 w-100">Rendement : <span class="rendement">' + rendement + '</span> %</p></div>'+
                            '</div>' +
                            '<div class="row no-gutters mt-2 mb-0">' +
                            '<div class="col-12"><b>Montant demandé : <span class="montantdemande colorbrand">'+ montantdemande + '</span> €</b></div> ' +
                            '<div class="col-12"><b>Rente moyenne annuelle  : <span class="rente colorbrand">' + rente + ' </span> €</b></div> ' +
                            '</div> '+
                            '</a>');


                    }
                    else
                    {

                    }

                })
            }
            else
            {
                var chosne  = JSON.parse(localStorage.getItem('chosen'));

                chosne.forEach(function (item,index) {

                    if(item!=0)
                    {
                        var index = parseInt(item);
                        var elment = getById(index,infos);



                        var cart = $('.shopping-cart');

                        //var imgtodrag = $('.').find("img").eq(0);


                        if (elment.logo) {

                                var rendement =elment.rendement;
                                var nom = elment.nom;
                                var montantdemande = elment.montantdemande;
                                var rente = elment.rente;
                                var logo = elment.logo;

                                $('.countitems span').text(parseInt($('.countitems span').text()) + 1);

                                $('.monpanier').after('<a class="list-group-item" data-index="'+index+'" href="/reservation/details/'+index+'">' +
                                    '<div class="d-flex align-items-center">' +
                                    '<div class="icon icon-sm text-primary"><img src="' + logo + '" class="img-fluid logoscpi"></div>' +
                                    '<div class=" ml-auto"> <h5 class="font-weight-bold text-uppercase text-primary mb-0 w-100">' + nom + '</h5>'+
                                    '<p class="font-size-sm text-gray-700 mb-0 w-100">Rendement : <span class="rendement">' + rendement + '</span> %</p></div>'+
                                    '</div>' +
                                    '<div class="row no-gutters mt-2 mb-0">' +
                                    '<div class="col-12"><b>Montant demandé : <span class="montantdemande colorbrand">'+ montantdemande + '</span> €</b></div> ' +
                                    '<div class="col-12"><b>Rente moyenne annuelle  : <span class="rente colorbrand">' + rente + ' </span> €</b></div> ' +
                                    '</div> '+
                                    '</a>');


                        }
                    }

                })
            }
            setTotal();
        }
    }

    //alert($('.slick-current.slick-active').height());

    //$('.slick-track').css('height',(parseInt($('.slick-current.slick-active').height())+10)+"px");

   // $('.slick-slide:not(.slick-active.slick-current)').css('height',(parseInt($('.slick-current.slick-active').height())+10)+"px");

});



/************************************/





