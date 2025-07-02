<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ErrorHandlingService;
use Illuminate\Support\Facades\Log;
use Exception;
use Mockery;

class ErrorHandlingServiceTest extends TestCase
{
    protected ErrorHandlingService $errorHandlingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->errorHandlingService = new ErrorHandlingService();
    }

    public function test_handle_error_logs_and_returns_error_data()
    {
        Log::shouldReceive('error')->once();
        
        $exception = new Exception('Test error message');
        $result = $this->errorHandlingService->handleError($exception, 'test_context');

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('error_id', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertEquals('An unexpected error occurred. Please try again later.', $result['message']);
    }

    public function test_handle_database_error_includes_operation_context()
    {
        Log::shouldReceive('error')->once();
        
        $exception = new Exception('SQLSTATE error');
        $result = $this->errorHandlingService->handleDatabaseError($exception, 'create', ['test' => 'data']);

        $this->assertFalse($result['success']);
        $this->assertEquals('Failed to create record. Please check your input and try again.', $result['message']);
    }

    public function test_handle_validation_error_logs_warning()
    {
        Log::shouldReceive('warning')->once();
        
        $errors = ['field' => 'Field is required'];
        $result = $this->errorHandlingService->handleValidationError($errors);

        $this->assertFalse($result['success']);
        $this->assertEquals('Validation failed.', $result['message']);
        $this->assertEquals($errors, $result['errors']);
    }

    public function test_json_error_response_returns_json()
    {
        $errorData = ['success' => false, 'message' => 'Test error'];
        $response = $this->errorHandlingService->jsonErrorResponse($errorData, 422);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals($errorData, $response->getData(true));
    }

    public function test_log_security_event()
    {
        Log::shouldReceive('warning')->once()->with(
            'Security Event: unauthorized_access',
            Mockery::on(function ($data) {
                return isset($data['event']) && $data['event'] === 'unauthorized_access';
            })
        );

        $this->errorHandlingService->logSecurityEvent('unauthorized_access', ['additional' => 'data']);
    }
}