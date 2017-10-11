<?
/**
 *  $id,
 *  $placements,
 *  $current_action, текуший placement
 */
?>
<div class="form-group col-lg-2 col-sm-12 col-md-6">
    <select name="actions" id="<?= $id ?>" class="form-control">
        <? foreach($placements as $placement){ ?>
            <option value="<?= $placement->_text->url ?>" <? if ($current_action == $placement->_text->url){?>selected<? } ?>><?= $placement->_text->name ?></option>
        <? } ?>
    </select>
</div>
<?
$action = ($current_action) ? $current_action : '';
?>
<script>
$(document).ready(function(){
    $('#<?= $id ?>').change(function(){
        var selected = $("#<?= $id ?> :selected").val();
        var action = '<?= $action ?>';
        if(action == ''){
            window.location = window.location.href+"/"+selected;
        }else{
            window.location = window.location.href.replace('<?= $action ?>', selected);
        }
    })
});
</script>