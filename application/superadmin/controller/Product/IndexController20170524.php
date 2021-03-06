<?php
// +----------------------------------------------------------------------
// |  [ 商品管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-16
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Product;
use app\superadmin\ActionController;

use \think\Config;

use app\lib\Db;
use app\lib\Img;
use app\lib\Model;
use app\model\Business\BusinessCategoryModel;
use app\model\Product\ProductModel;
use app\form\ProProduct\ProProductAdd;
use app\form\ProProductSpecValue\ProProductSpecValueAdd;
use app\form\ProProductSpec\ProProductSpecAdd;
use app\model\OrdTransportExtendModel;

class IndexController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [indexAction 模型]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-14T21:21:02+0800
     * @return   [type]                   [description]
     */
    public function listAction(){  
        
        $params = $this->params;
        foreach ($params as $k => $v) {
            $paramString .= "&$k=$v";
        }

        $categoryid         = $this->getParam('categoryid');
        $businesscategoryid = $this->getParam('businesscategoryid');

        //获取分类数据
        //$categoryArr
        
        //获取店铺分类数据
        $businessCategory = BusinessCategoryModel::formart_category($this->businessid);
        //var_dump($businessCategory); exit();
        $businessCategoryArr = [];
                 
        foreach ($businessCategory as $key => $value) {        
            $businessCategoryArr[$value['id']] = $value['_category_name'];
        }
        
        $category_top = $this->getParam('category_top');
        $category_son = $this->getParam('category_son');
        $businessname = $this->getParam('businessname');
       
        if(!empty($category_top)){
            if(empty($category_son)){
                $ids = '';

                $soncateData = Model::ins('ProCategory')->getList(['parent_id'=>$category_top],'id');
                
                foreach ($soncateData as $key => $value) {
                    if(!empty( $value['id']))
                        $ids .= $value['id'] . ',';
                }
                $ids = rtrim($ids,',');

                $where['categoryid'] = array('in',$ids);
            }else{
                $where['categoryid'] = $category_son;
            }
        }

        if(!empty($businessname)){

            $businessData = Model::ins('BusBusiness')->getList(['businessname'=>['like','%'.$businessname.'%']],'id');
           
            if(!empty($businessData)){

                $bids = '';
                foreach ($businessData as $key => $value) {
                   $bids.= $value['id'].',';
                }
                $bids = rtrim($bids,',');
                if(!empty($bids)){
                    $where['businessid'] = ['in',$bids];
                }
            }
        }
        


        $where = $this->searchWhere([
                "productname"=>"like",
                "checksatus"=>"=",
                "enable" => "=",
                "addtime" => "times",
            ],$where);

      
        
        //商品列表
        $list = Model::ins("ProProduct")->pageList($where,'*','id desc');
        //var_dump($list); exit();
        //TODO 获取商品分类名称
        foreach ($list['list']  as $pid => $product){
//             $proCategory  = Model::ins("ProCategoty")->getById($product['categoryid']);
//             $list['list'][$pid]['categoryname'] = $proCategory['name'];
//             //$list['list'][$pid]['categoryname'] = 'test';
            
            $proBusinessCategory  = Model::ins("BusBusinessCategory")->getById($product['businesscategoryid']);
            //var_dump($proBusinessCategory); exit();
            $list['list'][$pid]['businesscategoryname'] = $proBusinessCategory['category_name'];
        }
        //var_dump($list); exit();
        
        $viewData =[ 
                'businessCategoryArr' => $businessCategoryArr,
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
                'paramString' => $paramString
            ];

        return $this->view($viewData);

    }

     /**
     * [sortAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-06T13:08:26+0800
     * @return   [type]                   [description]
     */
    public function sortAction(){
        $ids = $this->getParam('id');
        $sort = $this->getParam('sort');
        $id_arr = explode(',', $ids);
        //print_r($id_arr);
        $sort_arr = explode(',', $sort);
        //print_r($sort_arr);
        foreach ($id_arr as $key => $value) {

           Model::ins('ProProduct')->update(['sort'=> (int) $sort_arr[$key]],['id'=>$value]);
        
        }
        
        $this->showSuccess('成功排序');
    }

    /**
     * [editProductAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-17T19:24:51+0800
     * @return   [type]                   [description]
     */
    public function editProductAction(){
        $goodsid=$this->getParam('goodsid');

        if(empty($goodsid))
            $this->showError('请选择需要编辑的商品');
        $res=array();

        //商品基础信息
        $ProProduct = Model::ins('ProProduct');
        $productinfo = $ProProduct->getRow(['id'=>$goodsid]);
      

        $ProProductInfo = Model::ins('ProProductInfo');
        $productinfo['description'] = $ProProductInfo->getRow(['id'=>$goodsid],'description')['description'];
        $productinfo['description'] = textimg($productinfo['description']);
       
        //商品规格数据
        $ProProductSpec = Model::ins('ProProductSpec');
        //规格数据
        $spec_array = $ProProductSpec->getList(["productid"=>$goodsid]);

        //print_r($spec_array);

        $sp_value = array();
        $sp_name=array();
        $ProProductPhoto = Model::ins('ProProductPhoto');
        $photos_arr = '';
        $photos_data = $ProProductPhoto->getList(['productid'=>$goodsid],'productpic');
        //print_r($photos_data);
        foreach ($photos_data as $key => $value) {
            $photos_arr .= $value['productpic'].',';
        }
        $photos_arr = rtrim($photos_arr,',');

          $moduleid=$productinfo['moduleid'];

        if(!isset($moduleid)||empty($moduleid))
        {
            return json_encode('产品添加需要先选好商品类型');
        }
        
        //查询上一步选中的类型
        $mo_where['id']=$moduleid;
        $moduleData=Model::ins("ProModule")->getRow($mo_where);
        //$res['module']=$moduleData;

        //查询关联的规格
        //$mor_sp_where['type']=1;
        $mor_sp_where['module_id']=$moduleid;
        $spec=array();
        $attribute=array();
        $specCount=0;
        $moduleRelationData=Model::ins("ProModuleRelation")->getList($mor_sp_where);
     

        foreach ($moduleRelationData as $key => $value) {
            if($value['type']==1)
            {
                $specData=Model::ins("ProSpec")->getRow(array('id'=>$value['obj_id']));
                if(!empty($specData))
                {
                    $specData['itemValue']=Model::ins("ProSpecValue")->getList(array('spec_id'=>$value['obj_id']));
                    $spec[$specData['id']]=$specData;
                }
                
            }
            
        }

        if(is_array($spec_array) && !empty($spec_array)){

            $spec_checked = [];

            foreach($spec_array as $k=>$v){
                $a = json_decode($v['productspec'], true);
                if(!empty($a)){

                    foreach ($spec as $spK => $spV) {
                        foreach ($spV['itemValue'] as $spvK => $spvV) {
                            $spv_arr[$spV['id']][] =  $spvV['id'];
                            $spv_arr_value[$spvV['id']] =  $spvV['spec_value_name'];
                        }
                    }
                  

                    foreach ($spv_arr as $sparrK => $sparrV) {
                        foreach ($a as $ak => $av) {
                            if(in_array($ak, $sparrV)){
                                
                                unset($spv_arr[$sparrK]);
                            }
                        }
                    }

                    foreach ($spv_arr as $spvRkey => $spvRvalue) {
                        $a[$spvRvalue[0]] = $spv_arr_value[$spvRvalue[0]]; 
                    }

                    foreach($a as $key=>$val){
                        $spec_checked[$key]['id'] = $key;
                        $spec_checked[$key]['name'] = $val;

                        //print_r($key);
                        //查询规格值数据的规格数据
                        $specValueData=Model::ins("ProSpecValue")->getRow(array('id'=>$key));
                        $top_spec[] = $specValueData['spec_id']; 
                        $sp_name[$specValueData['spec_id']]=$specValueData['spec_value_name'];
                    }
                    $matchs = array_keys($a);
                    sort($matchs);

                    $sid = str_replace ( ',', '_', implode ( ',', $matchs ) );

                 
                    $sp_value ['i_' . $sid . '|id'] = $v['id'];
                    $sp_value ['i_' . $sid . '|discount'] = $v['discount'];
                    $sp_value ['i_' . $sid . '|saleprice'] = DePrice($v['saleprice']);
                    $sp_value ['i_' . $sid . '|prouctprice'] = DePrice($v['prouctprice']);
                    $sp_value ['i_' . $sid . '|supplyprice'] = DePrice($v['supplyprice']);
                    $sp_value ['i_' . $sid . '|bullamount'] = DePrice($v['bullamount']);
                    $sp_value ['i_' . $sid . '|productstorage'] = $v['productstorage'];
                    $sp_value ['i_' . $sid . '|sku'] = $v['sku'];
                    $sp_value ['i_' . $sid . '|image'] = $v['productimage'];
                }
            }
          
            $res['spec_checked']=$spec_checked;


        }
        
        foreach ($top_spec as $key => $value) {
            if($spec[$value]['id'] != $value){
                $specData=Model::ins("ProSpec")->getRow(array('id'=>$value));
                if(!empty($specData))
                {
                    $specData['itemValue']=Model::ins("ProSpecValue")->getList(array('spec_id'=>$value));
                    $spec[$specData['id']]=$specData;
                }
            }
        }

        $res['sp_value']=$sp_value;
        $res['sp_name']=$sp_name;
        //count($sp_name);
        $res['sign_i']=count($sp_name);
        

      

   

        $proCategorydata =  ProductModel::getCategoryData();
        $proCategorydata = BusinessCategoryModel::tree($proCategorydata,'name');
      
        foreach ($proCategorydata as $key => $value) {
            $optionProCate[$value['id']] = $value['_name'];
        }
      

        $categoryData = Model::ins('BusBusinessCategory')->getList(['businessid'=>$productinfo['businessid']]);
        $categoryData = BusinessCategoryModel::tree($categoryData,'category_name');
        
        foreach ($categoryData as $key => $value) {
            $optionCate[$value['id']] = $value['_category_name'];
        }

        //品牌
        $brandArr=array();
        $brandData=Model::ins("ProBrand")->getList(['isdelete'=>0],"id,brandname","sort desc");
        $brandArr[0] = '其他';
        foreach ($brandData as $key => $value) {
            $brandArr[$value['id']]=$value['brandname'];

        } 
        

         //form验证token
        $formtoken = $this->Btoken('Product-Index-editProduct');


        //获取商家的运费模板
        $db_Transport = new OrdTransportExtendModel();
        
        $transport_array = $db_Transport->getList(['business_id'=>$this->businessid]);
       

        foreach($transport_array as $tk=>$tv){
            $transport_list[$tv['id']] = $tv['transport_title'];
        }
       
        //print_r($transport_list);

        $res['optionCate']= $optionCate;
        $res['optionProCate']= $optionProCate;
        $res['module']=$moduleData;
        $res['spec']=$spec;
        $res['attribute']=$attribute;
        $res['brand']=$brandArr;
        $res['formtoken']=$formtoken;
        $res['productinfo']=$productinfo;
        $res['photos'] = $photos_arr;
        $res['transport_list'] = $transport_list;

        return $this->view($res);


    }

    

    /**
     * [discheckAction 拒绝通过]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-27T20:01:53+0800
     * @return   [type]                   [description]
     */
    public function discheckAction(){

        $params = $this->params;
        foreach ($params as $k => $v) {
            $paramString .= "&$k=$v";
        }
        $goodsid = $this->getParam('goodsid');
        if(empty($goodsid))
            $this->showError('请选择商品');

        $res = [
            'goodsid' => $goodsid,
            'paramString'=>$paramString,
            'action'  => '/Product/Index/dodischeck'
        ]; 

       return $this->view($res);
    }
    
    /**
     * [dodischeckAction 拒绝通过动作]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-27T20:03:40+0800
     * @return   [type]                   [description]
     */
    public function dodischeckAction(){


        $params = $this->params;
        foreach ($params as $k => $v) {
            $paramString .= "&$k=$v";
        }
        $postparamString = $params['paramString'];
        $url = '/Product/Index/list'."?".$paramString.$postparamString;

        $stateremark = $this->getParam('stateremark');
        $goodsid = $this->getParam('goodsid');
        if(empty($goodsid))
            $this->showError('请选择商品');

        $updateData = [
            'stateremark' => $stateremark,
            'enable' => 2,
            'checksatus' => -1,
        ];

        Model::ins('ProProduct')->update($updateData,['id'=>$goodsid]);
        $updateDatas = [
            'enable' => 2,
        ];
        Model::Es("ProProduct")->update($updateDatas,['id'=>$goodsid]);

        $this->showSuccess('操作成功',$url);

    }

    /**
     * [passcheckAction 通过审核]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-27T20:14:41+0800
     * @return   [type]                   [description]
     */
    public function passcheckAction(){




        $ids = $this->getParam('ids');

        $params = $this->params;
        unset($params['ids']);
        foreach ($params as $k => $v) {
            $paramString .= "&$k=$v";
        }

        $url = '/Product/Index/list'."?".$paramString;

        //$goodsid = $this->getParam('goodsid');
        if(empty($ids))
            $this->showError('请选择商品');

        $id_arr = explode(',', $ids);

        $i = 0;
        $errorName = '';

        foreach ($id_arr as $key => $goodsid) {

            $proData = Model::ins('ProProduct')->getRow(['id'=>$goodsid],'id,productname,enable');

            if(empty($proData))
                $this->showError('商品信息不存在');

            if($proData['enable'] != 1){

                $errorName .= '<font color="green">'.$proData['productname'].'</font>&nbsp|&nbsp';
                //$this->showError('<font color="green">'.$proData['productname'].'</font>已上架不能删除');
            }

            if(!empty($proData) && $proData['enable'] == 1){

                $updateData = [
                    'enable' => 1,
                    'checksatus' => 1,
                ];

                Model::ins('ProProduct')->update($updateData,['id'=>$goodsid]);
                Model::Es("ProProduct")->update($updateData,['id'=>$goodsid]);
                $i++;
            }

        }
        
        if(!empty($errorName)){
            $msg = '其中'.rtrim($errorName,'&nbsp|&nbsp').'为下架商品不能通过';
        }
        
        $this->showSuccess('成功审核'.$i.'条记录'.$msg,'',10);
    }

   
    /**
     * [ajaxaddsepcAction ajax添加商品规格]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-17T14:51:20+0800
     * @return   [type]                   [description]
     */ 
    public function ajaxAddSepcValueAction(){

    	$spec_id=$this->getParam('spec_id');
        $spec_name=$this->getParam('spec_name');
        $value_name=$this->getParam('name');

        $data['spec_id']=$spec_id;
        $data['spec_value_name']=$value_name;
        $data['isdelete']=0;
        $data['store_id']=$this->businessid;

        $value_id=Model::ins("ProSpecValue")->insert($data);

        if($value_id){
            echo json_encode(['done' => true, 'value_id' => $value_id]);
        }else{
            echo json_encode(['done' => false]);
        }
        exit();
    }


    /**
     * [adminEditproductAction 修改商品]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-05T15:00:21+0800
     * @return   [type]                   [description]
     */
    public function adminEditproductAction(){

        $goodsid=$this->getParam('goodsid');

        if(empty($goodsid))
            $this->showError('请选择需要编辑的商品');
        $res=array();

        //商品基础信息
        $ProProduct = Model::ins('ProProduct');
        $productinfo = $ProProduct->getRow(['id'=>$goodsid]);
      

        $ProProductInfo = Model::ins('ProProductInfo');
        $productinfo['description'] = $ProProductInfo->getRow(['id'=>$goodsid],'description')['description'];
        $productinfo['description'] = textimg($productinfo['description']);
       
        //商品规格数据
        $ProProductSpec = Model::ins('ProProductSpec');
        //规格数据
        $spec_array = $ProProductSpec->getList(["productid"=>$goodsid]);
        //print_r($spec_array);
        $sp_value = array();
        $sp_name=array();
        $ProProductPhoto = Model::ins('ProProductPhoto');
        $photos_arr = '';
        $photos_data = $ProProductPhoto->getList(['productid'=>$goodsid],'productpic');
        //print_r($photos_data);
        foreach ($photos_data as $key => $value) {
            $photos_arr .= $value['productpic'].',';
        }
        $photos_arr = rtrim($photos_arr,',');

          $moduleid=$productinfo['moduleid'];

        if(!isset($moduleid)||empty($moduleid))
        {
            return json_encode('产品添加需要先选好商品类型');
        }
        
        //查询上一步选中的类型
        $mo_where['id']=$moduleid;
        $moduleData=Model::ins("ProModule")->getRow($mo_where);
        //$res['module']=$moduleData;

        //查询关联的规格
        //$mor_sp_where['type']=1;
        $mor_sp_where['module_id']=$moduleid;
        $spec=array();
        $attribute=array();
        $specCount=0;
        $moduleRelationData=Model::ins("ProModuleRelation")->getList($mor_sp_where);
     

        foreach ($moduleRelationData as $key => $value) {
            if($value['type']==1)
            {
                $specData=Model::ins("ProSpec")->getRow(array('id'=>$value['obj_id']));
                if(!empty($specData))
                {
                    $specData['itemValue']=Model::ins("ProSpecValue")->getList(array('spec_id'=>$value['obj_id']));
                    $spec[$specData['id']]=$specData;
                }
            }
            
        }

        if(is_array($spec_array) && !empty($spec_array)){

            $spec_checked = [];

            foreach($spec_array as $k=>$v){
                $a = json_decode($v['productspec'], true);
                
                foreach ($spec as $spK => $spV) {
                    foreach ($spV['itemValue'] as $spvK => $spvV) {
                        $spv_arr[$spV['id']][] =  $spvV['id'];
                        $spv_arr_value[$spvV['id']] =  $spvV['spec_value_name'];
                    }
                }

                foreach ($spv_arr as $sparrK => $sparrV) {
                    foreach ($a as $ak => $av) {
                        if(in_array($ak, $sparrV)){
                            
                            unset($spv_arr[$sparrK]);
                        }
                    }
                }

                foreach ($spv_arr as $spvRkey => $spvRvalue) {
                    $a[$spvRvalue[0]] = $spv_arr_value[$spvRvalue[0]]; 
                }

                if(!empty($a)){
                    foreach($a as $key=>$val){
                        $spec_checked[$key]['id'] = $key;
                        $spec_checked[$key]['name'] = $val;

                        //print_r($key);
                        //查询规格值数据的规格数据
                        $specValueData=Model::ins("ProSpecValue")->getRow(array('id'=>$key));
                        
                        $sp_name[$specValueData['spec_id']]=$specValueData['spec_value_name'];
                        $top_spec[] = $specValueData['spec_id']; 
                    }
                    $matchs = array_keys($a);
                    sort($matchs);

                    $sid = str_replace ( ',', '_', implode ( ',', $matchs ) );

                 
                    $sp_value ['i_' . $sid . '|id'] = $v['id'];
                    $sp_value ['i_' . $sid . '|discount'] = $v['discount'];
                    $sp_value ['i_' . $sid . '|saleprice'] = DePrice($v['saleprice']);
                    $sp_value ['i_' . $sid . '|prouctprice'] = DePrice($v['prouctprice']);
                    $sp_value ['i_' . $sid . '|supplyprice'] = DePrice($v['supplyprice']);
                    $sp_value ['i_' . $sid . '|bullamount'] = DePrice($v['bullamount']);
                    $sp_value ['i_' . $sid . '|productstorage'] = $v['productstorage'];
                    $sp_value ['i_' . $sid . '|sku'] = $v['sku'];
                    $sp_value ['i_' . $sid . '|image'] = $v['productimage'];
                }
            }
            //print_r($spec_checked);
            $res['spec_checked']=$spec_checked;

        }
       // print_r($spec);
        $spec_arr = [];
        foreach($spec_array as $k=>$v){
            $spec_value = json_decode($v['spec'],true);
            $productspec_value = json_decode($v['productspec'],true);

            $productspec_value_new = [];
            foreach($productspec_value as $id=>$productspec_value_value){
                $productspec_value_new[] = [
                    "id"=>$id,
                    //"specvalue"=>$productspec_value_value,
                ];
            }

            foreach($spec_value as $i=>$spec_value_value){
                $spec_value[$i]['id'] = $productspec_value_new[$i]['id'];

                $spec_arr[$spec_value_value['name']][$productspec_value_new[$i]['id']] = [
                    "id"=>$productspec_value_new[$i]['id'],
                    "spec_value_name"=>$spec_value_value['value'],                       
                ];
            }
        } 
        //print_r($spec_arr);
        $new_spec = [];
        $count = 0 ; 
        foreach ($spec_arr as $spkey => $spvalue) {
            $specData = Model::ins('ProSpec')->getRow(['specname'=>$spkey],'id,specname');
            // print_r($specData);
            if(!empty($specData)){
                $itemValue = Model::ins('ProSpecValue')->getList(['spec_id'=>$specData['id']],'id,spec_value_name');
                $specData['itemValue'] =  $itemValue;
                $new_spec[] = $specData;
            }else{

                $specData['id'] = $count;
                $specData['specname']   = $spkey;
                $specData['itemValue'] = $spvalue;
                $new_spec[] = $specData;
            }
            $count++;
        }
        //print_r($new_spec);

        foreach ($top_spec as $key => $value) {
            if($spec[$value]['id'] != $value){
                $specData=Model::ins("ProSpec")->getRow(array('id'=>$value));
                if(!empty($specData))
                {
                    $specData['itemValue']=Model::ins("ProSpecValue")->getList(array('spec_id'=>$value));
                    $spec[$specData['id']]=$specData;
                }
            }
        }

        $res['sp_value']=$sp_value;
        $res['sp_name']=$sp_name;
       
        $res['sign_i']=count($sp_name);
     

        $proCategorydata =  ProductModel::getCategoryData();
        $proCategorydata = BusinessCategoryModel::tree($proCategorydata,'name');
      
        foreach ($proCategorydata as $key => $value) {
            $optionProCate[$value['id']] = $value['_name'];
        }

        $categoryData = Model::ins('BusBusinessCategory')->getList(['businessid'=>$productinfo['businessid'],'is_delete'=>0]);
        $categoryData = BusinessCategoryModel::tree($categoryData,'category_name');
        
        foreach ($categoryData as $key => $value) {
            $optionCate[$value['id']] = $value['_category_name'];
        }

        //品牌
        $brandArr=array();
        $brandData=Model::ins("ProBrand")->getList(['isdelete'=>0],"id,brandname","sort desc");
        $brandArr[0] = '其他';
        foreach ($brandData as $key => $value) {
            $brandArr[$value['id']]=$value['brandname'];

        } 
        

         //form验证token
        $formtoken = $this->Btoken('Product-Index-editProduct');
      
        
      
        $parent_cate_id = Model::ins('ProCategory')->getRow(['id'=>$productinfo['categoryid']],'parent_id')['parent_id'];
       
        //print_r($transport_list);
        $res['parent_cate_id'] = $parent_cate_id;
        $res['optionCate']= $optionCate;
        $res['optionProCate']= $optionProCate;
        $res['module']=$moduleData;
        $res['spec']=$new_spec;
        $res['attribute']=$attribute;
        $res['brand']=$brandArr;
        $res['formtoken']=$formtoken;
        $res['productinfo']=$productinfo;
        $res['photos'] = $photos_arr;
      
        $price_type = Model::ins("BusBusiness")->getRow(['id'=>$productinfo['businessid']],'price_type');
        $type_arr = explode(',', $price_type['price_type']);
        $res['type_arr'] = $type_arr;


        return $this->view($res);
        
    }

     /**
     * [doaddOreditProductAction 添加或修改商品操作]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-17T15:24:49+0800
     * @return   [type]                   [description]
     */
    public function doaddOreditProductAction(){

        // if($this->Ctoken()){


            

            $post = $this->params;

            $this->businessid = (int) $post['businessid'];
            
            if($this->businessid < 0)
                $this->showError('商家信息错误','/login');

            // print_r($post);
            // exit();
            $post['categoryid'] = $post['categoryid_son'];

            $post['brandid'] = empty($post['brandid']) ? 0 : $post['brandid'];

            $id = $post['id'];


            if(!empty($id)){
                $url = '/Product/Index/editProduct?goodsid='.$id;
            }else{
                $url = 'addProductSetptwo?moduleid='.$post['moduleid'];
            }

            if(empty($post['categoryid'])){
                 $this->showError("请选择分类",$url);
            }

            foreach ($post['sp_name'] as $key => $value) {
                if(empty($post['sp_val'][$key])){
                     $this->showError("请勾选".$value."属性的值");
                }
            }

            foreach ($post['spec'] as $key => $value) {

                //获取价格
                $specformart = [
                    'supplyprice' =>$value['supplyprice'],
                    'saleprice' =>$value['saleprice'],
                    'settle_cycle' =>$post['saletype']
                ];

                $specformartPrice = ProductModel::formatPrice($specformart); 

                $specprouctprice = EnAdminPrice($specformartPrice['prouctprice']);
                $specbullamount = EnAdminPrice($specformartPrice['bullamount']);
                $specdiscount = $specformartPrice['discount'];
                $specsaleprice = EnAdminPrice($value['saleprice']);
                $specsupplyprice = EnAdminPrice($value['supplyprice']);
                $specsaletype = $post['saletype'];

                if($specbullamount <= 0 && $specprouctprice <= 0){
                    $this->showError("价格错误,请重新填写数据");
                }
                if($value['productstorage'] <= 0){
                    $this->showError("请填写库存");
                }
            }

            //轮播图 商品详情
            $photos = $post['photos'];
            //print_r($photos);
            $photos = explode(',', $photos);

            $post['bullamount'] =  empty($post['bullamount']) ? 568 : $post['bullamount'];
            $post['prouctprice'] =  empty($post['prouctprice']) ? 568 : $post['prouctprice'];
            //获取价格
            $formart = [
                'supplyprice' => empty($post['supplyprice']) ? 568 : $post['supplyprice'],
                'saleprice' => empty($post['saleprice'])  ? 789 : $post['saleprice'],
                'settle_cycle' =>$post['saletype']
            ];
            
            if($post['bullamount'] <= 0 && $post['prouctprice'] <= 0){
                $this->showError("价格错误,请重新填写数据");
            }

            $formartPrice = ProductModel::formatPrice($formart); 

            $prouctprice = EnAdminPrice($formartPrice['prouctprice']);
            $bullamount = EnAdminPrice($formartPrice['bullamount']);
            $discount = $formartPrice['discount'];
            $saleprice = EnAdminPrice($post['saleprice']);
            $supplyprice = EnAdminPrice($post['supplyprice']);
            $saletype = $post['saletype'];

            // if($bullamount <= 0 && $prouctprice <= 0){
            //     $this->showError("价格错误,请重新填写数据");
            // }
        
            // print_r($post);
            // exit();
            $ProProduct = Model::ins('ProProduct'); //商品基本信息
            $ProProductSpec = Model::ins('ProProductSpec');//商品sku信息（规格）
            $ProProductSpecValue = Model::ins('ProProductSpecValue');//商品所有sku组合信息
            $ProProductInfo = Model::ins('ProProductInfo');//商品详情等扩展信息
            $ProSpec = Model::ins('ProSpec');//商品规格
            $ProSpecValue = Model::ins('ProSpecValue');//商品规格对应值
            $ProModuleRelation = Model::ins('ProModuleRelation');
            $ProProductPhoto = Model::ins('ProProductPhoto');
            $ProCategory = Model::ins('ProCategory');//商品分类
            $ProCategory = Model::ins('ProCategory');//商品分类
            $BusBusinessCategory = Model::ins('BusBusinessCategory'); //商家分类

            //自动验证表单 需要修改form对应表名
            $ProProductAdd = new ProProductAdd();
            $ProProductSpecValueAdd = new ProProductSpecValueAdd();
            $ProProductSpecAdd = new ProProductSpecAdd();
            // var_dump(gettype($this->businessid));
            //  var_dump(gettype($post['enable']));
            if($post['freight'] == 1){
                $post['g_freight'] = 0;
            }else{
                $post['transportid'] = 0;
            }
            if(empty($id)){
               
                $procategoryname = $ProCategory->getRow(['id'=>$post['categoryid']],'name')['name'];
                $buscategoryname = $BusBusinessCategory->getRow(['id'=>$post['businesscategoryid']],'category_name')['category_name'];

                $addProData = [
                    'spu'=>$ProProduct->getSPuNo(),
                    'businessid' =>$this->businessid,
                    'categoryid'=>$post['categoryid'],
                    'categoryname' => $procategoryname,
                    'businesscategoryid'=>$post['businesscategoryid'],
                    'productname' => $post['productname'],
                    'prouctprice' => (int)  $prouctprice,
                    'supplyprice' => (int)  $supplyprice,
                    'bullamount' => (int)  $bullamount,
                    'saleprice' => (int) $saleprice,
                    'saletype' => (int)  $saletype,
                    'discount' => (int)  $discount,
                    'productstorage' => $post['productstorage'],
                    'thumb' => $post['thumb'],
                    'weight' => $post['weight'],
                    'weight_gross'=> $post['weight_gross'],
                    'volume' => $post['volume'],
                    'moduleid' => $post['moduleid'],
                    'brandid' => $post['brandid'],
                    'enable' => $post['enable'],
                    'freight' => empty($post['g_freight']) ? 0 : $post['g_freight'],
                    'transportid' => empty($post['transportid']) ? 0 : $post['transportid'],
                    'checksatus' => 1,
                    'addtime'=>date('Y-m-d H:i:s'),
                    'enabletime'=>date('Y-m-d H:i:s')
                ]; 
                
                if(!$ProProductAdd->isValid($addProData)){//验证是否正确 

                    $this->showError($ProProductAdd->getErr());//提示报错信息
                }else{
                    $data = $ProProduct->insert($addProData);
                    $pid = $data;
                  
                }

            }else{

                $rowPro = Model::ins("ProProduct")->getRow(["id"=>$id]);

               
                $procategoryname = $ProCategory->getRow(['id'=>$post['categoryid']],'name')['name'];
                $pid = $id;
                $updateProData = [
                    'businessid' =>$this->businessid,
                    'categoryid'=>$post['categoryid'],
                    'categoryname' => $procategoryname,
                    'businesscategoryid'=>$post['businesscategoryid'],
                    'productname' => $post['productname'],
                    'prouctprice' => (int)  $prouctprice,
                    'supplyprice' => (int)  $supplyprice,
                    'bullamount' => (int)  $bullamount,
                    'saleprice' => (int) $saleprice,
                    'saletype' => (int)  $saletype,
                    'discount' => (int)  $discount,
                    'productstorage' => $post['productstorage'],
                    'thumb' => $post['thumb'],
                    'weight' => $post['weight'],
                    'weight_gross'=> $post['weight_gross'],
                    'volume' => $post['volume'],
                    'moduleid' => $post['moduleid'],
                    'brandid' => $post['brandid'],
                    'enable' => $post['enable'],
                    'freight' => empty($post['g_freight']) ? 0 : $post['g_freight'],
                    'transportid' => empty($post['transportid']) ? 0 : $post['transportid'],
                    'checksatus' => 1
                ]; 

                if(empty($rowPro['addtime']))
                    $updateProData['addtime'] = date('Y-m-d H:i:s');
                
                if(empty($rowPro['enabletime']))
                    $updateProData['enabletime'] = date('Y-m-d H:i:s');

                if(!$ProProductAdd->isValid($updateProData)){//验证是否正确 
                    $this->showError($ProProductAdd->getErr());//提示报错信息
                }else{
                   
                    //$updata = $ProProduct->update($updateProData,['id'=>$id]);
                    $ProProduct->update($updateProData,['id'=>$id]);
                }
                
            }

            $ProProductPhoto->delete(['productid'=>$pid]);

            foreach ($photos as $key => $value) {
              
                $ProProductPhoto->insert([
                    'productpic'=>$value,
                    'productid'=>$pid,
                    'sort'=>$key,
                    'addtime'=>date('Y-m-d H:i:s')
                ]);
            }

           
            //组合所有规格
            $allSpecData = [];
            foreach ($post['sp_val'] as $key => $value) {
                $allSpecData[$key] = $post['sp_name'][$key];
            }

            
            //组合所有规格的值
            $specValue = [];
            
            foreach ($allSpecData as $ka => $va) {
                $tmp = [];
                $specValueData = $ProSpecValue->getList(['spec_id'=>$ka],'id,spec_value_name');
                foreach ($specValueData as $ko => $vo) {
                    $tmp[$vo['id']] = $vo['spec_value_name'];
                }
                $specValue[$ka] = $tmp; 
            }
           
            
            $addSpecValueData = [
                'productid'=>$pid,
                // 'spec_name'=> json_encode($specname,JSON_UNESCAPED_UNICODE),
                'spec_name'=> json_encode($allSpecData,JSON_UNESCAPED_UNICODE),
                'spec_vlaue'=> json_encode($specValue,JSON_UNESCAPED_UNICODE),
            ];
           
            $SpecValueData = $ProProductSpecValue->getRow(['productid'=>$pid]);
            
            if(empty($SpecValueData)){

                if(!$ProProductSpecValueAdd->isValid($addSpecValueData)){//验证是否正确 
                    $this->showError($ProProductSpecValueAdd->getErr());//提示报错信息
                }else{
                    $ProProductSpecValue->insert($addSpecValueData);
                }

            }else{

                if(!$ProProductSpecValueAdd->isValid($addSpecValueData)){//验证是否正确 
                    $this->showError($ProProductSpecValueAdd->getErr());//提示报错信息
                }else{
                    $ProProductSpecValue->update($addSpecValueData,['productid'=>$pid]);
                }

            }

            if(empty($id)){
              
                $prouctDescription = [
                    'id' => $pid,
                    'description' => $post['description']
                ];
                $ProProductInfo->insert($prouctDescription);

                if(!empty($post['spec'])){
                   
                    foreach ($post['spec'] as $key => $value) {
                     
                        if(!empty($value['sp_value'] && is_array($value['sp_value']))){
                            $spec = [];
                            $specname = [];
                            
                            $i = 0;
                            foreach ($value['sp_value'] as $k => $v) {
                                $specValueData = $ProSpecValue->getRow(['id'=>$k],'spec_id');
                                $specData = $ProSpec->getRow(['id'=>$specValueData['spec_id']],'specname'); 
                                $spec[$i]['name'] = $specData['specname'];
                                $spec[$i]['value'] = $v; 
                                $i++;
                                $specname[$specValueData['spec_id']] = $specData['specname'];
                            
                            }

                        }
                        
                        //获取价格
                        $specformart = [
                            'supplyprice' =>$value['supplyprice'],
                            'saleprice' =>$value['saleprice'],
                            'settle_cycle' =>$post['saletype']
                        ];

                        $specformartPrice = ProductModel::formatPrice($specformart); 

                        $specprouctprice = EnAdminPrice($specformartPrice['prouctprice']);
                        $specbullamount = EnAdminPrice($specformartPrice['bullamount']);
                        $specdiscount = $specformartPrice['discount'];
                        $specsaleprice = EnAdminPrice($value['saleprice']);
                        $specsupplyprice = EnAdminPrice($value['supplyprice']);
                        $specsaletype = $post['saletype'];

                        if($specbullamount <= 0 && $specprouctprice <= 0){
                            $this->showError("价格错误,请重新填写数据");
                        }
                                    
                        $addSpecData = [
                            'sku' =>  $ProProductSpec->getSKuNo(),
                            'productid'=>$data,
                            'productname'=>$post['productname'],
                            'prouctprice' =>  $specprouctprice,
                            'supplyprice' => $specsupplyprice,
                            'bullamount' =>  $specbullamount,
                            'saleprice' => $specsaleprice,
                            'saletype' => $specsaletype,
                            'discount' => $specdiscount,
                            'productstorage'=>$value['productstorage'],
                            'weight'=> $post['weight'],
                            'volume'=> $post['volume'],
                            'weight_gross'=> $post['weight_gross'],
                            'productspec'=> json_encode($value['sp_value'],JSON_UNESCAPED_UNICODE),
                            'spec' => json_encode($spec,JSON_UNESCAPED_UNICODE),
                            'productimage'=>$value['image_upload'],
                            'businessid' =>$this->businessid
                        ];
                      
                        if(!$ProProductSpecAdd->isValid($addSpecData)){//验证是否正确 
                            $this->showError($ProProductSpecAdd->getErr());//提示报错信息
                        }else{
                            $ProProductSpec->insert($addSpecData);
                        }
                        
                    }



                }

            }else{
              
                $prouctDescription = [
                    'description' => $post['description']
                ];

                $ProProductInfo->update($prouctDescription,['id'=>$pid]);
                
                if(!empty($post['spec'])){

                    $specDatas = $ProProductSpec->getList(['productid'=>$pid],'id,productspec');
                    

                    foreach ($specDatas as $sk => $sv) {
                        foreach ($post['spec'] as $spkey => $spv){
                            if($sv['productspec'] == json_encode($spv['sp_value'],JSON_UNESCAPED_UNICODE)){
                                unset($specDatas[$sk]);
                            }
                        }
                    }
                   
                  
                    if(!empty($specDatas)){
                        foreach ($specDatas as $key => $value) {
                           $ProProductSpec->delete(['id'=>$value['id']]);
                        }
                    }
                   
                    //$ProProductSpec->delete(['productid'=>$id]);
                    foreach ($post['spec'] as $key => $value) {
                        
                        $tmp_img = explode(',',$value['image']);
                        // print_r($tmp_img);
                        foreach($tmp_img as $mk => $mv) {
                             $value['image'] = $mv;
                        }

                        if(!empty($value['sp_value'] && is_array($value['sp_value']))){
                            $spec = [];
                            
                            $i = 0;
                            foreach ($value['sp_value'] as $k => $v) {
                                $specValueData = $ProSpecValue->getRow(['id'=>$k],'spec_id');
                                $specData = $ProSpec->getRow(['id'=>$specValueData['spec_id']],'specname'); 
                                $spec[$key][$i]['name'] = $specData['specname'];
                                $spec[$key][$i]['value'] = $v; 
                                $i++;
                            
                            }

                        }
                       
                       
                        // if(empty($value['goods_id'])){
                            
                        //获取价格
                        $specformart = [
                            'supplyprice' =>$value['supplyprice'],
                            'saleprice' =>$value['saleprice'],
                            'settle_cycle' =>$post['saletype']
                        ];

                        $specformartPrice = ProductModel::formatPrice($specformart); 

                        $specprouctprice = EnAdminPrice($specformartPrice['prouctprice']);
                        $specbullamount = EnAdminPrice($specformartPrice['bullamount']);
                        $specdiscount = $specformartPrice['discount'];
                        $specsaleprice = EnAdminPrice($value['saleprice']);
                        $specsupplyprice = EnAdminPrice($value['supplyprice']);
                        $specsaletype = $post['saletype'];

                        if($specbullamount <= 0 && $specprouctprice <= 0){
                            $this->showError("价格错误,请重新填写数据");
                        }

                        $addSpecData = [
                            'sku' =>  $ProProductSpec->getSKuNo(),
                            'productid'=>$id,
                            'productname'=>$post['productname'],
                            'prouctprice' =>  $specprouctprice,
                            'supplyprice' => $specsupplyprice,
                            'bullamount' => $specbullamount,
                            'saleprice' =>$specsaleprice,
                            'saletype' =>  $specsaletype,
                            'discount' =>  $specdiscount,
                            'productstorage'=>$value['productstorage'],
                            'weight'=> $post['weight'],
                            'volume'=> $post['volume'],
                            'weight_gross'=> $post['weight_gross'],
                            'productspec'=> json_encode($value['sp_value'],JSON_UNESCAPED_UNICODE),
                            'spec' => json_encode($spec[$key],JSON_UNESCAPED_UNICODE),
                            'productimage'=>$value['image'],
                            'businessid' =>$this->businessid
                        ];
                        //print_r($addSpecData);
                        if(!$ProProductSpecAdd->isValid($addSpecData)){//验证是否正确 
                            $this->showErrorPage($ProProductSpecAdd->getErr(),'/Product/Index/editProduct?goodsid='.$pid);//提示报错信息
                        }else{
                            $getSpecData = $ProProductSpec->getRow(['productspec'=>json_encode($value['sp_value'],JSON_UNESCAPED_UNICODE),'productid'=>$pid],'id','id desc');
                            if(empty($getSpecData)){
                                $ProProductSpec->insert($addSpecData);
                            }else{
                                unset($addSpecData['sku']);
                                $ProProductSpec->update($addSpecData,['id'=>$getSpecData['id']]);
                            }
                            //$ProProductSpec->insert($addSpecData);
                        }
                      
                    }
                }
            }

             //商品sku 所有情况
            $sku = ProductModel::getProSkuById($pid);
            $sku = $sku['sku'];
            //取出sku最低价
            $min = [];
            $arr = $sku;
            $len = count($arr);
            for ($i=0; $i<$len; $i++){
                if ($i==0){
                    $min = $arr[$i];

                    continue;

                }
                if($min['prouctprice'] > 0){
                    if ($arr[$i]['prouctprice'] < $min['prouctprice']){
                        $min = $arr[$i];
                    }
                }else{
                    if ($arr[$i]['bullamount'] < $min['bullamount']){
                        $min = $arr[$i];
                    }
                }

            }
           

            
            if(!empty($min)){
                $proDatas['prouctprice'] = EnAdminPrice($min['prouctprice']);
                $proDatas['supplyprice'] = EnAdminPrice($min['supplyprice']);
                $proDatas['bullamount'] = EnAdminPrice($min['bullamount']); 
                $proDatas['saleprice'] = EnAdminPrice($min['saleprice']);               
            }
            

            $ProProduct->update($proDatas,['id'=>$pid]);

            //写入ES
            $row = Model::ins("ProProduct")->getRow(["id"=>$pid]);
            
            Model::Es("ProProduct")->delete(["id"=>$pid]);

            Model::Es("ProProduct")->insert($row);
            
            $gooCountData = Model::ins('ProProduct')->getRow(['businessid'=>$this->businessid],'count(*) as count');

            Model::ins('BusBusinessInfo')->update(['goodscount'=>$gooCountData['count']],['id'=>$this->businessid]);
            
            $this->showSuccess('操作成功');//,'/Product/Index/list'

        // }else{
        //     $this->showError('token错误，禁止操作');
        // }
    }

    /**
     * [checkPriceAction 价格公式 生成价格]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-27T16:04:47+0800
     * @return   [type]                   [description]
     */
    public function formatPriceAction(){
        $post = $this->params;
        $data = ProductModel::formatPrice($post);
        echo json_encode($data);
    }


  
    /**
    * @user 礼品列表
    * @param 
    * @author jeeluo
    * @date 2017年5月9日上午10:26:30
    * @version 1.0.1
    */
    
    public function roleListAction() {
        
        $roleLookup = array('1' => '牛粉', '2' => '牛人', '3' => '牛创客', '4' => '牛商', '5' => '牛掌柜', "6" => "孵化中心", "7" => "运营中心");
        $where = array();
        $where['enable'] = ["in", "-1,1"];
        $where = $this->searchWhere([
            "productname" => "like",
            "businessname" => "like",
            "enable" => "=",
            "checksatus" => "=",
            "role" => "=",
            "addtime" => "times",
        ],$where);
        
        $list = Model::ins("RoleProduct")->pageList($where, "*", "sort asc");
        
        $count = 1;
        foreach ($list['list'] as $k => $v) {
//             $list['list'][$k]['prouctprice'] = DePrice($v['prouctprice']);
            $list['list'][$k]['supplyprice'] = DePrice($v['supplyprice']);
            $list['list'][$k]['type'] = $roleLookup[$v['role']];
            $list['list'][$k]['numid'] = $count;
            $count++;
        }
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 添加礼品页面
    * @param 
    * @author jeeluo
    * @date 2017年5月9日上午11:22:23
    * @version 1.0.1
    */
    public function addRoleProductAction() {
        
        $action = "/Product/Index/addRoleGift";
        // 获取vip牛商列表
        $vipBusList = Model::ins("BusBusiness")->pageList(array("enable" => 1, "isvip" => 1), "id,businessname", "id desc");
        
        $busList = array();
        foreach ($vipBusList['list'] as $v) {
            $busList[$v['id']] = $v['businessname'];
        }
        
        $viewData = array(
            "busList" => $busList,
            "actionUrl" => $action,
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 添加角色礼品
    * @param 
    * @author jeeluo
    * @version 1.0.1
    * @date 2017年5月9日上午11:34:15
    */
    public function addRoleGiftAction() {
        if(empty($this->params['type']) || empty($this->params['bus']) || empty($this->params['productname']) || empty($this->params['thumb']) || empty($this->params['supplyprice'])
            || empty($this->params['enable']) || empty($this->params['sort'])) {
                $this->showError("请填写完整信息");
            }
        $params['businessid'] = $this->params['bus'];
        // 获取商家信息
        $busInfo = Model::ins("BusBusiness")->getRow(array("id" => $params['businessid'], "isvip" => 1, "enable" => 1), "businessname");
        if(empty($busInfo)) {
            $this->showError("牛商信息有误，请重新选择");
        }
        
        $role_money = Config::get("role_money");
        $roleMoneyName = array('2' => 'bullPeoMoney', '3' => 'bullenMoney');
        
        $params['role'] = $this->params['type'];
        $params['businessname'] = $busInfo['businessname'];
        $params['productname'] = $this->params['productname'];
        $params['thumb'] = $this->params['thumb'];
        $params['prouctprice'] = $role_money[$roleMoneyName[$params['role']]];
        $params['supplyprice'] = EnPrice($this->params['supplyprice']);
        $params['enable'] = $this->params['enable'];
        $params['sort'] = $this->params['sort'];
//         $params['freight'] = !empty($this->params['freight']) ? $this->params['freight'] : 0;
        $params['addtime'] = $params['enabletime'] = getFormatNow();
//         $params['productunit'] = !empty($this->params['productunit']) ? $this->params['productunit'] : '';
        
        $status = Model::ins("RoleProduct")->insert($params);
        if($status) {
            $this->showSuccess("添加成功");
        }
        $this->showError("数据添加异常，请联系管理员");
    }
    
    /**
    * @user 礼品上下架
    * @param 
    * @author jeeluo
    * @version 1.0.1
    * @date 2017年5月9日下午4:47:29
    */
    public function enableGiftAction() {
        if(empty($this->getParam("id")) || empty($this->getParam("status"))) {
            $this->showError("请正确操作");
        }
        
        $role_product = Model::ins("RoleProduct")->getRow(array("id" => $this->getParam("id")));
        if(empty($role_product)) {
            $this->showError("商品不存在，请正确操作");
        }
        
        $updateData['enable'] = $this->getParam("status");
        $updateData['enabletime'] = getFormatNow();
        $status = Model::ins("RoleProduct")->modify($updateData, array("id" => $this->getParam("id")));
        
        $this->showSuccess("设置成功");
    }
    
    /**
    * @user 
    * @param 
    * @author jeeluo
    * @version 1.0.1
    * @date 2017年5月9日下午7:25:20
    */
    public function editGiftInfoAction() {
        
        if(empty($this->getParam("id"))) {
            $this->showError("请正确操作");
        }
        
        $role_product = Model::ins("RoleProduct")->getRow(array("id" => $this->getParam("id")));
        if(empty($role_product)) {
            $this->showError("商品不存在，请正确操作");
        }
        
        if($role_product['enable'] == 1) {
            $this->showError("礼品已上架，无法直接修改");
        }
        
        $action = "/Product/Index/updateGiftInfo?id=".$this->getParam("id");
        
        // 获取vip牛商列表
        $vipBusList = Model::ins("BusBusiness")->pageList(array("enable" => 1, "isvip" => 1), "id,businessname", "id desc");
        
        $busList = array();
        foreach ($vipBusList['list'] as $v) {
            $busList[$v['id']] = $v['businessname'];
        }
        
        $viewData = array(
            "productInfo" => $role_product,
            "actionUrl" => $action,
            "busList" => $busList,
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 修改礼品信息
    * @param 
    * @author jeeluo
    * @version 1.0.1
    * @date 2017年5月9日下午8:06:25
    */
    public function updateGiftInfoAction() {
        if(empty($this->getParam("id"))) {
            $this->showError("请正确操作");
        }
        
        if(empty($this->params['bus']) || empty($this->params['productname']) || empty($this->params['supplyprice']) || empty($this->params['enable']) || empty($this->params['sort'])) {
            $this->showError("请填写完整信息");
        }
        
        $params['businessid'] = $this->params['bus'];
        // 获取商家信息
        $busInfo = Model::ins("BusBusiness")->getRow(array("id" => $params['businessid'], "isvip" => 1, "enable" => 1), "businessname");
        if(empty($busInfo)) {
            $this->showError("牛商信息有误，请重新选择");
        }
        if(!empty($this->params['thumb'])) {
            $params['thumb'] = $this->params['thumb'];
        }
//         $role_money = Config::get("role_money");
//         $roleMoneyName = array('2' => 'bullPeoMoney', '3' => 'bullenMoney');
        
//         $params['role'] = $this->params['role'];
        $params['businessname'] = $busInfo['businessname'];
        $params['productname'] = $this->params['productname'];
//         $params['prouctprice'] = $role_money[$roleMoneyName[$params['role']]];
        $params['supplyprice'] = EnPrice($this->params['supplyprice']);
        $params['enable'] = $this->params['enable'];
        $params['sort'] = $this->params['sort'];
//         $params['freight'] = !empty($this->params['freight']) ? $this->params['freight'] : 0;
        $params['enabletime'] = getFormatNow();
        $params['productunit'] = !empty($this->params['productunit']) ? $this->params['productunit'] : '';
        
        Model::ins("RoleProduct")->modify($params, array("id" => $this->getParam("id")));
        $this->showSuccess("操作成功");
    }
}