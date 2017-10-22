<?php
/**
*
* 角色申请记录表(牛人 牛创客)类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: RoleApplyLog.php 10319 2017-03-14 11:55:17Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class RoleApplyLogDB extends MysqlDb {

	protected $_tableName = "role_apply_log";

	protected $_primary = "id";

	protected $_id 	= null;
	
	protected $_customerid = null;
	
	protected $_roleType = null;

	protected $_realname 	= null;

	protected $_idnumber 	= null;

	protected $_mobile 	= null;

	protected $_area 	= null;

	protected $_address 	= null;

	protected $_areaCode 	= null;

	protected $_instrodcermobile 	= null;

	protected $_addtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入角色申请记录表(牛人 牛创客)
	 */
	public function add() {
	    ! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
	    ! is_null($this->_roleType) && $data['role_type'] = $this->_roleType;
		! is_null($this->_realname) && $data['realname'] = $this->_realname;
		! is_null($this->_idnumber) && $data['idnumber'] = $this->_idnumber;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_areaCode) && $data['area_code'] = $this->_areaCode;
		! is_null($this->_instrodcermobile) && $data['instrodcermobile'] = $this->_instrodcermobile;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新角色申请记录表(牛人 牛创客)
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_roleType) && $data['role_type'] = $this->_roleType;
		! is_null($this->_realname) && $data['realname'] = $this->_realname;
		! is_null($this->_idnumber) && $data['idnumber'] = $this->_idnumber;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_areaCode) && $data['area_code'] = $this->_areaCode;
		! is_null($this->_instrodcermobile) && $data['instrodcermobile'] = $this->_instrodcermobile;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		return $this->update($data);
	}

	/**
	 * 删除角色申请记录表(牛人 牛创客)
	 */
	public function del($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		return $this->delete($data);
	}

	/**
	 *
	 * 根据ID获取一行
	 * @param interger $id
	 */
	public function getById($id) {
		$this->_id = is_null($id)? $this->_id : $id;
		return $this->getRow(array($this->_primary => $this->_id));
	}

	/**
	 *
	 * 获取所有角色申请记录表(牛人 牛创客)--分页
	 * @param interger $status
	 */
	public function getAllForPage($page = 0, $pagesize = 20) {
		$where = null; ## TODO
		if (! is_null($where)) {
			$this->where($where);
		}
		$this->_totalPage = $this->count();
		return $this->page($page, $pagesize)->order("{$this->_primary} desc")->select();
	}

	/**
	 * 获取所有角色申请记录表(牛人 牛创客)
	 * @return Ambigous 
	 */
	public function getAll() {
		$where = null; ## TODO
		if (! is_null($where)) {
			$this->where($where);
		}
		return $this->select();
	}
	
	public function cleanAll() {
				$this->_id 	= null;
				
				$this->_customerid = null;
				
				$this->_roleType = null;

				$this->_realname 	= null;

				$this->_idnumber 	= null;

				$this->_mobile 	= null;

				$this->_area 	= null;

				$this->_address 	= null;

				$this->_areaCode 	= null;

				$this->_instrodcermobile 	= null;

				$this->_addtime 	= null;

			}
}
?>