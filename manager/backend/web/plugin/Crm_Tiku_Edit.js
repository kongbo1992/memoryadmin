$(function(){
		
        // ztree 
		var setting = {
			check: {
				enable: true,
				chkStyle: "checkbox",
				chkboxType: {"Y" : "", "N" : ""}
			},
			data: {
				simpleData: {
					enable: true
				}
			}
		};
		var code;
		function setCheck() {
			
			var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
			py = $("#py").attr("checked")? "p":"",
			sy = $("#sy").attr("checked")? "s":"",
			pn = $("#pn").attr("checked")? "p":"",
			sn = $("#sn").attr("checked")? "s":"",
			type = { "Y":py + sy, "N":pn + sn};
			zTree.setting.check.chkboxType = type;
			showCode('setting.check.chkboxType = { "Y" : "' + type.Y + '", "N" : "' + type.N + '" };');
		}
		function showCode(str) {
			if (!code) code = $("#code");
			code.empty();
			code.append("<li>"+str+"</li>");
		}
		function checkall(){

			var zTree = $.fn.zTree.getZTreeObj("treeDemo");
			var edm = $("#eduid_all").val();
			if(edm != null && edm!= ""){
				var arr = edm.split(',');
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
				
			}else {
				var nodes = zTree.getCheckedNodes(true);
				for (var i=0,l=nodes.length; i < l; i++) {
					zTree.checkNode(nodes[i], false, true);
				}
			}
			
		}
		function checkall2(){
			var zTree = $.fn.zTree.getZTreeObj("treeDemo2");
			var edm = $("#eduid_all").val();
			if(edm != null && edm!= ""){
				var arr = edm.split(',');
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
				
			}else {
				var nodes = zTree.getCheckedNodes(true);
				for (var i=0,l=nodes.length; i < l; i++) {
					zTree.checkNode(nodes[i], false, true);
				}
			}
		}
		function checkall3(){
			var zTree = $.fn.zTree.getZTreeObj("treeDemo3");
			var edm = $("#eduid_all").val();
			if(edm != null && edm!= ""){
				var arr = edm.split(',');
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
				
			}else {
				var nodes = zTree.getCheckedNodes(true);
				for (var i=0,l=nodes.length; i < l; i++) {
					zTree.checkNode(nodes[i], false, true);
				}
			}
		}
	/*	$(document).ready(function(){
		
			var lx = $("#lx").val();
			var xd = $("#xd").val();
			
			$.post("./updateshushow",{"exam_type":lx,"exam_level":xd,"istype":true},function(edm){
				edm = $.parseJSON(edm);
				$.fn.zTree.init($("#treeDemo"), setting, edm[0]);
				var treeObj = $.fn.zTree.getZTreeObj("treeDemo"); 
				treeObj.expandAll(true);
				checkall();
				$.fn.zTree.init($("#treeDemo2"), setting, edm[1]);
				var treeObj2 = $.fn.zTree.getZTreeObj("treeDemo2"); 
				treeObj2.expandAll(true);
				checkall2();
				$.fn.zTree.init($("#treeDemo3"), setting, edm[2]);
				var treeObj3 = $.fn.zTree.getZTreeObj("treeDemo3"); 
				treeObj3.expandAll(true);
				checkall3();
				$(".edtt_3_ztree_posa").hide();
				
			});
		});*/
});











