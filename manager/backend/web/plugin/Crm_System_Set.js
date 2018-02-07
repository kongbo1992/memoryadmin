/*
 jQuery zTree
 * **/

var setting = {
	view: {
		addHoverDom: addHoverDom,
		removeHoverDom: removeHoverDom,
		selectedMulti: false,
		showIcon:true,
		dblClickExpand:dblClickExpand,
	},
	edit: {
		enable: true,
		editNameSelectAll: true
	},
	data: {
		key: {
			children: "nodeId"
		},
		simpleData: {
			enable: true
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
		onDrop: onDrop
		
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
	//console.log("子级ID:"+drop_id);
	//console.log("父级ID:"+drop_pId);
	//base.loading();
	$.post("./droptree",{"bs":"tb_dc_education","id":drop_id,"pid":drop_pId},function(edm){
		if(edm == true){
			//base.alert("节点移动成功！");
            alert("节点移动成功！");
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
//刷新当前节点 
function rereshNode(id){  
	var treeObj = $.fn.zTree.getZTreeObj("treeDemo");  
	var nownode = treeObj.getNodesByParam("id", id, null);  
	treeObj.reAsyncChildNodes(nownode[0], "refresh"); 
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
	if(treeNode.dataId != 0){
		$.post("./deleteknowdage",
		{
            "id":treeNode.dataId,
            "bs":"tb_dc_education"
		},function(edm){
                console.log(edm);
			if(edm == "false"){
				console.log("删除失败!");
			}else {
				var treeObj = $.fn.zTree.getZTreeObj("treeDemo"); 
				console.log("删除成功!"+treeNode.id+"--"+treeObj.getNodeByParam("id"));
			}
		});
	}
}

function onRename(e, treeId, treeNode) {
	//base.loading();
	showLog("[ "+getTime()+" onRename ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
	if(treeNode.dataId == 0){
		// 新增标准大纲节点
		$.post("./insertknowdage",
		{
			"id":treeNode.id,
			"pid":treeNode.pId,
		    "name":treeNode.name,
		    "dyntype":$("#zTreeTypeId").val(),
		    "exam_type":$("#lx").val(),
		    "exam_level":$("#xd").val(),
		},function(edm){
                console.log(edm);
			if(edm == false){
				console.log("新增失败!");
				//base.loadingend();
			}else {
				console.log("新增成功!");
				treeNode.id = edm;
				treeNode.dataId = edm;
				//base.loadingend();
			}
		});
	}else {
		
		// 修改标准大纲节点
		$.post("./updateknowdage",
		{
		    "id":treeNode.dataId,
		    "name":treeNode.name,
		    "dyntype":$("#zTreeTypeId").val(),
		    "exam_type":$("#lx").val(),
		    "exam_level":$("#xd").val(),
		},function(edm){
			if(edm == false){
				console.log("修改失败!");
				//base.loadingend();
			}else {
				console.log("修改成功!");
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
	//console.log("=================in add");
	if (treeNode.id > 0){
		//console.log("addHoverDom===" + treeNode.tId);
		var sObj = $("#" + treeNode.tId + "_span");
		if (treeNode.editNameFlag || $("#addBtn_"+treeNode.id).length>0) return;
		var addStr = "<span class='button add' id='addBtn_" + treeNode.id
			+ "' title='add node' onfocus='this.blur();'></span>";
		sObj.after(addStr);
		var btn = $("#addBtn_"+treeNode.id);
		
		if (btn) btn.bind("click", function(){
			var zTree = $.fn.zTree.getZTreeObj("treeDemo");
			//var aNodes = zTree.addNodes(treeNode, {id:(100 + newCount), pId:treeNode.id, dataId:"0",name:"new node" + (newCount++)});
			var aNodes = zTree.addNodes(treeNode, {id:0, pId:treeNode.id, dataId:"0",name:"new node" + (newCount++)});
			zTree.selectNode(aNodes[0]);
			zTree.editName(aNodes[0]);
			return false;
		});
	}
};
function removeHoverDom(treeId,treeNode){
	if (treeNode.id > 0){
		//console.log("removeHoverDom===" + treeNode.tId);
		$("#addBtn_"+treeNode.id).unbind().remove();	
	}
};
function selectAll() {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	zTree.setting.edit.editNameSelectAll =  $("#selectAll").attr("checked");
}
/*
 zTree  点击事件
 * */
function zTreeOnClick(e, treeId, treeNode){
	console.log(treeNode.id);
	//base.loading();
	$.post("./selecttypeid",{"id":treeNode.id},function(edm){
		if(edm){
			console.log(edm);
			$("#zTreeName").val(treeNode.name);
			$("#zTreeTypeId").val(edm.dyntype);
			var a=document.getElementById("zTreeTypeId");
			var b=document.getElementById("zTreeTypeId2");
			var c=document.getElementById("zTreeTypeId3");
                        var d=document.getElementById("zTreeImprotant");
                        var e=document.getElementById("zTreeImprotant2");
                        var f=document.getElementById("zTreeImprotant3");
                        var g=document.getElementById("zTreeImprotant4");
                        var h=document.getElementById("zTreeImprotant5");
			a.checked=false;
			b.checked=false;
			c.checked=false;
                        d.checked=false;
                        e.checked=false;
			f.checked=false;
			g.checked=false;
                        h.checked=false;
			if(edm.dyntype == "0"){
				var c=document.getElementById("zTreeTypeId");
				c.checked=true;
			}else if(edm.dyntype == "1"){
				var c=document.getElementById("zTreeTypeId2");
				c.checked=true;
			}
			else if(edm.dyntype == "2"){
				var c=document.getElementById("zTreeTypeId3");
				c.checked=true;
			}
                        if(edm.improtant == "1"){
                            var c=document.getElementById("zTreeImprotant");
				c.checked=true;
                        }else if(edm.improtant == "2"){
                            var c=document.getElementById("zTreeImprotant2");
				c.checked=true;
                        }else if(edm.improtant == "3"){
                            var c=document.getElementById("zTreeImprotant3");
				c.checked=true;
                        }else if(edm.improtant == "4"){
                            var c=document.getElementById("zTreeImprotant4");
				c.checked=true;
                        }else if(edm.improtant == "5"){
                            var c=document.getElementById("zTreeImprotant5");
				c.checked=true;
                        }
		}
		//base.loadingend();
	});
}

$(document).ready(function(){
	//base.loading();
	var lx = $("#lx").val();
	var xd = $("#xd").val();
	console.log(lx+"---"+xd);
    my_loading.show();
	$.post("./allknowdage",{"exam_type":lx,"exam_level":xd},function(edm){
        my_loading.hide();
		//console.log("数据返回！")
		var zNodes =(new Function("","return "+edm))();
		$.fn.zTree.init($("#treeDemo"), setting, zNodes);
		//base.loadingend();
	});
	$("#selectAll").bind("click", selectAll);
	/* 右侧窗口，保存事件 */
	$("#zTreeEditSave").click(function(){
		//base.loading();
		var NewtreeObj = $.fn.zTree.getZTreeObj("treeDemo");
		var nodes = NewtreeObj.getSelectedNodes();
		if(nodes == ""){
			//base.alert("请选择你要操作的目录！");
            alert("请选择你要操作的目录！");
			//base.loadingend();
		}else{
			var radios = document.getElementsByName("radio");
			var tag = false;
			var val;
			for(radio in radios) {
			   if(radios[radio].checked) {
			      tag = true;
			      val = radios[radio].value;
			      break;
			   }
			}
			if(tag) {
			   console.log(val);
			}
                        var radios1 = document.getElementsByName("radio1");
			var tag1 = false;
			var val1;
			for(radio in radios1) {
			   if(radios1[radio].checked) {
			      tag1 = true;
			      val1 = radios1[radio].value;
			      break;
			   }
			}
			if(tag1) {
			   console.log(val1);
			}
			$.post("./updateknowdage",{
				"id":nodes[0].id,"name":$('#zTreeName').val(),"dyntype":val,"orders":"0","improtant":val1
			},function(edm){
				if(edm == true){
					nodes[0].name = $('#zTreeName').val();
					$("#"+nodes[0].tId+" #"+nodes[0].tId+"_span").html(nodes[0].name);
					//base.alert("修改成功!");
                    alert("修改成功!");
					//base.loadingend();
				}else {
					//base.loadingend();
					//base.alert("已保存");
                    alert("已保存");
				}
			});
		}
	});
});




