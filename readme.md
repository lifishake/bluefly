# I.关于主题 #
- bluefly, Copyright 2015 
- bluefly 遵循GPLv2协议发布。你可以在license.txt中查看具体内容。
- WP4.4标准版测试通过。未经过中文版测试。

----------

# II. 资源 #

## a) Oblique ##
- 来源: http://flyfreemedia.com/themes/oblique
- 协议: GPLv2
- 协议源: http://www.gnu.org/licenses/gpl-2.0.html

## b) FontAwesome ##
- 来源: http://fontawesome.io
- 协议: SIL Open Font License, Version 1.1
- 协议源: https://scripts.sil.org/OFL?

## c) html5shiv ##
- 来源: https://code.google.com/p/html5shiv/
- 协议: MIT/GPLv2
- 协议源: http://opensource.org/licenses/MIT
- 协议源: http://www.gnu.org/licenses/gpl-2.0.html

## d) lazyload ##
- Copyright: 2015 Mika Tuupola
- 来源: http://www.appelsiini.net/projects/lazyload
- 协议: MIT
- 协议源: http://opensource.org/licenses/MIT

## e) Slicknav ##
- 来源: https://github.com/ComputerWolf/SlickNav
- 协议: MIT
- 协议源: http://opensource.org/licenses/MIT

## f) Infinite scroll ##
- 来源: https://github.com/infinite-scroll/infinite-scroll
- 协议： MIT
- 协议源: http://opensource.org/licenses/MIT

## g) 使用的图片 ##
缩略图中以及可见的标题图片可以在images目录下找到。

### Copyright: OpenClipartVectors ###
- 来源: https://pixabay.com/en/paper-note-memo-document-message-576550/
- 协议: CC0 1.0
- 协议源: http://creativecommons.org/publicdomain/zero/1.0/deed.en

### Copyright: Greyerbaby ###
- 来源: https://pixabay.com/en/legs-window-car-dirt-road-relax-434918/
- 协议: CC0 1.0
- 协议源: http://creativecommons.org/publicdomain/zero/1.0/deed.en

## h)ajax-comment ##
- 来源: https://fatesinger.com/59
- 协议: 未知

## i)CSSgram ##
- 来源: https://github.com/una/CSSgram
- 协议: MIT

----------

# III. 注意事项和使用说明 #
1. 如果不是以为GPL的要求，本人其实根本不想把这个主题公开。为了省事，所有的注释以及可配置的字符串都设成了中文UTF8编码。所有的歪果仁朋友，对不起了。    
	Sorry for non-Chinese developers, the author had never supposed to support any foreign languages.     
	非中国の開発者のため申し訳ありませんが、著者は、任意の外国語をサポートする予定はありませんでした。
1. 	无限加载效果与lazyload有冲突。我解决了使用unveil-ui.min.js时的冲突，如果有其它实现方法的需要自行解决。
2.  不支持文章内翻页（wp_link_pages（）），因为我觉得这是个没用的功能。
3.  不支持原生的gallery风格（media.h）。
4.	支持自定义标题字体。包括标题和副标题，上传字体文件到\fonts下，然后在主题选项中加入字体名字即可。自定义字体教程见：
	[教程](http://pewae.com/2015/10/yonginkscapezhizuozidingyizitibingtianjiadaowordpresszhong.html)
5.	支持首页和归档页面的无限滚动（可自定义）。搜索页面如需要同样效果，可自行参照修改。无限滚动效果使用Infinite scroll库。想修改loading图片，替换\images\loading.gif，想替换加载文字，替换\script.js中对应的内容。查找的参数利用的是关键字posts-navigation我利用了4.3以后的新函数the_posts_pagination（）与旧函数the_posts_navigation（）的区别，如果要修改请查阅the_posts_navigation（）的相关内容。
6.  我的理念是主题不负责与显示无关的事。所以，gravatar问题、smile问题、自动版本保存问题、google字体问题、中文截断问题、图片延时加载问题统统不提供解决方案，请自行修改添加。
7.  不支持多作者。作者名已隐藏。
8.  归档页面保留类别、标签和日期，其余归档页面自动跳转到404。
9.  请自行添加一个社会化导航menu，设置好之后右上角就可以显示。
10. 自带lazyload功能，如果与安装的插件有冲突请自行调整。


----------

# IV. 版本历史 #

**2016/1/21 0.23**
----------
- 修改相对时间的文字
- 删除作者链接
- 增加XP默认字体(新宋体)


**2016/1/8 0.22**
----------
- 增加跳转至留言的图标和js


**2015/12/22 0.21**
----------
- 修改WP4.4时已存在cookie用户隐藏信息表示不出对话框的bug

**2015/12/14 0.20**
----------
- 将del删除线的格式为斜线


**2015/11/30 0.19**
----------
- 修正错误搜索不到结果时的错误
- 修改部分配色

**2015/11/30 0.18**
----------
- 单张照片追加仿Instagram褪色效果(reyes)
- 修改部分配色
- 调整颜色计算函数的位置style.php->trait.php

**2015/11/17 0.17**
----------
-追加图片延时加载功能

**2015/11/16 0.16**
----------
- 调整“路过”为“圈阅”

**2015/11/11 0.15**
----------
- 修改阴影范围
- 修改comment模块每条留言的高度
- 调整"路过"和"留言"的顺序
- 修改首页图片显示方式

**2015/11/2 0.14**
----------
- 修改正文字体大小和行间距。从可定义的px单位改为1em固定。
- 非诚意留言数量为0时不显示列表
- 已留言者不能提交非诚意留言

**2015/10/31 0.13**
----------
- 优化了一些手机显示时的效果

**2015/10/29 0.12**
----------
- 追加“回到顶部”按钮
- 优化存档页标题
- 把不支持的archive格式重定向到404页
- 增加伪照片日期标识和电子表字体

**2015/10/23 0.11**
----------
- 支持无限滚动的主题选项
- 导航CSS优化
- 无限滚动CSS和文字优化
- 删除默认图片,去特色图片时改为显示类别选项的图标
- 放弃对图片背景以及动态背景动画的支持

**2015/10/19 0.10**
----------
- 删除customizer.js

**2015/10/12 0.09**
----------
- 讨论区非诚意用户留言墙CSS优化完成
- 增加对自定义标题字体的支持

**2015/9/21 0.08**
----------
- 对已知cookie隐藏信息

**2015/9/15 0.07**
----------
- 变为相对日期

**2015/9/14 0.06**
----------
- 增加导航函数,用于single和categroy
- 删除extra.php
- 删除template-tag.php中4.3已经包括的函数
- “已阅”功能完善

**2015/9/10 0.05**
----------
- 初步追加"已阅"按钮
- page的评论顺序改为倒序

**2015/9/2 0.04**
----------
- 追加ajax-comment效果

**2015/7/23 0.03**
----------
- 去掉斜角效果
- 改进grid视图
- 增加CSS3动态切入效果
- 删除不必要的js
- 增加默认缩略图

**2015/7/21 0.02**
----------
- 追加非移动设备底部雾效果
- 追加无限滚动功能

**2015/7/7 0.01**
----------
- 初版