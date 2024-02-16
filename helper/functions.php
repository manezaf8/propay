<?php

use Core\Response;

function dd($value)
{
	echo '<pre>';
	var_dump($value);
	echo '</pre>';

	die;
}

/**
 * Redict to another path
 *
 * @param string $path
 * @return void
 */
function redirect($path)
{
	$baseUrl = rtrim($_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']), '/');
	header("Location: http://$baseUrl$path");
	exit();
}

/**
 * Custom get error
 *
 * @return void
 */
function getError() {
    $errors = [];

    // Check if there are PHP errors
    if ($error = error_get_last()) {
        $errors[] = $error;
    }

    // Check if there are session errors
    if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])) {
        $errors = array_merge($errors, $_SESSION['errors']);
        unset($_SESSION['errors']); // Clear session errors
    }

    return $errors;
}


/**
 * Base path for uri
 *
 * @param string $path
 * @return string
 */
function base_path($path)
{
	return BASE_PATH . $path;
}

/**
 * Abort 404 error
 *
 * @param integer $code
 * @return void
 */
function abort($code = Response::NOT_FOUND)
{
	http_response_code($code);

	require base_path("/views/{$code}.php");

	die();
}

    /**
     * Get Website URL
     *
     * @param string $path
     * @return string
     */
    function getSiteUrl($path = '')
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $scriptName = $_SERVER['SCRIPT_NAME'];

        // Remove the script filename to get the base URL
        $baseUrl = dirname($scriptName);

        return $protocol . $host . $baseUrl . $path;
    }

/**
 * Check if data is serialized
 *
 * @param [type] $data
 * @param boolean $strict
 * @return boolean
 */
function is_serialized($data, $strict = true)
{
	// If it isn't a string, it isn't serialized.
	if (!is_string($data)) {
		return false;
	}
	$data = trim($data);
	if ('N;' === $data) {
		return true;
	}
	if (strlen($data) < 4) {
		return false;
	}
	if (':' !== $data[1]) {
		return false;
	}
	if ($strict) {
		$lastc = substr($data, -1);
		if (';' !== $lastc && '}' !== $lastc) {
			return false;
		}
	} else {
		$semicolon = strpos($data, ';');
		$brace = strpos($data, '}');
		// Either ; or } must exist.
		if (false === $semicolon && false === $brace) {
			return false;
		}
		// But neither must be in the first X characters.
		if (false !== $semicolon && $semicolon < 3) {
			return false;
		}
		if (false !== $brace && $brace < 4) {
			return false;
		}
	}
	$token = $data[0];
	switch ($token) {
		case 's':
			if ($strict) {
				if ('"' !== substr($data, -2, 1)) {
					return false;
				}
			} elseif (!str_contains($data, '"')) {
				return false;
			}
			// Or else fall through.
		case 'a':
		case 'O':
		case 'E':
			return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
		case 'b':
		case 'i':
		case 'd':
			$end = $strict ? '$' : '';
			return (bool) preg_match("/^{$token}:[0-9.E+-]+;$end/", $data);
	}
	return false;
}
