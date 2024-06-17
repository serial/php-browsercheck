<?php
require 'vendor/autoload.php';

$detect = new WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
//$detect = new WhichBrowser\Parser(getallheaders());

include 'counter.php';
global $count;
?>

<?php
function print_pre($array) {
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

function n_digit_random($digits) {
	return rand(pow(10, $digits - 1) - 1, pow(10, $digits) - 1);
}

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
				content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Browsercheck</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="assets/libs/jquery.fancybox-3.5.7.min.css"/>
	<link rel="stylesheet" href="assets/fonts/fontawesome-pro-6.5.2-web/css/all.min.css">

	<style>
		/* Set to display: none; to prevent the page from flickering */
		body {
			display: none;
		}
	</style>
</head>
<body>

<div class="browser-check-container">

	<?php
	//if ?debug=true is set in the url, the debug info will be displayed
	if (isset($_GET['debug']) && $_GET['debug'] == 'true') : ?>
		<div class="debug short">
			<?php
			echo "<h3>toString() functions</h3>";
			echo "Browser: {$detect->browser->toString()}";
			echo "<br />";
			echo "Engine: {$detect->engine->toString()}";
			echo "<br />";
			echo "OS: {$detect->os->toString()}";
			echo "<br />";
			echo "Device: {$detect->device->type}";
			echo "<br />";
			echo "<hr />";
			?>
		</div>

		<div class="debug object">
			<?php
			print_pre($_SERVER['HTTP_ACCEPT_LANGUAGE']);
			echo "<h3>UA object</h3>";
			print_pre($detect);
			?>
		</div>
	<?php endif; ?>


	<div id="capture" class="browser-info-display wrapper">

		<div class="browser-check-content">
			<div class="browser-check-title">
				<h1>Browser Check</h1>
			</div>
			<div id="token-id" class="text-center">
				<i class="fa-light fa-fingerprint"></i> <b>Token</b> (<span><?php echo n_digit_random(5); ?></span>)
			</div>
		</div>

		<div class="data-points-wrapper">
			<div class="data-point os-name">
				<div class="label">
					Operating System
				</div>
				<hr/>
				<div class="value">
					<?php echo $detect->os->name; ?>
				</div>
			</div>

			<div class="data-point os-version">
				<div class="label">
					OS Version
				</div>
				<hr/>
				<div class="value">
					<?php
					// if alias is not available it must be mobile or tablet with a value
					if (isset($detect->os->version->alias)) : echo $detect->os->version->alias;
					else : echo $detect->os->version->value; endif;
					?>
				</div>
			</div>

			<div class="data-point browser-name">
				<div class="label">
					Browser
				</div>
				<hr/>
				<div class="value">
					<?php echo $detect->browser->name ?>
				</div>
			</div>

			<div class="data-point browser-major-version">
				<div class="label">
					Browser Version
				</div>
				<hr/>
				<div class="value">
					<?php echo $detect->browser->version->value; ?>
				</div>
			</div>

			<div class="data-point browser-long-version">
				<div class="label">
					Browser Engine
				</div>
				<hr/>
				<div class="value">
					<?php echo ($dp = $detect->engine) ? $dp->name : 'n/a'; ?><?php echo (isset($detect->engine->version)) ? $dp->version->value : 'n/a'; ?>
				</div>
			</div>

			<div class="data-point mobile-detect">
				<div class="label">
					Device Type
				</div>
				<hr/>
				<div class="value">
					<?php echo $detect->device->type; ?>
				</div>
			</div>

			<div class="data-point js-cookie-status">
				<div class="label">
					Javascript and Cookies enabled
				</div>
				<hr/>
				<div class="value">
					True
				</div>
			</div>

			<div class="data-point do-not-track">
				<div class="label">
					Do Not Track enabled
				</div>
				<hr/>
				<div class="value">
					{{ sysinfo.dnt }}
				</div>
			</div>

			<div class="data-point window-size">
				<div class="label">
					Window Size (w x h)
				</div>
				<hr/>
				<div class="value">
					{{ sysinfo.window }}
				</div>
			</div>
		</div>

		<div class="data-info">
			<div class="token-info text-center small">
				<i class="fa-thin fa-timer"></i> Time: <?php echo date('d.m.Y - H:i:s', $_SERVER['REQUEST_TIME']); ?>
			</div>

			<div class="client-preferred-locales text-center small">
				<i class="fa-thin fa-language"></i>
				Locales: <?php echo $_SERVER['HTTP_ACCEPT_LANGUAGE']; ?>
			</div>

			<div class="host-info text-center small">
				<i class="fa-thin fa-location-crosshairs"></i>
				IP: <?php echo $_SERVER['REMOTE_ADDR']; ?> -
				Host: <?php echo $_SERVER['HTTP_HOST']; ?>
			</div>

			<div class="ua-string text-center small">
				<i class="fa-thin fa-browser"></i> <?php echo $_SERVER['HTTP_USER_AGENT']; ?>
			</div>

		</div>

	</div>

	<div class="checkbox-wrapper">
		<input id="accept-mail-terms" type="checkbox"/>
		<label for="accept-mail-terms">I confirm sending the mail with information to the webmaster</label>
	</div>

	<div class="actions">
		<a class="button disabled" id="sendEmail">Send Email</a>
		<a class="button" id="captureScreen">Capture Screen</a>
	</div>

	<div class="notice text-center hidden">
		<i class="fa-thin fa-square-info"></i>
		<p>
			This site will not store any information
		</p>
	</div>

	<div class="response">
		<div id="response-message"></div>
	</div>


	<a id="lightbox-link" href="" style="display:none;"></a>


	<footer>
		<div class="separator"></div>

		<div class="counter-hits text-center">
			<?php
			//variable $count comes from counter.php
			echo "Page Hits: " . $count;
			?>
		</div>

		<div class="copyright">
			<p>
				<i class="fa-light fa-copyright"></i> <?php echo date("Y"); ?>
				<a href="https://msn-systems.de" target="_blank">msn-systems.de</a>
			</p>
			<p>
				<a href="https://github.com/serial" target="_blank"><i class="fa-brands fa-github"></i> Michael Höse</a>
			</p>
		</div>

	</footer>

</div>


<script src="assets/libs/jquery-3.5.1.min.js"></script>
<script src="assets/libs/init.min.js"></script>
<script src="assets/libs/jquery.fancybox.3.5.7.min.js"></script>
<script src="assets/libs/html2canvas.min.js"></script>
</body>

</html>
