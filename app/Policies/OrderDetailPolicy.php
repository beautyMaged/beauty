<?php

namespace App\Policies;

use App\Model\OrderDetail;
use App\Model\Seller;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderDetailPolicy
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
     * @param  \App\OrderDetail  $orderDetail
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Seller $seller, OrderDetail $orderDetail)
    {
        return $seller->id == $orderDetail->seller_id;
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
     * @param  \App\OrderDetail  $orderDetail
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Seller $seller, OrderDetail $orderDetail)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\OrderDetail  $orderDetail
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Seller $seller, OrderDetail $orderDetail)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\OrderDetail  $orderDetail
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Seller $seller, OrderDetail $orderDetail)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\OrderDetail  $orderDetail
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Seller $seller, OrderDetail $orderDetail)
    {
        //
    }
}
