<?php

namespace GetCandy\Shipping\Events;

use GetCandy\DataTypes\ShippingOption;
use GetCandy\Models\Cart;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShippingOptionResolvedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The resolved shipping option.
     *
     * @var ShippingOption
     */
    public ShippingOption $shippingOption;

    /**
     * The instance of the cart.
     *
     * @var Cart
     */
    public Cart $cart;

    public function __construct(Cart $cart, ShippingOption $shippingOption)
    {
        $this->cart = $cart;
        $this->shippingOption = $shippingOption;
    }
}
