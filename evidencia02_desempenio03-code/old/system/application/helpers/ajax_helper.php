<?php
if(function_exists('isAjax')) {
  return;
} else {
  function isAjax() {
      return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']=="XMLHttpRequest");
  }
}
?>