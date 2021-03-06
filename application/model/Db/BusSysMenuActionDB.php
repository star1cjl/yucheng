<?php
/**
*
* 菜单操作按钮类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: BusSysMenuAction.php 10319 2017-03-13 15:00:25Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class BusSysMenuActionDB extends MysqlDb {

	protected $_tableName = "bus_sys_menu_action";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_menuid 	= null;

	protected $_actionname 	= null;

	protected $_rightvalue 	= null;

	protected $_createby 	= null;

	protected $_createtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入菜单操作按钮
	 */
	public function add() {
		! is_null($this->_menuid) && $data['menuid'] = $this->_menuid;
		! is_null($this->_actionname) && $data['actionname'] = $this->_actionname;
		! is_null($this->_rightvalue) && $data['rightvalue'] = $this->_rightvalue;
		! is_null($this->_createby) && $data['createby'] = $this->_createby;
		! is_null($this->_createtime) && $data['createtime'] = $this->_createtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新菜单操作按钮
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_menuid) && $data['menuid'] = $this->_menuid;
		! is_null($this->_actionname) && $data['actionname'] = $this->_actionname;
		! is_null($this->_rightvalue) && $data['rightvalue'] = $this->_rightvalue;
		! is_null($this->_createby) && $data['createby'] = $this->_createby;
		! is_null($this->_createtime) && $data['createtime'] = $this->_createtime;
		return $this->update($data);
	}

	/**
	 * 删除菜单操作按钮
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
	 * 获取所有菜单操作按钮--分页
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
	 * 获取所有菜单操作按钮
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

				$this->_menuid 	= null;

				$this->_actionname 	= null;

				$this->_rightvalue 	= null;

				$this->_createby 	= null;

				$this->_createtime 	= null;

			}
}
?>