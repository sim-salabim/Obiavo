<script>
$(document).ready(function() {
    $("#tree-category-select").on("click", function (event) {
        event.preventDefault();
        $("#tree-container").toggle();
    });
});

$("#tree-container").dynatree({
        checkbox: true,
        children: [
        <? foreach($categories as $cat){?>
    {title: "<?= $cat->techname ?>", isFolder: true, isLazy: true, key: "<?= $cat->id ?>"},
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
title: "Lazy loading sample",
    onSelect: function(flag, node){
    if(flag){
        var element = $("#checked-"+node.data.key);
        if(element.length == 0) {
            var checkedAmount = $("span[id^=checked-]").length;
            if(categoriesLimit <= checkedAmount){
                alert("<?= __('Categories limit:') ?> "+categoriesLimit );
                node.select(false);
            }else {
                $('#checkbox-select').append('<span id="checked-' + node.data.key + '" class="js_tree_el"><input type="hidden" name="categories[]" value="' + node.data.key + '" class="js_tree_el">' + node.data.title + ' <i style="cursor: pointer" class="fa fa-times js_tree_el" aria-hidden="true" id="checked-close-' + node.data.key + '" onclick="closeCheckedAndTree(' + node.data.key + ')"></i></span><br class="js_tree_el">');
                removeParents(node);
                uncheckChildren(node);
            }
        }
    }else{
        $("#checked-"+node.data.key).next().remove();
        $("#checked-"+node.data.key).remove();
    }
},
debugLevel: 0
});

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
    if(node){
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