<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use App\Models\ProductTranslation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use Auth;
use Carbon\Carbon;
use Storage;

//class ProductsImport implements ToModel, WithHeadingRow, WithValidation
class ProductsImport implements ToCollection, WithHeadingRow, WithValidation, ToModel
{
    private $rows = 0;

    public function collection(Collection $rows)
    {
        try{
        $product   = false;
        $canImport = true;
        $user = Auth::user();
        if ($user->user_type == 'seller' && addon_is_activated('seller_subscription')) {
            if ((count($rows) + $user->products()->count()) > $user->shop->product_upload_limit
                || $user->shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($user->shop->package_invalid_at), false) < 0
            ) {
                $canImport = false;
            flash(translate('Please upgrade your package.'))->warning();
        }
    }

    if ($canImport) {
        foreach ($rows as $row) {
            $approved = 1;
            if ($user->user_type == 'seller' && get_setting('product_approve_by_admin') == 1) {
                $approved = 0;
            }

            if (isset($row['jan']) && $row['jan'] !== null) {
                $product = Product::where('jan', $row['jan'])->first();


            }
            if ($product) {

                 $productId = Product::create([
                    'name' => $product->name,
                    'description' => $product->description,
                    'product_type' => $product->product_type,
                    'discount' =>$product->discount,
                    'discount_type' =>$product->discount_type,
                    'added_by' => $user->user_type == 'seller' ? 'seller' : 'admin',
                    'user_id' => $user->user_type == 'seller' ? $user->id : User::where('user_type', 'admin')->first()->id,
                    'approved' => $approved,
                    'category_id' => $product->category_id,
                    'brand_id' => $product->brand_id,
                    'video_provider' =>$product->video_provider,
                    'video_link' => $product->video_link,
                    'tags' => $product->tags,
                    'unit_price' => $product->unit_price,
                    'size' => $product->size,
                    'unit' => $product->unit,
                    'asin' => $product->asin,
                    'jan' => $product->jan,
                    'meta_title' => $product->meta_title,
                    'meta_description' => $product->meta_description,
                    'colors' => $product->colors,
                    'choice_options' => $product->choice_options,
                    'variations' => $product->variations,
                    'slug' => $product->slug. '-' . Str::random(5),
                    'thumbnail_img' => $product->thumbnail_img,
                    'photos' => $product->thumbnail_img,
                ]);
            } 

            else if(isset($row['product_type']) && $row['product_type'] == 2)
            {
                $productId = Product::create([
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'product_type' => $row['product_type'],
                    'discount' =>100,
                    'discount_type' =>'percent',
                    'added_by' => $user->user_type == 'seller' ? 'seller' : 'admin',
                    'user_id' => $user->user_type == 'seller' ? $user->id : User::where('user_type', 'admin')->first()->id,
                    'approved' => $approved,
                    'category_id' => $row['category_id'],
                    'brand_id' => $row['brand_id'],
                    'video_provider' => $row['video_provider'],
                    'video_link' => $row['video_link'],
                    'tags' => $row['tags'],
                    'jan' => $row['jan'],
                    'unit_price' => $row['unit_price'],
                    'size' => $row['size'],
                    'unit' => $row['unit'],
                    'meta_title' => $row['meta_title'],
                    'meta_description' => $row['meta_description'],
                    'colors' => json_encode(array()),
                    'choice_options' => json_encode(array()),
                    'variations' => json_encode(array()),
                    'slug' => preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($row['slug']))) . '-' . Str::random(5),
                    'thumbnail_img' => $this->downloadThumbnail($row['thumbnail_img']),
                    'photos' => $this->downloadGalleryImages($row['photos']),
                ]);
            }
            else
            {
                $productId = Product::create([
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'product_type' => $row['product_type'] ?? 1,
                    'added_by' => $user->user_type == 'seller' ? 'seller' : 'admin',
                    'user_id' => $user->user_type == 'seller' ? $user->id : User::where('user_type', 'admin')->first()->id,
                    'approved' => $approved,
                    'category_id' => $row['category_id'],
                    'brand_id' => $row['brand_id'],
                    'video_provider' => $row['video_provider'],
                    'video_link' => $row['video_link'],
                    'tags' => $row['tags'],
                    'unit_price' => $row['unit_price'],
                    'current_stock' => $row['current_stock'],
                    'low_stock_quantity' => $row['low_stock_quantity'],
                    'warranty_days' => $row['warranty_days'],
                    'warranty_cost' => $row['warranty_cost'],
                    'size' => $row['size'],
                    'unit' => $row['unit'],
                     'jan' => $row['jan'],
                    'meta_title' => $row['meta_title'],
                    'meta_description' => $row['meta_description'],
                    'colors' => json_encode(array()),
                    'choice_options' => json_encode(array()),
                    'variations' => json_encode(array()),
                    'slug' => preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($row['slug']))) . '-' . Str::random(5),
                    'thumbnail_img' => $this->downloadThumbnail($row['thumbnail_img']),
                    'photos' => $this->downloadGalleryImages($row['photos']),
                ]);
            }

            ProductStock::create([
                'product_id' => $productId->id,
                'qty' => $row['current_stock'],
                'price' => $row['unit_price'],
                'sku' => $row['sku'],
                'variant' => '',
            ]);

            if($row['description_en'] != null || $row['name_en'] != null){
                ProductTranslation::create([
                    'product_id' => $productId->id,
                    'lang'=> 'en', 
                    'unit'=> $row['unit'],  
                    'name'=> $row['name_en'], 
                    'description'=> $row['description_en'], 
                 ]);
            }

            if($row['description_br'] != null || $row['name_br'] != null){
                ProductTranslation::create([
                    'product_id' => $productId->id,
                    'lang'=> 'br', 
                    'unit'=> $row['unit'],  
                    'name'=> $row['name_br'], 
                    'description'=> $row['description_br'], 
                 ]);
            }

            if($row['description_jp'] != null || $row['name_jp'] != null){
                ProductTranslation::create([
                    'product_id' => $productId->id,
                    'lang'=> 'jp',
                    'unit'=> $row['unit'],  
                    'name'=> $row['name_jp'], 
                    'description'=> $row['description_jp'], 
                 ]);
            }
        }

        }

        flash(translate('Products imported successfully'))->success();
    }
    catch (\Exception $e) {
        flash('error: Enter file in correct format', 'An error occurred: Enter file in correct format ' . $e->getMessage())->error()->important();
    }
}

public function model(array $row)
{
    ++$this->rows;
}

public function getRowCount(): int
{
    return $this->rows;
}

public function rules(): array
{
    return [
            // Can also use callback validation rules
        'unit_price' => function ($attribute, $value, $onFailure) {
            if (!is_numeric($value)) {
                $onFailure('Unit price is not numeric');
            }
        }
    ];
}

public function downloadThumbnail($url)
{
    try {
        $upload = new Upload;
        $upload->external_link = $url;
        $upload->type = 'image';
        $upload->save();

        return $upload->id;
    } catch (\Exception $e) {
    }
    return null;
}

public function downloadGalleryImages($urls)
{  
    $data = array();
    foreach (explode(',', str_replace(' ', '', $urls)) as $url) {
        $data[] = $this->downloadThumbnail($url);
    }
    return implode(',', $data);
}



}
