$(function(){
    // 回车登录
    $('.fwb-password').keyup(function(e){
        if(e.keyCode == 13){
            $(".fwb-log").trigger("click");
        }
    });
    // 返回顶部
    $('.fwb-back-top').click(function() {
    	$('html,body').animate({'scrollTop':'0'});
    });
    // 初始化emoji
    window.emojiPicker = new EmojiPicker({
        emojiable_selector: '[data-emojiable=true]',
        assetsPath: 'http://xueba17.oss-cn-beijing.aliyuncs.com/Public/statics/emoji/images',
        'iconSize': 20
    });
    window.emojiPicker.discover();
    
    // 需要转emoji的选择器 表情选择、聊天框
    var toEmoji=['.bjy-emoji-box','.lqk-cfont-one','.bjy-cbhl-content'];
    $.each(toEmoji, function(index, val) {
        $.each($(val), function(k, v) {
            var str=$(v).html();
            var newStr=window.emojiPicker.unicodeToImage(str);
            $(v).html(newStr);            
        });

    });
    // 评论框内的图片转换为from中utf8
    var imageDiv=['.bjy-emoji-box1','.bjy-emoji-box2','.bjy-emoji-box3'];
    var emojiFrom=['.bjy-emoji-from1','.bjy-emoji-from2','.bjy-emoji-from3'];
    $.each(imageDiv, function(index, val) {
        $(val).blur(function(event) {
            var str=emojiDeleteTag($(this).html());
            $(emojiFrom[index]).val(str);
        });
    });

    /**
     * 将带有emoji图片的字符串转为utf8
     * @param  {string} str 带emoji图片的字符串
     * @return {string}     utf8字符串
     */
    function emojiDeleteTag(str){
        var str=str.replace(/<img.*?src=\".*?\".*?style=\".*?\".*?alt=\"/g,'');
        var str=str.replace(/<img.*?style=\'.*?\'.*?alt=\"/g,'');
        var str=str.replace(/\".*?src=\".*?\">/g,'');
        str=str.replace(/:">/g,':');
        str=window.emojiPicker.colonToUnicode(str);
        return str;
    }
    // 显示或隐藏表情框
    $('.bjy-emoji-ico').click(function(event) {
        var parentObj=$(this).parents('.bjy-show-out').eq(0);
        parentObj.find('.bjy-emoji-box').toggleClass('show');
        parentObj.find('.bjy-emoji-category').eq(0).click();
    });
    // 点击emoji分类；获取分类下的表情
    $('.bjy-emoji-box').on('click', '.bjy-emoji-category', function(event) {
        var indexNumber=$(this).index(),
            thisEmojiConfig=Config.EmojiCategories[indexNumber],
            thisHtml='',
            colon='';
        // 将colon格式的标签转为图片格式
        $.each(thisEmojiConfig, function(index, val) {
            colon +=':'+Config.Emoji[val][1][0]+':';
            thisHtml=window.emojiPicker.colonToImage(colon);
        });
        // 将图片插入到div中
        $(this).parents('.bjy-emoji-box').eq(0).find('.bjy-emoji-imgs').eq(indexNumber).html(thisHtml);
    });
    // 点击添加表情
    $('body').on('click','.bjy-emoji-box img', function(event) {
        var str=$(this).prop("outerHTML");
        $(this).parents('.bjy-show-out').eq(0).find('.bjy-show-box').focus();
        insertHtmlAtCaret(str);
        // 选择表情后关闭表情选择框
        $(this).parents('.bjy-show-out').eq(0).find('.bjy-emoji-box').removeClass('show')
    });
    
})


/**
 * 在textarea光标后插入内容
 * @param  string  str 需要插入的内容
 */
function insertHtmlAtCaret(str) {
    var sel, range;
    if(window.getSelection){
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();
            var el = document.createElement("div");
            el.innerHTML = str;
            var frag = document.createDocumentFragment(), node, lastNode;
            while ( (node = el.firstChild) ) {
                lastNode = frag.appendChild(node);
            }
                range.insertNode(frag);
            if(lastNode){
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    } else if (document.selection && document.selection.type != "Control") {
        document.selection.createRange().pasteHTML(str);
    }
}



// 删除提示和样式
function delete_hint(obj){
    var word=$(obj).text();
    if(word=='请先登陆后发表评论' || word=='请先登陆后回复评论' || word=='请先登陆后发表学霸秀'){
        $(obj).text('');
        $(obj).css('color', '#333');
    }
}

// 全局通用弹出提示
function xbAlert(str){
	var s=1;
	var i=3;
	if(s==1){
		s=0;
		setTimeout(function(){
			s=1;
		},1000);
		$('#lqk-General-tip').remove();
		// 插入div
		$('body').prepend('<div id="lqk-General-tip" class="lqk-General-js"><div class="lqk-General-img"></div><div class="lqk-General-border"></div><div class="lqk-General-text"><p class="lqk-General-word">'+str+'</p></div><div class="lqk-General-mark lqk-General-click"></div></div>');
		// 显示div
		$('.lqk-General-js').animate({right:0},300,function(){
			generalTipRun();
		});
		// div的hover效果
		$('.lqk-General-js').hover(function() {
			clearInterval(lqkTimer);
		}, function() {
			generalTipRun();
		});
		// 关闭div
		$('.lqk-General-click').click(function() {
			closeGeneralTips();
		});
		// 定时器
		function generalTipRun(){
			lqkTimer=setInterval(function(){
				i--;
				if(i==0){
					closeGeneralTips();
					clearInterval(lqkTimer);
				}
			},1000)	
		}
		// 关闭并删除div
		function closeGeneralTips(){
			$('.lqk-General-js').animate({right:-400}, 300,function(){
				$('#lqk-General-tip').remove();
			});
		}
	}
}

// 弹出登录框
function xbShowLogin(){
	$('#fwb-model').modal('show');
}

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

//点击刷新验证码
function flushCode() {
	var src =$('#code').attr('src');
	if( src.indexOf('?')>0){  
	    $('#code').attr("src", src+'&random='+Math.random());  
	}else{  
	   	$('#code').attr("src", src.replace(/\?.*$/,'')+'?'+Math.random());  
	} 
};

