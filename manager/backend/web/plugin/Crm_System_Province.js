var globalExamLevels = ['全部','幼儿园','小学','初中','高中'];
var setting = {
	view: {
		addHoverDom: addHoverDom,
		removeHoverDom: removeHoverDom,
		selectedMulti: false,
		showIcon:true,
		dblClickExpand:dblClickExpand,
		showTitle:true,
	},
	edit: {
		enable: true,
		editNameSelectAll: true
	},
	data: {
		key: {
			title: "tips"
		},
		simpleData: {
			enable: true,
		}
		
	},
	callback: {
		beforeEditName: beforeEditName,
		beforeRemove: beforeRemove,
		beforeRename: beforeRename,
		beforeDrop:beforeDrop,
		onRemove: onRemove,
		onRename: onRename,
		onClick: zTreeOnClick,
		onDrop: onDrop,
		
	}
};
var setting2 = {
	check: {
		enable: true,
		chkStyle: "checkbox",
		chkboxType: {"Y" : "", "N" : ""}
	},
	edit: {
		enable: true,
		showRemoveBtn: false,
		showRenameBtn: false,
	},
	data: {
		simpleData: {
			enable: true,
		}
	}
};
var drop_id;
var drop_pId;
function beforeDrop(treeId, treeNodes, targetNode, moveType, isCopy) {
	drop_id = treeNodes[0].id;
 	return !(targetNode == null || (moveType != "inner" && !targetNode.parentTId));
}
function onDrop(event, treeId, treeNodes, targetNode, moveType) {
    drop_pId = targetNode.id;
	console.log("子级ID:"+drop_id);
	console.log("父级ID:"+drop_pId);
	//base.loading();
	$.post("./droptree",{"bs":"tb_dc_education_practice","id":drop_id,"pid":drop_pId},function(edm){
		if(edm == true){
            alert("节点移动成功！");
			//base.alert("节点移动成功！");
			//base.loadingend();
		}else {
			console.log("节点移动失败！");
			//base.loadingend();
		}
	});
	
};

