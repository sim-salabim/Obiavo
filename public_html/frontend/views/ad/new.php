
<div class="row">
    <div class="col">
        <?= __('_Contacts')?>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <?= __('Your email')?>
    </div>
    <div class="col-sm-12 col-md-6">
        <?= $user->email ?>
    </div>
    <div class="w-100"></div>
    <div class="col-sm-12 col-md-6">
        <?= __('Your name')?>
    </div>
    <div class="col-sm-12 col-md-6">
        <?= $user->getFullName() ?>
    </div>
    <div class="w-100"></div>
    <div class="col-sm-12 col-md-6">
        <?= __('Your phone number')?>
    </div>
    <div class="col-sm-12 col-md-6">
        <?= $user->phone_number ?>
    </div>
</div>
<hr>
<div class="row padding-bottom-10">
    <div class="col">
        <?= __('Select category')?>
    </div>
</div>
<form>
    <div class="row">
        <div class="form-group col-lg-2 col-sm-12 col-md-6">
            <select name="category" id="category-select" class="form-control">
                <option value="0"><?= __('Category') ?></option>
                <? foreach ($categories as $category){?>
                    <? $has_children = (count($category->children) > 0) ? 1 : 0; ?>
                    <option value="<?= $category->id ?>" has_children="<?= $has_children ?>"><?= $category->_text->name ?></option>
                <? } ?>
            </select>
        </div>
        <div class="form-group col-lg-2 col-sm-12 col-md-6">
            <select name="subcategory" id="subcategory" class="form-control">
                <option value="0"><?= __('Subcategory') ?></option>
            </select>
        </div>
        <div class="form-group col-lg-2 col-sm-12 col-md-6" id="subsubdiv" style="display: none">
            <select name="subsubcategory" id="subsubcategory" class="form-control">
                <option value="0"><?= __('Subcategory') ?></option>
            </select>
        </div>
        <div class="form-group col-lg-2 col-sm-12 col-md-6">
            <select name="action" id="action_select" class="form-control">
                <option value="0"><?= __('Action') ?></option>
            </select>
        </div>
        <div class="w-100"></div>
        <div class="form-group col-lg-2 col-sm-12 col-md-6">
            <select name="action" class="form-control" disabled>
                <option value="0"><?= __('City') ?></option>
                <? foreach($cities as $city){ ?>
                    <option value="<?= $city->id ?>" <? if(\Yii::$app->user->identity->cities_id == $city->id) {?> selected="true" <? } ?>><?= $city->_text->name ?></option>
                <? } ?>
            </select>
        </div>
        <div class="form-group col-lg-2 col-sm-12 col-md-6">
            <select name="action" class="form-control">
                <option value="0"><?= __('Time') ?></option>
                <option value="86400"><?= __('One day') ?></option>
                <option value="172800"><?= __('Two days') ?></option>
                <option value="259200"><?= __('Three days') ?></option>
                <option value="345600"><?= __('Four days') ?></option>
                <option value="432000"><?= __('Five days') ?></option>
                <option value="518400"><?= __('Six days') ?></option>
                <option value="604800"><?= __('One week') ?></option>
                <option value="1209600"><?= __('Two weeks') ?></option>
                <option value="1814400"><?= __('Three weeks') ?></option>
                <option value="2419200"><?= __('One month') ?></option>
            </select>
        </div>
        <div class="w-100"></div>
    </div>
    <hr>
    <div class="row">
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <input class="form-control" type="text" name="title" placeholder="<?= __('Title')?>">
        </div>
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <textarea class="form-control" rows="10" name="text"></textarea>
        </div>
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <input class="form-control" type="text" name="price" placeholder="<?= __('Price')?>">
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="form-group col-lg-12 col-sm-12 col-md-12">
            <input class="btn btn-success col-lg-2 col-md-6 col-sm-12" value="<?= __('Publish') ?>">
        </div>
    </div>
</form>
<script>
    $(document).ready(function(){

        $('#category-select').on('change', function(){
            $('#subsubdiv').hide();
            cleanSubSelect();
            var category_id = $('#category-select :selected').val();
            if(category_id != 0) {
                $.ajax({
                    url: '/get-sub-categories',
                    data: {category_id: category_id},
                    method: 'POST',
                    success: function (data) {
                        var subcats = JSON.parse(data);
                        var stringToAppend = '<option value="0"><?= __('Subcategory') ?></option>'
                        subcats.forEach(function (item, i) {
                            stringToAppend += '<option value="' + item.id + '" has_children="' + item.has_children + '">' + item.name + '</option>';
                        });
                        $('#subcategory option').remove();
                        $('#subcategory').html(stringToAppend);
                    }
                });
            }
        });
        $('#subcategory').on('change', function(){
            var sub_category_id = $('#subcategory :selected').val();
            var has_children = $('#subcategory :selected').attr('has_children');
            cleanSubSubSelect()
            if(sub_category_id != 0 && has_children == 1){
                $('#subsubdiv').show();
                $.ajax({
                    url: '/get-sub-categories',
                    data: {category_id: sub_category_id},
                    method: 'POST',
                    success: function(data){
                        subsubcats = JSON.parse(data);
                        var stringToAppend = '<option value="0"><?= __('Subcategory') ?></option>'
                        subsubcats.forEach(function(item){
                            stringToAppend += '<option value="'+item.id+'" has_children="'+item.has_children+'">'+item.name+'</option>';
                            $('#subsubcategory option').remove();
                            $('#subsubcategory').html(stringToAppend);
                        });
                    }
                });
            }else{
                $('#subsubdiv').hide();
                selectCategoryAction(sub_category_id)
            }
        });
        $('#subsubcategory').on('change', function(){
            var sub_sub_category_id = $('#subsubcategory :selected').val();
            if(sub_sub_category_id != 0){
                selectCategoryAction(sub_sub_category_id)
            }else{
                cleanActionSelect()
            }
        });

        function cleanActionSelect(){
            var action = '<?= __('Action'); ?>';
            $('#action_select').html("<option value='0'>"+action+"</option>");
        }
        function cleanSubSelect(){
            var action = '<?= __('Subcategory'); ?>';
            $('#subcategory').html("<option value='0'>"+action+"</option>");
            cleanSubSubSelect();
        }
        function cleanSubSubSelect(){
            var action = '<?= __('Subcategory'); ?>';
            $('#subsubcategory').html("<option value='0'>"+action+"</option>");
            cleanActionSelect();
        }

        function selectCategoryAction(categoryId) {
            cleanActionSelect();
            if(categoryId != 0) {
                $.ajax({
                    url: '/get-category-placement',
                    data: {category_id: categoryId},
                    method: 'POST',
                    success: function (data) {
                        actions = JSON.parse(data);
                        console.log(actions);
                        var stringToAppend = '<option value="0"><?= __('Action') ?></option>'
                        actions.forEach(function (item) {
                            stringToAppend += '<option value="' + item.id + '">' + item.name + '</option>';
                            $('#action_select option').remove();
                            $('#action_select').html(stringToAppend);
                        });
                    }
                });
            }
        }
    })
</script>
