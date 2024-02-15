<?php
//CSP only works in modern browsers Chrome 25+, Firefox 23+, Safari 7+
$contentSecurityPolicy = "Content-Security-Policy:".
        //"default-src 'self';".
		"connect-src 'self' ;". // XMLHttpRequest (AJAX request), WebSocket or EventSource.
        "frame-ancestors 'self' ;". //allow parent framing - this one blocks click jacking and ui redress
        "frame-src 'none';".
		"img-src 'self';".
        "media-src 'self';". // vaid sources for media (audio and video html tags src)
        "object-src 'none'; ". // valid object embed and applet tags src
        "script-src 'self' code.jquery.com;". // allows js from self, jquery and google analytics.  Inline allows inline js
        "style-src 'self' https://fonts.googleapis.com;";// allows css from self and inline allows inline css
//Sends the Header in the HTTP response to instruct the Browser how it should handle content and what is whitelisted
//Its up to the browser to follow the policy which each browser has varying support
header($contentSecurityPolicy);
header('X-Frame-Options: SAMEORIGIN');
header('Strict-Transport-Security: max-age=631138519');
header('X-Content-Type-Options: nosniff');
header("X-XSS-Protection: 1; mode=block");
?>
