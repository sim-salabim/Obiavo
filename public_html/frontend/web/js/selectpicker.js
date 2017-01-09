Core.onFullLoad(function() {
    
    $('.selectpicker').selectpicker();
    
    $('.selectpicker').on('changed.bs.select', function (e) {
        
        
        console.log(e)
        console.log($(this).html())
    });
})