<?php

namespace App\Policies;

use App\Model\Banner;
use App\Model\Seller;
use Illuminate\Auth\Access\HandlesAuthorization;

class BannerPolicy
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
     * @param  \App\Banner  $banner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Seller $seller, Banner $banner)
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
     * @param  \App\Banner  $banner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Seller $seller, Banner $banner)
    {
        return $seller->id === $banner->seller_id;
    }

    /**
     * Determine whether the Seller can delete the model.
     *
     * @param  \App\Seller  $seller
     * @param  \App\Banner  $banner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Seller $seller, Banner $banner)
    {
        return $seller->id === $banner->seller_id;
    }

    /**
     * Determine whether the Seller can restore the model.
     *
     * @param  \App\Seller  $seller
     * @param  \App\Banner  $banner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Seller $seller, Banner $banner)
    {
        //
    }

    /**
     * Determine whether the Seller can permanently delete the model.
     *
     * @param  \App\Seller  $seller
     * @param  \App\Banner  $banner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Seller $seller, Banner $banner)
    {
        //
    }
}
