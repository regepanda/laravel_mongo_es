<?php

/**
 * Created by PhpStorm.
 * User=> regepanda
 * Date=> 18-5-28
 * Time=> 下午5=>24
 */
return [
    'indexMappings' => [
        "settings"=> [
            "analysis"=> [
                "analyzer"=> [
                    "ik"=> [
                        "tokenizer"=> "ik_max_word"
                    ]
                ]
            ]
        ],
        "mappings"=> [
            "tour_product" => [
                "dynamic" => True,
                "properties"=> [
                    "product_id"=> ["type"=> "integer"],
                    "provider_id"=> ["type"=> "integer"],
                    "is_published"=> ["type"=> "integer"],
                    "is_deleted"=> ["type"=> "integer"],
                    "created_at"=> ["type"=> "integer"],
                    "updated_at"=> ["type"=> "integer"],
                    "provider_code"=> ["type"=> "string"],
                    "code"=> ["type"=> "string"],
                    "subcode"=> ["type"=> "string"],
                    "name"=> ["type"=> "string", "analyzer"=> "ik_max_word", "search_analyzer"=> "ik_max_word"],
                    "name_provider"=> ["type"=> "string"],
                    "image_url"=> ["type"=> "string"],
                    "thumbnail_url"=> ["type"=> "string"],
                    "map_image_url"=> ["type"=> "string"],
                    "video_url"=> ["type"=> "string"],
                    "advertised_price"=> ["type"=> "string"],
                    "departure_city"=> ["type"=> "string"],
                    "return_city"=> ["type"=> "string"],
                ]
            ]
        ]
    ]
];