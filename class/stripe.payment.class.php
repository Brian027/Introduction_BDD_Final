<?php 

// class StripePayment extends PaymentModule
// Version 1.0.0

// Récupérer le panier

require('vendor/autoload.php');

use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripePayment {

    public function __construct(string $clientSecret){
        Stripe::setApiKey($clientSecret);
        Stripe::setApiVersion('2023-08-16'); // Version de l'API
    }

    public function startPayment($cart){
        // Création de la session de paiement
        $session = Session::create([
            'line_items' => [
                
            ],
            'mode' => 'payment',
            'success_url' => 'index.php?page=fo_panier',
            'cancel_url' => 'index.php?page=checkout_cancel',
            'payment_method_types' => ['card'],
        ]);
        header('Location: index.php?page=fo_panier');
    }
}

?>