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
                    "name"=> ["type"=> "string", "analyzer"=> "ik_max_word", "search_analyzer"=> "ik_max_word"]
                ]
            ]
        ]
    ]
];