<?php
/**
 * Форма для добавления и редактирования пунктов категорий
 * @var object $category - Объект редактируемой категории (пустой объект, если добавляем)
 * @var object $categoryGenerate - Объект из связанной модели сгерерированных категорий
 * @var string toUrl ссылку куда отправлять данные на обработку
 */

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
                            </span>Текстовые данные</a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">Тех. название</label>
                                        <div class="col-xs-10">
                                            <?= Html::activeTextInput($category, 'techname',['class' => 'form-control'])?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">Активность</label>
                                        <div class="col-xs-10">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <?= Html::activeCheckbox($category, 'active',['label' => false, 'class' => 'input-group-addon'])?>
                                                </span>
                                                <span class="form-control">Эта категория используется на сайте</span>
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
                            </span>Сео данные</a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">Название</label>
                                        <div class="col-xs-10">
                                            <?= Html::activeTextInput($categoryGenerate, 'seo_name',['class' => 'form-control'])?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">URL</label>
                                        <div class="col-xs-10">
                                            <?= Html::activeTextInput($categoryGenerate, 'url',['class' => 'form-control'])?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">SEO заголовок</label>
                                        <div class="col-xs-10">
                                            <?= Html::activeTextInput($categoryGenerate, 'seo_title',['class' => 'form-control'])?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">SEO описание</label>
                                        <div class="col-xs-10">
                                            <?= Html::activeTextInput($categoryGenerate, 'seo_desc',['class' => 'form-control'])?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">SEO ключевые слова</label>
                                        <div class="col-xs-10">
                                            <?= Html::activeTextInput($categoryGenerate, 'seo_keywords',['class' => 'form-control'])?>
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
                     data-link="<?= $toUrl;//Url::toRoute(['save-category','id' => $category->id])?>"
                     data-input="#form-update-category"
                     >
                <span class="glyphicon glyphicon-floppy-disk"></span>
                Сохранить
                </div>
            </div>
        </div>
    </div>
</div>