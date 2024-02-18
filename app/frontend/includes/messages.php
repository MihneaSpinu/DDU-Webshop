<?php

if (Session::exists('register-success')) {
  echo '<div class="alert alert-success mt-n5"><strong></strong>' . Session::flash('register-success') . '<a href="login.php"> Login Here</a></div>';
}

if (Session::exists('update-success')) {
  echo '<div class="alert alert-success mt-n5"><strong></strong>' . Session::flash('update-success') . '</div>';
}

if (Session::exists('create-post-success')) {
  echo '<div class="alert alert-success mt-n5"><strong></strong>' . Session::flash('create-post-success') . '</div>';
}

if (Session::exists('register-error')) {
  echo '<div class="alert alert-danger mt-n5"><strong></strong>' . Session::flash('register-error') . '</div>';
}
if (Session::exists('login-success')) {
  echo '<div class="alert alert-success mt-n5"><strong></strong>' . Session::flash('login-success') . '</div>';
}
