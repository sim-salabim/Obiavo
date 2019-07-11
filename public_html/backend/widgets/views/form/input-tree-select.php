<?
/**
 *  $attribute[
 *      - label,
 *      - no_cats_message,
 *      - selected_categories[
 *          [id=> , techname=> ], [id=> ,techname=> ], ...
 *        ],
 *      - model (Ads)
 *   ]
 */
?>
<?
$selected_categories = [];
if($attribute['model']){
    $selected_categories[0]['techname'] = $attribute['model']->category->techname;
    $selected_categories[0]['id'] = $attribute['model']->category->id;
    if(!empty($attribute['model']->categories)) {
        foreach ($attribute['model']->categories as $key => $c) {
            $key += 1;
            $selected_categories[$key]['techname'] = $c->techname;
            $selected_categories[$key]['id'] = $c->id;
        }
    }
}
?>

<div class="form-group row validation-errors">
    <label class="col-xs-2 col-form-label"><?= $attribute['label'] ?></label>
    <div class="form-group col-lg-10 col-sm-10 col-md-10" id="checkbox-select">
        <button id="tree-category-select" class="form-control text-align-left cursor-pointer margin-top-15 <?php if(Yii::$app->session->getFlash('categories_error')){?> is-invalid <? } ?>" >
            <?= $attribute['label'] ?>
        </button>
        <div class="invalid-feedback" id="categories_error"></div>
        <div class="form-control" id="tree-container" style="min-height: 550px">
        </div>
    </div>
    <? if(!empty($attribute['selected_categories'])){ ?>
        <div class="col-12 sub-title" id="picked-cats-div" style="padding-left: 10px">Выбранные категории</div>
    <? } ?>
    <div id="category-append" class="col-12" style="padding-left: 10px">
        <p id="no-cats-picked">
            <? if(empty($selected_categories)){ ?>
                <?= $attribute['no_cats_message'] ?>
            <? } ?>
        </p>
        <? if(!empty($selected_categories)){
            foreach($selected_categories as $selected_cat){
                ?>
                <span id="checked-<?= $selected_cat['id'] ?>" class="js_tree_el"><input type="hidden" name="categories[]" value="<?= $selected_cat['id'] ?>" class="js_tree_el"><?= $selected_cat['techname'] ?> <i style="cursor: pointer" class="fa fa-times js_tree_el" aria-hidden="true" id="checked-close-<?= $selected_cat['id'] ?>" onclick="closeCheckedAndTree(<?= $selected_cat['id'] ?>)"></i></span>
                <br class="js_tree_el">
            <?  }
        } ?>
    </div>
    <? $categories_limit = \common\models\Settings::find()->one()->categories_limit ?>
    <?= $this->render('/scripts/tree-select', ['categories' => $attribute['categories'], 'categories_limit' => 5, "if_user_logged" => true,
        "selected_categories" => $selected_categories]); ?>
</div>