<!doctype html>
<html>
<head>
	<title>404 - Page Not Found</title>
	<style type="text/css">
		body {
			background-color:	#fff;
			margin:				40px;
			font-family:		Helvetica, sans-serif;
			font-size:			12px;
			color:				#454545;
		}

		#content  {
			border:	#999 1px solid;
			width: 960px;
			margin:  0 auto;
			padding: 20px;
		}

		img { max-width: 960px; border: 0; }
	</style>
</head>
<body>
	<div id="content">
		<a href="<?site_url('/')?>">
			<img src="<?=STATIC_URL?>images/404.jpg"
				alt="404 - Page Not Found" title="404 - Page Not Found" />
		</a>
	</div>
</body>
</html>
