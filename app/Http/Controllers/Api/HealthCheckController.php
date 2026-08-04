<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class HealthCheckController extends Controller
{
    public function check(): JsonResponse
    {
        $status = 'healthy';
        $services = [];

        // 1. Check Database
        try {
            DB::connection()->getPdo();
            DB::select('SELECT 1');
            $services['database'] = [
                'status' => 'UP',
                'message' => 'Database connection is healthy.',
            ];
        } catch (Exception $e) {
            $status = 'unhealthy';
            $services['database'] = [
                'status' => 'DOWN',
                'error' => $e->getMessage(),
            ];
            Log::critical('Health Check Failed (Database): ' . $e->getMessage());
        }

        // 2. Check Storage
        try {
            $tempFileName = 'health_check_' . uniqid() . '.txt';
            Storage::disk('local')->put($tempFileName, 'OK');
            
            if (Storage::disk('local')->get($tempFileName) === 'OK') {
                Storage::disk('local')->delete($tempFileName);
                $services['storage'] = [
                    'status' => 'UP',
                    'message' => 'Storage is writable and readable.',
                ];
            } else {
                throw new Exception('Storage read verification failed.');
            }
        } catch (Exception $e) {
            $status = 'unhealthy';
            $services['storage'] = [
                'status' => 'DOWN',
                'error' => $e->getMessage(),
            ];
            Log::critical('Health Check Failed (Storage): ' . $e->getMessage());
        }

        $httpStatus = $status === 'healthy' ? 200 : 503;

        return response()->json([
            'status' => $status,
            'timestamp' => now()->toIso8601String(),
            'services' => $services,
        ], $httpStatus);
    }
}
