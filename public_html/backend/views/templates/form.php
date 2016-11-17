<?php

//$form = [
//    [
//        'model' => 'object'
//        'panel-title' => '',
//        'columns' => [
//            // attribute:typeField:label
//            'id:text:ID',
//            'name:text:Название',
//            'active:checkbox:Активность',
//        ]
//    ],
//    'saveUrl' => ''
//];
?>
<div id="form-update">
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group" id="accordion">

                <?php foreach($form as $panel) : ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-file">
                            </span><?= $panel['panel-title']?></a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php foreach($panel['columns'] as $column) {

                                            echo \backend\helpers\FormHtmlTag::row($column, $panel['model']);


                                     } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="btn btn-success btn-sm senddata"
                     data-link="<?= $saveUrl;?>"
                     data-input="#form-update"
                     >
                <span class="glyphicon glyphicon-floppy-disk"></span>
                Сохранить
                </div>
            </div>
        </div>
    </div>
</div>