=== WordPress支付宝插件 ===
Contributors: whyun
Donate link: https://me.alipay.com/iztme
Tags: 支付宝,自动下载,商城插件,alipay,wordpress支付宝插件
Requires at least: 2.7
Tested up to: 3.3.2
Stable tag: 2.1

== Description ==
2.0版本集成了支付宝<strong>即时到账、担保交易</strong>和<strong>双功能接口</strong>

这款wordpress支付宝插件是针对wordpress程序、支付宝付款接口而开发的，此插件可以在无任何人工干预的情况下完成付款、自动下载，并有邮件通知，作为一款支付类的插件，安全方面也是做了很多的工作，商品页面的付款表单可以防止恶意窜改，付款完成后的下载链接不会直接显示，只显示一个临时链接，并且有效时间为15分钟。

插件的安装非常简单，不需要繁琐的步骤，安装这款插件后，可以直接在后台设置支付宝商家账户信息，并且可以查看订单信息（包括交易时间、对方QQ、网址、支付宝账号等）
<br />

<strong>使用须知</strong>
在主题文章页面文件（一般为single.php）中，将`<?php alipay_form(); ?>`添加到你希望表单出现的位置

同时可以使用shortcode，在文章中调用表单，方法：在文章编辑器插入`[alipay]`，如果调用其他文章的表单，则`[alipay id="文章ID"]`


* <a href="http://www.iztwp.com/wordpress-alipay.html" title="wordpress支付宝插件" target="_blank">插件页面</a>
* <a href="http://www.iztwp.com/" title="爱主题" target="_blank"><strong>爱主题</strong></a>
* <a href="http://www.bywhy.com/" target="_blank">落幕丶部落格</a>
* <a href="http://www.iztwp.com/" title="wordpress主题">wordpress主题</a>
* <a href="http://www.iztwp.com/" title="wordpress企业主题">wordpress企业主题</a>
* <a href="http://www.iztwp.com/" title="wordpress cms主题">wordpress cms主题</a>
* <a href="http://www.iztwp.com/" title="wordpress图片主题">wordpress图片主题</a>
* <a href="http://www.izt8.com/" title="wordpress主题定制">wordpress主题定制</a>
* <a href="http://www.vmeixi.com/" title="唯美图片">唯美图片</a>


== Installation ==

1. 上传插件文件到 `/wp-content/plugins/` 目录

2. 在Wordpress后台控制面板"插件(Plugins)"菜单下激活wordpress支付宝插件

3. 在Wordpress后台控制面板"支付宝->账号设置"菜单下设置插件的必须信息。（只有经过设置，插件才能正常使用）

4. 添好相关信息，其中交易完成跳转页面可以选择两个：<br>
无自动下载:http://你的wp首页/wp-content/plugins/wp-alipay/return_url.php<br>
有自动下载：http://你的wp首页/wp-content/plugins/wp-alipay/return.php

== Screenshots ==

1.www.iztwp.com

== Frequently Asked Questions ==

= 如何使用插件？ =

在主题文章页面文件（一般为single.php）中，将`<?php alipay_form(); ?>`添加到你希望表单出现的位置

同时可以使用shortcode，在文章中调用表单，方法：在文章编辑器插入`[alipay]`，如果调用其他文章的表单，则`[alipay id="文章ID"]`


== Changelog ==
= 2.0 =
在原来集成即时到帐的基础上，将担保交易和双功能接口也集成到插件中，并可以随时切换使用三个接口
= 1.2.0 =
集成支付宝官方最新接口；修复部分空间交易记录无法保存到数据库；修复部分用户无法下载问题；其他小修改

= 1.1.0 =
修改了购买框样式，修复IE下错位现象，修改后台商品选项，解决编辑框错位

= 1.0.8 =
2011-8-14

增加订单页面翻页查询功能

2011-8-2 （重要更新）

可以在文章任意位置插入商品购买按钮，并且可以跨文章调用其他商品的购买信息，实现一篇文章展示多件商品（如此插件的购买演示）

2011-8-1 （重要更新）

新增邮件通知功能，买家成功购买后，站长和买家将收到邮件通知

2011-7-15

优化购买信息栏及购买按钮样式

2011-6-15

安全升级，提升安全性能


2011-6-12 （重要更新）

增加付款完成自动下载功能，下载链接有效时间为15分钟


== Upgrade Notice ==
= 2.0 =
在原来集成即时到帐接口的基础上，将担保交易和双功能接口也集成到插件中，此次升级是由于支付宝已经限制了个人申请即时到帐接口，个人只能申请担保交易或双功能接口，如果你已经申请到了即时到帐接口，则不用担心，可以继续使用，也可以不必升级你的插件

= 1.2.0 =
集成支付宝官方最新接口；修复部分空间交易记录无法保存到数据库；修复部分用户无法下载问题；其他小修改
= 1.0.8 =
增加订单页面翻页功能
