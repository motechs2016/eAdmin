/**
 *分页 js
 *2012-5-5
 * by  wq
 *@param query string url ajax url
 *@param filter object 查询条件
 *@param replaceDiv div ID  <div id='replaceDiv'><table></table></div> 默认为listDiv
 */
var page = {}; //实例化对象
page.filter = {};

page.changePageSize = function (obj) {
	var val = obj.value;

	this.filter.page_size = val;

	this.reload();

}

/**
 *第一页
 *
 */
page.gotoPageFirst = function () {
	if(this.filter.page == 1) {
		return ;
	}
	this.filter.page = 1;
	this.reload();
}

/**
 *最后一页
 *
 */
page.gotoPageLast = function () {
	if(this.filter.page == this.filter.page_num) {
		return ;
	}
	this.filter.page = this.filter.page_num;
	this.reload();
}

/**
 *上一页
 *
 */
page.gotoPagePrev = function () {
	if(this.filter.page == 1) {
		return ;
	}
	this.filter.page--;
	this.reload();
}
/**
 *下一页
 *
 */
page.gotoPageNext = function () {
	if(this.filter.page == this.filter.page_num) {
		return ;
	}
	this.filter.page++;
	this.reload();
}

/**
 *直接跳到第几页
 *
 */
page.gotoPage = function(obj) {
	var val = obj.value;
	if(val > this.filter.page_num) {
		return;
	}
	this.filter.page = val;

	this.reload();
}

/**
 *发送ajax请求
 *
 */
page.reload = function () {
	
	HTTP.post(this.query,this.filter,function(data){
		if(this.replaceDiv) {

		}else {
			document.getElementById('listDiv').innerHTML = data.content;
		}	
		
		page.filter = data.filter;

	},'json');

}