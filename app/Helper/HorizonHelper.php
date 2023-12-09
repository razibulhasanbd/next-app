<?php

namespace App\Helper;

use App\Helper\HorizonHelpers\HorizonDevelopmentSupervisor;
use App\Helper\HorizonHelpers\HorizonLocalSupervisor;
use App\Helper\HorizonHelpers\HorizonProductionSupervisor;

class HorizonHelper
{
    /**
     * returns the list of supervisors for local environment.
     * @return array[]
     */
    public static function getLocalSupervisors()
    {
        return HorizonLocalSupervisor::get();
    }

    /**
     * returns the list of supervisors for dev environment.
     * @return array[]
     */
    public static function getDevSupervisors()
    {
        return HorizonDevelopmentSupervisor::get();
    }

    /**
     * returns the list of supervisors for production environment.
     * @return array[]
     */
    public static function getProductionSupervisors()
    {
        return HorizonProductionSupervisor::get();
    }
}
