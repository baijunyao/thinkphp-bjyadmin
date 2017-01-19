if (xbIsLogin) {
    var instanceObj;
    RongIMClient.init(rongKey);
    // 设置连接监听状态 （ status 标识当前连接状态）
     // 连接状态监听器
    RongIMClient.setConnectionStatusListener({
        onChanged: function (status) {
            switch (status) {
                //链接成功
                case RongIMLib.ConnectionStatus.CONNECTED:
                    console.log('链接成功');
                    break;
                //正在链接
                case RongIMLib.ConnectionStatus.CONNECTING:
                    console.log('正在链接');
                    break;
                //重新链接
                case RongIMLib.ConnectionStatus.DISCONNECTED:
                    console.log('断开连接');
                    break;
                //其他设备登录
                case RongIMLib.ConnectionStatus.KICKED_OFFLINE_BY_OTHER_CLIENT:
                    console.log('其他设备登录');
                    break;
                  //网络不可用
                case RongIMLib.ConnectionStatus.NETWORK_UNAVAILABLE:
                  console.log('网络不可用');
                  break;
                }
        }});

    // 消息监听器
    RongIMClient.setOnReceiveMessageListener({
        // 接收到的消息
        onReceived: function (message) {
            // 判断消息类型
            switch(message.messageType){
                case RongIMClient.MessageType.TextMessage:
//                  接收发送给别人的消息
                    if(message.senderUserId!=xbUserInfo['id']){
                        // 发消息的用户id
                        var sendMessageUid=message.senderUserId;
                        // 接收到的消息
                        var receiveMessage=message.content.content;
                        // 获得utf的emoji表情
                        receiveMessage=RongIMLib.RongIMEmoji.emojiToHTML(receiveMessage);
                        // 将utf8表情转为图片
                        receiveMessage=window.emojiPicker.unicodeToImage(receiveMessage)
                        // 判断好友列表中是否有此用户
                        var htmlObj=rongGetFriendListObj(sendMessageUid);
                        if (!htmlObj) {
                            $.ajax({
                                url: rongUserInfoUrl,
                                type: 'POST',
                                dataType: 'json',
                                data: {uids: sendMessageUid},
                                async : false,
                                success: function(data){
                                    var data=data['data'][0];
                                    var userInfo={
                                        'id': data['id'],
                                        'isStudent': data['is_student'],
                                        'avatar': data['avatar'],
                                        'username': data['username'],
                                        'schoolName': data['school'],
                                        'schoolMajorEducation': data['school_major_education'],
                                        'date': new Date().getHours()+':'+new Date().getMinutes()+':'+new Date().getSeconds(),
                                        'count': 0
                                    };
                                    htmlObj=$(rongCreateFriendInfo(userInfo));
                                    $('#bjy-chat-modal .bjy-friend-list').prepend(htmlObj);
                                }
                            })
                        }
                        // 如果正在聊天 则将消息追加到聊天框中 否则好友列表插入一条
                        if (htmlObj.hasClass('bjy-flo-checked')) {
                            // 将消息插入对话框中
                            var messageContent={
                                'direction':'bjy-cbhla-left',
                                'avatar': htmlObj.attr('data-avatar'),
                                'content':receiveMessage
                            };
                            // 创建消息对象html
                            var messageStr=rongCreateMessage(messageContent);
                            $('#bjy-chat-modal .bjy-cb-history').append(messageStr);
                            rongRecountMessage(sendMessageUid,0);
                            rongChangeScrollHeight(99999);
                        }else{
                            // 重新计算未读数量
                            rongRecountMessage(sendMessageUid,1);    
                        }
                        
                        // 弹框提示
                        // alertStr=htmlObj.attr('data-username')+'：'+receiveMessage;
                        // alert(alertStr);
                    }

                    break;
                case RongIMClient.MessageType.ImageMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.DiscussionNotificationMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.LocationMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.RichContentMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.DiscussionNotificationMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.InformationNotificationMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.ContactNotificationMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.ProfileNotificationMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.CommandNotificationMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.CommandMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.UnknownMessage:
                    // do something...
                    break;
                default:
                    // 自定义消息
                    // do something...
            }
        }
    });


    // 连接融云服务器。
    RongIMClient.connect(rongToken, {
        onSuccess: function(userId) {
            // 获取会话列表
            RongIMClient.getInstance().getRemoteConversationList({
                onSuccess: function(list) {
                    // console.log(list);
                    var uids;
                    $.each(list, function(index, val) {
                        if (index==0) {
                            uids=val['targetId']
                        }else{
                            uids+=','+val['targetId'];
                        }
                    });
                    // 获取好友列表的用户数据
                    $.post(rongUserInfoUrl, {'uids': uids}, function(data) {
                        data=data['data'];
                        var str='';
                        $.each(data, function(index, val) {
                            var userInfo={
                                'id':val['id'],
                                'isStudent':val['is_student'],
                                'avatar':val['avatar'],
                                'username':val['username'],
                                'schoolName':val['school']
                            };
                            var times=list[index]['latestMessage']['sentTime'];
                            // 获取最后一条消息的时间
                            userInfo['date']=new Date(times).getHours()+':'+new Date(times).getMinutes()+':'+new Date(times).getSeconds();
                            // 获取未读消息数量统计
                            var conversationtype = RongIMLib.ConversationType.PRIVATE; // 私聊
                            RongIMClient.getInstance().getUnreadCount(conversationtype,userInfo['id'],{
                                onSuccess: function(count) {
                                    userInfo['count']=count;
                                },
                                    onError: function(error) {
                                }
                            });
                            // 判断是否是学霸
                            if (val['is_student']) {
                                userInfo['schoolName']=val['school'];
                                userInfo['schoolMajorEducation']=val['school_major_education'];
                            }else{
                                userInfo['schoolName']='';
                                userInfo['schoolMajorEducation']='';
                            }
                            str+=rongCreateFriendInfo(userInfo);

                        });
                        $('#bjy-chat-modal .bjy-friend-list').html(str);
                        // 获取未读消息的总数
                        RongIMClient.getInstance().getTotalUnreadCount({
                            onSuccess: function(count) {
                                if (count!=0) {
                                    $('#totalunreadcount').text(count).show();
                                }
                            },
                            onError: function(error) {
                            }
                        });
                    },'json');
                    // // 删除好友列表
                    // RongIMClient.getInstance().removeConversation(RongIMLib.ConversationType.PRIVATE,'89',{
                    //    onSuccess:function(isClear){
                    //             // isClear true 清除成功 ， false 清除失败
                    //    },
                    //    onError:function(){
                    //        //清除遇到错误。
                    //    }
                    // });
                },
                onError: function(error) {
                    console.log('获取会话列表失败')
                }
            },null);

        },
        onTokenIncorrect: function() {
          console.log('token无效');
        },
        onError:function(errorCode){
          var info = '';
          switch (errorCode) {
            case RongIMLib.ErrorCode.TIMEOUT:
              info = '超时';
              break;
            case RongIMLib.ErrorCode.UNKNOWN_ERROR:
              info = '未知错误';
              break;
            case RongIMLib.ErrorCode.UNACCEPTABLE_PaROTOCOL_VERSION:
              info = '不可接受的协议版本';
              break;
            case RongIMLib.ErrorCode.IDENTIFIER_REJECTED:
              info = 'appkey不正确';
              break;
            case RongIMLib.ErrorCode.SERVER_UNAVAILABLE:
              info = '服务器不可用';
              break;
          }
          console.log(errorCode);
        }
    });


}

