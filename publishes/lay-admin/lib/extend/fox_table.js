layui.define([], function (exports) {
  let $ = layui.$
    , table = layui.table
    , admin = layui.admin

  let st = {
      where: {},
      page: true,
      pageIndex: 1,
    },
    default_config = {
      loading: true,
      cellMinWidth: 60,
      authSort: false,
      text: {
        none: "无数据"
      },
      done: function (res, curr) {
        //这里添加 page到url中 这样可以支持url中记住table的page
        st.pageIndex = curr
        saveQueryToHash()
      }
    }

  st.render = function (config) {
    st.config = config
    st.id = config.id
    st.where = {}
    prepareSearchParams();

    //检查elem有效性
    let id_selector = '#' + config.id
      , elem = $(id_selector)
    //将table窗口的lay-filter设置为和id同样的值，后续对table的操作都使用id即可
    elem.attr('lay-filter', st.id)

    //config合并
    st.config.elem = id_selector
    layui.each(default_config, function (key, value) {
      st.config[key] = value;
    })
    // 表格渲染
    table.render(st.config);

    //触发排序事件
    table.on('sort(' + config.id + ')', function (obj) {
      console.log(obj.field); //当前排序的字段名
      console.log(obj.type); //当前排序类型：desc（降序）、asc（升序）、null（空对象，默认排序）

      delete st.where.order_by_asc
      delete st.where.order_by_desc
      if (obj.type === 'desc' || obj.type === 'asc') {
        st.where['order_by_' + obj.type] = obj.field
      }
      table.reload(config.id, {
        initSort: obj //记录初始排序，如果不设的话，将无法标记表头的排序状态。
        , where: st.where
      });
    });
  }
  //工具条事件
  st.toolbar = function (callback) {
    table.on('tool(' + st.id + ')', callback)
  }
  //表格重载
  st.reload = function (where) {
    where = where || {}
    st.where = where;
    table.reload(st.id, {where: where, page: {curr: 1}})
  }

  function prepareSearchParams() {
    let search = layui.router().search;
    if (search.page) {
      st.page = {curr: layui.router().search.page}
    } else {
      st.page = {curr: 1}
    }
    delete search.page
    if (search.order_by_asc) {
      st.config.initSort = {
        type: 'asc',
        field: search.order_by_asc
      }
    } else if (search.order_by_desc) {
      st.config.initSort = {
        type: 'desc',
        field: search.order_by_desc
      }
    }
    layui.each(search, function (key, value) {
      //查询参数decode
      st.where[key] = decodeURIComponent(value)
    })
    st.config.where = st.where
    st.config.page = st.page
  }

  //将查询条件保存到hash
  function saveQueryToHash() {
    let path = layui.router().path
      , item_arr = []
    if (path[path.length - 1] === '') {
      path[path.length - 1] = layui.setter.entry;
    }
    layui.each(st.where, function (key, value) {
      if ($.trim(value) !== '') {
        item_arr.push(key + '=' + value)
      }
    })
    if (st.pageIndex > 1) {
      item_arr.push('page=' + st.pageIndex)
    }
    let hash = '#/' + path.join('/')
    if (item_arr.length > 0) {
      item_arr.sort()
      let query_str = item_arr.join('/')
      hash += '/' + query_str
    }
    //修改hash但不刷新页面
    if (location.hash !== hash) {
      admin.hash_not_reload = true
      location.hash = hash
    }
  }

  exports("fox_table", st)
})
