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

                <?php foreach($rows as $row) : ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-file">
                            </span><?= $row['panel-title']?></a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $row['panel-form-content']; ?>
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
                     data-link="<?= ''?>"
                     data-input="#form-update"
                     >
                <span class="glyphicon glyphicon-floppy-disk"></span>
                Сохранить
                </div>
            </div>
        </div>
    </div>
</div>

<!--<div class="tab-content custom-tab-content">-->
  <!--<div>-->

  <!-- Nav tabs -->
  <ul class="nav nav-tabs custom-tab" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-home icon"></i><span class="link-lable"> Home</span></a></li>
   <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-envelope"></i><span class="link-lable"> Messages</span></a></li>
    <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-cog"></i><span class="link-lable"> Settings</span></a></li>

    <li role="presentation"><a href="#test5" aria-controls="test5" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-user"></i><span class="link-lable"> Profile</span></a></li>
  </ul>

<div class="tab-content">
  <div role="tabpanel" class="tab-pane fade in active" id="home">
      <input id="regiontext-name" class="form-control" name="RegionText[name]" value="Московская область" type="text">
 </div>

 <div role="tabpanel" class="tab-pane fade" id="messages">
     <input id="regiontext-name" class="form-control" name="RegionText[name]" value="Уфа" type="text">
 </div>
  <div role="tabpanel" class="tab-pane fade" id="settings"><h2>Tab 3</h2>
 <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></div>

  <div role="tabpanel" class="tab-pane fade" id="test5"><h2>Tab 5</h2>
 <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></div>
</div>



<!--</div>-->
<!--</div>-->