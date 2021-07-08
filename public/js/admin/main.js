function getUe() {
	return ue = UE.getEditor('content',{
		toolbars: [
			[
				'source', //源代码
				'anchor', //锚点
				'undo', //撤销
				'redo', //重做
				'bold', //加粗
				'indent', //首行缩进
				'snapscreen', //截图
				'italic', //斜体
				'underline', //下划线
				'strikethrough', //删除线
				'subscript', //下标
				'fontborder', //字符边框
				'superscript', //上标
				'formatmatch', //格式刷
				'blockquote', //引用
				'pasteplain', //纯文本粘贴模式
				'selectall', //全选
				'print', //打印
				'preview', //预览
				'horizontal', //分隔线
				'removeformat', //清除格式
				'unlink', //取消链接
				'splittorows', //拆分成行
				'splittocols', //拆分成列
				'splittocells', //完全拆分单元格
				'deletecaption', //删除表格标题
				'inserttitle', //插入标题
				'mergecells', //合并多个单元格
				'deletetable', //删除表格
				'cleardoc', //清空文档
				'insertparagraphbeforetable', //"表格前插入行"
				'insertcode', //代码语言
				'fontfamily', //字体
				'fontsize', //字号
				'paragraph', //段落格式
				'simpleupload', //单图上传
				'insertimage', //多图上传
				'edittable', //表格属性
				'edittd', //单元格属性
				'link', //超链接
				'emotion', //表情
				'map', //Baidu地图
				'insertvideo', //视频
				'justifyleft', //居左对齐
				'justifyright', //居右对齐
				'justifycenter', //居中对齐
				'justifyjustify', //两端对齐
				'forecolor', //字体颜色
				'backcolor', //背景色
				'insertorderedlist', //有序列表
				'insertunorderedlist', //无序列表
				'fullscreen', //全屏
				'directionalityltr', //从左向右输入
				'directionalityrtl', //从右向左输入
				'imagenone', //默认
				'imageleft', //左浮动
				'imageright', //右浮动
				'attachment', //附件
				'imagecenter', //居中
				'wordimage', //图片转存
				'lineheight', //行间距
				'edittip ', //编辑提示
				'customstyle', //自定义标题
				'autotypeset', //自动排版
				'touppercase', //字母大写
				'tolowercase', //字母小写
				'background', //背景
				'inserttable', //插入表格
			]
		]
		,scaleEnabled:true
	});
	// body...
}
function getUeCopy(id) {
	return ue_copy = UE.getEditor(id,{
		toolbars: [
			[
				'source', //源代码
				'anchor', //锚点
				'undo', //撤销
				'redo', //重做
				'bold', //加粗
				'indent', //首行缩进
				'snapscreen', //截图
				'italic', //斜体
				'underline', //下划线
				'strikethrough', //删除线
				'subscript', //下标
				'fontborder', //字符边框
				'superscript', //上标
				'formatmatch', //格式刷
				'blockquote', //引用
				'pasteplain', //纯文本粘贴模式
				'selectall', //全选
				'print', //打印
				'preview', //预览
				'horizontal', //分隔线
				'removeformat', //清除格式
				'unlink', //取消链接
				'splittorows', //拆分成行
				'splittocols', //拆分成列
				'splittocells', //完全拆分单元格
				'deletecaption', //删除表格标题
				'inserttitle', //插入标题
				'mergecells', //合并多个单元格
				'deletetable', //删除表格
				'cleardoc', //清空文档
				'insertparagraphbeforetable', //"表格前插入行"
				'insertcode', //代码语言
				'fontfamily', //字体
				'fontsize', //字号
				'paragraph', //段落格式
				'simpleupload', //单图上传
				'insertimage', //多图上传
				'edittable', //表格属性
				'edittd', //单元格属性
				'link', //超链接
				'emotion', //表情
				'map', //Baidu地图
				'insertvideo', //视频
				'justifyleft', //居左对齐
				'justifyright', //居右对齐
				'justifycenter', //居中对齐
				'justifyjustify', //两端对齐
				'forecolor', //字体颜色
				'backcolor', //背景色
				'insertorderedlist', //有序列表
				'insertunorderedlist', //无序列表
				'fullscreen', //全屏
				'directionalityltr', //从左向右输入
				'directionalityrtl', //从右向左输入
				'imagenone', //默认
				'imageleft', //左浮动
				'imageright', //右浮动
				'attachment', //附件
				'imagecenter', //居中
				'wordimage', //图片转存
				'lineheight', //行间距
				'edittip ', //编辑提示
				'customstyle', //自定义标题
				'autotypeset', //自动排版
				'touppercase', //字母大写
				'tolowercase', //字母小写
				'background', //背景
				'inserttable', //插入表格
			]
		]
		,scaleEnabled:true
	});
	// body...

}
function getContent(id) {
	if(!id)
	{
		id = 'content';
	}
	if(UE.getEditor(id).queryCommandState('source')!=0)
	{
		UE.getEditor(id).execCommand('source');
	}
}
/* ========================================================================
 * Bootstrap: alert.js v3.1.0
 * http://getbootstrap.com/javascript/#alerts
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

layui.use('jquery', function(){
	var $ = layui.$;
	var dismiss = '[data-dismiss="alert"]'
	var Alert   = function (el) {
		$(el).on('click', dismiss, this.close)
	}

	Alert.prototype.close = function (e) {
		var $this    = $(this)
		var selector = $this.attr('data-target')

		if (!selector) {
			selector = $this.attr('href')
			selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
		}

		var $parent = $(selector)

		if (e) e.preventDefault()

		if (!$parent.length) {
			$parent = $this.hasClass('alert') ? $this : $this.parent()
		}

		$parent.trigger(e = $.Event('close.bs.alert'))

		if (e.isDefaultPrevented()) return

		$parent.removeClass('in')

		function removeElement() {
			$parent.trigger('closed.bs.alert').remove()
		}

		$.support.transition && $parent.hasClass('fade') ?
			$parent
				.one($.support.transition.end, removeElement)
				.emulateTransitionEnd(150) :
			removeElement()
	}


	// ALERT PLUGIN DEFINITION
	// =======================

	var old = $.fn.alert

	$.fn.alert = function (option) {
		return this.each(function () {
			var $this = $(this)
			var data  = $this.data('bs.alert')

			if (!data) $this.data('bs.alert', (data = new Alert(this)))
			if (typeof option == 'string') data[option].call($this)
		})
	}

	$.fn.alert.Constructor = Alert


	// ALERT NO CONFLICT
	// =================

	$.fn.alert.noConflict = function () {
		$.fn.alert = old
		return this
	}
	$.execRowspan = function(table_id,fieldName,index,flag){
		// 1为不冻结的情况，左侧列为冻结的情况
		let fixedNode = index=="1"?$("#"+table_id).parent().find(".layui-table-body")[index - 1]:(index=="3"?$(".layui-table-fixed-r"):$(".layui-table-fixed-l"));
		// 左侧导航栏不冻结的情况
		let child = $(fixedNode).find("td");
		let childFilterArr = [];
		// 获取data-field属性为fieldName的td
		for(let i = 0; i < child.length; i++){
			if(child[i].getAttribute("data-field") == fieldName){
				childFilterArr.push(child[i]);
			}
		}
		// 获取td的个数和种类
		let childFilterTextObj = {};
		for(let i = 0; i < childFilterArr.length; i++){
			let childText = flag?childFilterArr[i].innerHTML:childFilterArr[i].textContent;
			if(childFilterTextObj[childText] == undefined){
				childFilterTextObj[childText] = 1;
			}else{
				let num = childFilterTextObj[childText];
				childFilterTextObj[childText] = num*1 + 1;
			}
		}
		let canRowspan = true;
		let maxNum;//以前列单元格为基础获取的最大合并数
		let finalNextIndex;//获取其下第一个不合并单元格的index
		let finalNextKey;//获取其下第一个不合并单元格的值
		for(let i = 0; i < childFilterArr.length; i++){
			(maxNum>9000||!maxNum)&&(maxNum = $(childFilterArr[i]).prev().attr("rowspan")&&fieldName!="8"?$(childFilterArr[i]).prev().attr("rowspan"):9999);
			let key = flag?childFilterArr[i].innerHTML:childFilterArr[i].textContent;//获取下一个单元格的值
			let nextIndex = i+1;
			let tdNum = childFilterTextObj[key];
			let curNum = maxNum<tdNum?maxNum:tdNum;
			if(canRowspan){
				for(let j =1;j<=curNum&&(i+j<childFilterArr.length);){//循环获取最终合并数及finalNext的index和key
					finalNextKey = flag?childFilterArr[i+j].innerHTML:childFilterArr[i+j].textContent;
					finalNextIndex = i+j;
					if((key!=finalNextKey&&curNum>1)||maxNum == j){
						canRowspan = true;
						curNum = j;
						break;
					}
					j++;
					if((i+j)==childFilterArr.length){
						finalNextKey=undefined;
						finalNextIndex=i+j;
						break;
					}
				}
				childFilterArr[i].setAttribute("rowspan",curNum);
				if($(childFilterArr[i]).find("div.rowspan").length>0){//设置td内的div.rowspan高度适应合并后的高度
					$(childFilterArr[i]).find("div.rowspan").parent("div.layui-table-cell").addClass("rowspanParent");
					$(childFilterArr[i]).find("div.layui-table-cell")[0].style.height= curNum*38-10 +"px";
				}
				canRowspan = false;
			}else{
				childFilterArr[i].style.display = "none";
			}
			if(--childFilterTextObj[key]==0|--maxNum==0|--curNum==0|(finalNextKey!=undefined&&nextIndex==finalNextIndex)){//||(finalNextKey!=undefined&&key!=finalNextKey)
				canRowspan = true;
			}
		}
	}
	//合并数据表格行
	$.layuiRowspan = function(table_id,fieldNameTmp,index,flag){
		let fieldName = [];
		if(typeof fieldNameTmp == "string"){
			fieldName.push(fieldNameTmp);
		}else{
			fieldName = fieldName.concat(fieldNameTmp);
		}
		for(let i = 0;i<fieldName.length;i++){
			$.execRowspan(table_id,fieldName[i],index,flag);
		}
	}
	// ALERT DATA-API
	// ==============

	$(document).on('click.bs.alert.data-api', dismiss, Alert.prototype.close)
})

layui.use(['form'], function(){
	var form = layui.form;
	form.render();

	form.verify({
		not_required_email: [/(^$)|^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/, "邮箱格式不正确"],
	});
});
