<?php
namespace Damas\Faturah;

class Product{
    public $id;
    public $name;
    public $description;
    public $quantity;
    public $price;
}

class Order{
    public $products=array();
    public $productIDs=array();
    public $productNames=array();
    public $productDescriptions=array();
    public $productQuantities=array();
    public $productPrices=array();
    public $deliveryCharge = 0;
    public $totalPrice;
    public $customerName;
    public $date;
    public $customerEmail;
    public $customerPhoneNumber;
    public $lang;

    public function __construct(){
        $this->date = date('m/d/Y h:i:s A');
        $this->totalPrice=0;
    }

    public function customerInfo($name = 'cutomer name', $email = 'customer@domain.com', $phone = '1234567890', $lang = 'en')
    {
        $this->customerName= $name;
        $this->customerEmail= $email;
        $this->customerPhoneNumber = $phone;
        $this->lang = $lang;
    }

    public function deliveryCharges($charges)
    {
        $this->deliveryCharge = $charges;
        $this->totalPrice += $this->deliveryCharge;
    }

    public function addItem($id, $name, $description, $quantity, $price){
        $product = new Product();
        $product->id=$id;
        $product->name=$name;
        $product->description=$description;
        $product->quantity=$quantity;
        $product->price=$price;
        $this->totalPrice += $product->price*$product->quantity;
        array_push($this->products, $product);
        array_push($this->productIDs, $product->id);
        array_push($this->productNames, $product->name);
        array_push($this->productDescriptions, $product->description);
        array_push($this->productQuantities, $product->quantity);
        array_push($this->productPrices, $product->price);
    }
}