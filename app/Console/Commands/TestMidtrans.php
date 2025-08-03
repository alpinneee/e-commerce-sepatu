<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Midtrans\Config;
use Midtrans\Snap;

class TestMidtrans extends Command
{
    protected $signature = 'midtrans:test';
    protected $description = 'Test Midtrans configuration';

    public function handle()
    {
        $this->info('Testing Midtrans Configuration...');
        
        // Set configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
        
        // Disable SSL verification for development
        if (!config('midtrans.is_production')) {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ];
        }
        
        $this->info('Configuration loaded:');
        $this->line('Server Key: ' . substr(config('midtrans.server_key'), 0, 10) . '...');
        $this->line('Client Key: ' . substr(config('midtrans.client_key'), 0, 10) . '...');
        $this->line('Is Production: ' . (config('midtrans.is_production') ? 'Yes' : 'No'));
        
        // Test creating a snap token
        try {
            $params = [
                'transaction_details' => [
                    'order_id' => 'TEST-' . time(),
                    'gross_amount' => 100000,
                ],
                'item_details' => [
                    [
                        'id' => 'test-item',
                        'price' => 100000,
                        'quantity' => 1,
                        'name' => 'Test Item'
                    ]
                ],
                'customer_details' => [
                    'first_name' => 'Test',
                    'email' => 'test@example.com',
                    'phone' => '08123456789',
                ]
            ];
            
            $snapToken = Snap::getSnapToken($params);
            
            $this->info('✅ Midtrans connection successful!');
            $this->line('Test Snap Token: ' . substr($snapToken, 0, 20) . '...');
            
        } catch (\Exception $e) {
            $this->error('❌ Midtrans connection failed!');
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}