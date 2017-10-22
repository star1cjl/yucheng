<?php
/**
*
* 微信app支付流水表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: AmoPayWeixinapp.php 10319 2017-03-20 11:59:35Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class AmoFlowPayWeixinappDB extends MysqlDb {

	protected $_tableName = "amo_flow_pay_weixinapp";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_flowid 	= null;

	protected $_amount 	= null;

	protected $_flowtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入微信app支付流水表
	 */
	public function add() {
		! is_null($this->_flowid) && $data['flowid'] = $this->_flowid;
		! is_null($this->_amount) && $data['amount'] = $this->_amount;
		! is_null($this->_flowtime) && $data['flowtime'] = $this->_flowtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新微信app支付流水表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_flowid) && $data['flowid'] = $this->_flowid;
		! is_null($this->_amount) && $data['amount'] = $this->_amount;
		! is_null($this->_flowtime) && $data['flowtime'] = $this->_flowtime;
		return $this->update($data);
	}

	/**
	 * 删除微信app支付流水表
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
	 * 获取所有微信app支付流水表--分页
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
	 * 获取所有微信app支付流水表
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

				$this->_amount 	= null;

				$this->_flowtime 	= null;

			}
}
?>