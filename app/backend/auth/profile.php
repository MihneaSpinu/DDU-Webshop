<?php
require_once 'app/backend/core/Init.php';

if (! $user->isLoggedIn())
{
     Redirect::to('/');
}

$data = $user->data();


