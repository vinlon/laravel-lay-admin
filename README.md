# Laravel LayUI Admin

## 更新日志

v0.7.1: 增加管理员密码找回功能

v0.7.0: 后台用户不再通过命令行创建，第一次打开后会进入初始化页面

v0.6.x: 增加资源管理功能，包括图片管理和内容管理，不需要手动执行db:seed初始化角色 

v0.5.0: 将菜单的定义放在配置文件中(去掉admin_menus表及菜单管理页面)，免去上线时同步系统菜单的步骤

v0.4.0: 更新layui版本到v2.6.4, [查看更新日志](https://www.layui.com/doc/base/changelog.html)

v0.3.1: 添加表单必填项的自动处理功能

v0.3.0: api路由前缀由admin改为lay-admin，以免和业务端代码产生冲突

v0.2.4: BaseController 增加 paginateResponse 方法，简化分页数据查询的代码


## 使用

1. #### 引入package 

```shell
composer require vinlon/laravel-lay-admin
```

2. #### 发布 public 文件

```shell
php artisan vendor:publish --provider="Vinlon\Laravel\LayAdmin\LayAdminServiceProvider" --tag=public --force
```

3. #### 发布 config 文件

```shell
php artisan vendor:publish --provider="Vinlon\Laravel\LayAdmin\LayAdminServiceProvider" --tag=config
```

4. #### 创建数据库表并初始化数据

```shell
# 如果数据库中已经存在对应的数据表，需要先将其 drop
php artisan migrate
```

5. #### 生成JWT_SECRET

```shell
php artisan jwt:secret
```
   
## 配置建议

```
# 默认值为admin, 管理后台访问地址为 ${APP_URL}/admin
LAY_ADMIN_ROUTE_PREFIX=
LAY_ADMIN_DISPLAY_NAME=

# JWT Token 有效期，单位为‘分’，默认值为 60 分钟
JWT_TTL=

# 如果需要通过邮件找回密码，则需要添加邮箱相关的配置
MAIL_MAILER=smtp
MAIL_HOST={邮箱使用的smtp服务器}
MAIL_PORT=465
MAIL_USERNAME={邮箱用户名}
MAIL_PASSWORD={邮箱密码}
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS={邮箱用户名}
MAIL_FROM_NAME={发件人名称}
```


## 命令

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

#### 表单必填项自动处理

对于如下的表单定义，框架会找到 lay-verify="required" 的元素，自动进行如下处理：

1. 通过 .parent().pref('label') 找到对应的label, 在label的内容前添加必填项标记（ 红色的 * ） 

2. 将 input 元素的 layType 设置为 tips， 将错误提示的样式改为 tips

3. 根据label的值(菜单名称)，为input元素定义默认的 placeholder 和 lay-reqtext (请输入菜单名称)

```html
<div class="layui-form-item">
  <label class="layui-form-label">菜单名称</label>
  <div class="layui-input-block">
    <input type="text" name="title" value="" lay-verify="required" autocomplete="off" class="layui-input">
  </div>
</div>
```

#### 

#### 自定义请求方法
    
在html代码中，可使用如下方法发起HTTP请求

```
admin.get(url, doneCallback, options)

admin.post(url, data, doneCallback, options)

admin.postJson: function (url, data, doneCallback, options)

admin.del(url, id, doneCallback, options)
```







