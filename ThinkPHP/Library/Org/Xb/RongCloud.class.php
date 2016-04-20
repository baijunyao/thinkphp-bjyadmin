<?php
namespace Org\Xb;
/**
 * 融云server API 接口 新版 1.0
 * Class RongCloud
 * @author  caolong
 * @date    2014-12-10  15:30
 * @modify  2015-02-02  10:21
 *
//使用
$p = new RongCloud('appKey','AppSecret');
$r = $p->getToken('11','22','33');
print_r($r);
 */

class RongCloud{
    private $appKey;                //appKey
    private $appSecret;             //secret
    const   SERVERAPIURL = 'https://api.cn.ronghub.com';    //请求服务地址
    private $format;                //数据格式 json/xml


    /**
     * 参数初始化
     * @param $appKey
     * @param $appSecret
     * @param string $format
     */
    public function __construct($appKey,$appSecret,$format = 'json'){
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        $this->format = $format;
    }

    /**
     * 获取 Token 方法
     * @param $userId   用户 Id，最大长度 32 字节。是用户在 App 中的唯一标识码，必须保证在同一个 App 内不重复，重复的用户 Id 将被当作是同一用户。
     * @param $name     用户名称，最大长度 128 字节。用来在 Push 推送时，或者客户端没有提供用户信息时，显示用户的名称。
     * @param $portraitUri  用户头像 URI，最大长度 1024 字节。
     * @return json|xml
     */
    public function getToken($userId, $name, $portraitUri) {
        try{
            if(empty($userId))
                throw new Exception('用户 Id 不能为空');
            if(empty($name))
                throw new Exception('用户名称 不能为空');
            if(empty($portraitUri))
                throw new Exception('用户头像 URI 不能为空');

            $ret = $this->curl('/user/getToken',array('userId'=>$userId,'name'=>$name,'portraitUri'=>$portraitUri));
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    /**
     * 发送会话消息
     * @param $fromUserId   发送人用户 Id。（必传）
     * @param $toUserId     接收用户 Id，提供多个本参数可以实现向多人发送消息。（必传）
     * @param $objectName   消息类型，参考融云消息类型表.消息标志；可自定义消息类型。（必传）
     * @param $content      发送消息内容，参考融云消息类型表.示例说明；如果 objectName 为自定义消息类型，该参数可自定义格式。（必传）
     * @param string $pushContent   如果为自定义消息，定义显示的 Push 内容。(可选)
     * @param string $pushData  针对 iOS 平台，Push 通知附加的 payload 字段，字段名为 appData。(可选)
     * @return json|xml
     */
    public function messagePublish($fromUserId, $toUserId = array(), $objectName, $content, $pushContent='', $pushData = '') {
        try{
            if(empty($fromUserId))
                throw new Exception('发送人用户 Id 不能为空');
            if(empty($toUserId))
                throw new Exception('接收用户 Id 不能为空');
            if(empty($objectName))
                throw new Exception('消息类型 不能为空');
            if(empty($content))
                throw new Exception('发送消息内容 不能为空');

            $params = array(
                'fromUserId'=>$fromUserId,
                'objectName'=>$objectName,
                'content'=>$content,
                'pushContent'=>$pushContent,
                'pushData'=>$pushData,
                'toUserId' => $toUserId
            );

            $ret = $this->curl('/message/publish', $params);
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    /**
     * 以一个用户身份向群组发送消息
     * @param $fromUserId           发送人用户 Id。（必传）
     * @param $toGroupId             接收群Id，提供多个本参数可以实现向多群发送消息。（必传）
     * @param $objectName           消息类型，参考融云消息类型表.消息标志；可自定义消息类型。（必传）
     * @param $content              发送消息内容，参考融云消息类型表.示例说明；如果 objectName 为自定义消息类型，该参数可自定义格式。（必传）
     * @param string $pushContent   如果为自定义消息，定义显示的 Push 内容。(可选)
     * @param string $pushData      针对 iOS 平台，Push 通知附加的 payload 字段，字段名为 appData。(可选)
     * @return json|xml
     */
    public function messageGroupPublish($fromUserId, $toGroupId = array(), $objectName, $content, $pushContent='', $pushData = '') {
        try{
            if(empty($fromUserId))
                throw new Exception('发送人用户 Id 不能为空');
            if(empty($toGroupId))
                throw new Exception('接收群Id 不能为空');
            if(empty($objectName))
                throw new Exception('消息类型 不能为空');
            if(empty($content))
                throw new Exception('发送消息内容 不能为空');

            $params = array(
                'fromUserId'=>$fromUserId,
                'objectName'=>$objectName,
                'content'=>$content,
                'pushContent'=>$pushContent,
                'pushData'=>$pushData,
                'toGroupId' => $toGroupId
            );

            $ret = $this->curl('/message/group/publish',$params);
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }


    /**
     * 一个用户向聊天室发送消息
     * @param $fromUserId               发送人用户 Id。（必传）
     * @param $toChatroomId             接收聊天室Id，提供多个本参数可以实现向多个聊天室发送消息。（必传）
     * @param $objectName               消息类型，参考融云消息类型表.消息标志；可自定义消息类型。（必传）
     * @param $content                  发送消息内容，参考融云消息类型表.示例说明；如果 objectName 为自定义消息类型，该参数可自定义格式。（必传）
     * @return json|xml
     */
    public function messageChatroomPublish($fromUserId, $toChatroomId = array(), $objectName, $content) {
        try{
            if(empty($fromUserId))
                throw new Exception('发送人用户 Id 不能为空');
            if(empty($toChatroomId))
                throw new Exception('接收聊天室Id 不能为空');
            if(empty($objectName))
                throw new Exception('消息类型 不能为空');
            if(empty($content))
                throw new Exception('发送消息内容 不能为空');
            $params = array(
                'fromUserId' => $fromUserId,
                'objectName' => $objectName,
                'content' => $content,
                'toChatroomId' => $toChatroomId
            );

            $ret = $this->curl('/message/chatroom/publish',$params);
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }
    
    /**
     * 发送讨论组消息
     * @param $fromUserId               发送人用户 Id。（必传）
     * @param $toDiscussionId             接收讨论组 Id。（必传）
     * @param $objectName               消息类型，参考融云消息类型表.消息标志；可自定义消息类型。（必传）
     * @param $content                  发送消息内容，参考融云消息类型表.示例说明；如果 objectName 为自定义消息类型，该参数可自定义格式。（必传）
     * @param string $pushContent   如果为自定义消息，定义显示的 Push 内容。(可选)
     * @param string $pushData  针对 iOS 平台，Push 通知附加的 payload 字段，字段名为 appData。(可选)
     * @return json|xml
     */
    public function messageDiscussionPublish($fromUserId,$toDiscussionId,$objectName,$content,$pushContent='',$pushData='') {
        try{
            if(empty($fromUserId))
                throw new Exception('发送人用户 Id 不能为空');
            if(empty($toDiscussionId))
                throw new Exception('接收讨论组 Id 不能为空');
            if(empty($objectName))
                throw new Exception('消息类型 不能为空');
            if(empty($content))
                throw new Exception('发送消息内容 不能为空');
    
            $params = array(
                    'fromUserId'=>$fromUserId,
                    'toDiscussionId'=>$toDiscussionId,
                    'objectName'=>$objectName,
                    'content'=>$content,
                    'pushContent'=>$pushContent,
                    'pushData'=>$pushData
            );
            $paramsString = http_build_query($params);
            $ret = $this->curl('/message/discussion/publish',$paramsString);
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    /**
     * 一个用户向一个或多个用户发送系统消息
     * @param $fromUserId       发送人用户 Id。（必传）
     * @param $toUserId         接收用户Id，提供多个本参数可以实现向多用户发送系统消息。（必传）
     * @param $objectName       消息类型，参考融云消息类型表.消息标志；可自定义消息类型。（必传）
     * @param $content          发送消息内容，参考融云消息类型表.示例说明；如果 objectName 为自定义消息类型，该参数可自定义格式。（必传）
     * @param string $pushContent   如果为自定义消息，定义显示的 Push 内容。(可选)
     * @param string $pushData  针对 iOS 平台，Push 通知附加的 payload 字段，字段名为 appData。(可选)
     * @return json|xml
     */
    public function messageSystemPublish($fromUserId,$toUserId = array(),$objectName,$content,$pushContent='',$pushData = '') {
        try{
            if(empty($fromUserId))
                throw new Exception('发送人用户 Id 不能为空');
            if(empty($toUserId))
                throw new Exception('接收用户 Id 不能为空');
            if(empty($objectName))
                throw new Exception('消息类型 不能为空');
            if(empty($content))
                throw new Exception('发送消息内容 不能为空');

            $params = array(
                'fromUserId' => $fromUserId,
                'objectName' => $objectName,
                'content' => $content,
                'pushContent' => $pushContent,
                'pushData' => $pushData,
                'toUserId' => $toUserId
            );

            $ret = $this->curl('/message/system/publish',$params);
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    /**
     * 某发送消息给一个应用下的所有注册用户。
     * @param $fromUserId       发送人用户 Id。（必传）
     * @param $objectName       消息类型，参考融云消息类型表.消息标志；可自定义消息类型。（必传）
     * @param $content          发送消息内容，参考融云消息类型表.示例说明；如果 objectName 为自定义消息类型，该参数可自定义格式。（必传）
     * @return json|xml
     */
    public function messageBroadcast($fromUserId,$objectName,$content) {
        try{
            if(empty($fromUserId))
                throw new Exception('发送人用户 Id 不能为空');
            if(empty($objectName))
                throw new Exception('消息类型不能为空');
            if(empty($content))
                throw new Exception('发送消息内容不能为空');
            $ret = $this->curl(
                '/message/broadcast',
                array(
                    'fromUserId' => $fromUserId,
                    'objectName' => $objectName,
                    'content' => $content
                )
            );
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    /**
     * 获取 APP 内指定某天某小时内的所有会话消息记录的下载地址
     * @param $date     指定北京时间某天某小时，格式为：2014010101,表示：2014年1月1日凌晨1点。（必传）
     * @return json|xml
     */
    public function messageHistory($date) {
        try{
            if(empty($date))
                throw new Exception('时间不能为空');
            $ret = $this->curl('/message/history', array('date' => $date));
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    /**
     * 删除 APP 内指定某天某小时内的所有会话消息记录
     * @param $date string 指定北京时间某天某小时，格式为2014010101,表示：2014年1月1日凌晨1点。（必传）
     * @return mixed
     */
    public function messageHistoryDelete($date) {
        try{
            if(empty($date))
                throw new Exception('时间 不能为空');
            $ret = $this->curl('/message/history/delete', array('date' => $date));
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }


    /**
     * 向融云服务器提交 userId 对应的用户当前所加入的所有群组。
     * @param $userId           被同步群信息的用户Id。（必传）
     * @param array $data       该用户的群信息。（必传）array('key'=>'val')
     * @return json|xml
     */
    public function groupSync($userId, $data = array()) {
        try{
            if(empty($userId))
                throw new Exception('被同步群信息的用户 Id 不能为空');
            if(empty($data))
                throw new Exception('该用户的群信息 不能为空');
            $arrKey = array_keys($data);
            $arrVal = array_values($data);
            $params = array(
                'userId' => $userId
            );
            foreach ($data as $key => $value) {
                $params['group[' . $key . ']'] = $value;
            }

            $ret = $this->curl('/group/sync', $params);
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }


    /**
     * 将用户加入指定群组，用户将可以收到该群的消息。
     * @param $userId           要加入群的用户 Id。（必传）
     * @param $groupId          要加入的群 Id。（必传）
     * @param $groupName        要加入的群 Id 对应的名称。（可选）
     * @return json|xml
     */
    public function groupJoin($userId, $groupId, $groupName) {
        try{
            if(empty($userId))
                throw new Exception('被同步群信息的用户 Id 不能为空');
            if(empty($groupId))
                throw new Exception('加入的群 Id 不能为空');
            if(empty($groupName))
                throw new Exception('加入的群 Id 对应的名称不能为空');
            $ret = $this->curl('/group/join',
                array(
                    'userId' => $userId,
                    'groupId' => $groupId,
                    'groupName' => $groupName
                )
            );
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }


    /**
     * 将用户从群中移除，不再接收该群组的消息。
     * @param $userId       要退出群的用户 Id。（必传）
     * @param $groupId      要退出的群 Id。（必传）
     * @return mixed
     */
    public function groupQuit($userId, $groupId) {
        try{
            if(empty($userId))
                throw new Exception('被同步群信息的用户 Id 不能为空');
            if(empty($groupId))
                throw new Exception('加入的群 Id 不能为空');
            $ret = $this->curl('/group/quit',
                array('userId' => $userId, "groupId" => $groupId)
            );
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    /**
     * 解散群组方法  将该群解散，所有用户都无法再接收该群的消息。
     * @param $userId           操作解散群的用户 Id。（必传）
     * @param $groupId          要解散的群 Id。（必传）
     * @return mixed
     */
    public function groupDismiss($userId, $groupId) {
        try{
            if(empty($userId))
                throw new Exception('操作解散群的用户 Id 不能为空');
            if(empty($groupId))
                throw new Exception('要解散的群 Id 不能为空');
            $ret = $this->curl('/group/dismiss',
                array('userId' => $userId, "groupId" => $groupId));
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }


    /**
     * 创建群组，并将用户加入该群组，用户将可以收到该群的消息。注：其实本方法是加入群组方法 /group/join 的别名。
     * @param $userId       要加入群的用户 Id。（必传）
     * @param $groupId      要加入的群 Id。（必传）
     * @param $groupName    要加入的群 Id 对应的名称。（可选）
     * @return json|xml
     */
    public function groupCreate($userId, $groupId, $groupName) {
        try{
            if(empty($userId))
                throw new Exception('要加入群的用户 Id 不能为空');
            if(empty($groupId))
                throw new Exception('要加入的群 Id 不能为空');
            if(empty($groupName))
                throw new Exception('要加入的群 Id 对应的名称 不能为空');
            $ret = $this->curl('/group/create',
                array('userId' => $userId, 'groupId' => $groupId,'groupName' => $groupName)
            );
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }
    
    /**
     * 查询群成员 方法
     * @param $groupId      群 Id。（必传）
     * @return json|xml
     */
    public function groupUserQuery( $groupId ) {
        try{
            if(empty($groupId))
                throw new Exception('要加入的群 Id 不能为空');
            $ret = $this->curl('/group/user/query',
                    array('groupId' => $groupId)
            );
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }


    /**
     * 创建聊天室
     * @param array $data   key:要创建的聊天室的id；val:要创建的聊天室的name。（必传）
     * @return json|xml
     */
    public function chatroomCreate($data = array()) {
        try{
            if(empty($data))
                throw new Exception('要加入群的用户 Id 不能为空');
            $params = array();
            foreach($data as $key=>$val) {
                $k = 'chatroom['.$key.']';
                $params["$k"] = $val;
            }
            $ret = $this->curl('/chatroom/create', $params);
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    /**
     * 销毁聊天室
     * @param $chatroomId   要销毁的聊天室 Id。（必传）
     * @return json|xml
     */
    public function chatroomDestroy($chatroomId) {
        try{
            if(empty($chatroomId))
                throw new Exception('要销毁的聊天室 Id 不能为空');
            $ret = $this->curl('/chatroom/destroy', array('chatroomId' => $chatroomId));
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    /**
     * 查询聊天室信息 方法
     * @param $chatroomId   要查询的聊天室id（必传）
     * @return json|xml
     */
    public function chatroomQuery($chatroomId) {
        try{
            if(empty($chatroomId))
                throw new Exception('要查询的聊天室 Id 不能为空');
            $ret = $this->curl('/chatroom/query', array('chatroomId' => $chatroomId));
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }
    
    /**
     * 查询聊天室内用户
     * @param $chatroomId 聊天室 Id
     */
    public function userChatroomQuery($chatroomId) {
        try{
            if(empty($chatroomId)) {
                throw new Exception('聊天室 Id 不能为空');
            }
            $ret = $this->curl('/chatroom/user/query', array('chatroomId' => $chatroomId));
            if(empty($ret)) {
                throw new Exception('请求失败');
            }
            return $ret;
        } catch(Exception $e) {
            print_r($e->getMessage());
        }
    }


    /**
     * 检查用户在线状态 方法
     * @param $userId    用户 Id。（必传）
     * @return mixed
     */
    public function userCheckOnline($userId) {
        try{
            if(empty($userId))
                throw new Exception('用户 Id 不能为空');
            $ret = $this->curl('/user/checkOnline', array('userId' => $userId));
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }


    /**
     * 封禁用户 方法
     * @param $userId   用户 Id。（必传）
     * @param $minute   封禁时长,单位为分钟，最大值为43200分钟。（必传）
     * @return mixed
     */
    public function userBlock($userId,$minute) {
        try{
            if(empty($userId))
                throw new Exception('用户 Id 不能为空');
            if(empty($minute))
                throw new Exception('封禁时长不能为空');
            $ret = $this->curl('/user/block', array('userId' => $userId, 'minute' => $minute));
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }


    /**
     * 解除用户封禁 方法
     * @param $userId   用户 Id。（必传）
     * @return mixed
     */
    public function userUnBlock($userId) {
        try{
            if(empty($userId))
                throw new Exception('用户 Id 不能为空');
            $ret = $this->curl('/user/unblock', array('userId' => $userId));
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    /**
     * 获取被封禁用户 方法
     * @return mixed
     */
    public function userBlockQuery() {
        try{
            $ret = $this->curl('/user/block/query','');
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }


    /**
     *刷新用户信息 方法  说明：当您的用户昵称和头像变更时，您的 App Server 应该调用此接口刷新在融云侧保存的用户信息，以便融云发送推送消息的时候，能够正确显示用户信息
     * @param $userId   用户 Id，最大长度 32 字节。是用户在 App 中的唯一标识码，必须保证在同一个 App 内不重复，重复的用户 Id 将被当作是同一用户。（必传）
     * @param string $name  用户名称，最大长度 128 字节。用来在 Push 推送时，或者客户端没有提供用户信息时，显示用户的名称。
     * @param string $portraitUri   用户头像 URI，最大长度 1024 字节
     * @return mixed
     */
    public function userRefresh($userId,$name='',$portraitUri='') {
        try{
            if(empty($userId))
                throw new Exception('用户 Id 不能为空');
            if(empty($name))
                throw new Exception('用户名称不能为空');
            if(empty($portraitUri))
                throw new Exception('用户头像 URI 不能为空');
            $ret = $this->curl('/user/refresh',
                array('userId' => $userId, 'name' => $name, 'portraitUri' => $portraitUri));
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }


    /**
     * 添加用户到黑名单
     * @param $userId       用户 Id。（必传）
     * @param $blackUserId  被加黑的用户Id。(必传)
     * @return mixed
     */
    public function userBlacklistAdd($userId,$blackUserId = array()) {
        try{
            if(empty($userId))
                throw new Exception('用户 Id 不能为空');
            if(empty($blackUserId))
                throw new Exception('被加黑的用户 Id 不能为空');

            $params = array(
                'userId' => $userId,
                'blackUserId' => $blackUserId
            );

            $ret = $this->curl('/user/blacklist/add', $params);
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }


    /**
     * 获取某个用户的黑名单列表
     * @param $userId   用户 Id。（必传）
     * @return mixed
     */
    public function userBlacklistQuery($userId) {
        try{
            if(empty($userId))
                throw new Exception('用户 Id 不能为空');
            $ret = $this->curl('/user/blacklist/query', array('userId' => $userId));
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }


    /**
     * 从黑名单中移除用户
     * @param $userId               用户 Id。（必传）
     * @param array $blackUserId    被移除的用户Id。(必传)
     * @return mixed
     */
    public function userBlacklistRemove($userId, $blackUserId = array()) {
        try{
            if(empty($userId))
                throw new Exception('用户 Id 不能为空');
            if(empty($blackUserId))
                throw new Exception('被移除的用户 Id 不能为空');

            $params = array(
                'userId' => $userId,
                'blackUserId' => $blackUserId
            );

            $ret = $this->curl('/user/blacklist/remove', $params);
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }

    }
    
    /**
     * 添加禁言群成员
     * @param $userId   用户 Id。（必传）
     * @param $groupId 群组 Id。（必传）
     * @param $minute 禁言时长，以分钟为单位，可以不传此参数，默认为永久禁言。
     * @return mixed
     */
    public function groupUserGagAdd($userId,$groupId,$minute) {
        try{
            if(empty($userId))
                throw new Exception('用户 Id 不能为空');
            if(empty($groupId))
                throw new Exception('群组 Id 不能为空');
            if (empty($minute))
                throw new Exception('禁言时长 不能为空');
            $params['userId'] = $userId;
            $params['groupId'] = $groupId;
            $params['minute'] = $minute;
            $ret = $this->curl('/group/user/gag/add',$params);
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }
    
    
    /**
     * 移除禁言群成员
     * @param $userId   用户 Id。（必传）
     * @param $groupId 群组 Id。（必传）
     * @return mixed
     */
    public function groupUserGagRollback($userId,$groupId) {
        try{
            if(empty($userId))
                throw new Exception('用户 Id 不能为空');
            if(empty($groupId))
                throw new Exception('群组 Id 不能为空');
            $params['userId'] = $userId;
            $params['groupId'] = $groupId;
            $ret = $this->curl('/group/user/gag/rollback',$params);
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }
    
    /**
     * 查询被禁言群成员
     * @param $groupId 群组 Id。（必传）
     * @return mixed
     */
    public function groupUserGagList($groupId) {
        try{
            if(empty($groupId))
                throw new Exception('群组 Id 不能为空');
            $params['groupId'] = $groupId;
            $ret = $this->curl('/group/user/gag/list',$params);
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }
    
    /**
     * 添加敏感词
     * @param $word 敏感词，最长不超过 32 个字符。（必传）
     * @return mixed
     */
    public function wordfilterAdd($word) {
        try{
            if(empty($word))
                throw new Exception('敏感词不能为空');
            $params['word'] = $word;
            $ret = $this->curl('/wordfilter/add',$params);
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }
    
    /**
     * 移除敏感词
     * @param $word 敏感词，最长不超过 32 个字符。（必传）
     * @return mixed
     */
    public function wordfilterDelete($word) {
        try{
            if(empty($word))
                throw new Exception('敏感词不能为空');
            $params['word'] = $word;
            $ret = $this->curl('/wordfilter/delete',$params);
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }
    /**
     * 查询敏感词列表
     * @return mixed
     */
    public function wordfilterList() {
        try{
            $ret = $this->curl('/wordfilter/list',array());
            if(empty($ret))
                throw new Exception('请求失败');
            return $ret;
        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }


    /**
     * 创建http header参数
     * @param array $data
     * @return bool
     */
    private function createHttpHeader() {
        $nonce = mt_rand();
        $timeStamp = time();
        $sign = sha1($this->appSecret.$nonce.$timeStamp);
        return array(
            'RC-App-Key:'.$this->appKey,
            'RC-Nonce:'.$nonce,
            'RC-Timestamp:'.$timeStamp,
            'RC-Signature:'.$sign,
        );
    }

    /**
     * 重写实现 http_build_query 提交实现(同名key)key=val1&key=val2
     * @param array $formData 数据数组
     * @param string $numericPrefix 数字索引时附加的Key前缀
     * @param string $argSeparator 参数分隔符(默认为&)
     * @param string $prefixKey Key 数组参数，实现同名方式调用接口
     * @return string
     */
    private function build_query($formData, $numericPrefix = '', $argSeparator = '&', $prefixKey = '') {
        $str = '';
        foreach ($formData as $key => $val) {
            if (!is_array($val)) {
                $str .= $argSeparator;
                if ($prefixKey === '') {
                    if (is_int($key)) {
                        $str .= $numericPrefix;
                    }
                    $str .= urlencode($key) . '=' . urlencode($val);
                } else {
                    $str .= urlencode($prefixKey) . '=' . urlencode($val);
                }
            } else {
                if ($prefixKey == '') {
                    $prefixKey .= $key;
                }
                if (is_array($val[0])) {
                    $arr = array();
                    $arr[$key] = $val[0];
                    $str .= $argSeparator . http_build_query($arr);
                } else {
                    $str .= $argSeparator . $this->build_query($val, $numericPrefix, $argSeparator, $prefixKey);
                }
                $prefixKey = '';
            }
        }
        return substr($str, strlen($argSeparator));
    }

    /**
     * 发起 server 请求
     * @param $action
     * @param $params
     * @param $httpHeader
     * @return mixed
     */
    public function curl($action, $params) {
        $action = self::SERVERAPIURL.$action.'.'.$this->format;
        $httpHeader = $this->createHttpHeader();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $action);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->build_query($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false); //处理http证书问题
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);
        if (false === $ret) {
            $ret =  curl_errno($ch);
        }
        curl_close($ch);
        return $ret;
    }
}
