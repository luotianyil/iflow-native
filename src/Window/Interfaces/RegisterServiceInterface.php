<?php

namespace iflow\native\Window\Interfaces;

use iflow\native\Native;

interface RegisterServiceInterface {

    public function boot(Native $native): RegisterServiceInterface;

}