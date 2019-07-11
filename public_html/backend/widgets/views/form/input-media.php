<?
/**
 *
 */
$files = [];
if($attribute['model']){
    foreach($attribute['model']->files as $file){
        $files[] = $file->id;
    }
}
?>

<div class="form-group row validation-errors">
    <label class="col-xs-2 col-form-label"><?= $attribute['label'] ?></label>
    <div class="form-group col-lg-10 col-sm-10 col-md-10 " id="dropzone-container" >
        <div class="dropzone" id="file-uploader"></div>
    </div>
    <?=  $this->render('/partials/_file_uploader.php', ['container_id' => 'file-uploader', 'files' => $files]) ?>
</div>