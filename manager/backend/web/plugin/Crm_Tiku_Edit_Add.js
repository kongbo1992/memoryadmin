$(function(){
		// 题干及选项 正确答案checkbox点击事件
		$(".Crm_Tiku_da label").click(function(){
			if($(this).find("input[type='checkbox']").is(":checked")){
				$(this).find("span").css("color","green");
			}else {
				$(this).find("span").css("color","#333");
			}
		});
		/************************
		 * 题干的Ueditor [TG]
		 * ************************/
		ueditor = UE.getEditor('ueditor', {
		    initialFrameWidth : 820,
		    initialFrameHeight: 150,
		    scaleEnabled:true,
		    elementPathEnabled:false,
		    wordCount:false,
            toolbars: [
            		['source','|','tex','|','simpleupload','|','forecolor','|','fontsize']
            ],
            labelMap:{
				'Tex': '插入Tex',
			}
        }).commands['tex'] = { execCommand: function() {
	        	// 这里执行一个ajax请求后台的方法，将返回的img图片 插入到页面内
	       	/*base.alert("ueditor_alert");*/ // 显示Tex请求后台生成图片的弹出窗
	       	// 把 this 赋值给 _this 在点击确定 插入图片时引用它 
	       	var _this = this;
	       	// 点击Tex弹出窗的确定按钮
	       	$("#ueditor_yes").unbind();
	       	$("#ueditor_yes").bind("click",function(){
	       		// 往uditor中插入一个图片
				$.post("/index.php/Home/Question/TexToPng",{"text":$("#ueditor_alert textarea").val()},function(edm){
					_this.execCommand('insertHtml', "<img src='"+edm.url+"' />");
				},"json");
				// 插入图片后 关闭Tex的弹出窗口
	       	/*	base.alert_clos("ueditor_alert");*/
	       		$("#ueditor_yes").unbind();
	       		return true;
			});
        },queryCommandState: function(){}};; 
        
		/************************
		*  答案编辑的ueditor [edit]
		*************************/
		ueditor = UE.getEditor('ueditor_edit', {
		    initialFrameWidth : 480,
		    initialFrameHeight: 200,
		    scaleEnabled:true,
		    elementPathEnabled:false,
            toolbars: [
            		['tex','|','simpleupload']
            ],
            labelMap:{
				'Tex': '插入Tex',
			}
        }).commands['tex'] = { execCommand: function() {
	        	// 这里执行一个ajax请求后台的方法，将返回的img图片 插入到页面内
	     /*  	base.alert("ueditor_alert"); */// 显示Tex请求后台生成图片的弹出窗
	       	$("#ueditor_alert").css("z-index","1002")
	       	// 把 this 赋值给 _this 在点击确定 插入图片时引用它 
	       	var _this = this;
	       	// 点击Tex弹出窗的确定按钮
	       	$("#ueditor_yes").unbind();
	       	$("#ueditor_yes").bind("click",function(){
				// 往uditor中插入一个图片
				$.post("/index.php/Home/Question/TexToPng",{"text":$("#ueditor_alert textarea").val()},function(edm){
					_this.execCommand('insertHtml', "<img src='"+edm.url+"' />");
				},"json");
	       		// 插入图片后 关闭Tex的弹出窗口
	       /*		base.alert_clos("ueditor_alert");*/
	       		 $("#ueditor_yes").unbind();
	       		return true;
			});
        },queryCommandState: function(){}};; 
        /************************
		*  答案解析的ueditor [jx]
		**************************/
        ueditor = UE.getEditor('ueditor_jx', {
		    initialFrameWidth : 820,
		    initialFrameHeight:150,
		    scaleEnabled:true,
		    elementPathEnabled:false,
            toolbars: [
            		['tex','|','simpleupload']
            ],
            labelMap:{
				'Tex': '插入Tex',
			}
        }).commands['tex'] = { execCommand: function() {
	        	// 这里执行一个ajax请求后台的方法，将返回的img图片 插入到页面内
	      /* 	base.alert("ueditor_alert"); */// 显示Tex请求后台生成图片的弹出窗
	       	// 把 this 赋值给 _this 在点击确定 插入图片时引用它 
	       	var _this = this;
	       	// 点击Tex弹出窗的确定按钮
	       	$("#ueditor_yes").unbind();
	       	$("#ueditor_yes").bind("click",function(){
				// 往uditor中插入一个图片
				$.post("/index.php/Home/Question/TexToPng",{"text":$("#ueditor_alert textarea").val()},function(edm){
					_this.execCommand('insertHtml', "<img src='"+edm.url+"' />");
				},"json");
	       		// 插入图片后 关闭Tex的弹出窗口
	       	/*	base.alert_clos("ueditor_alert");*/
	       		 $("#ueditor_yes").unbind();
	       		return true;
			});
        },queryCommandState: function(){}};
       /************************
		*  答案A ueditor_a [a]
		*************************/
        ueditor = UE.getEditor('ueditor_a', {
		    initialFrameWidth : 780,
		    initialFrameHeight:55,
		    scaleEnabled:true,
		    elementPathEnabled:false,
            toolbars: [
            		['tex','simpleupload']
            ],
            labelMap:{
				'Tex': '插入Tex',
			}
        }).commands['tex'] = { execCommand: function() {
	        	// 这里执行一个ajax请求后台的方法，将返回的img图片 插入到页面内
	     /*  	base.alert("ueditor_alert"); */// 显示Tex请求后台生成图片的弹出窗
	       	// 把 this 赋值给 _this 在点击确定 插入图片时引用它 
	       	var _this = this;
	       	// 点击Tex弹出窗的确定按钮
	       	$("#ueditor_yes").unbind();
	       	$("#ueditor_yes").bind("click",function(){
				// 往uditor中插入一个图片
				$.post("/index.php/Home/Question/TexToPng",{"text":$("#ueditor_alert textarea").val()},function(edm){
					_this.execCommand('insertHtml', "<img src='"+edm.url+"' />");
				},"json");
	       		// 插入图片后 关闭Tex的弹出窗口
	       	/*	base.alert_clos("ueditor_alert");*/
	       		 $("#ueditor_yes").unbind();
	       		return true;
			});
        },queryCommandState: function(){}};
        /************************
		*  答案B ueditor_b [b]
		*************************/
        ueditor = UE.getEditor('ueditor_b', {
		    initialFrameWidth : 780,
		    initialFrameHeight:55,
		    scaleEnabled:true,
		    elementPathEnabled:false,
            toolbars: [
            		['tex','simpleupload']
            ],
            labelMap:{
				'Tex': '插入Tex',
			}
        }).commands['tex'] = { execCommand: function() {
	        	// 这里执行一个ajax请求后台的方法，将返回的img图片 插入到页面内
	      /* 	base.alert("ueditor_alert"); */// 显示Tex请求后台生成图片的弹出窗
	       	// 把 this 赋值给 _this 在点击确定 插入图片时引用它 
	       	var _this = this;
	       	// 点击Tex弹出窗的确定按钮
	       	$("#ueditor_yes").unbind();
	       	$("#ueditor_yes").bind("click",function(){
				// 往uditor中插入一个图片
				$.post("/index.php/Home/Question/TexToPng",{"text":$("#ueditor_alert textarea").val()},function(edm){
					_this.execCommand('insertHtml', "<img src='"+edm.url+"' />");
				},"json");
	       		// 插入图片后 关闭Tex的弹出窗口
	       	/*	base.alert_clos("ueditor_alert");*/
	       		 $("#ueditor_yes").unbind();
	       		return true;
			});
        },queryCommandState: function(){}};
        /************************
		*  答案C ueditor_c [c]
		*************************/
        ueditor = UE.getEditor('ueditor_c', {
		    initialFrameWidth : 780,
		    initialFrameHeight:55,
		    scaleEnabled:true,
		    elementPathEnabled:false,
            toolbars: [
            		['tex','simpleupload']
            ],
            labelMap:{
				'Tex': '插入Tex',
			}
        }).commands['tex'] = { execCommand: function() {
	        	// 这里执行一个ajax请求后台的方法，将返回的img图片 插入到页面内
	     /*  	base.alert("ueditor_alert"); */// 显示Tex请求后台生成图片的弹出窗
	       	// 把 this 赋值给 _this 在点击确定 插入图片时引用它 
	       	var _this = this;
	       	// 点击Tex弹出窗的确定按钮
	       	$("#ueditor_yes").unbind();
	       	$("#ueditor_yes").bind("click",function(){
				// 往uditor中插入一个图片
				$.post("/index.php/Home/Question/TexToPng",{"text":$("#ueditor_alert textarea").val()},function(edm){
					_this.execCommand('insertHtml', "<img src='"+edm.url+"' />");
				},"json");
	       		// 插入图片后 关闭Tex的弹出窗口
	       	/*	base.alert_clos("ueditor_alert");*/
	       		 $("#ueditor_yes").unbind();
	       		return true;
			});
        },queryCommandState: function(){}};
        /************************
		*  答案D ueditor_d [d]
		*************************/
        ueditor = UE.getEditor('ueditor_d', {
		    initialFrameWidth : 780,
		    initialFrameHeight:55,
		    scaleEnabled:true,
		    elementPathEnabled:false,
            toolbars: [
            		['tex','simpleupload']
            ],
            labelMap:{
				'Tex': '插入Tex',
			}
        }).commands['tex'] = { execCommand: function() {
	        	// 这里执行一个ajax请求后台的方法，将返回的img图片 插入到页面内
	      /* 	base.alert("ueditor_alert");*/ // 显示Tex请求后台生成图片的弹出窗
	       	// 把 this 赋值给 _this 在点击确定 插入图片时引用它 
	       	var _this = this;
	       	// 点击Tex弹出窗的确定按钮
	       	$("#ueditor_yes").unbind();
	       	$("#ueditor_yes").bind("click",function(){
				// 往uditor中插入一个图片
				$.post("/index.php/Home/Question/TexToPng",{"text":$("#ueditor_alert textarea").val()},function(edm){
					_this.execCommand('insertHtml', "<img src='"+edm.url+"' />");
				},"json");
	       		// 插入图片后 关闭Tex的弹出窗口
	       		/*base.alert_clos("ueditor_alert");*/
	       		 $("#ueditor_yes").unbind();
	       		return true;
			});
        },queryCommandState: function(){}};
        /************************
		*  答案D ueditor_e [e]
		*************************/
        ueditor = UE.getEditor('ueditor_e', {
		    initialFrameWidth : 780,
		    initialFrameHeight:55,
		    scaleEnabled:true,
		    elementPathEnabled:false,
            toolbars: [
            		['tex','simpleupload']
            ],
            labelMap:{
				'Tex': '插入Tex',
			}
        }).commands['tex'] = { execCommand: function() {
	        	// 这里执行一个ajax请求后台的方法，将返回的img图片 插入到页面内
	       /*	base.alert("ueditor_alert");*/ // 显示Tex请求后台生成图片的弹出窗
	       	// 把 this 赋值给 _this 在点击确定 插入图片时引用它 
	       	var _this = this;
	       	// 点击Tex弹出窗的确定按钮
	       	$("#ueditor_yes").unbind();
	       	$("#ueditor_yes").bind("click",function(){
				// 往uditor中插入一个图片
				$.post("/index.php/Home/Question/TexToPng",{"text":$("#ueditor_alert textarea").val()},function(edm){
					_this.execCommand('insertHtml', "<img src='"+edm.url+"' />");
				},"json");
	       		// 插入图片后 关闭Tex的弹出窗口
	       		/*base.alert_clos("ueditor_alert");*/
	       		 $("#ueditor_yes").unbind();
	       		return true;
			});
        },queryCommandState: function(){}};
        /************************
		*  答案D ueditor_f [f]
		*************************/
        ueditor = UE.getEditor('ueditor_f', {
		    initialFrameWidth : 780,
		    initialFrameHeight:55,
		    scaleEnabled:true,
		    elementPathEnabled:false,
            toolbars: [
            		['tex','simpleupload']
            ],
            labelMap:{
				'Tex': '插入Tex',
			}
        }).commands['tex'] = { execCommand: function() {
	        	// 这里执行一个ajax请求后台的方法，将返回的img图片 插入到页面内
	       /*	base.alert("ueditor_alert");*/ // 显示Tex请求后台生成图片的弹出窗
	       	// 把 this 赋值给 _this 在点击确定 插入图片时引用它 
	       	var _this = this;
	       	// 点击Tex弹出窗的确定按钮
	       	$("#ueditor_yes").unbind();
	       	$("#ueditor_yes").bind("click",function(){
				// 往uditor中插入一个图片
				$.post("/index.php/Home/Question/TexToPng",{"text":$("#ueditor_alert textarea").val()},function(edm){
					_this.execCommand('insertHtml', "<img src='"+edm.url+"' />");
				},"json");
	       		// 插入图片后 关闭Tex的弹出窗口
	       	/*	base.alert_clos("ueditor_alert");*/
	       		 $("#ueditor_yes").unbind();
	       		return true;
			});
        },queryCommandState: function(){}};
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
			/*base.loadingend();*/
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
		$(document).ready(function(){
		
			var lx = $("#lx").val();
			var xd = $("#xd").val();
			$.post("./updateshushow",{"exam_type":lx,"exam_level":xd},function(edm){
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
				/*base.loadingend();*/
			});
		});
});











