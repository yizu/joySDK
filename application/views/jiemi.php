
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN"><!-- InstanceBegin template="/Templates/tool.dwt" codeOutsideHTMLIsLocked="false" -->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!-- InstanceBeginEditable name="doctitle" -->
        <title>乐恒DES加密解密</title>
        <meta name="Keywords" content="" />
        <meta name="Description" content="" />
        <script type="text/javascript" src="../../resource/js/jquery-1.8.2.min.js"></script>
    </head>
        <body>
            <div class="main">
                <h3 style="margin:0">乐恒DES加密解密</h3>
                <table border=0>
                    <form name="encme">
                        <tr><td>原<br />文</td>
                            <td>
                                <textarea id="inps" name="inps" cols="90" rows="10"></textarea>
                            </td>
                        </tr>
                        <tr><td>密<br />码</td>
                            <td>
                                <input id="password" type="password" name="deskey" maxlength="100" size="16" value="562asd32">
                                <input type="button" onclick="javascript:encMe()" value="&#8595;加密&#8595;">
                                <input type="button" onclick="javascript:uncMe()" value="&#8593;解密&#8593;">
                          </td>
                        </tr>
                        <tr><td>密<br />文</td><td>
                                <textarea id="outs" name="outs" cols="90" rows="10"></textarea>
                        </td></tr>
                    </form>
                </table>
            </div>
        </body>
</html>
        <style type="text/css">
            <!--
            body,td,th {
                font-family: Verdana, 宋体;
                font-size: 14px;
            }
            body {
                margin-left: 0px;
                margin-top: 0px;
            }
            a{
                color: #0000FF;
                text-decoration: none;
            }
            a:hover {
                text-decoration: underline;
                color: #990000;
            }
            .locn {
                font-family: Verdana, "宋体";
                font-size: 12px;
                line-height: normal;
                text-align: left;
                vertical-align: middle;
                margin: 0;
                padding: 3px;
            }
            .endtag1 {
                font-size: 12px;
                color: #666666;
                margin: 0;
                padding: 3px;
                text-align: left;
            }
            .endtag2 {
                font-size: 12px;
                color: #666666;
                margin: 0;
                padding: 3px;
                text-align:center;
            }
            -->
        </style>
        <style type="text/css">
            <!--
            body{text-align:center}
            .main{
                margin:22px auto;
                width:750px;
                text-align:left;
            }
            .main h3{text-align:center;}
            -->
        </style>
    <script>
        function  encMe(){
            $.post('ajaxJiami', {mingwen: inps.value, key: password.value}, function (ret) {
                //var info = eval('(' + ret + ')');
                outs.value=ret;
            });
        }
        
        function  uncMe(){
            $.post('ajaxJiemi', {miwen: outs.value, key: password.value}, function (ret) {
                if (ret == "") {
                    alert("密文不合法");
                    return false;
                } else {
                    inps.value=ret;
                }
            });
        }
    </script>