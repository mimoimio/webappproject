<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        // Customer can view their own orders
        if ($user->isCustomer() && $order->customer_id === $user->id) {
            return true;
        }

        // Vendor can view orders placed at their business
        if ($user->isVendor() && $user->vendor && $order->vendor_id === $user->vendor->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isCustomer();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        // Only vendors can update orders (change status)
        return $user->isVendor() && $user->vendor && $order->vendor_id === $user->vendor->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        // Customers can delete their own pending orders
        if ($user->isCustomer() && $order->customer_id === $user->id && $order->status === 'pending_payment') {
            return true;
        }

        return false;
    }
}
