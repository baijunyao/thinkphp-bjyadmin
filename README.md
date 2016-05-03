## 简介
使用thinkphp开发项目的过程中把一些常用的功能或者第三方sdk集成好；开源供亲们参考
这些都是经过线上运营考验的；无毒害可以免费放心折腾使用；只要不会某一天找到我说因为借鉴了这个开源产品的一些方法；而导致了好几个亿的损失；要我负责并赔偿就好；^_^

## 使用说明
1. 请将程序直接放在根目录下；不要多层目录；例如正确：www/;错误：www/bjyadmin/ ；
2. 默认用户名：admin   密码：123456

## 针对thinkphp的改进优化；
1. 修复tinkphp的session设置周期无效的bug；
2. 自定义标签 /Application/Common/Tag/My.class.php；
3. 将html视图页面分离；

## php集成
1. PHPMail邮件类库；
2. 容联云通讯短信验证码
3. 阿里支付宝pc端支付及移动端支付接口
4. 阿里oss云存储
5. 融云即时通讯
6. 友盟推送
7. Memcached缓存
8. Auth权限管理

## php集成使用说明
大量常用的php工具及sdk已经集成；并写成函数；只要配置好各种key使用非常之方便；<br />
例如：send_email('邮箱','标题','内容'); 即可发送一封邮件<br />
设置好需要上传到oss的目录；使用upload('路径'); 用户上传文件时会自动上传到oss；并且可以选择是否在本地保留文件；<br />
更多功能可以查看 /Application/Common/Common/function.php 等源代码

## 前端集成
1. boostrap、sui、framework7、frozenui等前端框架；
2. ueditor、umeditor百度富文本编辑器；
3. webuploader上传、iCheck美化的单选复选按钮、layer弹出层、laydate日期等插件；
4. font-awesome、animate.css；

## 前端使用说明
大量常用的框架及插件已经集成并且加入标签库；在html页面中中只需要很简单的代码就可以引入；<br />
例如：<br /> `<ueditor name="content" />` 这样一个简单的标签就可以将editor编辑器引入并设置name名为content;
提交post时后台即可通过content字段直接获取到内容；<br />
`<jquery />`标签可引入jQuery<br />
不知道怎么自定义标签？传送门：http://baijunyao.com/article/21 <br />
更多标签可以查看 /Application/Common/Tag/My.class.php 源代码

## 链接
官网：http://www.baijunyao.com <br />
github：https://github.com/shuaibai/thinkphp-bjyadmin <br />
oschina：http://git.oschina.net/shuaibai123/thinkphp-bjyadmin <br />

## 商业友好的开源协议
Thinkbjy遵循Apache2开源协议发布。Apache Licence是著名的非盈利开源组织Apache采用的协议。该协议和BSD类似，鼓励代码共享和尊重原作者的著作权，同样允许代码修改，再作为开源或商业软件发布。


