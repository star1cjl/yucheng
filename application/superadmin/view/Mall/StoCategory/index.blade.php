<?php
use app\lib\Db;

    $title = $title;
    /*
    设置列表，表头
    "mobile"=>array("name"=>"所属分会"),"sort"=>true,"width"=>"120","head_str"=>"width='30' nowrap","align"=>"left"),
    mobile 显示的字段
    name     表头名称
    sort     是否排序，默认false 不排序
    width    列宽度
    head_str 表头的其他信息
    data_str 数据其他信息
    align    设置内容项位置：left,center,right 默认是居中center
    nowrap   设置表格内容过长时是否进行缩进，默认false
    */
    $list_set = array(
        "checkbox"=>array("name"=>"选择"),
        "_categoryname"=>array("name"=>"分类名称",),
        "category_icon"=>array("name"=>"分类图标","thumb"=>["width"=>50,"height"=>50]),
         "sort"=>array("name"=>"排序","data_sort"=>true,"_url"=>"/Mall/StoCategory/sort",'sort'=>true),
        "act"=>array("name"=>"操作"),
    );

    /*
    前端显示数据处理
    也可以在controller中处理
    */
    foreach($pagelist as $key=>$row){
        
        $pagelist[$key]['businessid'] = Db::Model("BusBusiness")->getRow(['id'=>$row['businessid']],'businessname')['businessname'];

        $pagelist[$key]['act'][]= array("type"=>"popup_listpage","name"=>"button","value"=>"编辑","_width"=>"500","_height"=>"400","_title"=>"编辑","_url"=>"/Mall/StoCategory/editStoCategory?id=".Encode($row['id']));

        if($row['parentid'] == 0)
             $pagelist[$key]['act'][]= array("type"=>"popup_listpage","name"=>"button","value"=>"添加子类","_width"=>"500","_height"=>"400","_title"=>"添加子类","_url"=>"/Mall/StoCategory/addStoCategory?topid=".Encode($row['id']));
        $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"删除","_width"=>"500","_height"=>"200","_title"=>'是否确定删除该记录?',"_url"=>"/Mall/StoCategory/delStoCategory?ids=".Encode($row['id']));

        $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
    }

    /*
    设置搜索项
    "keyword"=>array("type"=>"text","name"=>"商品名称/关键字","value"=>$this->_request->getParam("keyword")),
    "keyword" 表示搜索项的名称
    "type"    类型：text | select | time | times 
    "value"   初始化值，之后的值自动获取到，但是controller层需要把数据提交过来
    "name"    中文说明
    "option"  设置select类型的下拉选项
    "dateFmt" 1-年月日（默认） 2-年月日时分秒 3-年月 4-年  用于时间控件
    
    说明：times类型下，表单的命名自动在前面加"start_"和"end_"   
          显示的值  自动获取 
    */
    $search = array(
       // "category_name"=>array("type"=>"text","name"=>"分类名称/关键字","value"=>"")
    );

    //自动生成按钮
    $button = array(
            "bt1"=>array("type"=>"popup_listpage","name"=>"button","value"=>"添加分类","_width"=>"500","_height"=>"300","_title"=>'添加分类',"_url"=>"/Mall/StoCategory/addStoCategory"),

            // "bt2"=>array("type"=>"confirm_all","name"=>"button","value"=>"批量删除","_width"=>"500","_height"=>"200","_title"=>'删除选中项?',"_url"=>"/Mall/StoCategory/delCategory?rel=delete")
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