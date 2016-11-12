<?php
/**
 * Форма для добавления и редактирования языков
 * @var object $language - Объект редактируемого языка (пустой объект, если добавляем)
 * @var string toUrl ссылку куда отправлять данные на обработку
 */

use yii\helpers\Url;
use yii\bootstrap\Html;

$textLang = $language->getText()->one();

$text = $textLang ? $textLang : new \common\models\LanguageText;

?>
<div id="form-update-lang">
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-file">
                            </span>Текстовые данные</a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">Код</label>
                                        <div class="col-xs-10">
                                            <?= Html::activeTextInput($language, 'code',['class' => 'form-control'])?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">Активность</label>
                                        <div class="col-xs-10">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <?= Html::activeCheckbox($language, 'active',['label' => false, 'class' => 'input-group-addon'])?>
                                                </span>
                                                <span class="form-control">Язык используется</span>
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
                            </span>Текстовые данные</a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">Название языка</label>
                                        <div class="col-xs-10">
                                            <?= Html::activeTextInput($text, 'name',['class' => 'form-control'])?>
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
                     data-link="<?= $toUrl?>"
                     data-input="#form-update-lang"
                     >
                <span class="glyphicon glyphicon-floppy-disk"></span>
                Сохранить
                </div>
            </div>
        </div>
    </div>
</div>