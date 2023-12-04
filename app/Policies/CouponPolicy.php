<?php

namespace App\Policies;

use App\Model\Coupon;
use App\Model\Seller;
use Illuminate\Auth\Access\HandlesAuthorization;

class CouponPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the Seller can view any models.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(Seller $seller)
    {
        //
    }

    /**
     * Determine whether the Seller can view the model.
     *
     * @param  \App\Seller  $seller
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Seller $seller, Coupon $coupon)
    {
        //
    }

    /**
     * Determine whether the Seller can create models.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Seller $seller)
    {
        //
    }

    /**
     * Determine whether the Seller can update the model.
     *
     * @param  \App\Seller  $seller
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Seller $seller, Coupon $coupon)
    {
        return $seller->id === $coupon->seller_id;
    }

    /**
     * Determine whether the Seller can delete the model.
     *
     * @param  \App\Seller  $seller
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Seller $seller, Coupon $coupon)
    {
        return $seller->id === $coupon->seller_id;
    }

    /**
     * Determine whether the Seller can restore the model.
     *
     * @param  \App\Seller  $seller
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Seller $seller, Coupon $coupon)
    {
        //
    }

    /**
     * Determine whether the Seller can permanently delete the model.
     *
     * @param  \App\Seller  $seller
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Seller $seller, Coupon $coupon)
    {
        //
    }
}
