<?php
use yii\helpers\Url;
?>
<div class="form-update-category">
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
                                    <div class="form-group">
                                        <input type="text" name="email" id="email" class="form-control" placeholder="Тех. название" required />
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                              <input type="checkbox">
                                            </span>
                                            <span class="form-control">Эта категория используется на сайте</span>
                                        </div>
                                    </div>
                                    <!--<div class="form-group">
                                        <textarea class="form-control" placeholder="Content" rows="5" required></textarea>
                                    </div>-->
                                </div>
                            </div>
<!--                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category">
                                            Category</label>
                                        <select class="form-control" id="category">
                                            <option>Articles</option>
                                            <option>Tutorials</option>
                                            <option>Freebies</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tags">
                                            Tags</label>
                                        <input type="text" class="form-control" id="tags" placeholder="Tags" />
                                    </div>
                                </div>
                            </div>-->
<!--                            <div class="row">
                                <div class="col-md-6">
                                    <div class="well well-sm">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?//= \Yii::$app->request->serverName?>/</span>
                                            <input type="text" class="form-control" placeholder="Custom url" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="well well-sm well-primary">
                                        <form class="form form-inline " role="form">
                                        <div class="form-group">
                                            <input type="text" class="form-control" value="" placeholder="Date" required />
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control">
                                                <option>Draft</option>
                                                <option>Published</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <span class="glyphicon glyphicon-floppy-disk"></span>Save</button>
                                            <button type="button" class="btn btn-default btn-sm">
                                                <span class="glyphicon glyphicon-eye-open"></span>Preview</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>-->
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
                    <div id="collapseTwo" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">Название</label>
                                        <div class="col-xs-10">
                                            <input type="text" class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">URL</label>
                                        <div class="col-xs-10">
                                            <input type="text" class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">SEO заголовок</label>
                                        <div class="col-xs-10">
                                            <input type="text" class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">SEO описание</label>
                                        <div class="col-xs-10">
                                            <input type="text" class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xs-2 col-form-label">SEO ключевые слова</label>
                                        <div class="col-xs-10">
                                            <input type="text" class="form-control" required />
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
                     data-link="<?= Url::toRoute(['save-category'])?>"
                     >
                <span class="glyphicon glyphicon-floppy-disk"></span>
                Сохранить
                </div>
            </div>
        </div>
    </div>
</div>