<?php

namespace App\Policies;

use App\Model\Product;
use App\Model\Seller;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(Seller $seller)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Product  $product
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Seller $seller, Product $product)
    {
        return $seller->id == $product->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Seller $seller)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Product  $product
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Seller $seller, Product $product)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Product  $product
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Seller $seller, Product $product)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Product  $product
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Seller $seller, Product $product)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Product  $product
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Seller $seller, Product $product)
    {
        //
    }
}
