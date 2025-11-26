<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class CsvImportService
{
    protected $successCount = 0;
    protected $errorCount = 0;
    protected $errors = [];
    
    /**
     * Parse CSV file
     */
    public function parseCsv($file, $delimiter = ',', $enclosure = '"')
    {
        $data = [];
        $header = null;
        
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            while (($row = fgetcsv($handle, 10000, $delimiter, $enclosure)) !== false) {
                if (!$header) {
                    $header = array_map('trim', $row);
                } else {
                    $data[] = array_combine($header, array_map('trim', $row));
                }
            }
            fclose($handle);
        }
        
        return $data;
    }
    
    /**
     * Import data with model and validation rules
     */
    public function import($file, $modelClass, $validationRules, $transformCallback = null)
    {
        $this->resetCounters();
        
        try {
            $data = $this->parseCsv($file);
            
            if (empty($data)) {
                throw new Exception('File CSV kosong atau format tidak valid.');
            }
            
            DB::beginTransaction();
            
            foreach ($data as $index => $row) {
                $rowNumber = $index + 2; // +2 karena index 0-based + 1 header row
                
                try {
                    // Validate row
                    $validator = \Validator::make($row, $validationRules);
                    
                    if ($validator->fails()) {
                        $this->errorCount++;
                        $this->errors[] = [
                            'row' => $rowNumber,
                            'data' => $row,
                            'errors' => $validator->errors()->all()
                        ];
                        continue;
                    }
                    
                    // Transform data if callback provided
                    $transformedData = $transformCallback ? $transformCallback($row) : $row;
                    
                    // Create or update model
                    $modelClass::create($transformedData);
                    
                    $this->successCount++;
                    
                } catch (Exception $e) {
                    $this->errorCount++;
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'data' => $row,
                        'errors' => [$e->getMessage()]
                    ];
                    
                    Log::error("CSV Import Error at row {$rowNumber}: " . $e->getMessage(), [
                        'data' => $row
                    ]);
                }
            }
            
            DB::commit();
            
            return [
                'success' => true,
                'successCount' => $this->successCount,
                'errorCount' => $this->errorCount,
                'errors' => $this->errors,
                'total' => count($data)
            ];
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('CSV Import Failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate CSV template
     */
    public function generateTemplate($headers, $filename)
    {
        $handle = fopen('php://output', 'w');
        
        // Write UTF-8 BOM for Excel compatibility
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Write headers
        fputcsv($handle, $headers);
        
        fclose($handle);
        
        return response()->streamDownload(function() use ($headers) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, $headers);
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    
    protected function resetCounters()
    {
        $this->successCount = 0;
        $this->errorCount = 0;
        $this->errors = [];
    }
}
