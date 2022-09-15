jQuery(function($) {
    var ticker = document.querySelector('.ticker')
    , list = document.querySelector('.ticker__list')
    , clone = list.cloneNode(true)  
    ticker.append(clone);

    function setIklanFloat(){
        let hhead = $('#page > header').height();
        if($('#wpadminbar').length) {
            hhead = hhead+$('#wpadminbar').height();
        }
        $('.float-iklan').css('top', hhead);
        let cwi = $('.float-iklan').data('container');
        cwi = (($( window ).width()-cwi)/2)-20;
        $('.float-iklan').css('max-width', cwi);
        if(cwi < 80) {
            $('.float-iklan').addClass('d-none');
        } else {
            $('.float-iklan').removeClass('d-none');
        }
    }
    setIklanFloat();
    $(window).resize(function() {
        setIklanFloat();
    });

});