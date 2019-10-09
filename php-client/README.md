# RatingCaptain PHP Client
RatingCaptain PHP Client class

You can manage your RatingCaptain email queue with this class.

First, you need to create new instance and pass your RatingCaptain API key,
you can find your API key here: https://ratingcaptain.com/app/websites
<br>
Example: '9f6ag554a73a12d224fc1C3bb2274345' <br>
You have 3 methods available:

Add a product

        /*
            @ID:int Your product ID,
            @NAME:string Your product name,
            @PRICE:float Your product price,
            @IMAGE_URL:string Your product image url,
            @DESC:string Product description 
            @return void
        */
        $ratingcaptain->addProduct(10, 'name', 10.00, 'http://www.website.com/images/1', 'Description');

Add an order to email queue

        /*
            @DATA:array In this array you should have fileds like external_id and email, you can also specify send_date or send_after              
            @return array                                                                                                      
         */
        $order = ["external_id" => $order->id, "email" => $order->email, 'send_date' => Date('Y-m-d H:i:s', strtotime('+5 days'))];
        $test = $ratingcaptain->send($order);

Delete an email from the queue

        /*
            @ID:int Your order id,
            @return:array 
        */
        $ratingcaptain->deleteOrder($order->id);
        
Example integration: 
       
        $order = [];
        $ratingcaptain = new \RatingCaptain($request->apiKey);
        foreach ($request->products as $product){
            $ratingcaptain->addProduct($product['id'], $product['name'], $product['price'], $product['image_url']);
        }
        $order = ["external_id" => $order->id, "email" => $order->email, 'send_date' => Date('Y-m-d H:i:s', strtotime('+5 days'))];
        $test = $ratingcaptain->send($order);
        $ratingcaptain = new \RatingCaptain($request->apiKey);


