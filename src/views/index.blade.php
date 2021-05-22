<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="assets/layui-v2.6.7/css/layui.css" media="all">
</head>
<body>
<div id="LAY_app"></div>
<script src="assets/layui-v2.6.4/layui.js"></script>
<script>
  layui.config({
    base: '{{ $view_path }}'
    , title: '{{ $title }}'
    , debug: '{{ $debug }}'
    , version: '{{ $static_version  }}'
  }).use('index');
</script>
</body>
</html>


