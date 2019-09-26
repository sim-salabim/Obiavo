<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?

$dir = __DIR__;
$image_url = ($_SERVER["SERVER_NAME"] == "admin.obiavo.loc") ? "http://obiavo.loc" : "https://obiavo.ru";
if($_SERVER["SERVER_NAME"] == "adm.obiavo.site"){
    $image_url = "http://obiavo.site";
}
str_replace("backend/views/moderation", 'frontend/views/moderation', $dir);
require_once $dir.'/dynatree_script.php';
?>
<?php
use yii\helpers\Url;
use backend\widgets\TableList;
use yii\helpers\Html;
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="lang-table">


    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Модерация</h3>

            <div class="box-tools">

            </div>
        </div>
        <? include_once 'navigation.php'?>
        <div class="tab-content">
            <!--       модерация         -->
            <div role="tabpanel" class="tab-pane active" id="moderation">
                <?= TableList::widget([
                    'title' => 'Модерация',
                    'data'  => $ads,
                    'columns' => [
                        [
                            'attribute'    => 'id',
                            'label'        => 'ID',
                        ]
                        ,[
                            'label'        => '',
                            'content' => function($model) use($image_url){
                                $avatar = $model->avatar(true);
                                $html = "<img class='img-fluid' src='$image_url$avatar' alt='$model->title'>";
                                return $html;
                            }
                        ],
                        [
                            'label'        => 'Название',
                            'content'      => function($model){
                                $html = Html::a($model->title, "https://".$model->city->region->country->domain."/".$model->city->domain."/".$model->url."/");
                                $html .= "<br>$model->price ".$model->city->region->country->currency->_text->name_short."<br>";
                                $html .= cutText($model->text, 100)."<br>";
                                $html .= "<span><small class='ads-pre-text'> ".$model->placement->_text->name.", ".$model->category->_text->name.", ".$model->city->_text->name."</small></span><br/>";
                                $html .= $model->getHumanDate()."<br>";
                                $html .= "Активно до " . $model->getHumanDate(\common\models\Ads::DATE_TYPE_EXPIRATION)."<br>";

                                return $html;
                            },
                        ],
                        [
                            'label' => "Статус",
                            'content' => function($model){
                                $html = backend\helpers\ActiveLabel::status($model->active, [
                                    'active' => 'активно',
                                    'inactive' => 'не активно'
                                ]);
                                return $html;
                            }
                        ],
                        [
                            'label'        => 'Управление',
                            'format'       => TableList::TYPE_OPT_BUTTON,
                            'buttons'      => [
                                'unpublish',
                                'update',
                                'delete',
                            ]
                        ],
                    ]
                ]);?>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <?
                        $index = 1;
                        while($index <= $pages_amount){?>
                            <li class="page-item <? if($current_page == $index){?>disabled<? } ?>"><a class="page-link" href="<?= $link."page=".$index ?>"><?= $index ?></a></li>
                            <?
                            $index++;
                        } ?>
                    </ul>
                </nav>
            </div>
            <!--      прошедшие модерацию      -->
            <div role="tabpanel" class="tab-pane" id="moderated">
                прошедшие
            </div>
            <!--      все      -->
            <div role="tabpanel" class="tab-pane" id="all">
                все
            </div>
        </div>

    </div>