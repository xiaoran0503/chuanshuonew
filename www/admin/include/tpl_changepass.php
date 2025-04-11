<?php require_once 'function.php';
?>

<!doctype html>
<html  class="ShipSayCms">
<head>
	<meta charset="UTF-8">
	<title>后台登录-X-admin2.2</title>
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <script src="/layui/layui.js"></script>
   <script src="//cdn.staticfile.org/jquery/3.4.0/jquery.min.js"></script>

   <style>
   .login-bg{
       background: #e6e6e6;
}
.login{
   margin: 0 auto;
   /* min-height: 420px; */
   max-width: 420px;
   padding: 40px;
   background-color: #ffffff;
   margin-left: auto;
   margin-right: auto;
   border-radius: 4px;
   box-sizing: border-box;
}
.login a.logo{
   display: block;
   height: 58px;
   width: 167px;
   margin: 0 auto 30px auto;
   background-size: 167px 42px;
}
.login .message {
   margin: 10px -40px;
   padding: 18px 10px 18px 90px;
   background: #189F92;
   position: relative;
   color: #fff;
   font-size: 16px;
}
.login #darkbannerwrap {
   width: 18px;
   height: 10px;
   margin: 0 0 20px -58px;
   position: relative;
}

.login input[type=text],
.login input[type=file],
.login input[type=password],
.login input[type=email], select {
   border: 1px solid #DCDEE0;
   vertical-align: middle;
   border-radius: 3px;
   height: 50px;
   padding: 0px 16px;
   font-size: 14px;
   color: #555555;
   outline:none;
   width:100%;
   box-sizing: border-box;
}
.login input[type=text]:focus,
.login input[type=file]:focus,
.login input[type=password]:focus,
.login input[type=email]:focus, select:focus {
   border: 1px solid #27A9E3;
}
.login input[type=submit],
.login input[type=button]{
   display: inline-block;
   vertical-align: middle;
   padding: 12px 24px;
   margin: 0px;
   font-size: 18px;
   line-height: 24px;
   text-align: center;
   white-space: nowrap;
   vertical-align: middle;
   cursor: pointer;
   color: #ffffff;
   background-color: #189F92;
   border-radius: 3px;
   border: none;
   -webkit-appearance: none;
   outline:none;
   width:100%;
}
.login hr {
   background: #fff url() 0 0 no-repeat;
}
.login hr.hr15 {
   height: 15px;
   border: none;
   margin: 0px;
   padding: 0px;
   width: 100%;
}
.login hr.hr20 {
   height: 20px;
   border: none;
   margin: 0px;
   padding: 0px;
   width: 100%;
}
   </style>
</head>

<body class="login-bg">
   
   <div class="login layui-anim layui-anim-up">
       <div class="message">修改密码</div>
       <div id="darkbannerwrap"></div>
       
       <form method="post" class="layui-form" >
           <input name="password" placeholder="新密码"  type="password" class="layui-input">
           <hr class="hr15">
           <input name="repassword" placeholder="重复新密码"  type="password" class="layui-input">
           <hr class="hr15">
           <input id="btn-submit" value="提交" style="width:100%;" type="submit">
           <hr class="hr20" >
       </form>
   </div>

   <script>
           $('#btn-submit').on('click', function(){
               if($('input[name="password"]').val() == '' || $('input[name="repassword"]').val() == ''){

               alert('密码不能为空');

               return false;

               } 

               if($('input[name="password"]').val() != $('input[name="repassword"]').val()){

                   alert('两次输入的密码不一致');

                   return false;

               } 

               $.ajax({
                   type : 'POST',
                   url : "changepass.php",
                   data: {
                       "password" : $('input[name="password"]').val(),
                   },
                   success : function(state) {
                       alert( state == 200 ? '修改完成' : '修改失败');
                   }

               })
           });

           layui.use(['element','form'], function(){
               let element = layui.element;
               let form = layui.form;
               form.render();
           })
   </script>
</body>
</html>