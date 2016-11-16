<?php
use yii\helpers\Url;
use yii\bootstrap\Html;

?>
<div id="form-update-category">
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-file">
                            </span>Основныe данные</a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">Домен</label>
                                        <div class="col-xs-10">
                                            <?= Html::activeTextInput($country, 'domain',['class' => 'form-control'])?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">Активность</label>
                                        <div class="col-xs-10">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <?= Html::activeCheckbox($country, 'active',['label' => false, 'class' => 'input-group-addon'])?>
                                                </span>
                                                <span class="form-control">Эта страна используется на сайте</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><span class="glyphicon glyphicon-th-list">
                            </span>Тексты</a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">Название</label>
                                        <div class="col-xs-10">
                                            <?= Html::activeTextInput($countryText, 'name',['class' => 'form-control'])?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">Название в родительном падеже</label>
                                        <div class="col-xs-10">
                                            <?= Html::activeTextInput($countryText, 'name_rp',['class' => 'form-control'])?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">Название в предложном падеже</label>
                                        <div class="col-xs-10">
                                            <?= Html::activeTextInput($countryText, 'name_pp',['class' => 'form-control'])?>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="btn btn-success btn-sm senddata"
                     data-link="<?= $toUrl;?>"
                     data-input="#form-update-category"
                     >
                <span class="glyphicon glyphicon-floppy-disk"></span>
                Сохранить
                </div>
            </div>
        </div>
    </div>
</div>