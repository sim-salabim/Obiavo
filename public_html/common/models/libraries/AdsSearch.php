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
    public $offset;
    public $sorting;
    public $expired;
    public $all;

    function __construct(){
        $this->user = null;
        $this->category = null;
        $this->action = null;
        $this->limit = 10;
        $this->offset = 0;
        $this->query = null;
        $this->all = false;
        $this->expired = false;
        $this->location = [
            'country' => null,
            'region' => null,
            'city' => null
        ];
        $this->sorting = [
            'created_at' => 'SORT_ASC',
            'title'      => 'SORT_DESC'
        ];
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
     * @param $offset, int
     */
    function setOffest($offset){
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    function getOffset(){
        return $this->offset;
    }

    /**
     * @param $sorting_arr, array (['id' => SORT_ASC, 'title' => SORT_DESC])
     */
    function setSorting($sorting_arr){
        $this->sorting = $sorting_arr;
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

    function getAll(){
        return $this->all;
    }
}