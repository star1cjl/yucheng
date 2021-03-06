<?php
/**
* 牛掌柜推荐信息表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-14 11:47:22Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class RoleRecoStoModel {

	protected $_id 	= null;
	
	protected $_cusRoleId = null;

	protected $_stoTypeId 	= null;

	protected $_stoName 	= null;

	protected $_mobile 	= null;

	protected $_discount 	= null;

	protected $_stoHourBegin 	= null;

	protected $_stoHourEnd 	= null;

	protected $_serviceType 	= null;
	
	protected $_delivery = null;

	protected $_nearbyVillage 	= null;

	protected $_stoMobile 	= null;

	protected $_area 	= null;
	
	protected $_areaCode = null;

	protected $_longitude 	= null;

	protected $_latitude 	= null;

	protected $_metroId 	= null;
	
	protected $_busDistrict = null;
	
	protected $_idnumber = null;

	protected $_stoImage 	= null;

	protected $_licenceImage 	= null;

	protected $_idnumberImage 	= null;

	protected $_description 	= null;

	protected $_examinetime 	= null;
	
	protected $_remark = null;

	protected $_status 	= null;
	
	protected $_disabled = null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('RoleRecoSto');
	}

	/**
	 *
	 * 添加牛掌柜推荐信息表
	 */
// 	public function add() {
// 		$this->_modelObj->_stoTypeId  		= $this->_stoTypeId;
// 		$this->_modelObj->_stoName  		= $this->_stoName;
// 		$this->_modelObj->_mobile  		= $this->_mobile;
// 		$this->_modelObj->_discount  		= $this->_discount;
// 		$this->_modelObj->_stoHourBegin  		= $this->_stoHourBegin;
// 		$this->_modelObj->_stoHourEnd  		= $this->_stoHourEnd;
// 		$this->_modelObj->_serviceType  		= $this->_serviceType;
// 		$this->_modelObj->_nearbyVillage  		= $this->_nearbyVillage;
// 		$this->_modelObj->_stoMobile  		= $this->_stoMobile;
// 		$this->_modelObj->_area  		= $this->_area;
// 		$this->_modelObj->_longitude  		= $this->_longitude;
// 		$this->_modelObj->_latitude  		= $this->_latitude;
// 		$this->_modelObj->_metroId  		= $this->_metroId;
// 		$this->_modelObj->_idnumber       = $this->_idnumber;
// 		$this->_modelObj->_stoImage  		= $this->_stoImage;
// 		$this->_modelObj->_licenceImage  		= $this->_licenceImage;
// 		$this->_modelObj->_idnumberImage  		= $this->_idnumberImage;
// 		$this->_modelObj->_description  		= $this->_description;
// 		$this->_modelObj->_examinetime  		= $this->_examinetime;
// 		$this->_modelObj->_status  		= $this->_status;
// 		$this->_modelObj->_addtime  		= $this->_addtime;
// 		return $this->_modelObj->add();
// 	}

	public function add($data) {
	    return $this->insert($data);
	}
	
	public function insert($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新牛掌柜推荐信息表
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_stoTypeId  = $this->_stoTypeId;
// 		$this->_modelObj->_stoName  = $this->_stoName;
// 		$this->_modelObj->_mobile  = $this->_mobile;
// 		$this->_modelObj->_discount  = $this->_discount;
// 		$this->_modelObj->_stoHourBegin  = $this->_stoHourBegin;
// 		$this->_modelObj->_stoHourEnd  = $this->_stoHourEnd;
// 		$this->_modelObj->_serviceType  = $this->_serviceType;
// 		$this->_modelObj->_nearbyVillage  = $this->_nearbyVillage;
// 		$this->_modelObj->_stoMobile  = $this->_stoMobile;
// 		$this->_modelObj->_area  = $this->_area;
// 		$this->_modelObj->_longitude  = $this->_longitude;
// 		$this->_modelObj->_latitude  = $this->_latitude;
// 		$this->_modelObj->_metroId  = $this->_metroId;
// 		$this->_modelObj->_idnumber       = $this->_idnumber;
// 		$this->_modelObj->_stoImage  = $this->_stoImage;
// 		$this->_modelObj->_licenceImage  = $this->_licenceImage;
// 		$this->_modelObj->_idnumberImage  = $this->_idnumberImage;
// 		$this->_modelObj->_description  = $this->_description;
// 		$this->_modelObj->_examinetime  = $this->_examinetime;
// 		$this->_modelObj->_status  = $this->_status;
// 		$this->_modelObj->_addtime  = $this->_addtime;
// 		return $this->_modelObj->modify($id);
// 	}

	public function modify($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}

	/**
	 *
	 * 详细
	 */
	public function getById($id = null) {
		$this->_id = is_null($id)? $this->_id : $id;
		$this->_dataInfo = $this->_modelObj->getById($this->_id);
		return $this->_dataInfo;
	}
	
	/*
	 * 获取单条数据
	 * $where 可以是字符串 也可以是数组
	 */
	public function getInfoRow($where, $fields='*', $order='', $otherstr='') {
	    return $this->getRow($where, $fields, $order, $otherstr);
	}
	
	public function getRow($where, $fields='*', $order='', $otherstr='') {
	    return $this->_modelObj->getRow($where, $fields, $order, $otherstr);
	}

	/**
	 *
	 * 牛掌柜推荐信息表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}
	
	/*
	 * 分页列表
	 * $flag = 0 表示不返回总条数
	 */
	public function pageList($where,$field='*',$order='',$flag=1){
	    return $this->_modelObj->pageList($where,$field,$order,$flag);
	}

	/**
	 * 获取所有牛掌柜推荐信息表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除牛掌柜推荐信息表
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}


	/**
	 * 设置主键id
	 *
	 */
	public function setId($id) {
		$this->_id = $id;
		return $this;
	}
	
	public function setCusRoleId($cusRoleId) {
	    $this->_cusRoleId = $cusRoleId;
	    return $this;
	}

	/**
	 * 设置sto_type 类型id
	 *
	 */
	public function setStoTypeId($stoTypeId) {
		$this->_stoTypeId = $stoTypeId;
		return $this;
	}

	/**
	 * 设置商家名称
	 *
	 */
	public function setStoName($stoName) {
		$this->_stoName = $stoName;
		return $this;
	}

	/**
	 * 设置商家注册手机
	 *
	 */
	public function setMobile($mobile) {
		$this->_mobile = $mobile;
		return $this;
	}

	/**
	 * 设置结算折扣
	 *
	 */
	public function setDiscount($discount) {
		$this->_discount = $discount;
		return $this;
	}

	/**
	 * 设置营业时间开始
	 *
	 */
	public function setStoHourBegin($stoHourBegin) {
		$this->_stoHourBegin = $stoHourBegin;
		return $this;
	}

	/**
	 * 设置营业时间结束
	 *
	 */
	public function setStoHourEnd($stoHourEnd) {
		$this->_stoHourEnd = $stoHourEnd;
		return $this;
	}

	/**
	 * 设置商家服务(1为免费wifi，2为免费停车, 3为送货上门) 用,进行拼接
	 *
	 */
	public function setServiceType($serviceType) {
		$this->_serviceType = $serviceType;
		return $this;
	}
	
	public function setDelivery($delivery) {
	    $this->_delivery = $delivery;
	    return $this;
	}

	/**
	 * 设置附近楼宇或小区名
	 *
	 */
	public function setNearbyVillage($nearbyVillage) {
		$this->_nearbyVillage = $nearbyVillage;
		return $this;
	}

	/**
	 * 设置商家服务电话(用,进行拼接)
	 *
	 */
	public function setStoMobile($stoMobile) {
		$this->_stoMobile = $stoMobile;
		return $this;
	}

	/**
	 * 设置营业地址
	 *
	 */
	public function setArea($area) {
		$this->_area = $area;
		return $this;
	}
	
	public function setAreaCode($areaCode) {
	    $this->_areaCode = $areaCode;
	    return $this;
	}
	
	public function setAddress($address) {
	    $this->_address = $address;
	    return $this;
	}

	/**
	 * 设置商家经度
	 *
	 */
	public function setLongitude($longitude) {
		$this->_longitude = $longitude;
		return $this;
	}

	/**
	 * 设置商家纬度
	 *
	 */
	public function setLatitude($latitude) {
		$this->_latitude = $latitude;
		return $this;
	}

	/**
	 * 设置地铁id
	 *
	 */
	public function setMetroId($metroId) {
		$this->_metroId = $metroId;
		return $this;
	}
	
	public function setBusDistrict($busDistrict) {
	    $this->_busDistrict = $busDistrict;
	    return $this;
	}
	
	public function setIdnumber($idnumber) {
	    $this->_idnumber = $idnumber;
	    return $this;
	}

	/**
	 * 设置店铺主图(用,进行拼接)
	 *
	 */
	public function setStoImage($stoImage) {
		$this->_stoImage = $stoImage;
		return $this;
	}

	/**
	 * 设置营业执照图片(用,进行拼接)
	 *
	 */
	public function setLicenceImage($licenceImage) {
		$this->_licenceImage = $licenceImage;
		return $this;
	}

	/**
	 * 设置身份证图片(用,进行拼接)
	 *
	 */
	public function setIdnumberImage($idnumberImage) {
		$this->_idnumberImage = $idnumberImage;
		return $this;
	}

	/**
	 * 设置描述(购买须知)
	 *
	 */
	public function setDescription($description) {
		$this->_description = $description;
		return $this;
	}

	/**
	 * 设置审核时间
	 *
	 */
	public function setExaminetime($examinetime) {
		$this->_examinetime = $examinetime;
		return $this;
	}
	
	public function setRemark($remark) {
	    $this->_remark = $remark;
	    return $this;
	}

	/**
	 * 设置审核状态(1为待审核，2为审核通过，3为审核失败)
	 *
	 */
	public function setStatus($status) {
		$this->_status = $status;
		return $this;
	}
	
	public function setDisabled($disabled) {
	    $this->_disabled = $disabled;
	    return $this;
	}

	/**
	 * 设置添加时间
	 *
	 */
	public function setAddtime($addtime) {
		$this->_addtime = $addtime;
		return $this;
	}

	public static function getModelObj() {
		return new RoleRecoStoDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>