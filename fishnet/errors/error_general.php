<!doctype html>
<html>
<head>
	<title>Error</title>
	<style type="text/css">
		body {
			background-color:	#fff;
			font-family:		Helvetica, Arial, sans-serif;
			font-size:			65%;
			color:				#454545;
			width:              960px;
			margin:             28px auto;
		}

		#content  {
			font-size: 1.3em;
		}

		h2 { font-size: 1.4em; margin-top: 0; font-weight: normal; }

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
		<h2>There is an error</h2>
		<p><?php echo $message; ?></p>
		<a href="/">Go back to the homepage</a>
	</div>
</body>
</html>
