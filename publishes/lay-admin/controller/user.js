layui.define(['table', 'form'], function (exports) {
  var $ = layui.$
      , table = layui.table
      , view = layui.view
      , admin = layui.admin
      , form = layui.form
  var resourceUrl = './admin/user'
  //用户管理
  table.render({
    elem: '#LAY-user-manage'
    , url: resourceUrl
    , cols: [[
      {field: 'id', width: 50, title: 'ID'}
      , {field: 'username', title: '登录用户名', width: 100}
      , {title: '角色', width: 100, templet: '<span>{{ d.role.name }}</span>'}
      , {field: 'created_at', title: '添加时间', minWidth: 200}
      , {title: '操作', width: 300, align: 'left', fixed: 'right', toolbar: '#LAY-user-operate'}
    ]]
    , text: '数据加载失败，请联系管理员'
  });

  //加载角色数据
  var loadRole = function (callback) {
    admin.get('admin/role', function (res) {
      callback(res.data);
    });
  }

  var showAddForm = function (data) {
    var title = data ? '修改用户' : '添加用户';
    loadRole(function (roles) {
      data = data || {};
      data.roles = roles;
      admin.popup({
        title: title
        , area: ['500px', '350px']
        , id: 'LAY-popup-user-add'
        , success: function (layero, index) {
          view(this.id).render('user/user/form', data).done(function () {
            form.render(null, 'LAY-user-form');
            //监听提交
            form.on('submit(LAY-user-submit)', function (data) {
              var field = data.field; //获取提交的字段
              if (field.password != field.verify_password) {
                layer.msg('两次输入的密码不一致');
                return;
              }
              //提交 Ajax 成功后，关闭当前弹层并重载表格
              admin.post(resourceUrl, field, function (res) {
                layui.table.reload('LAY-user-manage'); //重载表格
                layer.close(index); //执行关闭
              })
            });
          })
        }
      });
    })
  }

  //监听工具条
  table.on('tool(LAY-user-manage)', function (obj) {
    var data = obj.data;
    if (obj.event === 'edit') {
      showAddForm(data);
    } else if (obj.event === 'del') {
      layer.confirm('确认删除该数据?', function (index) {
        admin.del(resourceUrl, data.id, function (res) {
          layer.close(index);
          layui.table.reload('LAY-user-manage'); //重载表格
        })
      });
    } else if (obj.event === 'reset') {
      admin.popup({
        title: '重置密码'
        , area: ['400px', '300px']
        , id: 'LAY-popup-user-reset'
        , success: function (layero, index) {
          view(this.id).render('user/user/reset', data).done(function () {
            form.render(null, 'LAY-user-reset-form');
            //监听提交
            form.on('submit(LAY-user-reset-submit)', function (data) {
              var field = data.field; //获取提交的字段
              if (field.password !== field.verify_password) {
                layer.msg('两次输入的密码不一致');
                return;
              }
              //提交 Ajax 成功后，关闭当前弹层并重载表格
              admin.post('./admin/user/resetPassword', field, function (res) {
                layer.close(index); //执行关闭
              })
            });
          });
        }
      });
    }
  });
  $('#LAY-user-add').click(function () {
    showAddForm();
  });
  exports('user', {})
})