layui.define(['table', 'form'], function (exports) {
  let $ = layui.$
      , table = layui.table
      , view = layui.view
      , admin = layui.admin
      , form = layui.form

  let resourceUrl = 'admin/menus'

  //菜单管理
  table.render({
    elem: '#LAY-menu-manage'
    , where: layui.router().search
    , url: resourceUrl
    , cols: [[
      {field: 'title', title: '菜单名称', width: 100}
      , {title: '父菜单', align: 'center', width: 100, templet: '<span>{{ d.p_menu ? d.p_menu.title : "-" }}</span>'}
      , {field: 'icon', title: '图标', width: 200}
      , {field: 'path', title: '跳转地址', width: 150}
      , {field: 'created_at', title: '添加时间', minWidth: 100}
      , {title: '操作', width: 300, align: 'left', fixed: 'right', toolbar: '#LAY-menu-operate'}
    ]]
  });

  let showAddForm = function (data) {
    let operate = data ? '修改' : '添加'
        , pid = layui.router().search.pid || 0;
    let level = pid === 0 ? '一级' : '子';
    let title = operate + level + '菜单';
    data = data || {};
    data.pid = pid;
    data.p_title = decodeURI(layui.router().search.p_title);
    //查询同级菜单
    admin.get(resourceUrl + '?pid=' + pid, function (res) {
      data.siblings = res.data;
      admin.popup({
        title: title
        , area: ['500px', '400px']
        , id: 'LAY-popup-menu-add'
        , success: function (layero, index) {
          view(this.id).render('system/menu/form', data).done(function () {
            form.render(null, 'LAY-menu-form');
          })
        }
      })
    })
  }
  //监听提交
  form.on('submit(LAY-menu-submit)', function (data) {
    let field = data.field; //获取提交的字段
    //提交 Ajax 成功后，关闭当前弹层并重载表格
    admin.post(resourceUrl, field, function (res) {
      layui.table.reload('LAY-menu-manage'); //重载表格
      layer.closeAll(); //执行关闭
    })
  });

  //监听工具条
  table.on('tool(LAY-menu-manage)', function (obj) {
    let data = obj.data;
    if (obj.event === 'edit') {
      showAddForm(data);
    } else if (obj.event === 'del') {
      layer.confirm('确认删除该数据?', function (index) {
        admin.del(resourceUrl, data.id, function (res) {
          layer.close(index);
          layui.table.reload('LAY-menu-manage'); //重载表格
        })
      });
    }
  });

  $('#LAY-menu-add').click(function () {
    showAddForm();
  });
  exports('menu', {})
})