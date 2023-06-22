<?php 
header('Content-Type: application/octet-stream');
header('Cache-Control: no-cache'); // recommended to prevent caching of event data.

// Turn off output buffering
ini_set('output_buffering', 'off');
// Turn off PHP output compression
ini_set('zlib.output_compression', false);
// Implicitly flush the buffer(s)
ini_set('implicit_flush', true);
ob_implicit_flush(true);
// Clear, and turn off output buffering
while (ob_get_level() > 0) {
    // Get the curent level
    $level = ob_get_level();
    // End the buffering
    ob_end_clean();
    // If the current level has not changed, abort
    if (ob_get_level() == $level) break;
	
	
}   

$counter = 0;

while($progress < 100) {
    // STUFF TO DO...
    echo $counter.' - o<br />' . $progress;
	
	$counter++;
	usleep(500);
}
?>