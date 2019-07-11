<?
/**
 * $message - текст на аплоадере
 * $container_id - id html контейнера
 *
 */

$message = (isset($message) AND $message) ? $message : "Кликните или перетащите сюда файл";
$container_id = (isset($container_id) AND $container_id) ? $container_id : 'file-uploader';
?>
<?
// если SERVER_NAME == admin.obiavo.loc (именно так у меня назван локальный хост) то ищем картинку локально (http://obiavo.loc/.....)
// если SERVER_NAME != admin.obiavo.loc - значит мы на сервере и значит ищем там (https://obiavo.ru/......)
$domain = ($_SERVER["SERVER_NAME"] == "admin.obiavo.loc") ? "http://obiavo.loc" : "https://obiavo.ru";
$js_files = '';
if(!empty($files)){?>
    <? foreach($files as $key => $file){?>
        <input type="hidden" class="files_ids" name="files[]" value="<?= $file ?>">
        <?
        $existing_file = \common\models\Files::findOne(['id' => $file]);
        $file_user_id = $existing_file->users_id ? $existing_file->user->id : null;
        $js_files .= 'var existingFile'.$key.' = {name:"'.$existing_file->name.".".$existing_file->ext->ext.'", hash:"'.$existing_file->hash.'",type: "'.$existing_file->ext->mime.'", files_exts_id: "'.$existing_file->ext->id.'", users_id: "'.$file_user_id.'", id: "'.$existing_file->id.'"};
            this.addFile.call(this, existingFile'.$key.');
            this.options.thumbnail.call(this, existingFile'.$key.', "'.$domain.$existing_file->getImage().'");';
    } ?>
<? } ?>
<script>
    var container = '<?= $container_id ?>';
    var dropzone = $("#"+container).dropzone({
        url: "/files/upload/",
        method: "POST",
        dictDefaultMessage: 'Кликните или перетащите сюда файл',
        addRemoveLinks: true,
        paramName: 'file',
        dictRemoveFile: 'Удалить',
        dictCancelUpload: 'Отменить загрузку',
        dictCancelUploadConfirmation: 'Вы уверены?',
        dictFileTooBig: 'Слишком большой файл',
        dictMaxFilesExceeded: 'Вы не можете загружать больше файлов',
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
                    url: '/files/remove/',
                    data: {id: file_id},
                    method: 'POST'
                });

            });
            this.on("success", function(file, response) {
                console.log(response);
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
