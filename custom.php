<?php

function t($string, array $args = array(), array $options = array()) {
  return \Drupal::translation()->translate($string, $args, $options);
}

function l($text, \Drupal\Core\Url $url, array $options = []) {
  $options['text'] = $text;
  return \Drupal::service('link_generator')->generate($url, $options);
}