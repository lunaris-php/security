<?php

    namespace Lunaris\Security\Http;

    abstract class Request
    {
        protected $inputData;
        protected $jsonData;
        protected $queryParams;
        protected $headers;
        protected $files;
        protected $customData = [];
        protected $errors = [];
        protected $isValidated = false;

        public function __construct()
        {
            $this->inputData = $this->sanitize($_POST);
            $this->queryParams = $this->sanitize($_GET);
            $this->jsonData = json_decode(file_get_contents('php://input'), true);

            $this->headers = getallheaders();
            $this->files = $_FILES;

            if (method_exists($this, 'handle')) {
                $this->handle();
            }
        }

        public function all()
        {
            return array_merge(
                $this->queryParams, 
                $this->inputData, 
                $this->customData, 
                $this->jsonData
            );
        }

        public function input(string $key, $default = null)
        {
            return $this->inputData[$key] ?? $default;
        }

        public function json(string $key, $default = null)
        {
            return $this->jsonData[$key] ?? $default;
        }

        public function param(string $key, $default = null)
        {
            return $this->queryParams[$key] ?? $default;
        }

        public function add(string $key, $value) {
            $this->customData[$key] = $value;
        }

        public function data(string $key, $default = null)
        {
            return $this->customData[$key] ?? $default;
        }

        public function header(string $key, $default = null)
        {
            return $this->headers[$key] ?? $default;
        }

        public function file(string $key)
        {
            return $this->files[$key] ?? null;
        }

        public function error(string $field, string $message)
        {
            $this->errors[$field] = $message;
        }

        public function errors()
        {
            return $this->errors;
        }

        public function validated()
        {
            if (!$this->isValidated) {
                $this->validate();
                $this->isValidated = true;
            }

            return empty($this->errors);
        }

        public function sanitize($data)
        {
            if (is_array($data)) {
                return array_map([$this, 'sanitize'], $data);
            }

            return htmlspecialchars(strip_tags($data), ENT_QUOTES, 'UTF-8');
        }

        abstract protected function validate();

        abstract protected function handle();
    }