function dblClickExpand(treeId, treeNode) {
	return treeNode.level > 0;
}
var log, className = "dark";
function beforeEditName(treeId, treeNode) {
	className = (className === "dark" ? "":"dark");
	showLog("[ "+getTime()+" beforeEditName ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	zTree.selectNode(treeNode);
}
function beforeRename(treeId, treeNode, newName) {
	className = (className === "dark" ? "":"dark");
	showLog("[ "+getTime()+" beforeRename ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
	if (newName.length == 0) {
		alert("名称不能为空.");
		var zTree = $.fn.zTree.getZTreeObj("treeDemo");
		setTimeout(function(){zTree.editName(treeNode)}, 10);
		return false;
	}
	return true;
}
function beforeRemove(treeId, treeNode) {
	className = (className === "dark" ? "":"dark");
	showLog("[ "+getTime()+" beforeRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	zTree.selectNode(treeNode);
	
	return confirm("确定要删除：［" + treeNode.name + "］吗？");
	
}
function onRemove(e, treeId, treeNode) {
	showLog("[ "+getTime()+" onRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
	console.log("删除ID："+treeNode.id);
	$.post("/outline/default/deleteknowdage",{
		"id":treeNode.id,
		"bs":"tb_dc_education_practice",// 删除分省大纲表
	},function(edm){
		if(edm == "false"){
			console.log("删除失败!");
		}else {
			console.log("删除成功！");
		}
	});
}

function onRename(e, treeId, treeNode) {
	//base.loading();
	showLog("[ "+getTime()+" onRename ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
	var edu_id = ""; // 大纲ID数组
	var orders = $("#orders").val();
    var examlevel = 0;
    $("#examlevel input").each(function(){
    		if($(this).is(":checked")){
    			examlevel=$(this).val();
    		}
    });
    
	if(treeNode.id <= 0 || treeNode.id == undefined){
		var provinceid = $("#Provinceset_input").val(); // 省份ID
		var treeObj=$.fn.zTree.getZTreeObj("treeDemo2"),nodes=treeObj.getCheckedNodes(true),v="";
	    for(var i=0;i<nodes.length;i++){
	    		v+=nodes[i].name + ",";
	    		edu_id += nodes[i].id+",";
	    }
		// 新增分省大纲节点
		$.post("./insertknowdage_fensheng",
		{
			"orders":orders,
			"exam_level":examlevel,
			"pid":treeNode.pId,
			"name":treeNode.name,
			"provinceid":provinceid,
			"edu_id":""
		},function(edm){
                console.log(edm);
			if(edm == "false"){
				console.log("新增失败!");
				//base.loadingend();
			}else{
				//console.log("新增成功! 关联id："+edu_id);
			    treeNode.id = edm[0];
			    treeNode.dataId = edm[1];
			    treeNode.examlevel = examlevel;
			    treeNode.orders = orders;
			    treeNode.tips=globalExamLevels[examlevel] + "-" + treeNode.name;
			    //console.log(treeNode.id);
			    //base.loadingend();
			}
		});
	}else {
		var provinceid = $("#Provinceset_input").val(); // 省份ID
		var treeObj=$.fn.zTree.getZTreeObj("treeDemo2"),nodes=treeObj.getCheckedNodes(true),v="";
	    for(var i=0;i<nodes.length;i++){
	    		v+=nodes[i].name + ",";
	    		edu_id += nodes[i].id+",";
	    }
		// 修改分省大纲节点
		$.post("./updateknowdage_fensheng",
		{
			"orders":orders,
			"examlevel":examlevel,
		    "id":treeNode.id,
		    "name":treeNode.name,
		    "edu_id":edu_id,
		},function(edm){
			if(edm == "false"){
				console.log("修改失败!");
				//base.loadingend();
			}else {
				console.log("修改成功! 关联id："+edu_id);
			    treeNode.examlevel = examlevel;
			    treeNode.orders = orders;
			    treeNode.tips=globalExamLevels[examlevel] + "-" + treeNode.name;
			    
			    //base.loadingend();
			}
		});
	}
}
function showLog(str) {
	if (!log) log = $("#log");
	log.append("<li class='"+className+"'>"+str+"</li>");
	if(log.children("li").length > 8) {
		log.get(0).removeChild(log.children("li")[0]);
	}
}
function getTime() {
	var now= new Date(),
	h=now.getHours(),
	m=now.getMinutes(),
	s=now.getSeconds(),
	ms=now.getMilliseconds();
	return (h+":"+m+":"+s+ " " +ms);
}
var newCount = 1;
function addHoverDom(treeId, treeNode) {
	
	if (treeNode.id > 0){
		var sObj = $("#" + treeNode.tId + "_span");
		if (treeNode.editNameFlag || $("#addBtn_"+treeNode.id).length>0) return;
		var addStr = "<span class='button add' id='addBtn_" + treeNode.id
			+ "' title='add node' onfocus='this.blur();'></span>";
		sObj.after(addStr);
		var btn = $("#addBtn_"+treeNode.id);
		$("#addBtn_"+treeNode.id).unbind("click");
		if(btn){
			btn.bind("click", function(){
				//console.log("新增");
					var zTreeD2 = $.fn.zTree.getZTreeObj("treeDemo2");
					var nodesD2 = zTreeD2.getCheckedNodes(true);
					for (var i=0,l=nodesD2.length; i < l; i++) {
						zTreeD2.checkNode(nodesD2[i], false, false);
					}
				var zTree = $.fn.zTree.getZTreeObj("treeDemo");
				var aNodes = zTree.addNodes(treeNode, {id:0, pId:treeNode.id, dataId:"0",name:"" + (newCount++),orders:orders,examlevel:examlevel,tips:globalExamLevels[examlevel] + "-" + newCount });
				zTree.selectNode(aNodes[0]);
				zTree.editName(aNodes[0]);
				return false;
			});
		}
	}
};
function removeHoverDom(treeId,treeNode){
	if (treeNode.id > 0){
		$("#addBtn_"+treeNode.id).unbind().remove();
	}
};
function selectAll(){
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	zTree.setting.edit.editNameSelectAll =  $("#selectAll").attr("checked");
}
/*
 zTree  点击事件
 * */
var onclickid="";
function zTreeOnClick(e, treeId, treeNode){
	//base.loading();
	onclickid = treeNode.id;
	var zTree = $.fn.zTree.getZTreeObj("treeDemo2");
	$.post("./crm_dagang_select",{"id":treeNode.id},function(edm){
		if(edm != null && edm!= ""){
			var arr = edm.edu_id.split(',');
			var nodes = zTree.getCheckedNodes(true);
			for (var i=0,l=nodes.length; i < l; i++) {
				zTree.checkNode(nodes[i], false, true);
			}
			for(var i = 0;i < arr.length; i++){
				var node = zTree.getNodeByParam("id",arr[i]);
				if(node != null){
					node.checked = true;
			 		zTree.checkNode(node, true, true);
				}
			}
			//console.log("点击事件，与其关联的ID："+treeNode.edu_id);
			if(treeNode.edu_id != ""){
				for(var i = 0;i < arr.length; i++){
					var node = zTree.getNodeByParam("id",arr[i]);
					if(node != null){
						node.checked = true;
				 		zTree.checkNode(node, true, true);
					}
				}
			}
		}else {
			var nodes = zTree.getCheckedNodes(true);
			for (var i=0,l=nodes.length; i < l; i++) {
				zTree.checkNode(nodes[i], false, true);
			}
		}
		// 分省大纲，input修改框赋值
		$("#provincename").val(treeNode.name); // name
		$("#orders").val(treeNode.orders); // orders
		// 学段
		$("#examlevel input").each(function(){
			if(treeNode.examlevel == $(this).val()){
				$(this).attr("checked",true);
			}
		});
                $("#improtant input").each(function(){
			if(edm.improtant == $(this).val()){
				$(this).attr("checked",true);
			}
		});
		//base.loadingend();
	});
	
	
}
function Provinceset_ok(id,obj){
	//base.loading();
	$("#Provinceset_input").val(id);
	$("#Provinceset").hide();
	// 加载分省大纲
	var zNodes;
	if(id != ""){
        my_loading.show();
		$.post("./allknowdage_fensheng",{"provinceid":id},function(edm){
            my_loading.hide();
            //console.log(edm);
			zNodes =(new Function("","return "+edm))();
			//console.log(zNodes);
			$.fn.zTree.init($("#treeDemo"), setting, zNodes);
			//base.loadingend();
		});
		// 加载标准大纲
		$.post("/outline/default/allknowdage",{"exam_level":"100","exam_type":"100"},function(edm){
			var zNodes =(new Function("","return "+edm))();
			$.fn.zTree.init($("#treeDemo2"),setting2, zNodes);
			//base.loadingend();
		});
	}else {
		//base.alert("选择对应省份！");
        alert("选择对应省份！");
	}
	//console.log();
	$("#SystemzTreeTitle").html($(obj).html())
}
$(document).ready(function(){
    //alert(111);
	//base.loading();
	$.post("./showfensheng",function(msg){
		//base.alert("Provinceset");
        //alert("Provinceset");
		var ProvinceHtml = "";
		$.each(msg, function(x,y) {
			ProvinceHtml+="<label onclick='Provinceset_ok("+x+",this)'>"+y+"</label>";
		});
		$("#Provinceset .alert_cont").append(ProvinceHtml);
	},"json");
	//保存
	$("#zTreeEditSave").click(function(){
		//base.loading();
		// 获取当前勾选的checkbox
		var treeObj = $.fn.zTree.getZTreeObj("treeDemo2");
		var nodes = treeObj.getChangeCheckedNodes();
		
		var edu_ids = "";
		$.each(nodes,function(key,val){
            if($.isPlainObject(val) || $.isArray(val)){
                edu_ids += val.id+",";
            }
        });
        var name = $("#provincename").val(); // 获取名称
        var orders = $("#orders").val();// 获取序号
	    var examlevel;// 获取学段
            var improtant;//获取重要程度
	    $("#examlevel input").each(function(){
	    		if($(this).is(":checked")){
	    			examlevel=$(this).val();
	    		}
	    });
            $("#improtant input").each(function(){
	    		if($(this).is(":checked")){
	    			improtant=$(this).val();
	    		}
	    });
        edu_ids=edu_ids.substring(edu_ids.length-1,0);
		//  获取当前选中的checkbox
		$.post("./crm_dagang_save",{"id":onclickid,"edu_id":edu_ids,"name":name,"orders":orders,"exam_level":examlevel,"improtant":improtant},function(edm){
			if(edm == true){
				var zTree = $.fn.zTree.getZTreeObj("treeDemo");
                                var nodes = zTree.getSelectedNodes();
                                nodes[0].name = $('#provincename').val();
				$("#"+nodes[0].tId+" #"+nodes[0].tId+"_span").html(nodes[0].name);
				//base.alert("保存成功！");
                alert("保存成功！");
				//base.loadingend();
			}else {
				//base.alert("已保存！");
                alert("已保存！");
			}
		});
	});
});


