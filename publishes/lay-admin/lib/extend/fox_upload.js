layui.define(['upload', 'laytpl'], function (exports) {
  let $ = layui.$
    , upload = layui.upload
    , laytpl = layui.laytpl
    , u = {}

  let tpl_upload =
    '<div class="layui-inline ant-upload ant-upload-select" id="select_{{d.id}}">' +
    ' <span role="button" tabindex="0" class="ant-upload-button" id="btn_upload_{{d.id}}">' +
    '   <div>' +
    '     <i class="layui-icon layui-icon-add-1"></i>' +
    '     <div class="ant-upload-text">{{d.btn_text}}</div>' +
    '   </div>' +
    ' </span>' +
    '</div>'

    , tpl_show =
    '<div class="layui-inline ant-upload ant-upload-show" id="show_{{d.show_id}}">' +
    ' <div class="ant-upload-list-item">' +
    '   <img class="ant-upload-list-item-image" src="{{d.image_url}}"/>' +
    '   <input type="hidden" name="{{d.name}}" value="{{d.image_url}}">' +
    '   <div class="ant-upload-list-item-mask">' +
    '   <span class="ant-upload-list-item-action">' +
    '     <i class="layui-icon btn-preview" data-url="{{d.image_url}}">' +
    '       <svg viewBox="64 64 896 896" data-icon="eye" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false" class="">' +
    '         <path d="M942.2 486.2C847.4 286.5 704.1 186 512 186c-192.2 0-335.4 100.5-430.2 300.3a60.3 60.3 0 0 0 0 51.5C176.6 737.5 319.9 838 512 838c192.2 0 335.4-100.5 430.2-300.3 7.7-16.2 7.7-35 0-51.5zM512 766c-161.3 0-279.4-81.8-362.7-254C232.6 339.8 350.7 258 512 258c161.3 0 279.4 81.8 362.7 254C791.5 684.2 673.4 766 512 766zm-4-430c-97.2 0-176 78.8-176 176s78.8 176 176 176 176-78.8 176-176-78.8-176-176-176zm0 288c-61.9 0-112-50.1-112-112s50.1-112 112-112 112 50.1 112 112-50.1 112-112 112z"></path>' +
    '       </svg>' +
    '     </i>' +
    '     <i class="layui-icon btn-delete" data-show_id="{{d.show_id}}" data-id="{{d.id}}">' +
    '       <svg viewBox="64 64 896 896" data-icon="delete" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false" class="">' +
    '         <path d="M360 184h-8c4.4 0 8-3.6 8-8v8h304v-8c0 4.4 3.6 8 8 8h-8v72h72v-80c0-35.3-28.7-64-64-64H352c-35.3 0-64 28.7-64 64v80h72v-72zm504 72H160c-17.7 0-32 14.3-32 32v32c0 4.4 3.6 8 8 8h60.4l24.7 523c1.6 34.1 29.8 61 63.9 61h454c34.2 0 62.3-26.8 63.9-61l24.7-523H888c4.4 0 8-3.6 8-8v-32c0-17.7-14.3-32-32-32zM731.3 840H292.7l-24.2-512h487l-24.2 512z"></path>' +
    '       </svg>' +
    '     </i>' +
    '    </span>' +
    '   </div>' +
    ' </div>' +
    '</div>'
    ,
    css_tpl = '.ant-upload{display:table;height:104px;width:104px;margin-right:8px;margin-bottom:8px;border-radius:4px;background-color:#fafafa}.ant-upload-select{border:1px dashed #d9d9d9;cursor:pointer;width:104px}.ant-upload-select:hover{border-color:#1890ff}.ant-upload-button{display:table-cell;width:100%;height:100%;padding:8px;text-align:center;vertical-align:middle}.ant-upload-show{border:1px solid #d9d9d9;cursor:pointer}.ant-upload-list-item{position:relative;float:left;height:88px;width:88px;margin:8px}.ant-upload-list-item-mask{position:absolute;top:0;left:0;width:100%;height:100%;background-color:rgba(0,0,0,0.5);color:#fff;opacity:0;transition:all .6s}.ant-upload-list-item:hover .ant-upload-list-item-mask{opacity:1}.ant-upload-list-item-image{position:relative;width:100%;height:100%;overflow:hidden;object-fit:scale-down}.ant-upload-list-item-action{position:absolute;top:50%;left:50%;z-index:10;white-space:nowrap;transform:translate(-50%,-50%)}'

  u.set = function (options) {
    u.options = options
  }
  u.render = function (options) {
    //合并options
    layui.each(u.options, function (key, value) {
      if (!options[key]) {
        options[key] = value
      }
    })
    let id = getRandomId()
      , is_multi = options.multi || false
      , value = options.value
      , style_id = 'fox_uploader_style'

    //写入style
    if (!$('#' + style_id).length) {
      let style_html = '<style id="fox_uploader_style">' + laytpl(css_tpl).render({}) + '</style>'
      $('html').append(style_html)
    }

    //显示上传按钮
    showUploader({elem: options.elem, id: id, btn_text: options.text || 'Upload'});
    let done = options.done
    delete options.done;

    let showParams = {
      elem: options.elem
      , id: id
      , name: options.name || id
      , is_multi: is_multi
    }
    if (Array.isArray(value)) {
      layui.each(value, function (index, url) {
        showImage(showParams, url)
      })
    } else if (value) {
      showImage(showParams, value)
    }


    upload.render({
      elem: '#btn_upload_' + id
      , url: options.url
      , headers: options.headers
      , done: function (res) {
        showImage(showParams, res.data.image_url)
        if (typeof done == 'function') {
          done(res)
        }
      }
    })
  }

  function showUploader(params) {
    let container = $(params.elem)
    let uploadHtml = laytpl(tpl_upload).render(params)
    container.css('display', 'flex').addClass('layui-row')
    container.append(uploadHtml);
  }

  function showImage(params, url) {
    params.image_url = url
    params.show_id = getRandomId()

    let show_html = laytpl(tpl_show).render(params)
      , selectElem = $('#select_' + params.id)
      , is_multi = params.is_multi || false

    selectElem.before(show_html);
    if (!is_multi) {
      selectElem.hide()
    }

    $('.btn-preview').click(function () {
      let url = $(this).data('url')
      previewImage(url)
    })
    $('.btn-delete').click(function (e) {
      let show_id = $(this).data('show_id')
        , id = $(this).data('id')
      $('#show_' + show_id).remove()
      if (!is_multi) {
        $('#select_' + id).show()
      }
    })
  }

  function previewImage(url) {
    layer.photos({
      photos: {
        "title": "图片预览"
        , "data": [{"src": url}]
      }
      , shade: 0.01
      , closeBtn: 1
      , anim: 5
    })
  }

  function getRandomId() {
    return (new Date()).getTime() + '_' + Math.ceil(Math.random() * 1000)
  }

  exports('fox_upload', u)
})
