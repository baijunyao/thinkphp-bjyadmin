
/**
 * 通用跳转函数
 * @param  String url	 跳转的目标url	
 * @param  Number second  多少秒后跳转
 * @param  String message 提示信息
 */
function xbGoTo(url,second,message){
	var url=arguments[0] ? arguments[0] : '/',
		second=arguments[1] ? arguments[1] : 0,
		message=arguments[2] ? arguments[2] : '';
	// 转换为毫秒 为了处理0毫秒的问题所以+1
	second=second*1000+1;
	// 设置提示信息
	if (message!='') {
		xbAlert(message);
	}
	// 设置跳转时间
	setTimeout(function(){
		location.href=url;
	},second)
}

/**
 * 刷新本页
 * @param  {Number}  second  多少秒后刷新 默认是0立即刷新
 * @param  {Boolean} history 默认为  false刷新后停留在当前位置  true 刷新后到顶部
 */
function xbRefresh(second,history){
	var second=arguments[0] ? arguments[0] : 0,
		history=arguments[1] ? arguments[1] : false;
	// 转换为毫秒 为了处理0毫秒的问题所以+1
	second=second*1000+1;
	setTimeout(function(){
		if (history) {
			location.reload(true);
		}else{
			console.log(history);
			location.reload(false);
		}
	},second)
}

/**
 * 检测是否登录
 * @return {boolean} 登录：true    未登录：false；
 */
function xbCheckLogin(){
	var isLogin=false;
	$.ajaxSetup({ 
	    async : false 
	});  
	// ajax检测是否登录
	$.get(xbCheckLoginUrl, function(data) {
		if (data['error_code']==0) {
			isLogin=true;
		}
	},'json');
	return isLogin;
}

/**
 * 如果登录直接访问连接；未登录则弹出登录框
 * @param  {string} url 连接
 */
function xbNeedLogin(url){
	if(xbCheckLogin()){
		xbGoTo(url);
	}else{
		xbAlert('您需要登录');
		// 设置cookie
		xbSetCookie('thisUrl',url);
		// 显示登录框
		xbShowLogin()
	}
}

/**
 * 需要确认的跳转
 * @param  {string} url  跳转的连接
 * @param  {string} word 确认的提示语 默认是 确认操作？
 */
function xbNeedConfirm(url,word){
	var word=arguments[1] ? arguments[1] : '确认操作';
	if (confirm(word)) {
		location.href=url;
	}
}

/**
 * 获取form中的数据并转为json对象格式
 * @param  {object} obj form对象
 * @return {json}       json对象
 */
function xbGetForm(obj){
	var formData=$(obj).serializeArray();
	var formArray={};
	$.each(formData, function(index, val) {
		formArray[val['name']]=val['value'];
	});
	return formArray;
}

/**
 * 设置cookie
 * @param {string} name  键名
 * @param {string} value 键值
 * @param {integer} days cookie周期
 */
function xbSetCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }else{
        var expires = "";
    }
    document.cookie = name+"="+value+expires+"; path=/";
}
 
// 获取cookie
function xbGetCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
 
// 删除cookie
function xbDeleteCookie(name) {
    xbSetCookie(name,"",-1);
}
