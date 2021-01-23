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
3. #### 发布 config 文件

    ```
    php artisan vendor:publish --provider="Vinlon\Laravel\LayAdmin\LayAdminServiceProvider" --tag=config
    ```

4. #### 创建数据库表并初始化数据

    ```
    # 如果数据库中已经存在对应的数据表，需要先将其 drop
    php artisan migrate
    php artisan db:seed --class=LayAdminSeeder
    ```
   
## 配置建议

```
# 默认值为admin, 管理后台访问地址为 ${APP_URL}/admin
LAY_ADMIN_ROUTE_PREFIX=

# 生成JWT Token使用的密钥，可使用 php artisan jwt:secret 命令生成
JWT_SECRET=

# JWT Token 有效期，单位为‘分’，默认值为 60 分钟
JWT_TTL=

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

#### BaseController

- LayAdmin对数据返回的格式有统一要求，可调用 successResponse 和 errorResponse 方法分别对成功和失败的返回结果进行封装

- 分页查询的结果可以调用 paginateResponse 返回，该方法接收一个 Eloquent Builder 作为参数

#### 自定义请求方法
    
    在html代码中，可使用如下方法发起HTTP请求

    ```
    admin.get(url, doneCallback, options)
    
    admin.post(url, data, doneCallback, options)
    
    admin.del(url, id, doneCallback, options)
    ```







