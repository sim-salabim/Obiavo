Core.onFullLoad(function() {
    
    $('.selectpicker').selectpicker();
    
    $('select.selectpicker.redirect').on('changed.bs.select', function (e) {
        var url = $(this).val();
        
        Navigation.changeLocation(url);
    });
})