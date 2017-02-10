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
$panelTitle = function($title){
    $currentAction = Yii::$app->controller->action->id;
    $savelangAction = 'save-lang';
    $p = [];

    if(stristr($title, '{language}')){
        if($currentAction === $savelangAction){
            $lang = common\models\Language::findOne(Yii::$app->request->get('languages_id'));

            $p["{language}"] = $lang->code;

            return strtr($title, $p);
        }
    }

    return $title;
};

?>
<div id="form-update">
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group" id="accordion">

                <?php foreach($rows as $row) : ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-file">
                            </span><?= $panelTitle($row['panel-title'])?></a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $row['panel-content']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endforeach; ?>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="btn btn-success btn-sm senddata"
                     data-link="<?= $saveUrl?>"
                     data-input="#form-update"
                     >
                <span class="glyphicon glyphicon-floppy-disk"></span>
                Сохранить
                </div>
            </div>
        </div>
    </div>
</div>

<!--</div>-->
<!--</div>-->