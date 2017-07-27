<?php namespace Damas\Faturah\Facades;

use Illuminate\Support\Facades\Facade;

class FaturahFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'Faturah';
    }

}