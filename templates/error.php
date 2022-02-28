<?php
// these are our templates
$traceline = "#%s %s(%s): %s(%s)";
$msg = "PHP Fatal error:  Uncaught exception '%s' with message '%s' in %s:%s\nStack trace:\n%s\n  thrown in %s on line %s";

// alter your trace as you please, here
$trace = $e->getTrace();
foreach ($trace as $key => $stackPoint) {
    // I'm converting arguments to their type
    // (prevents passwords from ever getting logged as anything other than 'string')
    $trace[$key]['args'] = array_map('gettype', $trace[$key]['args']);
}

// build your tracelines
$result = array();
foreach ($trace as $key => $stackPoint) {
    $result[] = sprintf(
            $traceline, $key, $stackPoint['file'], $stackPoint['line'], $stackPoint['function'], implode(', ', $stackPoint['args'])
    );
}
// trace always ends with {main}
$result[] = '#' . ++$key . ' {main}';

// write tracelines into main template
$msg = sprintf(
        $msg, get_class($e), $e->getMessage(), $e->getFile(), $e->getLine(), implode("\n", $result), $e->getFile(), $e->getLine()
);
$date = date('l jS \of F Y h:i A');
$msgdated = "\n\n\n" . $date . "\n" . $msg;

// log or echo as you please
error_log($msgdated, 3, "log.txt");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ooops!</title>
    <link defer rel="stylesheet" href="style.css" />
</head>
<main>
<div class="error-page">
    <header>
        <h1>Oops! Something went wrong.</h1>
    </header>
    <p>Sorry for the inconvenience. Please try again later.</p>
    <a class="btn" href="index.php">Take me back home</a>
</div>
</main>
<footer>
    <p class="copyright">
        &copy; <?php echo date("Y"); ?> BetterList inc.
    </p>
</footer>
</body>
</html>