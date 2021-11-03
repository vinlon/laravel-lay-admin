/**

 @Name：layuiAdmin 视图模块
 @Author：贤心
 @Site：http://www.layui.com/admin/
 @License：LPPL

 */

layui.define(['laytpl', 'layer'], function (exports) {
  var $ = layui.jquery
    , laytpl = layui.laytpl
    , layer = layui.layer
    , setter = layui.setter
    , device = layui.device()
    , hint = layui.hint()

    //对外接口
    , view = function (id) {
      return new Class(id);
    }

    , SHOW = 'layui-show', LAY_BODY = 'LAY_app_body'

    //构造器
    , Class = function (id) {
      this.id = id;
      this.container = $('#' + (id || LAY_BODY));
    };

  //加载中
  view.loading = function (elem) {
    elem.append(
      this.elemLoad = $('<i class="layui-anim layui-anim-rotate layui-anim-loop layui-icon layui-icon-loading layadmin-loading"></i>')
    );
  };

  //移除加载
  view.removeLoad = function () {
    this.elemLoad && this.elemLoad.remove();
  };

  //清除 token，并跳转到登入页
  view.exit = function () {
    //清空本地记录的 token
    layui.data(setter.tableName, {
      key: setter.request.tokenName
      , remove: true
    });

    //跳转到登入页
    location.hash = '/_base/user/login';
  };

  //Ajax请求
  view.req = function (options) {
    var success = options.success
      , error = options.error
      , request = setter.request
      , response = setter.response
      , debug = function () {
      return setter.debug
        ? '<br><cite>URL：</cite>' + options.url
        : '';
    };

    options.data = options.data || {};
    options.headers = options.headers || {};

    if (request.tokenName) {
      //自动给 Request Headers 传入 jwt token
      options.headers['Authorization'] = "Bearer " + layui.data(setter.tableName)[request.tokenName] || '';
    }

    delete options.success;
    delete options.error;

    return $.ajax($.extend({
      type: 'get'
      , dataType: 'json'
      , success: function (res) {
        var statusCode = response.statusCode;
        //只有 response 的 code 一切正常才执行 done
        if (res[response.statusName] === statusCode.ok) {
          typeof options.done === 'function' && options.done(res);
        } else {
          if (typeof options.fail === 'function') {
            options.fail(res);
          } else {
            //如果未自定义回调，统一处理fail情况
            view.notice(res[response.msgName], '操作失败');
          }
        }
        //只要 http 状态码正常，无论 response 的 code 是否正常都执行 success
        typeof success === 'function' && success(res);
      }
      , error: function (e, code) {
        // 如果http状态码为401,则退出登录
        if (e.status === 401) {
          view.exit();
          return;
        }
        if (typeof error === 'function') {
          error(e);
        } else {
          // 如果未自定义error回调，统一处理error
          let msg = e.responseJSON.message || code;
          var errorText = [
            '请求异常，请重试<br><cite>错误信息：</cite>' + msg
            , debug()
          ].join('');
          view.error(errorText);
        }
      }
    }, options));
  };

  //弹窗
  view.popup = function (options) {
    var success = options.success
      , skin = options.skin;

    delete options.success;
    delete options.skin;

    //适配弹出框的大小，不超过当前页面尺寸
    if (options.area) {

      var area = options.area || []
        , windowWidth = $(window).width()
        , windowHeight = $(window).height()
        , areaWidth = area[0] ? area[0].replace('px', '') : windowWidth
        , areaHeight = area[1] ? area[1].replace('px', '') : windowHeight
      options.area = [
        Math.min(windowWidth, areaWidth) + 'px',
        Math.min(windowHeight, areaHeight) + 'px'
      ];
    }

    return layer.open($.extend({
      type: 1
      , title: '提示'
      , content: ''
      , id: 'LAY-system-view-popup'
      , skin: 'layui-layer-admin' + (skin ? ' ' + skin : '')
      , shadeClose: true
      , closeBtn: false
      , success: function (layero, index) {
        var elemClose = $('<i class="layui-icon" close>&#x1006;</i>');
        layero.append(elemClose);
        elemClose.on('click', function () {
          layer.close(index);
        });
        typeof success === 'function' && success.apply(this, arguments);
      }
    }, options))
  };

  // 错误弹出框
  view.error = function (content, options) {
    return view.popup($.extend({
      content: content
      , maxWidth: 300
      , offset: 't'
      , anim: 6
      , id: 'LAY_adminError'
    }, options))
  };
  // 通知弹出框
  view.notice = function (content, title) {
    title = title || '提示信息';
    layer.alert(content, {
      icon: 0,
      shadeClose: true,
      title: title,
    });
  }


  //请求模板文件渲染
  Class.prototype.render = function (views, params) {
    var that = this, router = layui.router();
    views = setter.views + views + setter.engine;

    $('#' + LAY_BODY).children('.layadmin-loading').remove();
    view.loading(that.container); //loading

    //请求模板
    $.ajax({
      url: views
      , type: 'get'
      , dataType: 'html'
      , data: {
        v: layui.cache.version
      }
      , success: function (html) {
        html = '<div>' + html + '</div>';

        var elemTitle = $(html).find('title')
          , title = elemTitle.text() || (html.match(/\<title\>([\s\S]*)\<\/title>/) || [])[1];

        var res = {
          title: title
          , body: html
        };

        elemTitle.remove();
        that.params = params || {}; //获取参数

        if (that.then) {
          that.then(res);
          delete that.then;
        }

        that.parse(html, false, function () {
          //自动处理form表单验证
          let verifyElems = $('*[lay-verify]');
          if (verifyElems.length > 0) {
            verifyElems.each(function () {
              let verifyElem = $(this)
                , labelElem = verifyElem.parent().prev('label')
              ;
              verifyElem.attr('lay-verType', 'tips');
              if (labelElem) {
                let defaultMessage = '请输入' + labelElem.text();
                if (!verifyElem.attr('placeholder')) {
                  verifyElem.attr('placeholder', defaultMessage);
                }
                if (verifyElem.attr('lay-verify') === 'required') {
                  if (!verifyElem.attr('lay-reqtext')) {
                    verifyElem.attr('lay-reqtext', verifyElem.attr('placeholder'));
                  }
                  labelElem.addClass('form-required');
                }
              }
            });
          }
        });

        view.removeLoad();
        if (that.done) {
          that.done(res);
          delete that.done;
        }
      }
      , error: function (e) {
        view.removeLoad();

        if (that.render.isError) {
          return view.error('请求视图文件异常，状态：' + e.status);
        }

        if (e.status === 404) {
          that.render('_base/template/tips/404');
        } else {
          that.render('_base/template/tips/error');
        }

        that.render.isError = true;
      }
    });
    return that;
  };

  //解析模板
  Class.prototype.parse = function (html, refresh, callback) {
    var that = this
      , isScriptTpl = typeof html === 'object' //是否模板元素
      , elem = isScriptTpl ? html : $(html)
      , elemTemp = isScriptTpl ? html : elem.find('*[template]')
      , fn = function (options) {
      var tpl = laytpl(options.dataElem.html())
        , res = $.extend({
        params: router.params
      }, options.res);

      options.dataElem.after(tpl.render(res));

      typeof callback === 'function' && callback();

      try {
        options.done && new Function('d', options.done)(res);
      } catch (e) {
        console.error(options.dataElem[0], '\n存在错误回调脚本\n\n', e)
      }
    }
      , router = layui.router();

    elem.find('title').remove();
    that.container[refresh ? 'after' : 'html'](elem.children());

    router.params = that.params || {};

    //遍历模板区块
    for (var i = elemTemp.length; i > 0; i--) {
      (function () {
        var dataElem = elemTemp.eq(i - 1)
          , layDone = dataElem.attr('lay-done') || dataElem.attr('lay-then') //获取回调
          , url = laytpl(dataElem.attr('lay-url') || '').render(router) //接口 url
          , data = laytpl(dataElem.attr('lay-data') || '').render(router) //接口参数
          , headers = laytpl(dataElem.attr('lay-headers') || '').render(router); //接口请求的头信息

        try {
          data = new Function('return ' + data + ';')();
        } catch (e) {
          hint.error('lay-data: ' + e.message);
          data = {};
        }
        ;

        try {
          headers = new Function('return ' + headers + ';')();
        } catch (e) {
          hint.error('lay-headers: ' + e.message);
          headers = headers || {}
        }
        ;

        if (url) {
          view.req({
            type: dataElem.attr('lay-type') || 'get'
            , url: url
            , data: data
            , dataType: 'json'
            , headers: headers
            , success: function (res) {
              fn({
                dataElem: dataElem
                , res: res
                , done: layDone
              });
            }
          });
        } else {
          fn({
            dataElem: dataElem
            , done: layDone
          });
        }
      }());
    }

    return that;
  };

  //直接渲染字符
  Class.prototype.send = function (views, data) {
    var tpl = laytpl(views || this.container.html()).render(data || {});
    this.container.html(tpl);
    return this;
  };

  //局部刷新模板
  Class.prototype.refresh = function (callback) {
    var that = this
      , next = that.container.next()
      , templateid = next.attr('lay-templateid');

    if (that.id != templateid) return that;

    that.parse(that.container, 'refresh', function () {
      that.container.siblings('[lay-templateid="' + that.id + '"]:last').remove();
      typeof callback === 'function' && callback();
    });

    return that;
  };

  //视图请求成功后的回调
  Class.prototype.then = function (callback) {
    this.then = callback;
    return this;
  };

  //视图渲染完毕后的回调
  Class.prototype.done = function (callback) {
    this.done = callback;
    return this;
  };

  //对外接口
  exports('view', view);
});
