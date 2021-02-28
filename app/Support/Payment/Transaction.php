<?php
namespace App\Support\Payment;

use App\Order;
use App\Payment;
use App\Support\Basket\Basket;
use App\Support\Payment\Gateways\Saman;
use App\Support\Payment\Gateways\Pasargad;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Transaction
{
    private $request;
    private $basket;
    public function __construct(Request $request , Basket $basket)
    {
        $this->request = $request;
        $this->basket = $basket;
    }
    function checkout()
    {
        $order = $this->makeOrder();
        $payment = $this->makePayment($order);
        if ($payment->isOnline())
        {
        $this->gatewayFactory()->pay($order);
        }
        $this->basket->clear();
        return $order;
    }
    private function gatewayFactory()
    {
        $gateway = [
            'saman' => Saman::class,
            'pasargad' => Pasargad::class

        ][$this->request->gateway];
        return resolve($gateway);
    }
    private function makeOrder()
    {
        $order = Order::create([
            'user_id' => auth()->user()->id,
            'code' => bin2hex(Str::random(16)),
            'amount' => $this->basket->subTotal()
            ]);
            
        $order->products()->attach($this->products());
        return $order;
    }
    private function makePayment($order)
    {

        return Payment::create([
            'order_id' => $order->id,
            'method' => $this->request->method,
            'amount' => $order->amount
            ]);
    }
    private function products()
    {
        foreach ($this->basket->all() as $product) {
            $products[$product->id] = ['quantity' => $product->quantity];
        }
        return $products;
    }
}
