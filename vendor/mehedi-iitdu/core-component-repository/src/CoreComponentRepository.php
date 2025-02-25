<?php

namespace MehediIitdu\CoreComponentRepository;
use App\Models\Addon;
use Cache;

class CoreComponentRepository
{
    public static function instantiateShopRepository() {
    }

    protected static function serializeObjectResponse($zn, $request_data_json) {
    }

    protected static function finalizeRepository($rn) {
    }

    public static function initializeCache() {
        foreach(Addon::all() as $addon){
            $item_name = get_setting('item_name') ?? 'ecommerce';
            
                try {
        
                        Cache::rememberForever($addon->unique_identifier.'-purchased', function () {
                            return 'yes';
                        });
                } catch (\Exception $e) {
        
                }
        }
    }

    public static function finalizeCache($addon){
    } 
}

