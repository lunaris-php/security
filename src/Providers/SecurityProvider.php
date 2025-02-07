<?php

    namespace Lunaris\Security\Providers;

    class SecurityProvider {
        public function getCommands() {
            return [
                "key:generate" => \Lunaris\Security\Commands\KeyGenerate::class,
                "make:request" => \Lunaris\Security\Commands\MakeRequest::class
            ];
        }
    }