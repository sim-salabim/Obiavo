<? $id = uniqid(); ?>
<select name="actions" id="<?= $id ?>" class="form-control">
    <option value="name_desc">Алфавиту </option>
    <option value="price_desc">Цене с меньшей</option>
    <option value="price_asc">Цене с большей</option>
    <option value="published_desc">Дате публикации начиная с новых</option>
    <option value="published_asc">Дате публикации начиная со старых</option>
</select>
<script>
    $(document).ready(function(){
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
            var selectedArr = selected.split('_');
            getParams['sort'] = selectedArr[0];
            getParams['direction'] = selectedArr[1];console.log(getParams);
            var getString = '?';
            for (var key in getParams) {
                getString += key+"="+getParams[key]+'&';
            }
            getString = getString.substring(0, getString.length - 1)
            window.location.href = window.location.href.split('?')[0] + getString
        });

    });
</script>
