<?php
interface ErrorHandlerInterface
{
    public function getErrors(): array;    
    public function hasErrors(): bool;     
}