/**
 * 发送消息
 * @param  {integer} uid  用户id
 * @param  {string}  word 发送的消息
 */
function rongSendMessage(uid,word){
     // 定义消息类型,文字消息使用 RongIMLib.TextMessage
     var msg = RongIMLib.TextMessage.obtain(word);
     var conversationtype = RongIMLib.ConversationType.PRIVATE; // 私聊
     var targetId = uid; // 目标 Id
     RongIMClient.getInstance().sendMessage(conversationtype, targetId, msg, {
        // 发送消息成功
        onSuccess: function (message) {
            //message 为发送的消息对象并且包含服务器返回的消息唯一Id和发送消息时间戳
            console.log("Send successfully");
        },
        onError: function (errorCode,message) {
            var info = '';
            switch (errorCode) {
                case RongIMLib.ErrorCode.TIMEOUT:
                    info = '超时';
                    break;
                case RongIMLib.ErrorCode.UNKNOWN_ERROR:
                    info = '未知错误';
                    break;
                case RongIMLib.ErrorCode.REJECTED_BY_BLACKLIST:
                    info = '在黑名单中，无法向对方发送消息';
                    break;
                case RongIMLib.ErrorCode.NOT_IN_DISCUSSION:
                    info = '不在讨论组中';
                    break;
                case RongIMLib.ErrorCode.NOT_IN_GROUP:
                    info = '不在群组中';
                    break;
                case RongIMLib.ErrorCode.NOT_IN_CHATROOM:
                    info = '不在聊天室中';
                    break;
                default :
                    info = x;
                    break;
            }
            console.log('发送失败:' + info);
        }
    });
     
       
}

