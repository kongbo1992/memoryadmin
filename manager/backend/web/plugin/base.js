
// base start!
var base={
		/*
		 * 作者：770124976@qq.com
		 * 时间：2015-09-22
		 * 描述：遮罩层 
		 * 代码：base.load();
		 * */
		load: function(){
			$("#base_load",window.top.document).show();
		},
		/*
		 * 作者：770124976@qq.com
		 * 时间：2015-09-22
		 * 描述：动画加载中
		 * 代码：base.load();
		 * */
		loading: function(){
			$("#base_load,#base_load_img",window.top.document).show();
			var obj = document.getElementById("base_load_img");
			if(obj){
				obj.style.marginLeft = -(obj.offsetWidth/2) + 'px';	
				obj.style.marginTop = -(obj.offsetHeight/2) + 'px';		
			}	
		},
		loadingend: function(data){
			if(data=="cengshang"){
				$("#base_load_img",window.top.document).hide();
			}else{
			    $("#base_load,#base_load_img",window.top.document).hide();
			}
		},
		/*
		 * 作者：770124976@qq.com
		 * 时间：2015-09-22
		 * 描述：动画加载中
		 * 代码：base.center(id);
		 * */
		alert:function(obj){
			var _obj = document.getElementById(obj);
			
			if(_obj == null){
				if(obj == "clos"){
					$("#alert,#base_load2").hide();
				}else {
					$("#alert,#base_load2").show().find(".alert_cont").html(obj);
					$("#alert").css("z-index","99999999999");
					var this_W = $("#alert").width();
					var this_H = $("#alert").height();
					var win_w = ($(window).width()-this_W)/2; 
					var win_h = ($(window).height()-this_H)/2;
					$("#alert").css({"left":win_w+"px","top":win_h+"px"});
					$("#alert .alert_cont").css({"line-height":"80px","color":"#cc0000","font-size":"16px"});
					if($("#alert .alert_btn_box a").length == 2){
						$("#alert .alert_btn_box a:first").remove();
					}
					$("#alert .alert_btn_box a").css("margin-right","55px").html("确定");
					$("#alert .alert_btn_box a").click(function(){
						$("#alert,#base_load2").hide();
					});
				}
			}else {
				$("#"+obj+",#base_load").show();
				var this_W = $("#"+obj).width();
				var this_H = $("#"+obj).height();
				var win_w = ($(window).width()-this_W)/2; 
				var win_h = ($(window).height()-this_H)/2;
				$("#"+obj).css({"left":win_w+"px","top":win_h+"px"});
			}
		},
		// alert 的取消按钮
		alert_clos:function (alert_name){
			$("#"+alert_name).hide();
			$("#base_load",window.top.document).hide();
		},
		/*
		 * 作者：770124976@qq.com
		 * 时间：2015-09-22
		 * 描述：动画加载中
		 * 代码：base.center(id);
		 * */
		center:function(obj){
			if(obj){
				//$("#base_load",window.top.document).show();	
				var this_width = $("#"+obj).width();
				var this_height = $("#"+obj).height();
				var win_w = ($(window).width()-this_width)/2; 
				var win_h = ($(window).height()-this_height)/2;
				$("#"+obj).css({"top":win_h,"left":win_w,"position":"fixed","z-index":"999"}).show();
			}
		},
		/*
		 * 作者：770124976@qq.com
		 * 时间：2015-09-22
		 * 描述：窗口关闭
		 * 代码：base.clos(id);
		 * */
		clos:function(obj){
			$("#"+obj+",#base_load").hide();
		},
		/*
		 * 作者：770124976@qq.com
		 * 时间：2015-09-22
		 * 描述：iframe 高度自适应
		 * 代码：base.frameH(id);
		 * */
		frameH: function(frameId,json) {
			var json = json || {};
			var num = json.height || 450;
			var oFrame=document.getElementById(frameId);
			if(oFrame){
				function sgetHeight(){
					if (oFrame.contentDocument && oFrame.contentDocument.body && oFrame.contentDocument.body.offsetHeight){
						oFrame.style.height = 0;
						if(oFrame.contentDocument.body.offsetHeight > num){
							oFrame.style.height = oFrame.contentDocument.body.offsetHeight+'px';
						}else{
							oFrame.style.height =num+'px';		
						}
					}
				}
				sgetHeight();
				oFrame.onload=function(){
					//  加载后的高度
					var subWeb =  document.frames ? document.frames[frameId].document : oFrame.contentDocument;
					//alert(subWeb.body.scrollHeight);
					
					if (oFrame.contentDocument && oFrame.contentDocument.body && oFrame.contentDocument.body.offsetHeight){
						//oFrame.style.height = 0;
						if(oFrame.contentDocument.body.offsetHeight > num){
							oFrame.style.height = oFrame.contentDocument.body.offsetHeight+'px';
						}else{
							oFrame.style.height =num+'px';		
						}
					}
					json.loadFn && json.loadFn();
					if(window.parent && window.parent.setFrame){
						window.parent.setFrame();	
					}
				};
			}
		},
		/**
		 * 图片上传
		 * */
		fileimg:function(inputid,imgid){
			$("#"+inputid).change(function(){
				//建立一個可存取到該file的url
				function getObjectURL(file) {
					var url = null ; 
					if (window.createObjectURL!=undefined) { // basic
						url = window.createObjectURL(file) ;
					} else if (window.URL!=undefined) { // mozilla(firefox)
						url = window.URL.createObjectURL(file) ;
					} else if (window.webkitURL!=undefined) { // webkit or chrome
						url = window.webkitURL.createObjectURL(file) ;
					}
					return url ;
				}
				var objUrl = getObjectURL(this.files[0]);
				//console.log("objUrl = "+objUrl) ;
				if (objUrl) {
					$("#"+imgid).attr("src", objUrl) ;
				}
			});
		}
}
// base End!
$(function(){
	// input 的提示信息
	$(".IsIs input,.IsIs select,.IsIs textarea").focus(function(){
		$(this).siblings("em").show();
	}).blur(function(){
		$(this).siblings("em").hide();
	});
	// tbs 切换
	$('#tab-title li').click(function(){
		//removeClass就是删除当前其他类；只有当前对象有addClass("selected")；siblings()意思就是当前对象的同级元素，removeClass就是删除；
		$(this).addClass("action").siblings().removeClass();
		$("#tab-content > div").hide().eq($('#tab-title li').index(this)).show();
		
	});
	// checkbox 点击赋值 0-1
	$(".checkbox").click(function(){
		if($(this).is(":checked")){
			$(this).val("1");
		}else {
			$(this).val("0");
		}
	});
});




