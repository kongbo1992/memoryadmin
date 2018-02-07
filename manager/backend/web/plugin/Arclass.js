var setting = {
	view: {
		//addHoverDom: addHoverDom,
		removeHoverDom: removeHoverDom,
		selectedMulti: false,
		showIcon:true,
		dblClickExpand:dblClickExpand,
	},
	check: {
		enable: true,
		chkStyle: "radio",
		radioType: "all"
	},
	//edit: {
	//	enable: true,
	//	editNameSelectAll: true
	//},
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
/*
 zTree  点击事件
 * */
function zTreeOnClick(e, treeId, treeNode){
	console.log(treeNode.id);
	//base.loading();
	//$.post("./selecttypeid",{"id":treeNode.id},function(edm){
	//	if(edm){
	//		console.log(edm);
	//		$("#zTreeName").val(treeNode.name);
	//		$("#zTreeTypeId").val(edm.dyntype);
	//		var a=document.getElementById("zTreeTypeId");
	//		var b=document.getElementById("zTreeTypeId2");
	//		var c=document.getElementById("zTreeTypeId3");
     //                   var d=document.getElementById("zTreeImprotant");
     //                   var e=document.getElementById("zTreeImprotant2");
     //                   var f=document.getElementById("zTreeImprotant3");
     //                   var g=document.getElementById("zTreeImprotant4");
     //                   var h=document.getElementById("zTreeImprotant5");
	//		a.checked=false;
	//		b.checked=false;
	//		c.checked=false;
     //                   d.checked=false;
     //                   e.checked=false;
	//		f.checked=false;
	//		g.checked=false;
     //                   h.checked=false;
	//		if(edm.dyntype == "0"){
	//			var c=document.getElementById("zTreeTypeId");
	//			c.checked=true;
	//		}else if(edm.dyntype == "1"){
	//			var c=document.getElementById("zTreeTypeId2");
	//			c.checked=true;
	//		}
	//		else if(edm.dyntype == "2"){
	//			var c=document.getElementById("zTreeTypeId3");
	//			c.checked=true;
	//		}
     //                   if(edm.improtant == "1"){
     //                       var c=document.getElementById("zTreeImprotant");
	//			c.checked=true;
     //                   }else if(edm.improtant == "2"){
     //                       var c=document.getElementById("zTreeImprotant2");
	//			c.checked=true;
     //                   }else if(edm.improtant == "3"){
     //                       var c=document.getElementById("zTreeImprotant3");
	//			c.checked=true;
     //                   }else if(edm.improtant == "4"){
     //                       var c=document.getElementById("zTreeImprotant4");
	//			c.checked=true;
     //                   }else if(edm.improtant == "5"){
     //                       var c=document.getElementById("zTreeImprotant5");
	//			c.checked=true;
     //                   }
	//	}
	//	//base.loadingend();
	//});
}

$(document).ready(function(){
    $.post("/article/relationships/class",function(edm){
        my_loading.hide();
		var zNodes =(new Function("","return "+edm))();
		$.fn.zTree.init($("#treeDemo"), setting, zNodes);
		if($("#ararticle-class_id").val()){
			var zTree = $.fn.zTree.getZTreeObj("treeDemo");
			var node = zTree.getNodeByParam("id",$("#ararticle-class_id").val());
			zTree.checkNode(node, true, true);
		}

	});

});
