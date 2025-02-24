<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Cart;
use App\Notifications\EmailVerificationNotification;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens, HasRoles;

    public function sendEmailVerificationNotification()
    {
        $this->notify(new EmailVerificationNotification());
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
          'name', 'email', 'password', 'address', 'city', 'postal_code', 'phone', 'country', 'provider_id', 'email_verified_at', 'verification_code' ,'balance','consent_to_notifications','birthday', 'referral_code'
    ];




    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function affiliate_user()
    {
        return $this->hasOne(AffiliateUser::class);
    }

    public function affiliate_withdraw_request()
    {
        return $this->hasMany(AffiliateWithdrawRequest::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }
    public function courier()
    {
        return $this->hasOne(Courier::class);
    }

    public function seller()
    {
        return $this->hasOne(Seller::class);
    }


    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function seller_orders()
    {
        return $this->hasMany(Order::class, "seller_id");
    }
    public function seller_sales()
    {
        return $this->hasMany(OrderDetail::class, "seller_id");
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class)->orderBy('created_at', 'desc');
    }

    public function club_point()
    {
        return $this->hasOne(ClubPoint::class);
    }

    public function customer_package()
    {
        return $this->belongsTo(CustomerPackage::class);
    }

    public function customer_package_payments()
    {
        return $this->hasMany(CustomerPackagePayment::class);
    }

    public function customer_products()
    {
        return $this->hasMany(CustomerProduct::class);
    }

    public function seller_package_payments()
    {
        return $this->hasMany(SellerPackagePayment::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function affiliate_log()
    {
        return $this->hasMany(AffiliateLog::class);
    }

    public function product_bids()
    {
        return $this->hasMany(AuctionProductBid::class);
    }
    public function product_queries(){
        return $this->hasMany(ProductQuery::class,'customer_id');
    }
    public function uploads(){
        return $this->hasMany(Upload::class);
    }
    public function candidate_profile()
    {
        return $this->hasOne(JobProfile::class);
    }

    // Define direct referrals relationship
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by', 'id');
    }

    public function referral_commission()
    {
        return $this->hasMany(ReferralCommission::class);
    }

    // Define accessor to get all referrals recursively
    public function getAllReferralsAttribute()
    {
        $allReferrals = collect([$this]);

        $this->load('referrals');

        foreach ($this->referrals as $referral) {
            $allReferrals = $allReferrals->merge($referral->allReferrals);
        }

        return $allReferrals;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->media_folder = self::generateUniqueMediaFolder($user->name);
        });
    }

        /**
     * Generate a unique media folder name based on the user's name.
     *
     * @param string $userName
     * @return string
     */
    protected static function generateUniqueMediaFolder($userName)
    {
        $baseName = Str::slug($userName);
        $uniqueName = $baseName;
        $counter = 1;

        while (User::where('media_folder', $uniqueName)->exists()) {
            $uniqueName = $baseName . '-' . $counter;
            $counter++;
        }

        return $uniqueName;
    }


}
