<?
/**
 * $message - текст на аплоадере
 * $container_id - id html контейнера
 *
 */

$message = (isset($message) AND $message) ? $message : __('Click or drop the file here.');
$container_id = (isset($container_id) AND $container_id) ? $container_id : 'file-uploader';
?>
<?
$js_files = '';
if(!empty($files)){?>
    <? foreach($files as $key => $file){?>
        <input type="hidden" class="files_ids" name="files[]" value="<?= $file ?>">
        <?
        $existing_file = \common\models\Files::findOne(['id' => $file]);
        $js_files .= 'var existingFile'.$key.' = {name:"'.$existing_file->name.".".$existing_file->ext->ext.'", hash:"'.$existing_file->hash.'",type: "'.$existing_file->ext->mime.'", files_exts_id: "'.$existing_file->ext->id.'", users_id: "'.$existing_file->user->id.'", id: "'.$existing_file->id.'"};
            this.addFile.call(this, existingFile'.$key.');
            this.options.thumbnail.call(this, existingFile'.$key.', "'.$existing_file->getImage().'");';
    } ?>
<? } ?>
<script>
    var container = '<?= $container_id ?>';
    var dropzone = $("#"+container).dropzone({
        url: "/files-upload/",
        method: "POST",
        dictDefaultMessage: '<?= __('Click or drop the file here.') ?>',
        addRemoveLinks: true,
        paramName: 'file',
        dictRemoveFile: '<?= __('Delete') ?>',
        dictCancelUpload: '<?= __('Cancel download')?>',
        dictCancelUploadConfirmation: '<?= __('Are you sure?')?>',
        dictFileTooBig: '<?=__('File is too big')?>',
        dictMaxFilesExceeded: '<?= __('You cannot upload anymore files')?>',
        init: function(){
            this.on("removedfile", function(file) {
                var file_id = null;
                if(file.xhr.response !== ""){
                    file_id = file.xhr.response.id;
                }else{
                    file_id = file.id;
                }

                $('input[value='+file_id+']').remove();
                $.ajax({
                    url: '/remove-file/',
                    data: {id: file_id},
                    method: 'POST'
                });

            });
            this.on("success", function(file, response) {
                if(response){
                    var data = JSON.parse(response);
                    $('.files_ids').first().clone().appendTo('#hidden-files-inputs');
                    $('.files_ids').last().attr('name', 'files[]');
                    $('.files_ids').last().val(data.id);
                }
            });
            <?= $js_files; ?>
        }
    });

</script>
<div id="hidden-files-inputs" style="display: none">
    <input type="hidden" class="files_ids">
</div>