/**
 * 获取历史记录
 * @param  {integer} uid 用户id
 */
function rongGetMessage(uid,userInfo,scrollTopNumber){
    // 连接融云服务器。
    RongIMClient.getInstance().getHistoryMessages(RongIMLib.ConversationType.PRIVATE, uid, null, 20, {
        onSuccess: function(list, hasMsg) {
            // 判断是否获取到数据；如果没有；递归继续获取 作用是针对融云经常不返回数据的bug处理
            if (list.length==0) {
                rongGetMessage(uid,userInfo,scrollTopNumber)
            }else{
                var str='',
                    messageContent={};
                $.each(list, function(index, val) {
                    // 判断是自己发的消息；或是对方发的消息
                    if (val['senderUserId']==uid) {
                        messageContent['direction']='bjy-cbhla-left';
                        messageContent['avatar']=userInfo['avatar'];
                    }else{
                        messageContent['direction']='bjy-cbhla-right';
                        messageContent['avatar']=xbUserInfo['avatar'];
                    }
                    messageContent['content']=val['content']['content'];
                    str +=rongCreateMessage(messageContent);
                });
                // 获得utf的emoji表情
                str=RongIMLib.RongIMEmoji.emojiToHTML(str);
                // 将utf8表情转为图片
                str=window.emojiPicker.unicodeToImage(str)
                $('#bjy-chat-modal .bjy-cb-history').prepend(str);   
                rongChangeScrollHeight(scrollTopNumber);                     
            }

        },
        onError: function(error) {
            console.log('APP未开启消息漫游或处理异常')
        }
    });        
}

/**
 * 组合聊天框中的消息
 * @param  {obj} messageContent 消息内容
 */
function rongCreateMessage(messageContent){
    var approveImg=messageContent['isStudent'] ? '<img class="bjy-cbhla-approve" src="/tpl/default/User/Public/images/user-v.png">' : '';
    str='<div class="bjy-cbh-one '+messageContent['direction']+'"><div class="bjy-cbhl-avatar"><img class="bjy-cbhla-img" src="'+messageContent['avatar']+'">'+approveImg+'<div class="bjy-cbhla-triangle"></div></div><div class="bjy-cbhl-content">'+messageContent['content']+'</div></div>';
    return str;
}


/**
 * 清空单个用户的未读消息数量
 * @param  {integer} uid 用户id
 */
function rongClearnMessage(uid){
    var conversationtype = RongIMLib.ConversationType.PRIVATE; // 私聊
    RongIMClient.getInstance().clearUnreadCount(conversationtype,uid,{
        onSuccess:function(isClear){
            // isClear true 清除成功 ， false 清除失败
            if (isClear) {
                // 获取此用户在好友列表中的html对象
                var htmlObj=rongGetFriendListObj(uid);
                // 获取原来的未读消息数量
                var oldCount=htmlObj.find('.bjy-flo-count').text();
                var oldCount=-oldCount;
                // 重新计算未读数量
                rongRecountMessage(uid,oldCount);
            }
        },
        onError:function(){
            //清除遇到错误。
        }
    });
}

/**
 * 传递用户id获取在聊天列表中的html对象
 * @param  {integer} uid 用户id
 * @return {obj}     html对象
 */
function rongGetFriendListObj(uid){
    var obj=$('#bjy-chat-modal .bjy-fl-one[data-uid='+uid+']');
    obj=obj.length==0 ? undefined : obj;
    return obj;

}

/**
 * 创建好友列表的html
 * @param  {obj} userInfo 用户的数据
 */
function rongCreateFriendInfo(userInfo) {
    // 判断是否是学霸
    var approveImg=userInfo['isStudent'] ? '<img class="bjy-floa-approve" src="/tpl/default/User/Public/images/user-v.png">' : '';
    // 判断是否有未读消息
    var countStrShow=userInfo['count'] ?  'xb-show': '';
    var countStr='<div class="bjy-flo-count '+countStrShow+'">'+userInfo['count']+'</div>';
    var str='<div class="bjy-fl-one" data-edu="" data-avatar="'+userInfo['avatar']+'" data-username="'+userInfo['username']+'" data-uid="'+userInfo['id']+'"><div class="bjy-flo-avatar"><img class="bjy-floa-img" src="'+userInfo['avatar']+'">'+approveImg+countStr+'</div><ul class="bjy-flo-info"><li class="bjy-flo-username">'+userInfo['username']+'<span class="bjy-flou-time">'+userInfo['date']+'</span></li><li class="bjy-flo-school"></li></ul></div>';
    return str;
}

