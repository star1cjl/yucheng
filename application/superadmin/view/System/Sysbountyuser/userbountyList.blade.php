<?php
	
	$title = $title;
	/*
    设置列表，表头
    "f_mobile"=>array("name"=>_("所属分会"),"sort"=>true,"width"=>"120","head_str"=>"width='30' nowrap","align"=>"left"),
    f_mobile 显示的字段
    name     表头名称
    sort     是否排序，默认false 不排序
    width    列宽度
    head_str 表头的其他信息
    data_str 数据其他信息
    align    设置内容项位置：left,center,right 默认是居中center
    nowrap   设置表格内容过长时是否进行缩进，默认false
    */
    $list_set = array(
        "mobile"=>array("name"=>"手机号码","align"=>"left"),
        "isget"=>array("name"=>"是否领取","align"=>"left"),
        "customerid"=>array("name"=>"注册用户id",'align'=>"left"),
        "amount"=>array("name"=>"领取金额（支付金额大于2时）",'align'=>"left"),
        "minamount"=>array("name"=>"领取金额（支付金额小于等于2时）",'align'=>"left"),
        "gettime"=>array("name"=>"领取时间",'align'=>"left"),
        "addtime"=>array("name"=>"添加时间",'align'=>"left"),
        // "act"=>array("name"=>"操作","width"=>"200"),
    );

    //临时变量
    $temp_list = array();
    //系统三级菜单

   

  
   	//$enable_arr = Html::html("color",array("value"=>Default_Model_DbTable_SysMenu::$enable_arr,"color"=>array("0"=>"red")));   
   	foreach($pagelist as $key=>$row){
       
   	}
    //帮助说明内容
    //$helpstr = "暂无帮助内容";
    //$exportflag = false; //不导出
    //
    $search = array(
        "mobile"=>array("type"=>"text","name"=>"手机号码","class"=>"width250"),
    );
 	$button = array(
        "bt1"=>array("type"=>"newpage","name"=>"button","value"=>"查看平台奖励金","_width"=>"500","_height"=>"300","_title"=>'查看平台奖励金',"_url"=>"/System/Sysbountyuser/mallbounty"),

 		);
   
?> 
<?php if ($full_page){?>
<!---头部-->
{include file="Pub/header" /}
{include file="Pub/pubHead" /}
<!---头部-->
            <!---搜索条件-->
            {include file="Pub/pubSearch" /}
            <!---搜索条件-->
            
            <!---这里可以写操作按钮等-->
           
                  
            <!---这里可以写操作按钮等-->
<?php }?>
            
            <!---列表+分页-->       
            {include file="Pub/pubList" /}
            <!---列表+分页-->
<?php if ($full_page){?>
<!---尾部-->
{include file="Pub/pubTail" /}
{include file="Pub/footer" /}
<!---尾部-->
<?php }?>
<!--这里可以写js-->