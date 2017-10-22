<?php
/**
*
* 
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: MallBanner.php 10319 2017-03-03 15:20:31Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class OpenIntegralImportDB extends MysqlDb {

	protected $_tableName = "open_integral_import";

	protected $_primary = "id";

	protected $_totalPage = null;

	protected $_db = "nnh_open";

	protected $_table_prefix = "";

}
?>