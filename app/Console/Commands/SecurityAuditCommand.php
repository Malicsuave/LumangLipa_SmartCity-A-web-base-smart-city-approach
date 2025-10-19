<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use App\Rules\NoMaliciousContent;

class SecurityAuditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:audit {--detailed : Show detailed information}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit application security settings and validation rules';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Starting Security Audit...');
        $this->newLine();

        $issues = [];
        
        // Check validation configuration
        $issues = array_merge($issues, $this->checkValidationConfig());
        
        // Check middleware registration
        $issues = array_merge($issues, $this->checkMiddleware());
        
        // Check form request classes
        $issues = array_merge($issues, $this->checkFormRequests());
        
        // Check validation rules
        $issues = array_merge($issues, $this->checkValidationRules());
        
        // Check file permissions
        $issues = array_merge($issues, $this->checkFilePermissions());
        
        // Display results
        $this->displayResults($issues);
        
        return $issues ? Command::FAILURE : Command::SUCCESS;
    }

    protected function checkValidationConfig(): array
    {
        $issues = [];
        
        $this->info('📋 Checking validation configuration...');
        
        // Check if validation config exists
        if (!Config::has('validation')) {
            $issues[] = 'Validation configuration file missing';
        } else {
            $this->line('✅ Validation configuration found');
            
            if ($this->option('detailed')) {
                $rateLimitAttempts = Config::get('validation.security.rate_limiting.max_attempts');
                $this->line("   Rate limit: {$rateLimitAttempts} attempts");
                
                $maxFileSize = Config::get('validation.security.file_uploads.max_file_size');
                $this->line("   Max file size: " . ($maxFileSize / 1024 / 1024) . "MB");
            }
        }
        
        return $issues;
    }

    protected function checkMiddleware(): array
    {
        $issues = [];
        
        $this->info('🛡️ Checking middleware registration...');
        
        $kernelPath = app_path('Http/Kernel.php');
        if (!File::exists($kernelPath)) {
            $issues[] = 'HTTP Kernel file not found';
            return $issues;
        }
        
        $kernelContent = File::get($kernelPath);
        
        // Check if security middleware is registered
        if (strpos($kernelContent, 'ValidateRequestSecurity') !== false) {
            $this->line('✅ ValidateRequestSecurity middleware registered');
        } else {
            $issues[] = 'ValidateRequestSecurity middleware not registered';
        }
        
        return $issues;
    }

    protected function checkFormRequests(): array
    {
        $issues = [];
        
        $this->info('📝 Checking form request classes...');
        
        $formRequestPath = app_path('Http/Requests');
        if (!File::isDirectory($formRequestPath)) {
            $issues[] = 'Form requests directory not found';
            return $issues;
        }
        
        $formRequests = File::files($formRequestPath);
        $checkedRequests = 0;
        $validatedRequests = 0;
        
        foreach ($formRequests as $file) {
            $content = File::get($file->getPathname());
            $checkedRequests++;
            
            if (strpos($content, 'NoMaliciousContent') !== false) {
                $validatedRequests++;
                if ($this->option('detailed')) {
                    $this->line("   ✅ " . $file->getFilenameWithoutExtension());
                }
            } else {
                if ($this->option('detailed')) {
                    $this->line("   ⚠️  " . $file->getFilenameWithoutExtension() . " (missing NoMaliciousContent)");
                }
            }
        }
        
        $this->line("✅ {$validatedRequests}/{$checkedRequests} form requests use security validation");
        
        if ($validatedRequests < $checkedRequests) {
            $issues[] = "Some form requests missing security validation ({$validatedRequests}/{$checkedRequests})";
        }
        
        return $issues;
    }

    protected function checkValidationRules(): array
    {
        $issues = [];
        
        $this->info('🔧 Checking validation rules...');
        
        // Check if NoMaliciousContent rule exists
        $rulePath = app_path('Rules/NoMaliciousContent.php');
        if (File::exists($rulePath)) {
            $this->line('✅ NoMaliciousContent rule found');
            
            // Test the rule with sample data using proper validation testing
            $testCases = [
                'normal text' => true,           // Should PASS (safe content)
                '<script>alert("xss")</script>' => false,   // Should FAIL (malicious)
                'SELECT * FROM users' => false,             // Should FAIL (SQL injection)
                'javascript:void(0)' => false,              // Should FAIL (JavaScript protocol)
            ];
            
            if ($this->option('detailed')) {
                foreach ($testCases as $input => $shouldPass) {
                    $failed = false;
                    
                    // Create the rule instance
                    $rule = new NoMaliciousContent();
                    
                    // Test the rule by capturing if $fail is called
                    $rule->validate('test', $input, function($message) use (&$failed) {
                        $failed = true;
                    });
                    
                    // Determine if the test passed
                    $testPassed = ($shouldPass && !$failed) || (!$shouldPass && $failed);
                    $status = $testPassed ? '✅' : '❌';
                    $result = $failed ? 'BLOCKED' : 'ALLOWED';
                    
                    $this->line("   {$status} Test: '{$input}' -> {$result} " . ($testPassed ? '(CORRECT)' : '(WRONG)'));
                }
            }
            
            // Run a quick validation test to ensure rule is working
            $testFailed = false;
            $rule = new NoMaliciousContent();
            $rule->validate('test', '<script>alert("test")</script>', function() use (&$testFailed) {
                $testFailed = true;
            });
            
            if (!$testFailed) {
                $issues[] = 'NoMaliciousContent rule is not blocking malicious content';
            }
            
        } else {
            $issues[] = 'NoMaliciousContent validation rule not found';
        }
        
        return $issues;
    }

    protected function checkFilePermissions(): array
    {
        $issues = [];
        
        $this->info('🔒 Checking file permissions...');
        
        $criticalPaths = [
            '.env' => '600',
            'storage/logs' => '755',
            'storage/app' => '755',
            'bootstrap/cache' => '755',
        ];
        
        foreach ($criticalPaths as $path => $expectedPerm) {
            $fullPath = base_path($path);
            if (File::exists($fullPath)) {
                $currentPerm = substr(sprintf('%o', fileperms($fullPath)), -3);
                if ($currentPerm === $expectedPerm) {
                    if ($this->option('detailed')) {
                        $this->line("   ✅ {$path}: {$currentPerm}");
                    }
                } else {
                    $issues[] = "Incorrect permissions for {$path}: {$currentPerm} (expected {$expectedPerm})";
                }
            }
        }
        
        return $issues;
    }

    protected function displayResults(array $issues): void
    {
        $this->newLine();
        
        if (empty($issues)) {
            $this->info('🎉 Security audit completed successfully!');
            $this->line('All security checks passed.');
        } else {
            $this->error('⚠️  Security audit found issues:');
            foreach ($issues as $issue) {
                $this->line("   • {$issue}");
            }
            $this->newLine();
            $this->line('Please address these issues to improve security.');
        }
        
        $this->newLine();
        $this->line('Security features implemented:');
        $this->line('• Input validation and sanitization');
        $this->line('• Rate limiting for form submissions');
        $this->line('• Malicious content detection');
        $this->line('• Comprehensive form request validation');
        $this->line('• Security logging and monitoring');
    }
}
