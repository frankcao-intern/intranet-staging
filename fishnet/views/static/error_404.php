<!doctype html>
<html>
<head>
	<title>404 - Page Not Found</title>
	<style type="text/css">
		body {
			background-color:	#fff;
			font-family:		Helvetica, Arial, sans-serif;
			font-size:			65%;
			color:				#454545;
			margin: 28px auto;
			width: 960px;
		}

		#content  {
			font-size: 1.3em;
		}

		img { border: 0; display: block; margin-bottom: 12px; }

		h2 { font-size: 1.6em; margin-top: 0; font-weight: normal; }

		a { text-decoration: none; color: #607DB7; }
		a:active, a:focus, a:hover { text-decoration: underline; color: #9A0000; }
	</style>
</head>
<body>
	<h1 class="logo">
		<a href="/" accesskey="h">
			<img src="<?=STATIC_URL?>images/logo-gradient.png" height="64" width="122" alt="Home Page" />
		</a>
	</h1>
	<div id="content">
		<a href="<?=site_url('/')?>">
			<img src="<?=STATIC_URL?>images/404-1.jpg" width="764" height="319"
				alt="404 - Page Not Found" title="404 - Page Not Found" />
		</a>
		<?=anchor('/', 'Go back to the homepage and try searching for it.')?>
	</div>
</body>
</html>
