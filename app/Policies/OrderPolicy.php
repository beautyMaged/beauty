<?php

namespace App\Policies;

use App\Model\Order;
use App\Model\Seller;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
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
     * @param  \App\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Seller $seller, Order $order)
    {
        return $seller->id == $order->seller_id;
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
     * @param  \App\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Seller $seller, Order $order)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Seller $seller, Order $order)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Seller $seller, Order $order)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Seller $seller, Order $order)
    {
        //
    }
}
