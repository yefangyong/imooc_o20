/*页面 全屏-添加*/
function o2o_edit(title,url){
    var index = layer.open({
        type: 2,
        title: title,
        content: url
    });
    layer.full(index);
}

/*添加或者编辑缩小的屏幕*/
function o2o_s_edit(title,url,w,h){
    layer_show(title,url,w,h);
}
/*-删除*/
function o2o_del(id,url){

    layer.confirm('确认要删除吗？',function(index){
        window.location.href=url;
    });
}

/*ajax异步排序功能*/
$('.listorder input').blur(function(){
    var id = $(this).attr('attr-id');
    var listorder = $(this).val();
    var postData = {
        'id':id,
        'listorder':listorder
    }
    var url = SCOPE.listorder_url;
    var jump_url = SCOPE.jump_url;
    $.post(url,postData,function(result){
        if(result.status == 1){
            return dialog.success(result.message,jump_url);
        }else if(result.status == 0){
            return dialog.error(result.message);
        }
    },"JSON");
});

/**
 * 删除操作采用了layer插件和异步加载方式
 */
$(' .o2o_delete').click(function(){
    var id = $(this).attr('attr-id');
    var message=$(this).attr('attr-message');
    var url = SCOPE.status_url;
    data={};
    data['id'] = id;
    data['status'] = -1;

    layer.open({
        type : 0,
        title : '是否提交？',
        btn : ['yes','no'],
        icon :3,
        closeBtn : 2,
        content : '是否确认'+message,
        scorllbar : true,
        yes: function(){
            todelete(url,data);
        },
    });
});

function todelete(){
    var url = SCOPE.status_url;
    //ajax的异步操作，交互性好
    $.post(
        url,data,function(s){
            if(s.status == 1){
                return dialog.success('操作成功','');
            }else{
                return dialog.error('操作失败');
            }
        },"JSON");
}

/*ajax异步更改状态功能*/
$('.o2o_yfycms .o2o_status').click(function(){
    var id = $(this).attr('attr-id');
    var status = $(this).attr('attr-status');
    var postData = {
        'id':id,
        'status':status
    }
    var url = SCOPE.status_url;
    var jump_url = SCOPE.jump_url;
    $.post(url,postData,function(result){
        if(result.status == 1){
            return dialog.success(result.message,jump_url);
        }else if(result.status == 0){
            return dialog.error(result.message);
        }
    },"JSON");
});

/*
 城市相关二级内容
 */
$('.cityId').change(function() {
    var city_id = $(this).val();
    //抛送请求
    var postData = {
        'id':city_id
    };
    var url = SCOPE.city_url;
    $.post(url,postData,function(result){
        if(result.status == 1) {
            //将信息填充到页面
            data = result.data;
            var city_html = '';
            $(data).each(function(i){
                city_html += "<option value='"+this.id+"'>"+this.name+"</option>"
            });
            $('.se_city_id').html(city_html);
        }else if(result.status == 0) {
            $('.se_city_id').html('');
        }
    },'JSON')
});


/*
 所属分类内容
 */
$('.categoryId').change(function() {
    var categoryId = $(this).val();
    //抛送请求
    var postData = {
        'id':categoryId
    };
    var url = SCOPE.category_url;
    $.post(url,postData,function(result){
        if(result.status == 1) {
            data = result.data;
            category_html = '';
            $(data).each(function(i){
                category_html += '<input name="se_category_id[]" type="checkbox" value="'+this.id+'"/>'+this.name;
                category_html += '<label for="">&nbsp;</label>';
            });
            $('.se_category_id').html(category_html);
        }else if(result.status == 0) {
            $('.se_category_id').html(' ');
        }
    },'JSON')
});

/**
 * 提交表单模块
 */
$('#o2o-yfycms-submit').click(function(){
    var data = $("#yfycms-form").serializeArray();
    postData = {};
    console.log(data);
    $(data).each(function(i){
        postData[this.name] = this.value;
    });
    console.log(postData);
    var  url = SCOPE.save_url;
    var jump_url = SCOPE.jump_url;
    $.post(url,postData,function(result){
        if(result.status == 1){
            return dialog.success(result.message,jump_url);
        }else if(result.status == 0){
            return dialog.error(result.message);
        }
    },"JSON");
});

/**
 * 时间插件js代码
 * @param flag
 */
function selecttime(flag){
    if(flag==1){
        var endTime = $("#countTimeend").val();
        if(endTime != ""){

            WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',maxDate:endTime})}else{
            WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})}
    }else{
        var startTime = $("#countTimestart").val();
        if(startTime != ""){
            WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',minDate:startTime})}else{
            WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})}
    }
}
