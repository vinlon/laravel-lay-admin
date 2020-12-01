layui.define(['table', 'form'], function (exports) {
  var $ = layui.$
      , table = layui.table
      , view = layui.view
      , admin = layui.admin
      , form = layui.form
  var resourceUrl = './admin/role'
  //角色管理
  table.render({
    elem: '#LAY-role-manage'
    , url: resourceUrl
    , cols: [[
      {field: 'id', width: 100, title: 'ID'}
      , {field: 'name', title: '角色名称', minWidth: 100}
      , {field: 'description', title: '角色描述', minWidth: 100}
      , {field: 'created_at', title: '添加时间', minWidth: 100}
      , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#LAY-role-operate'}
    ]]
    , text: '数据加载失败，请联系管理员'
  });

  //监听工具条
  table.on('tool(LAY-role-manage)', function (obj) {
    var data = obj.data;
    if (obj.event === 'del') {
      layer.confirm('确认删除该数据?', function (index) {
        admin.del(resourceUrl, data.id, function (res) {
          layer.close(index);
          layui.table.reload('LAY-role-manage'); //重载表格
        })
      });
    }
  });
  exports('role', {})
})