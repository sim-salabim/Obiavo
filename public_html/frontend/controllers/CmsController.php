<?php
namespace frontend\controllers;

use Yii;
use common\models\Cms;

class CmsController extends BaseController
{
    public $params;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    public function actionView(){
        $cms_url = Yii::$app->request->get('cmsUrl');

        $cms_page = Cms::find()->leftJoin('cms_text', '`cms_text`.`cms_id` = `cms`.`id`')->where(['cms_text.url' => $cms_url])->one();
        $this->setPageTitle($cms_page->_text->seo_title);
        $breadcrumbs = [['label' => $cms_page->_text->seo_title, 'link' => $cms_page->_text->url]];
        Yii::$app->view->params['breadcrumbs'] = $this->setBreadcrumbs($breadcrumbs);
        Yii::$app->view->params['h1'] = $cms_page->_text->seo_h2;
        Yii::$app->view->params['desc'] = $cms_page->_text->seo_desc;
        Yii::$app->view->params['keywords'] = $cms_page->_text->seo_keywords;

        return $this->render('view', [
            'page'   => $cms_page
        ]);
    }
}