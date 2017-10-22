<?php
/**
*
* 用户绑定现金流水表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: AmoFlowCusProfit.php 10319 2017-03-20 11:34:50Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class AmoFlowCusProfitDB extends MysqlDb {

	protected $_tableName = "amo_flow_cus_profit";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_flowid 	= null;

	protected $_customerid 	= null;

	protected $_role 	= null;

	protected $_flowtype 	= null;

	protected $_direction 	= null;

	protected $_amount 	= null;

	protected $_finalAmount 	= null;

	protected $_beforeamount 	= null;

	protected $_afteramount 	= null;

	protected $_remark 	= null;

	protected $_flowtime 	= null;

	protected $_userid 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入用户绑定现金流水表
	 */
	public function add() {
		! is_null($this->_flowid) && $data['flowid'] = $this->_flowid;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_role) && $data['role'] = $this->_role;
		! is_null($this->_flowtype) && $data['flowtype'] = $this->_flowtype;
		! is_null($this->_direction) && $data['direction'] = $this->_direction;
		! is_null($this->_amount) && $data['amount'] = $this->_amount;
		! is_null($this->_finalAmount) && $data['final_amount'] = $this->_finalAmount;
		! is_null($this->_beforeamount) && $data['beforeamount'] = $this->_beforeamount;
		! is_null($this->_afteramount) && $data['afteramount'] = $this->_afteramount;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_flowtime) && $data['flowtime'] = $this->_flowtime;
		! is_null($this->_userid) && $data['userid'] = $this->_userid;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新用户绑定现金流水表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_flowid) && $data['flowid'] = $this->_flowid;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_role) && $data['role'] = $this->_role;
		! is_null($this->_flowtype) && $data['flowtype'] = $this->_flowtype;
		! is_null($this->_direction) && $data['direction'] = $this->_direction;
		! is_null($this->_amount) && $data['amount'] = $this->_amount;
		! is_null($this->_finalAmount) && $data['final_amount'] = $this->_finalAmount;
		! is_null($this->_beforeamount) && $data['beforeamount'] = $this->_beforeamount;
		! is_null($this->_afteramount) && $data['afteramount'] = $this->_afteramount;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_flowtime) && $data['flowtime'] = $this->_flowtime;
		! is_null($this->_userid) && $data['userid'] = $this->_userid;
		return $this->update($data);
	}

	/**
	 * 删除用户绑定现金流水表
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
	 * 获取所有用户绑定现金流水表--分页
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
	 * 获取所有用户绑定现金流水表
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

				$this->_flowid 	= null;

				$this->_customerid 	= null;

				$this->_role 	= null;

				$this->_flowtype 	= null;

				$this->_direction 	= null;

				$this->_amount 	= null;

				$this->_finalAmount 	= null;

				$this->_beforeamount 	= null;

				$this->_afteramount 	= null;

				$this->_remark 	= null;

				$this->_flowtime 	= null;

				$this->_userid 	= null;

			}
}
?>