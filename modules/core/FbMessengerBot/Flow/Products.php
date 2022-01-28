<?php

namespace FbMessengerBot\Flow;

use App\Models\Catalog;

class Products implements \Chatbot\Api\FlowInterface
{

    public function getResponse(\FbMessengerBot\HttpClient\Server $server): array
    {

        $catalog = new Catalog();

        $items = $catalog->find(['status' => Catalog::ACTIVE])->toArray();
        
        if (count($items) > 0) {
            $elements = [];
            foreach($items as $item){
                $exploded = explode(';', $item->image_urls);
                $img = array_shift($exploded);
                $elements[] = [
                    'title' => $item->name,
                    'image_url' => url($img),
                    'subtitle' => 'PHP' . number_format($item->price, 2),
                    'buttons' => [
                        [
                            'type' => 'postback',
                            'title' => 'Add to Cart',
                            'payload' => json_encode([
                                'action' => 'buy',
                                '_id' => $item->_id->__toString(),
                            ]),
                        ]
                    ]
                ];
                unset($item);
            }
    
            return [
                'attachment' => [
                    'type' => 'template',
                    'payload' => [
                        'template_type' => 'generic',
                        'elements' => $elements
                    ]
                ]
            ];
        }

        return [
            'text' => 'Sorry! We do not have products available right now :('
        ];
    }
}