/**
 * 重新计算未读消息数量
 * @param  {integer} uid 用户id
 * @param  {integer} num 正数增加 负数减少
 */
function rongRecountMessage(uid,num){
    // 获取此用户在好友列表中的html对象
    var htmlObj=rongGetFriendListObj(uid);
    var countObj=htmlObj.find('.bjy-flo-count');
    // 获取原来的未读消息数量
    var oldCount=countObj.text();
    // 计算新的未读消息数量
    var newCount=oldCount*1+num*1;
    countObj.text(newCount);
    // 获取总共的未读消息数量
    var totalCount=$('#totalunreadcount').text();
    // 计算还有多少条未读消息
    var newTotalCount=totalCount*1+num*1;
    $('#totalunreadcount').text(newTotalCount);
    // 如果有未读消息就显示 没有则隐藏
    if (newTotalCount==0) {
        $('#totalunreadcount').hide();
    }else{
        $('#totalunreadcount').show();
    }
    if (newCount==0) {
        countObj.hide();
    }else{
        countObj.show();
    }
}

/**
 * 调整对话框滚动轴位置
 * @param  {integer} num 滚动轴位置
 */
function rongChangeScrollHeight(num){
    $('#bjy-chat-modal .bjy-cb-history').scrollTop(num);
}

$(function(){
    // 点击左侧好友列表；获取历史消息
    $('.bjy-friend-list').on('click', '.bjy-fl-one', function(event) {
        // 获取用户信息
        var uid=$(this).attr('data-uid'),
            userInfo={
                'username': $(this).attr('data-username'),
                'avatar': $(this).attr('data-avatar'),
            };
        // 如果已经选中；则不再获取历史消息
        if (!$(this).hasClass('bjy-flo-checked')) {
            $('#bjy-chat-modal .bjy-cb-history').html('');
            // 增加选中样式
            $(this).addClass('bjy-flo-checked').siblings('.bjy-fl-one').removeClass('bjy-flo-checked');
            // 设置uid
            $('#bjy-chat-modal .bjy-cbh-send').attr('data-uid',uid);
            $('#bjy-chat-modal .bjy-cbh-send').attr('data-avatar',userInfo['avatar']);
            // 设置聊天框title
            $('#bjy-chat-modal .bjy-tt-name').text(userInfo['username']);
            // 获取历史记录
            rongGetMessage(uid,userInfo,99999);
        }
        // 清空单个用户的未读消息数量
        rongClearnMessage(uid);
    });

    // 点击发送消息按钮
    $('.bjy-cbh-send').click(function(event) {
        // 获取消息内容和uid
        var str=$('#bjy-chat-modal .bjy-cbs-content').val(),
            htmlStr=$('#bjy-chat-modal .bjy-emoji-box3').html(),
            uid=$(this).attr('data-uid');
        if (uid=='') {
            alert('请选择聊天的好友');
            return false;
        }
        if (str=='') {
            alert('请输入聊天内容');
            return false;
        }
        // 将消息插入对话框中
        var messageContent={
            'direction':'bjy-cbhla-right',
            'avatar':xbUserInfo['avatar'],
            'isStudent':xbUserInfo['is_student'],
            'content':htmlStr
        };
        var messageStr=rongCreateMessage(messageContent);
        $('#bjy-chat-modal .bjy-cb-history').append(messageStr);
        // 调整滚动轴到底部
        rongChangeScrollHeight(99999);
        // 发送消息
        rongSendMessage(uid,str);
        // 清空消息框中的内容
        $('#bjy-chat-modal .bjy-cbs-content').val('');
        $('#bjy-chat-modal .bjy-emoji-box3').html('');
        rongClearnMessage(uid)
    });

    // 点击约聊按钮
    $('.bjy-rong-chat').click(function(event) {
        var userInfo={
            'id':$(this).attr('targetid'),
            'avatar':$(this).attr('avatar'),
            'username':$(this).attr('targetname'),
            'count':0,
            'date':new Date().getHours()+':'+new Date().getMinutes()+':'+new Date().getSeconds(),
        };
        // 如果约聊的用户已经在列表中；直接触发点击事件 否则插入列表
        var htmlObj=rongGetFriendListObj(userInfo['id']);
        if (htmlObj) {
            htmlObj.click();
        }else{
            var str=rongCreateFriendInfo(userInfo);
            $('#bjy-chat-modal .bjy-friend-list').prepend(str);
            $('#bjy-chat-modal .bjy-fl-one').eq(0).click();            
        }
        $('#bjy-chat-modal').modal('show');
    });

})



