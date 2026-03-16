<?php 

$template = get_option('oyiso_home_tpl');
$tpl = $template ? $template : 'home';
get_template_part("templates/tpl-{$tpl}");