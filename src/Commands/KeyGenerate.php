<?php

    namespace Lunaris\Security\Commands;

    use Defuse\Crypto\Key;
    use Exception;

    class KeyGenerate
    {
        private string $path;
        private array $args;

        public function __construct(string $path, array $args) {
            $this->path = $path;
            $this->args = $args;
        }

        public function execute(): void {
            $envFilePath = $this->path . '/.env';
            if(!file_exists($envFilePath)) {
                throw new Exception(".env file not found at {$envFilePath}");
            }

            $key = Key::createNewRandomKey()->saveToAsciiSafeString();
            $envKeyVariable = "APP_KEY";
            $envContent = file_get_contents($envFilePath);
            if(preg_match("/^{$envKeyVariable}=/m", $envContent)) {
                $envContent = preg_replace(
                    "/^{$envKeyVariable}=.*/m",
                    "{$envKeyVariable}={$key}",
                    $envContent
                );
            } else {
                $envContent .= PHP_EOL . "{$envKeyVariable}={$key}" . PHP_EOL;
            }

            file_put_contents($envFilePath, $envContent);

            echo "Key generated successfully" . PHP_EOL;
        }
    }