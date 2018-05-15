<div class="row">
    <?php if(Yii::$app->session->getFlash('success')){?>
        <div class="alert alert-success col-lg-12" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php } ?>
    <form method="POST">
        <div class="form-group col-lg-12">
            <select class="form-control" name="sn_group_id">
                <? foreach($groups as $group){?>
                    <option value="<?= $group->id ?>" <? if($group_selected AND $group->id == $group_selected->social_networks_groups_id){?> selected<? } ?>><?= $group->name ?></option>
                <? } ?>
            </select>
        </div>
        <div class="form-group col-lg-12">
            <button class="btn btn-success">
                Сохранить
            </button>
        </div>
    </form>
    <? if($group_selected){?>
        <div class="col-lg-12">
        <a href="/test-post/<?= $ad->id ?>/<?= $group_selected->id ?>/" class="btn btn-primary btn-sm">
            Тест автопостинга
        </a>
        </div>
    <? } ?>
</div>