<?php

use App\Domain\Users\Controllers\UserController;

it('tests a route accessible only for admin users', function (){
    $this->getJson(action([UserController::class, 'index']))->assertUnauthorized();
});
