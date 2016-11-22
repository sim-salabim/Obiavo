<div class="col-xs-6 col-md-3">
    <div class="clear"></div>
    <div class="main-sidebar">
        <button class="btn-change">Подать объявление</button>

        <div class="hr-black"></div>

        <div class="sidebar-menu">
            <ul class="list-unstyled">
                <li><a href="<?= yii\helpers\Url::toRoute('login')?>">Вход</a></li>
                <li><a href="<?= yii\helpers\Url::toRoute('registration')?>">Регистрация</a></li>
            </ul>
        </div>
    </div>
</div>

<style>
.clear{
    clear: both;
}

.main-sidebar{
    background-color: rgba(220,220,220,0.5);
}

.sidebar-menu {
    display: inline-block;
    margin: 20px;
}

.btn-change{
    height: 50px;
    width: 90%;
    background: lightseagreen;
    margin: 20px;
    box-shadow: 0 0 1px #ccc;
    -webkit-transition: all 0.5s ease-in-out;
    border: 0px;
    color: #fff;
}
.btn-change:hover{
    -webkit-transform: scale(1.1);
    background: #31708f;
}

.hr-black{
    display: inline-block;
    width: 100%;
    margin-bottom: 20px;
    border-bottom-width: 2px; /* Толщина линии внизу */
    border-bottom-style: solid; /* Стиль линии внизу */
    border-bottom-color: rgba(110,100,100,0.9); /* Цвет линии внизу */
}
</style>