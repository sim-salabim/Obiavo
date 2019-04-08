<?
/**
 * categories - все категории не имеющие родителей
 * categories_limit - int максимальное количество категорий для выбора
 * if_user_logged - 1 либо 0
 * selected_categories - массив с выбранными категориями
 */
?>
<script>
var categoriesLimit = <?= $categories_limit ?>;
var selected_cats = [];
var selected_cat_ids = [];
<?
    $selected_cat_ids = [];
    foreach($selected_categories as $key => $selected_cat){
        $selected_cat_ids[] = $selected_cat['id'];
        ?>
        selected_cats[<?= $key ?>] = [];
        selected_cats[<?= $key ?>]['techname'] = '<?= $selected_cat['techname'] ?>';
        selected_cats[<?= $key ?>]['id'] = '<?= $selected_cat['id'] ?>';
        selected_cat_ids[<?= $key ?>] = '<?= $selected_cat['id'] ?>';
<? } ?>
var ifUserLogged = <?= $if_user_logged ?>;
$(document).ready(function() {
    if(ifUserLogged) {
        $("#tree-container").show();
    }
    $("#tree-category-select").on("click", function (event) {
        event.preventDefault();
        $("#tree-container").toggle();
    });
    $(window).on("click", function (event) {
        if(event.target.id.indexOf("a-cat-") != 0) {
            if (event.target.id.indexOf("checkbox-") == -1 ) {
//                                $('#tree-container').hide();
            }
        }
    });
});

$("#tree-container").dynatree({
        checkbox: true,
        children: [
        <? foreach($categories as $cat){?>
    {title: "<?= $cat->techname ?>", isFolder: true, isLazy: true, key: "<?= $cat->id ?>", select: <? if(array_search($cat->id, $selected_cat_ids) !== false){?>true<?}else{?>false<?}?>},
<? } ?>
],
onLazyRead: function(dtnode){
    dtnode.appendAjax(
        {url: "<?= yii\helpers\Url::toRoute('/categories/get-root-categories/') ?>",
            dataType: "JSON",
            data: {
                key: dtnode.data.key,
                sleep: 1,
                mode: "branch"
            }
        });
},
onCreate: function(node, nodeSpan){
    var element = $("#checked-"+node.data.key);
    if(element.length >= 1){
        node.select(true);
    }
},
title: "Подгрузка...",
    onSelect: function(flag, node){
    if(flag){
        var element = $("#checked-"+node.data.key);
        if(element.length == 0) {
            var checkedAmount = $("span[id^=checked-]").length;
            if(checkedAmount == 0){
                $("#category-append").text("");
            }
            if(categoriesLimit <= checkedAmount){
                alert("<?= __('Categories limit:') ?> "+categoriesLimit );
                node.select(false);
            }else {
                $('#category-append').append('<span id="checked-' + node.data.key + '" class="js_tree_el"><input type="hidden" name="categories[]" value="' + node.data.key + '" class="js_tree_el">' + node.data.title + ' <i style="cursor: pointer" class="fa fa-times js_tree_el" aria-hidden="true" id="checked-close-' + node.data.key + '" onclick="closeCheckedAndTree(' + node.data.key + ')"></i></span><br class="js_tree_el">');
                removeParents(node);
                uncheckChildren(node);
            }
        }
    }else{
        $("#checked-"+node.data.key).next().remove();
        $("#checked-"+node.data.key).remove();
        appendDefaultText();
    }
},
debugLevel: 0
});
function appendDefaultText(){
    var checkedAmount = $("span[id^=checked-]").length;
    console.log(checkedAmount);
    if(checkedAmount == 0){
        $("#category-append").append("<?= __('No categories picked'); ?>");
    }
}
function uncheckChildren(node){
    if(node.childList){
        node.childList.forEach(function(item, i, arr){
            if(item.bSelected){
                closeCheckedAndTree(item.data.key);
            }
            if(item.childList){
                uncheckChildren(item)
            }
        });
    }
}
function removeParents(node){
    if(node.parent && node.parent.data.title){
        closeCheckedAndTree(node.parent.data.key);
        if(node.parent.parent && node.parent.parent.data.title){
            removeParents(node.parent)
        }
    }
}
function unselectNode(id){
    var node = $("#tree-container").dynatree('getTree').getNodeByKey(""+id+"");
    if(node) {
        node.select(false);
    }
}
function closeCheckedAndTree(id){
    $("#checked-"+id).next().remove();
    $("#checked-"+id).remove();
    unselectNode(id);
}

function selectNode(id){
    var node = $("#tree-container").dynatree('getTree').getNodeByKey(""+id+"");
    if(node){
        node.select(true);
    }
}

</script>