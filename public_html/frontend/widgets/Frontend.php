<?php
namespace frontend\widgets;

use yii;
use yii\base\Widget;
use frontend\helpers\TextHelper;

/**
 * Отдает собранные gulp'ом скрипты( пока только jsx), адаптированные для вывода в шапку сайта
 */
class Frontend extends Widget {   
    
    public $scriptTemplate = "<script src=\"/dist/js/{pathToScript}\"></script>";
    
    public $linkTemplate = "<link href=\"{pathToStyle}\" rel=\"stylesheet\" />";
    
    // сюда будут собираться все крипты и стили
    protected $headerHtml = '';

    public function init(){
        parent::init();

    }

    public function run(){
        
        $params = parse_ini_file(Yii::getAlias('@app')."/config/frontend.ini", true);
        
        
        
        $this->headerHtml .= $this->getScripts($params['js']);
        $this->headerHtml .= $this->getStyles();
        
        return $this->headerHtml;

    }
    
    protected function getScripts($params = []){
        
        $scriptHtml = '';
        
        foreach ($params as $path){
            
            $filePath = TextHelper::exclude_ext($path).'.js';
            
           $scriptHtml .= str_replace("{pathToScript}", TextHelper::dash2slash($filePath), $this->scriptTemplate);
        }
        
        return $scriptHtml;
    }
    
    protected function getStyles(){
        return '';
    }
    
    
}