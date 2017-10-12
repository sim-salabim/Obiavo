<? $id = uniqid(); ?>
<div class="col-12 align-items-center">
    <button id="<?= $id ?>" class="btn btn-success my-2 col-12"><?= __('Load more'); ?></button>
</div>

<script>
    $(document).ready(function(){
        if(getParam('loaded', window.location.href)){
            $(window).scrollTop($(document).height());
        }
        $('#<?= $id ?>').bind('click', function(){
            var urlArr = window.location.href.split('?');
            var getParams = [];
            if(urlArr[1]){
                var params = urlArr[1].split('&');
                params.forEach(function(item){
                    var get = item.split('=');
                    getParams[get[0]] = get[1];
                });
            }
            getParams['loaded'] = <?= $loaded ?>;
            var getString = '?';
            for (var key in getParams) {
                getString += key+"="+getParams[key]+'&';
            }
            getString = getString.substring(0, getString.length - 1);
            window.location.href = window.location.href.split('?')[0] + getString;
        })
    });
    function getParam( name, url ) {
        if (!url) url = location.href;
        name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
        var regexS = "[\\?&]"+name+"=([^&#]*)";
        var regex = new RegExp( regexS );
        var results = regex.exec( url );
        return results == null ? null : results[1];
    }
</script>