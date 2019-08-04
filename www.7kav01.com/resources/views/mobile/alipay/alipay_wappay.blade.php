<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>支付验证</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
</head>
<body>
<script type="text/javascript" src="{{ asset('js/apl.js') }}"></script>
<script>
	_AP.pay("{{$gotoUrl}}");
</script>
</body>
</html>