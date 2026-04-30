<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BulkImportExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        return view('admin.bulk-import-export');
    }

    public function export()
    {
        $products = Product::with('category')->get();
        
        $filename = 'products_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Add headers
        fputcsv($handle, ['ID', 'Name', 'SKU', 'Price', 'Compare Price', 'Stock', 'Category', 'Description', 'Status', 'Created At']);
        
        // Add data rows
        foreach ($products as $product) {
            fputcsv($handle, [
                $product->id,
                $product->name,
                $product->sku,
                $product->price,
                $product->compare_price,
                $product->stock,
                $product->category->name ?? 'N/A',
                $product->description,
                $product->status,
                $product->created_at->format('Y-m-d H:i:s')
            ]);
        }
        
        fclose($handle);
        exit;
    }

    public function exportSample()
    {
        $filename = 'sample_products_template.csv';
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Add headers
        fputcsv($handle, ['name', 'sku', 'price', 'compare_price', 'stock', 'category', 'description', 'status']);
        
        // Add sample row
        fputcsv($handle, [
            'Sample Product',
            'SAMPLE001',
            '99.99',
            '129.99',
            '50',
            'Electronics',
            'This is a sample product description',
            'active'
        ]);
        
        fputcsv($handle, [
            'Another Product',
            'SAMPLE002',
            '49.99',
            '59.99',
            '100',
            'Clothing',
            'Another product description',
            'active'
        ]);
        
        fclose($handle);
        exit;
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:5120'
        ]);
        
        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');
        
        // Read headers
        $headers = fgetcsv($handle);
        
        $errors = [];
        $successCount = 0;
        
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($headers, $row);
            
            // Skip if name is empty
            if (empty($data['name'])) {
                $errors[] = "Product name is required for row";
                continue;
            }
            
            // Check if SKU already exists
            if (Product::where('sku', $data['sku'])->exists()) {
                $errors[] = "SKU '{$data['sku']}' already exists";
                continue;
            }
            
            // Find or create category
            $category = null;
            if (!empty($data['category'])) {
                $category = Category::firstOrCreate(
                    ['name' => $data['category']],
                    ['slug' => Str::slug($data['category'])]
                );
            }
            
            // Create product
            Product::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']) . '-' . time() . rand(1000, 9999),
                'sku' => $data['sku'],
                'price' => $data['price'] ?? 0,
                'compare_price' => $data['compare_price'] ?? null,
                'stock' => $data['stock'] ?? 0,
                'category_id' => $category ? $category->id : null,
                'description' => $data['description'] ?? '',
                'status' => $data['status'] ?? 'active',
                'user_id' => auth()->id()
            ]);
            
            $successCount++;
        }
        
        fclose($handle);
        
        if (count($errors) > 0) {
            return back()->with('import_errors', $errors)
                         ->with('partial_success', "Imported {$successCount} products with errors.");
        }
        
        return redirect()->route('products.index')->with('success', "Successfully imported {$successCount} products!");
    }
}