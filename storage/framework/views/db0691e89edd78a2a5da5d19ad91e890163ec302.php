<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>系统发生错误</title>
    <meta name="robots" content="noindex,nofollow" />
    <style>
        /* Base */
        *{padding: 0;margin:0;}
        body {
            color: #333;
            font: 16px Verdana, "Helvetica Neue", helvetica, Arial, 'Microsoft YaHei', sans-serif;
            margin: 0;
            padding: 0 20px 20px;
        }
        h1{
            margin: 10px 0 0;
            font-size: 28px;
            font-weight: 500;
            line-height: 32px;
        }
        h2{
            color: #4288ce;
            font-weight: 400;
            padding: 6px 0;
            margin: 6px 0 0;
            font-size: 18px;
            border-bottom: 1px solid #eee;
        }
        h3{
            margin: 12px;
            font-size: 16px;
            font-weight: bold;
        }
        abbr{
            cursor: help;
            text-decoration: underline;
            text-decoration-style: dotted;
        }
        a{
            color: #78b0f0;
            cursor: pointer;
            text-decoration: none;
        }

        .fb-error{width: 500px;margin:10% auto 0 auto;}
        .fb-error-title{font-size: 40px;color: #333333;text-align: center;margin:50px 0 30px 0;line-height: 40px;}
        .fb-error-con{font-size: 16px;color: #999;text-align: center;line-height: 40px;}
        .fb-error-button{width: 150px;height: 50px;border-radius: 5px;margin:38px auto 0 auto;background: #b14b4d;text-align: center;line-height: 50px;}
        .fb-error-button a{color: #fff;font-size: 16px;display: block;}
    </style>
</head>
<body>
<div class="fb-error">
    <div class="fb-error-img"><img src="/images/error.png" alt=""></div>
    <div class="fb-error-title"><?php echo e($message); ?></div>
    <div class="fb-error-con">
        <p>如有任何建议，请及时反馈给 <a href="http://www.feibu.info">广州飞步信息科技有限公司</a></p>
    </div>
</div>

</body>
</html>
