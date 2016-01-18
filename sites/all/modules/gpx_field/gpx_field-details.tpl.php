<?php
/**
 * @file
 * Template file to generate gpx file's information.
 *
 * Available float variables:
 * - $elevation
 *   The elevation in meter.
 * - $demotion
 *   The demotion in meter.
 * - $highest_point
 *   The highest point in meter.
 * - $lowest_point
 *   The lowest point in meter.
 * - $distance
 *   The full distance in kilometer.
 */
?>
<p><?php print t('Distance: %distance km', array('%distance' => $distance)); ?></p>
<p><?php print t('Elevation: %elevation m', array('%elevation' => $elevation)); ?></p>
<p><?php print t('Demotion: %demotion m', array('%demotion' => $demotion)); ?></p>
<p><?php print t('Lowest point: %lowest_point m', array('%lowest_point' => $lowest_point)); ?></p>
<p><?php print t('Highest point: %highest_point m', array('%highest_point' => $highest_point)); ?></p>
