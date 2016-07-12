<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>找回密码</title>
    <link href="http://account.joy4you.com/joysdk/resource/css/normalize.css" rel="stylesheet"/>
    <link href="http://account.joy4you.com/joysdk/resource/css/jquery-ui.css" rel="stylesheet"/>
    <link href="http://account.joy4you.com/joysdk/resource/css/jquery.idealforms.min.css" rel="stylesheet" media="screen"/>
    <style type="text/css">
        body{font:normal 15px/1.5 Arial, Helvetica, Free Sans, sans-serif;color: #222;background:url(http://account.joy4you.com/joysdk/resource/css/images/pattern.png);overflow-y:scroll;padding:60px 0 0 0;}
        #my-form{width:755px;margin:0 auto;border:1px solid #ccc;padding:3em;border-radius:3px;box-shadow:0 0 2px rgba(0,0,0,.2);}
    </style>
</head>
<body>
    <div class="row">
        <div class="eightcol last">
            <!-- Begin Form -->
            <form id="my-form" action="http://account.joy4you.com/joysdk/index.php/forgot/updpwd" method="post">
                <section name="找回密码">
                    <div><label>用户名:</label><input name="username" type="text" value="<?php echo $username ?>" disabled="true"/></div>
                    <input hidden="true" name="username" type="text" value="<?php echo $username ?>"/>
                    <input id="check" name="check" type="text" hidden="true" value="<?php echo $token ?>"/>
                    <div><label>密码:</label><input id="password" name="password" type="password" maxlength="15"/></div>
                    <div><label>确认密码:</label><input id="password2" name="password2" type="password" maxlength="15"/></div>
                </section>
                <div><hr/></div>
                <div>
                    <button id="submit" type="submit">提交</button>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="http://account.joy4you.com/joysdk/resource/js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="http://account.joy4you.com/joysdk/resource/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="http://account.joy4you.com/joysdk/resource/js/jquery.idealforms.js"></script>
    <script type="text/javascript">
        var options = {
            onFail: function () {
                alert('字段不合法');
            },
            inputs: {
                'password': {
                    filters: 'required pass',
                },
                'password2': {
                    filters: 'required pass',
                },
            }
        };
        var $myform = $('#my-form').idealforms(options).data('idealforms');
        $myform.focusFirst();
    </script>
    <div style="text-align:center;">
        <p>来源：<a href="http://www.joy4you.com/" title="乐恒互动" target="_blank">乐恒互动</a></p>
    </div>
</body>
</html>