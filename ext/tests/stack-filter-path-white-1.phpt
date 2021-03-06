--TEST--
Filtered stack traces: path whitelist [1]
--INI--
xdebug.default_enable=1
xdebug.auto_trace=0
xdebug.collect_params=4
--FILE--
<?php
$cwd = __DIR__; $s = DIRECTORY_SEPARATOR;

include "$cwd/filter/stack/one.php";
include "$cwd/filter/stack/two.php";
include "$cwd/filter/stack/three.php";

$three = new \Stack\Three( new stdClass );
$two = new \Stack\Two( $three );
$one = new \Stack\One( $two );

xdebug_set_filter(XDEBUG_FILTER_TRACING, XDEBUG_PATH_WHITELIST, [ "{$cwd}{$s}filter{$s}stack{$s}one" ] );
$one->callObj( 'callObj', 'error', 'Error triggered!' );

xdebug_set_filter(XDEBUG_FILTER_TRACING, XDEBUG_PATH_WHITELIST, [ "{$cwd}{$s}filter{$s}stack{$s}one.php" ] );
$one->callObj( 'callObj', 'error', 'Error triggered!' );

xdebug_set_filter(XDEBUG_FILTER_TRACING, XDEBUG_PATH_WHITELIST, [ "{$cwd}{$s}filter{$s}stack{$s}one.p", "{$cwd}{$s}filter{$s}stack{$s}two.php" ] );
$one->callObj( 'callObj', 'error', 'Error triggered!' );

xdebug_set_filter(XDEBUG_FILTER_TRACING, XDEBUG_PATH_WHITELIST, [ "{$cwd}{$s}filter{$s}stack{$s}t" ] );
$one->callObj( 'callObj', 'error', 'Error triggered!' );

xdebug_set_filter(XDEBUG_FILTER_TRACING, XDEBUG_PATH_WHITELIST, [ 'x' ] );
$one->callObj( 'callObj', 'error', 'Error triggered!' );

xdebug_set_filter(XDEBUG_FILTER_TRACING, XDEBUG_PATH_WHITELIST, [ "{$cwd}" ] );
$one->callObj( 'callObj', 'error', 'Error triggered!' );

xdebug_set_filter(XDEBUG_FILTER_TRACING, XDEBUG_PATH_WHITELIST, [ '' ] );
$one->callObj( 'callObj', 'error', 'Error triggered!' );
?>
--EXPECTF--
Warning: Error triggered! in %sthree.php on line 18

Call Stack:
%w%f %w%d   1. {main}() %sstack-filter-path-white-1.php:0
%w%f %w%d   3. Stack\Two->callObj($name = 'error', ...$arguments = variadic('Error triggered!')) %sone.php:13


Warning: Error triggered! in %sthree.php on line 18

Call Stack:
%w%f %w%d   1. {main}() %sstack-filter-path-white-1.php:0
%w%f %w%d   3. Stack\Two->callObj($name = 'error', ...$arguments = variadic('Error triggered!')) %sone.php:13


Warning: Error triggered! in %sthree.php on line 18

Call Stack:
%w%f %w%d   1. {main}() %sstack-filter-path-white-1.php:0
%w%f %w%d   3. Stack\Two->callObj($name = 'error', ...$arguments = variadic('Error triggered!')) %sone.php:13
%w%f %w%d   4. Stack\Three->error($value = 'Error triggered!') %stwo.php:13


Warning: Error triggered! in %sthree.php on line 18

Call Stack:
%w%f %w%d   1. {main}() %sstack-filter-path-white-1.php:0
%w%f %w%d   4. Stack\Three->error($value = 'Error triggered!') %stwo.php:13
%w%f %w%d   5. trigger_error('Error triggered!', 512) %sthree.php:18


Warning: Error triggered! in %sthree.php on line 18

Call Stack:
%w%f %w%d   1. {main}() %sstack-filter-path-white-1.php:0


Warning: Error triggered! in %sthree.php on line 18

Call Stack:
%w%f %w%d   1. {main}() %sstack-filter-path-white-1.php:0
%w%f %w%d   2. Stack\One->callObj($name = 'callObj', ...$arguments = variadic('error', 'Error triggered!')) %sstack-filter-path-white-1.php:28
%w%f %w%d   3. Stack\Two->callObj($name = 'error', ...$arguments = variadic('Error triggered!')) %sone.php:13
%w%f %w%d   4. Stack\Three->error($value = 'Error triggered!') %stwo.php:13
%w%f %w%d   5. trigger_error('Error triggered!', 512) %sthree.php:18


Warning: Error triggered! in %sthree.php on line 18

Call Stack:
%w%f %w%d   1. {main}() %sstack-filter-path-white-1.php:0
%w%f %w%d   2. Stack\One->callObj($name = 'callObj', ...$arguments = variadic('error', 'Error triggered!')) %sstack-filter-path-white-1.php:31
%w%f %w%d   3. Stack\Two->callObj($name = 'error', ...$arguments = variadic('Error triggered!')) %sone.php:13
%w%f %w%d   4. Stack\Three->error($value = 'Error triggered!') %stwo.php:13
%w%f %w%d   5. trigger_error('Error triggered!', 512) %sthree.php:18
