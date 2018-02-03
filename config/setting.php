<?php

return [
    //添加账号显示大区
    'areas' => [
        'AZQQ' => '安卓QQ',
        'AZVX' => '安卓微信',
        'IOSQQ' => '苹果QQ',
        'IOSVX' => '苹果微信',
    ],
    //后台筛选页面显示大区
    'showareas' => [
        'AZVXWZ' => '安卓微信-王者荣耀金币/经验', //安卓微信
        'AZQQWZ' => '安卓QQ-王者荣耀金币/经验', //安卓QQ
        'IOSVXWZ' => '苹果微信-王者荣耀金币/经验', //IOS微信
        'IOSQQWZ' => '苹果QQ-王者荣耀金币/经验', //IOSQQ

        'AZVXWZ2' => '安卓微信-王者荣耀熟练度', //安卓微信
        'AZQQWZ2' => '安卓QQ-王者荣耀熟练度', //安卓QQ
        'IOSVXWZ2' => '苹果微信-王者荣耀熟练度', //IOS微信
        'IOSQQWZ2' => '苹果QQ-王者荣耀熟练度', //IOSQQ

        'AZVXFC' => '安卓微信-飞车', //安卓微信 飞车
        'AZQQFC' => '安卓QQ-飞车', //安卓QQ 飞车
        'IOSVXFC' => '苹果微信-飞车', //IOS微信 飞车
        'IOSQQFC' => '苹果QQ-飞车', //IOSQQ 飞车
    ],
    //每个大区的价格
    'prices' => [
        'AZVXWZ' => 5, //安卓微信
        'AZQQWZ' => 4, //安卓QQ
        'IOSVXWZ' => 5, //IOS微信
        'IOSQQWZ' => 4, //IOSQQ

        'AZVXWZ2' => 50, //安卓微信
        'AZQQWZ2' => 50, //安卓QQ
        'IOSVXWZ2' => 50, //IOS微信
        'IOSQQWZ2' => 50, //IOSQQ

        'AZVXFC' => 18, //安卓微信 飞车
        'AZQQFC' => 15, //安卓QQ 飞车
        'IOSVXFC' => 18, //IOS微信 飞车
        'IOSQQFC' => 15, //IOSQQ 飞车
    ],
    //代理后台显示价格
    'remarks' => "<br>".'王者荣耀_金币/经验：安卓QQ：4点/次，苹果QQ：4点/次，安卓微信：5点/次，苹果微信：5点/次，'
        ."<br>".'王者荣耀_熟练度：安卓QQ：50点/次，苹果QQ：50点/次，安卓微信：50点/次，苹果微信：50点/次'
        ."<br>".'QQ飞车_金币/经验：安卓QQ：15点/次，苹果QQ：15点/次，安卓微信：18点/次，苹果微信：18点/次',

    //大区转换关系
    'areastoarea' => [
        'AZVXWZ' => 'AZVX', //安卓微信
        'AZQQWZ' => 'AZQQ', //安卓QQ
        'IOSVXWZ' => 'IOSVX', //IOS微信
        'IOSQQWZ' => 'IOSQQ', //IOSQQ

        'AZVXFC' => 'AZVX', //安卓微信 飞车
        'AZQQFC' => 'AZQQ', //安卓QQ 飞车
        'IOSVXFC' => 'IOSVX', //IOS微信 飞车
        'IOSQQFC' => 'IOSQQ', //IOSQQ 飞车
    ],

    //地图
    'maps' => [
        'DS' => [
            'name'=>'王者荣耀_金币/经验',
            'show'=>'王者金币/经验', //显示
            'pre' => 'WZ',
            'jiaji' => 'WZRY', //加急标记
            'time' => 250, //默认刷图次数
            'game' => ''  //显示图片后边拼接字段
        ],
        'JY' => [
            'name' => '王者荣耀_熟练度',
            'show' => '王者熟练度',
            'pre' => 'WZ2',
            'jiaji' => 'WZRY2', //加急标记
            'time' => 200,
            'game' => ''
        ],
        'QQFC' => [
            'name' => 'QQ飞车_金币/经验',
            'show' => '飞车金币/经验',
            'pre' => 'FC',
            'jiaji' => 'FC', //加急标记
            'time' => 600,
            'game' => ''
        ],
    ],
    //新增账号导入 刷图选择索引
    'selects' => [
        '王者荣耀_金币/经验',
        '王者荣耀_熟练度',
        'QQ飞车_金币/经验',
    ],
    'modes' => [
        '关闭','模式一','模式二','模式三'
    ],
    'statuss' => [
        '0' => '代练完成',
        '1' => '排队等待',
        '2' => '正在登陆',
        '3' => '代练中',
        '4' => '健康系统',
        '5' => '成功登陆',
        '6' => '领取奖励',
        '13' => '选择好友',
        '12' => '微信二维码',
        '11' => '手机验证码',
        '-1' => '手动停挂',
        '-2' => '密码错误',
        '-3' => '地图未过',
        '-4' => '防沉迷',
        '-5' => '账号冻结',
        '-6' => '未关闭登陆保护',
        '-7' => '新号',
        '-8' => '授权超时',
        '-9' => '刷图限制',
        '21' => '内部错误',
        '-20' => '验证失败',
        '-21' => '点数不足',
        '-10' => '区不存在',
        '-11' => '抢先服不能刷',
        '22' => '更换设备挂机',
        '-14' => '短信验证码登陆',
        '-15' => '微信异常无法登陆',
        '-22' => '留存备用',
    ],




];
