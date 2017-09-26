<?
$message = (isset($message) AND $message) ? $message : __('Click or drop the file here.');
$container_id = (isset($container_id) AND $container_id) ? $container_id : 'file-uploader';
?>
<div id="hidden-files-inputs" style="display: none">
    <input type="hidden" class="files_ids">
</div>
<?=
\kato\DropZone::widget([
    'options' => [
        'url' => '/files-upload',
        'method' => 'POST',
        'addRemoveLinks' => true,
        'paramName' => 'file',
        'dictDefaultMessage' => __('Click or drop the file here.')
    ],
    'clientEvents' => [
        'complete' => "function(file, response){ 
            
        }",
        'removedfile' => "function(file){console.log();
            var data = JSON.parse(file.xhr.response)
            $('input[value='+data.id+']').remove();
            $.ajax({
                url: '/remove-file',
                data: {id: data.id},
                method: 'POST'
            });
        }",
        'success' => "function(file, response){
            var data = JSON.parse(response)
            $('.files_ids').first().clone().appendTo('#hidden-files-inputs');
            $('.files_ids').last().attr('name', 'files[]')
            $('.files_ids').last().val(data.id)
        }"
    ],
]); ?>
