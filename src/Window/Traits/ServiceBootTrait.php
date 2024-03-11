<?php

namespace iflow\native\Window\Traits;

use iflow\native\Exceptions\RegisterServiceValidException;
use iflow\native\Window\Interfaces\RegisterServiceInterface;

trait ServiceBootTrait {

    protected array $registerService = [];


    protected function boot(): void {

        $registerService = array_merge(
            $this->registerService, $this -> config -> registerService()
        );

        $this->registerService = [];

        foreach ($registerService as $service) {
            $service = new $service;

            if (!$service instanceof RegisterServiceInterface) {
                throw new RegisterServiceValidException();
            }
            $this->registerService[$service::class] = $service -> boot($this -> getNative());
        }
    }

    /**
     * @param string|class-string $service
     * @return T|RegisterServiceInterface
     * @template T
     */
    public function getService(string $service) {
        return $this->registerService[$service] ?? null;
    }

}