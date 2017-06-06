# Padagogy 中文站 #
基于 wordpress 的移动App分类网站。[线上地址](http://padagogy.cn/)
## 前言 ##
随着移动互联网技术的的蓬勃发展，以及手媒体的时代到来，人们更习惯于在手机或平板上使用APP来进行学习与交流，市场上的教育类APP成千上万，但大多数应用商店对于教育类APP缺乏专业性的细致分类，用户想要找到合适的APP无疑于大海捞针。教师使用APP应用于移动学习教学的经验和案例也没有得到充分的分享。

笔者设计并开发一款基于WordPress的Padagogy网站,Padagogy Wheel是澳大利亚学习设计师Alan基于 布鲁姆教育目标分类学提出的电子书包教学法模型，有助于教师设计电子书包环境下的参与式学习，网站的主要作用是分享App使用相关教学案例以及根据Padagogy模型将App分类，使得App的功能在移动学习的领域得到充分的挖掘和利用,本文将介绍该网站从设计到开发的具体流程.


## 功能 ##
- [x] 基于Wordpress网站开发，网站具有CMS系统的基本功能，可以编辑，管理，发布新闻，文章等
- [x] 系统能够提供用邮箱注册用户账号，登录功能
- [x] Padagogy的App信息以Wiki的形式存在于系统之中，每条App信息至少要关联对应的官网链接，和下载地址，每条信息可以关联对应的教学案例或相关的文章
- [x] 所有用户默认具有wiki的编辑，添加,发布,权限，每个用户都可以添加App信息，在发布时，确定wiki的分类信息标签，发布之前的App信息教学案例和相关的应用经验
- [x] 未注册用户能够浏览网站文章，能够根据Padagogy的分类标签，筛选/检索App信息




## 重点目录结构介绍 ##

	|-- screenshots                      // 项目截图目录
	|-- wp-content                       // wordpress 用户扩展目录
	|   |-- plugins                      // 插件目录
	|       |-- padagogy                 // Padagogy 插件目录
	|-- wp-config.php                    // 项目配置 数据库配置
	|-- .gitignore                       // 忽略的文件
	|-- index.php                        // 入口php文件
	|-- package.json                     // 项目及工具的依赖配置文件
	|-- README.md                        // 说明


## 安装启动步骤 ##

	git clone git@github.com:yunqiangwu/padagogy_web.git	// 把源码下载到本地
	cd padagogy_web											// 进入项目目录
	start http://padagogy.cn/								// 运行线上项目


## 使用说明与演示 ##

### 登录网站后台 ###
访问Wordpress后台。访问地址：[Wordpress后台管理](http://padagogy.cn/wp-admin/)
用户名：wuyun
密码：wu950429

### 添加App ###
访问Padagogy管理界面后台。访问地址：[管理Padagogy](http://padagogy.cn/wp-admin/edit.php?post_type=padagogy)

### 编辑多重筛选菜单 ###
基于wordpress后台菜单管理开发。访问地址：[Wordpress后台](http://padagogy.cn/wp-admin/nav-menus.php?action=edit&menu=20)

## 项目截图 ##
### 网站首页 ###

![Image text](https://github.com/yunqiangwu/padagogy_web/raw/master/screenshots/index.png)

### APP筛选列表 ###

![Image text](https://github.com/yunqiangwu/padagogy_web/raw/master/screenshots/padagogylist.png)

### Padagogy管理 ###

![Image text](https://github.com/yunqiangwu/padagogy_web/raw/master/screenshots/appmgr.png)

### 多重筛选菜单编辑  ###

![Image text](https://github.com/yunqiangwu/padagogy_web/raw/master/screenshots/menumgr.png)

### APP展示页面 ###

![Image text](https://github.com/yunqiangwu/padagogy_web/raw/master/screenshots/app.png)