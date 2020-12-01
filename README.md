# Laravel LayUI Admin

## 使用

1. #### 引入package 

    ```shell script
    composer require vinlon/laravel-lay-admin
    ```
2. #### 发布 public 文件

    ```
    php artisan vendor:publish --provider="Vinlon\Laravel\LayAdmin\LayAdminServiceProvider" --tag=public --force
    ```

3. #### 创建数据库表并初始化数据

    ```
    # 如果数据库中已经存在对应的数据表，需要先将其 drop
    php artisan migrate
    php artisan db:seed --class=LayAdminSeeder
    ```
   
4. #### 生成JWT_SECRET

    生成JWT_SECRET, 并自动写入根目录下的.env文件
    
    ```
    php artisan jwt:secret
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


## 开发指引

#### 使用auth:lay-admin middleware 对请求的登录状态进行验证

    ```
    Route::group(['middleware' => ['auth:lay-admin']], function () {
        //这里放置你的需要登录Admin后台API路由
    });
    ```

#### 返回值格式

    LayAdmin对数据返回的格式有统一要求，可通过继承 BaseController， 然后调用其中的 successResponse和errorResponse方法对返回结果进行封装

#### 自定义请求方法
    
    在html代码中，可使用如下方法发起HTTP请求

    ```
    admin.get(url, doneCallback, options)
    
    admin.post(url, data, doneCallback, options)
    
    admin.del(url, id, doneCallback, options)
    ```







