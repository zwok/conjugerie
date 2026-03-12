<?php

// Polyfill for Illuminate\Contracts\JsonSchema\JsonSchema
// This interface is expected by laravel/ai but not yet present in the framework.
// Remove this file once Laravel ships the contract.

namespace Illuminate\Contracts\JsonSchema;

interface JsonSchema {}