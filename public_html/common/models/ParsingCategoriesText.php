<?php

namespace common\models;

use Yii;
use common\models\Language;

/**
 * This is the model class for table "categories_text".
 *
 * @property integer $id
 * @property integer $categories_id
 * @property integer $languages_id
 * @property string $url
 * @property string $name
 * @property string $seo_h1
 * @property string $seo_h2
 * @property string $seo_title
 * @property string $seo_desc
 * @property string $seo_keywords
 * @property string $seo_text
 *
 */
class ParsingCategoriesText extends \yii\db\ActiveRecord
{
    const SCENARIO_DEFAULT = 'default';

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => [
                'categories_id',
                'languages_id',
                'url',
                'name',
                'seo_h1',
                'seo_h2',
                'seo_title',
                'seo_desc',
                'seo_keywords',
                'seo_text'
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parsing_categories_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categories_id' => 'Categories ID',
            'languages_id' => 'Languages ID',
            'url' => 'Url',
            'seo_title' => 'Seo Title',
            'name' => 'Seo Name',
            'seo_h1' => 'Seo H1',
            'seo_h2' => 'Seo H2',
            'seo_desc' => 'Seo Desc',
            'seo_keywords' => 'Seo Keywords',
            'seo_text' => __('SEO text'),
        ];
    }


}
