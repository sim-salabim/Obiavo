<?
/**
 * $message - текст на аплоадере
 * $container_id - id html контейнера
 *
 */

$message = (isset($message) AND $message) ? $message : __('Click or drop the file here.');
$container_id = (isset($container_id) AND $container_id) ? $container_id : 'file-uploader';
?>
<div id="hidden-files-inputs" style="display: none">
    <input type="hidden" class="files_ids">
    <?
        $js_files = '';
        if(!empty($files)){?>
        <? foreach($files as $key => $file){?>
            <input type="hidden" class="files_ids" name="files[]" value="<?= $file ?>">
        <?
            $existing_file = \common\models\Files::findOne(['id' => $file]);
            $js_files .= 'var existingFile'.$key.' = {name:"'.$existing_file->name.".".$existing_file->ext->ext.'", size: 20564,type: "'.$existing_file->ext->mime.'"};
            this.addFile.call(this, existingFile'.$key.');
            this.options.thumbnail.call(this, existingFile'.$key.', "'.$existing_file->getImage().'");';
        } ?>
    <? } ?>
</div>
<?=
\kato\DropZone::widget([

    'options' => [
        'url' => '/files-upload/',
        'method' => 'POST',
        'addRemoveLinks' => true,
        'paramName' => 'file',
        'dictDefaultMessage' => __('Click or drop the file here.'),
        'dictRemoveFile' => __('Delete'),
        'dictCancelUpload' => __('Cancel download'),
        'dictCancelUploadConfirmation' => __('Are you sure?'),
        'dictFileTooBig' => __('File is too big'),
        'dictMaxFilesExceeded' => __('You cannot upload anymore files'),
        'init' => new \yii\web\JsExpression("function(){
           ".$js_files."
        }")

    ],
    'clientEvents' => [
        'removedfile' => "function(file){
            console.log(file.xhr.response);
            if(file.xhr.response){
                var data = JSON.parse(file.xhr.response)
                $('input[value='+data.id+']').remove();
                $.ajax({
                    url: '/remove-file/',
                    data: {id: data.id},
                    method: 'POST'
                });
            }
        }",
        'success' => "function(file, response){
            if(response){
                var data = JSON.parse(response);
                $('.files_ids').first().clone().appendTo('#hidden-files-inputs');
                $('.files_ids').last().attr('name', 'files[]')
                $('.files_ids').last().val(data.id)
            }
        }"
    ],
]);
?>
