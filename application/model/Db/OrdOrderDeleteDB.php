<?php
/**
*
* 退款订单日志表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: OrdOrderDelete.php 10319 2017-03-09 15:03:13Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class OrdOrderDeleteDB extends MysqlDb {

	protected $_tableName = "ord_order_delete";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_customerid 	= null;

	protected $_custName 	= null;

	protected $_actualfreight 	= null;

	protected $_productcount 	= null;

	protected $_productamount 	= null;

	protected $_bullamount 	= null;

	protected $_totalamount 	= null;

	protected $_addtime 	= null;

	protected $_orderstatus 	= null;

	protected $_businessid 	= null;

	protected $_businessname 	= null;

	protected $_evaluate 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入退款订单日志表
	 */
	public function add() {
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_custName) && $data['cust_name'] = $this->_custName;
		! is_null($this->_actualfreight) && $data['actualfreight'] = $this->_actualfreight;
		! is_null($this->_productcount) && $data['productcount'] = $this->_productcount;
		! is_null($this->_productamount) && $data['productamount'] = $this->_productamount;
		! is_null($this->_bullamount) && $data['bullamount'] = $this->_bullamount;
		! is_null($this->_totalamount) && $data['totalamount'] = $this->_totalamount;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_orderstatus) && $data['orderstatus'] = $this->_orderstatus;
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_businessname) && $data['businessname'] = $this->_businessname;
		! is_null($this->_evaluate) && $data['evaluate'] = $this->_evaluate;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新退款订单日志表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_custName) && $data['cust_name'] = $this->_custName;
		! is_null($this->_actualfreight) && $data['actualfreight'] = $this->_actualfreight;
		! is_null($this->_productcount) && $data['productcount'] = $this->_productcount;
		! is_null($this->_productamount) && $data['productamount'] = $this->_productamount;
		! is_null($this->_bullamount) && $data['bullamount'] = $this->_bullamount;
		! is_null($this->_totalamount) && $data['totalamount'] = $this->_totalamount;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_orderstatus) && $data['orderstatus'] = $this->_orderstatus;
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_businessname) && $data['businessname'] = $this->_businessname;
		! is_null($this->_evaluate) && $data['evaluate'] = $this->_evaluate;
		return $this->update($data);
	}

	/**
	 * 删除退款订单日志表
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
	 * 获取所有退款订单日志表--分页
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
	 * 获取所有退款订单日志表
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

				$this->_orderno 	= null;

				$this->_customerid 	= null;

				$this->_custName 	= null;

				$this->_actualfreight 	= null;

				$this->_productcount 	= null;

				$this->_productamount 	= null;

				$this->_bullamount 	= null;

				$this->_totalamount 	= null;

				$this->_addtime 	= null;

				$this->_orderstatus 	= null;

				$this->_businessid 	= null;

				$this->_businessname 	= null;

				$this->_evaluate 	= null;

			}
}
?>