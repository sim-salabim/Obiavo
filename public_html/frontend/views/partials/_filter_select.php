<? $id = uniqid(); ?>
<select name="actions" id="<?= $id ?>" class="form-control">
    <option value="title%asc">Алфавиту</option>
    <option value="price%asc">Цене с меньшей</option>
    <option value="price%desc">Цене с большей</option>
    <option value="created_at%asc">Дате публикации начиная с новых</option>
    <option value="created_at%desc">Дате публикации начиная со старых</option>
</select>
<script>
    $(document).ready(function(){
        var sorting = getParam('sort', window.location.href)
        var direction = getParam('direction', window.location.href)
        if(sorting && direction){
            $('#<?= $id ?> option[value="'+sorting+'%'+direction+'"]').attr('selected', true)
        }
        $('#<?= $id ?>').on('change', function(){
            var selected = $('#<?= $id ?> :selected').val();
            var urlArr = window.location.href.split('?');
            var getParams = [];
            if(urlArr[1]){
                var params = urlArr[1].split('&');
                params.forEach(function(item){
                    var get = item.split('=');
                    getParams[get[0]] = get[1];
                });
            }
            var selectedArr = selected.split('%');
            getParams['sort'] = selectedArr[0];
            getParams['direction'] = selectedArr[1];
            var getString = '?';
            for (var key in getParams) {
                getString += key+"="+getParams[key]+'&';
            }
            getString = getString.substring(0, getString.length - 1);
            window.location.href = window.location.href.split('?')[0] + getString;
        });
        function getParam( name, url ) {
            if (!url) url = location.href;
            name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
            var regexS = "[\\?&]"+name+"=([^&#]*)";
            var regex = new RegExp( regexS );
            var results = regex.exec( url );
            return results == null ? null : results[1];
        }
    });
</script>
