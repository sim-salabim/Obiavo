<?php
namespace common\models\libraries;

class AdsSearch {

    public $params = [];
    public $user;
    public $category;
    public $action;
    public $location;
    public $limit;
    public $query;
    public $sorting;
    public $expired;
    public $all;
    public $page;

    function __construct(){
        $this->user = null;
        $this->category = null;
        $this->action = null;
        $this->limit = 2;
        $this->query = null;
        $this->all = false;
        $this->expired = false;
        $this->page = 1;
        $this->location = [
            'country' => \Yii::$app->location->country,
            'region' => \Yii::$app->location->region,
            'city' => \Yii::$app->location->city
        ];
        $this->sorting =
            'created_at DESC, title ASC';
    }

    /**
     * @param $page
     */
    function setPage($page){
        $this->page = $page;
    }

    /**
     * @return int
     */
    function getPage(){
        return $this->page;
    }

    /**
     * @param $location
     */
    function setLocation($location){
        $this->location = $location;
    }

    /**
     * @return array
     */
    function getLocation(){
        return $this->location;
    }

    /**
     * @return array
     */
    function getParams(){
        return $this->params;
    }

    /**
     * @param $params
     */
    function setParams($params){
        $this->params = $params;
    }

    /**
     * @param $user
     */
    function setUser($user){
        $this->user = $user;
    }

    /**
     * @return int, user_id
     */
    function getUser(){
        return $this->user;
    }

    /**
     * @param $category
     */
    function setCategory($category){
        $this->category = $category;
    }

    /**
     * @return int, category_id
     */
    function getCategory(){
        return $this->category;
    }

    /**
     * @param $action, placement_id
     */
    function setAction($action){
        $this->action = $action;
    }

    /**
     * @return int, placement_id
     */
    function getAction(){
        return $this->action;
    }

    /**
     * @param $limit, int
     */
    function setLimit($limit){
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    function getLimit(){
        return $this->limit;
    }

    /**
     * @param $sorting_str, string
     */
    function setSorting($sorting_str){
        $this->sorting = $sorting_str;
    }

    /**
     * @return array
     */
    function getSorting(){
        return $this->sorting;
    }

    /**
     * @param $expired, bool
     */
    function setExpired($expired){
        $this->expired = $expired;
    }

    /**
     * @return bool
     */
    function getExpired(){
        return $this->expired;
    }

    /**
     * @param $all
     */
    function setAll($all){
        $this->all = $all;
    }

    /**
     * @return bool
     */
    function getAll(){
        return $this->all;
    }

    /**
     * @param $query
     */
    function setQuery($query){
        $this->query = $query;
    }

    /**
     * @return null
     */
    function getQuery(){
        return $this->query;
    }
}