<?php

    namespace Lunaris\Security\Commands;

    use Lunaris\Security\Utils\Template;

    class MakeRequest {
        private string $path;
        private array $args;

        public function __construct(string $path, array $args) {
            $this->path = $path;
            $this->args = $args;
        }

        public function execute() {
            $args = Template::getArgs($this->args);

            $requestName = $args['name'] ?? null;
            $moduleName = $args['module'] ?? 'Main';

            $content = Template::request($moduleName, $requestName);
            $modulePath = $this->path . "/src/modules/" . $moduleName;
            $requestsFolderPath = $this->checkRequestsFolder($modulePath);
            if($requestsFolderPath) {
                $this->generate($requestName, $content, $requestsFolderPath);
            }
        }

        private function checkRequestsFolder($modulePath) {
            $folderPath = $modulePath . "/Requests";

            if(!is_dir($folderPath)) {
                if(mkdir($folderPath, 0777, true)) {
                    echo "Requests folder has been created in {$modulePath}." . PHP_EOL;
                } else {
                    echo "Failed to create Requests folder in {$modulePath}." . PHP_EOL;
                    return false;
                }
            }

            return $folderPath;
        }

        private function generate($name, $content, $path) {
            $requestFileName = $name . ".php";
            $requestFilePath = $path . "/" . $requestFileName;
            if(file_exists($requestFilePath)) {
                echo "{$name} already exists in {$path}" . PHP_EOL;
                return false;
            }

            file_put_contents($requestFilePath, $content);

            echo $name . " has been created in " . $path . PHP_EOL;
        }
    }