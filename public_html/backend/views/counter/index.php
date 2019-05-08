<?php
use backend\widgets\TableList;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Подсчет обьявлений';
?>

<div id="loadcontent-container" style="display: none"></div>

<div id="categories-table">


    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Подсчет обьявлений</h3>

            <div class="box-tools">
                <div class="input-group">
                </div>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Управление</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Категории</td>
                        <td>
                            <select id="select-category">
                                <? foreach($countries as $country){?>
                                    <option value="<?= $country->id ?>"><?= $country->_text->name ?></option>
                                <? }?>
                            </select>
                            <button id="btn-category" onclick="count('category')" class="btn btn-primary btn-sm">
                                Посчитать
                            </button>
                            <div id="load-category" style="display: none">
                                <span>Загрузка...</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Города</td>
                        <td>
                            <button id="btn-city" onclick="count('city')" class="btn btn-primary btn-sm">
                                Посчитать
                            </button>
                            <div id="load-city" style="display: none">
                                <span>Загрузка...</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Категории + Города</td>
                        <td>
                            <button id="btn-category-city" onclick="count('category-city')" class="btn btn-primary btn-sm">
                                Посчитать
                            </button>
                            <div id="load-category-city" style="display: none">
                                <span>Загрузка...</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

    </div>

</div>
<script>
    $(document).ready(function(){

    });

    function count(title){
        $('#btn-'+title).hide();
        $('#load-'+title).show();
        var url = '';
        var additioanalUrl = "";
        switch (title){
            case 'category':
                var countryId = $('#select-category :selected').val();
                additioanalUrl = "&country="+countryId;
                url = '/counter/category';
                break;
            case 'city':
                url = '/counter/city';
                break;
            case 'category-city':
                url = 'counter/city-category';
                break;
            default:
                url = '/counter/category';
        }
        $.ajax({
            url: url+'?token=<?= Yii::$app->params['cron_token'] ?>'+additioanalUrl,
            method: 'POST',
            success: function(data){
                if(data.status === 200){
                    $('#btn-'+title).show();
                    $('#load-'+title).hide();
                }
            },
            error: function(){
                $('#load-'+title+" span").text('Произошла ошибка');
            }
        });
    }
</script>