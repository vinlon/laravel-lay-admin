# Laravel LayUI Admin

## 使用

1. #### 引入package 

    ```shell script
    composer require vinlon/laravel-wechat-auth
    ```
4. #### 创建数据库表并初始化数据

    ```
    # 如果数据库中已经存在 wx_users表，需要先将其 drop
    php artisan migrate
    php artisan db:seed --class=LayAdminSeeder
    ```
   
5. #### 生成JWT_SECRET

    生成JWT_SECRET, 并自动写入根目录下的.env文件
    
    ```
    php artisan jwt:secret
    ```

7. #### 使用auth:lay-admin middleware 对请求的登录状态进行验证

    ```
    Route::group(['middleware' => ['auth:wxapp']], function () {
        //这里放置你的需要登录的 api 路由
    });
    ```


## 命令

- 创建管理员

    ```
    php artisan lay-admin:super admin
    ```

    为用户生成随机密码并输出

    ```
    =====用户创建成功=====
    用户名：admin
    密码：PkLNPdyC
    ```

- 重置密码

    ```
    php artisan lay-admin:reset-password admin admin
    ```

    ```
    =====重置成功=====
    当前密码为： admin
    ```

 


## 自定义请求方法 

admin.get(url, doneCallback, options)

admin.post(url, data, doneCallback, options)

admin.del(url, id, doneCallback, options)






