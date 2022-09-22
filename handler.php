<?
require_once (__DIR__.'/crest.php');
$iDealID = htmlspecialchars($_REQUEST['properties']['id']);

//Дописать обработку средней цены на услуги
//Дописать обработччик для подсчета туристов


if ($iDealID > 0) { 
    $get_deal = CRest::call(
        'crm.deal.get',
        [
            'id' => $iDealID
        ]
    );  

    $date_from = explode('T', $get_deal['result']['UF_CRM_1546973064714']);
    $date_to = explode('T', $get_deal['result']['UF_CRM_1546972629975']);

    $city_to = $get_deal['result']['UF_CRM_5FD733FE7C097'];
    $service = $get_deal['result']['UF_CRM_5C30DC2A0F44F'];
    
    $city_from = explode('|', $get_deal['result']['UF_CRM_5C55A3BA4EFFD']);
	$user_notify = $get_deal['result']['ASSIGNED_BY_ID'];
    $comments = $get_deal['result']['UF_CRM_612E3DB3508E5'];
  	

    $get_contact_id = CRest::call(
        'crm.deal.contact.items.get',
        [
            'id' => $iDealID
        ]
    );


    $contact_id = $get_contact_id['result'];

    $get_product_deal = CRest::call(
        'crm.deal.productrows.get',
        [
            'id' => $iDealID
        ]
    );
    
    $money_all_price_product = 0;
    foreach($get_product_deal['result'] as $product_item) {
        $product_quantity = (int)($product_item['QUANTITY']);
        $money_all_price_product = $money_all_price_product + ($product_item['PRICE'] * $product_quantity);
    }

    $product_id = $get_product_deal['result'][0]['PRODUCT_ID'];
    $product_name = $get_product_deal['result'][0]['PRODUCT_NAME'];
    $product_quantity_deal_field = (int)($get_deal['result']['UF_CRM_1635518116764']);
    $money_all = (int)$money_all_price_product / $product_quantity_deal_field;
    /*$add_comment_price_product = CRest::call(
        'crm.timeline.comment.add',
        [
            'FIELDS' => [
                'ENTITY_ID' => $iDealID,
                'ENTITY_TYPE' => 'deal',
                'COMMENT' => $money_all_price_product
            ]
        ]
    );*/
    $money_from = (int)($get_deal['result']['UF_CRM_1543073641866'])/$product_quantity_deal_field;
    $money_to = (int)$money_all - (int)$money_from;
    $get_info_product = CRest::call(
        'crm.product.get',
        [
            'id' => $product_id
        ]
    );

    $get_info_section = CRest::call(
        'crm.productsection.get',
        [
            'id' => $get_info_product['result']['SECTION_ID']
        ]
    );
    
    $get_info_section_1 = CRest::call(
        'crm.productsection.get',
        [
            'id' => $get_info_section['result']['SECTION_ID']
        ]
    );
    
    /*$get_entity_section_1 = CRest::call(
        'entity.section.get',
        [
            'ENTITY' => 'lists_entity',
            'FILTER' => [
                '=NAME' => $iDealID
            ]
        ]
    );
    $id_section_get_1 = $get_entity_section['result'][0]['ID'];
    $get_item_section_1 = CRest::call(
        'entity.item.get',
        [
            'ENTITY' => 'lists_entity',
            'FILTER' => [
                '=SECTION' => $id_section_get_1
            ]
        ]
    );
foreach($get_item_section_1['result'] as $item_info_1){
    
    $result_del = $del_item_section['result']
    $add_comment_deal_3 = CRest::call(
        'crm.timeline.comment.add',
        [
            'fields' => [
                'ENTITY_ID' => $iDealID,
                'ENTITY_TYPE' => 'deal',
                'COMMENT' => $result_del
            ]
        ]
    );
}*/
/*$del_item_section = CRest::call(
    'entity.item.delete',
    [
        'ENTITY' => 'lists_entity',
        'ID' => '10853'
    ]
);
$del_item_section_1 = CRest::call(
    'entity.item.delete',
    [
        'ENTITY' => 'lists_entity',
        'ID' => '10849'
    ]
);
$get_entity_section_del = CRest::call(
    'entity.section.delete',
    [
        'ENTITY' => 'lists_entity',
        'ID' => '969'
    ]
);*/
    
    $package = $get_info_section['result']['NAME'];
    $region = $get_info_section_1['result']['NAME'];
    $date_from_origin = (date('d.m.Y',strtotime($date_from[0])));
    $date_to_origin = (date('d.m.Y',strtotime($date_to[0])));

    $get_entity_section = CRest::call(
        'entity.section.get',
        [
            'ENTITY' => 'lists_entity',
            'FILTER' => [
                '=NAME' => $iDealID
            ]
        ]
    );
    $get_section_true = (int)$get_entity_section['total'];
    /*$json_section = json_encode($get_entity_section['result']);
    $add_comment_deal_0 = CRest::call(
        'crm.timeline.comment.add',
        [
            'fields' => [
                'ENTITY_ID' => $iDealID,
                'ENTITY_TYPE' => 'deal',
                'COMMENT' => $json_section
            ]
        ]
    );*/
    
    $id_section_get = $get_entity_section['result'][0]['ID'];

    if($get_section_true != 0 && $id_section_get != 'null'){
        $get_item_section = CRest::call(
            'entity.item.get',
            [
                'ENTITY' => 'lists_entity',
                'FILTER' => [
                    '=SECTION' => $id_section_get
                ]
            ]
        );
        
        $json_item_section = json_encode($get_item_section['result']);
        $get_total_item_elem = (int)$get_item_section['total'];
        /*$add_comment_deal_4 = CRest::call(
            'crm.timeline.comment.add',
            [
                'fields' => [
                    'ENTITY_ID' => $iDealID,
                    'ENTITY_TYPE' => 'deal',
                    'COMMENT' => $json_item_section
                ]
            ]
        );*/
    }
    
    
    if($get_section_true != 0 && $get_total_item_elem != 0) {
        foreach($get_item_section['result'] as $item_info) {

            $id_section_get_item = $item_info['SECTION'];
            
            //Проверка на  изменение города отправления
            if($item_info['PROPERTY_VALUES']['list_field_5'] != $city_from[0]){
                $add_comment_deal_city_from = CRest::call(
                    'crm.timeline.comment.add',
                    [
                        'fields' => [
                            'ENTITY_ID' => $iDealID,
                            'ENTITY_TYPE' => 'deal',
                            'COMMENT' => 'Город отправления изменен'
                        ]
                    ]
                );
                $update_entity_item = CRest::call(
                    'entity.item.update',
                    [
                        'ENTITY' => 'lists_entity',
                        'SECTION' => $id_section_get,
                        'ID' => $item_info['ID'],
                        'PROPERTY_VALUES' => [
                            'list_field_5' => $city_to
                        ]
                    ]
                );
                $get_fields_lists_1 = CRest::call(
                    'lists.field.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id']
                    ]
                );
                $arr_name_field_1 = array();
                foreach($get_fields_lists_1['result'] as $field_item_get) {
                    array_push($arr_name_field_1, $field_item_get['FIELD_ID']);
                }
    
                $get_list_elem = CRest::call(
                    'lists.element.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_item_new']
                    ]
                );
    
                $update_list_elem = CRest::call(
                    'lists.element.update',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_item_new'],
                        'FIELDS' => [
                            $arr_name_field_1[0] =>$get_list_elem['result'][0][$arr_name_field_1[0]],
                            $arr_name_field_1[1] => current($get_list_elem['result'][0][$arr_name_field_1[1]]),
                            $arr_name_field_1[2] => current($get_list_elem['result'][0][$arr_name_field_1[2]]),
                            $arr_name_field_1[3] => current($get_list_elem['result'][0][$arr_name_field_1[3]]),
                            $arr_name_field_1[4] => $city_from[0],
                            $arr_name_field_1[5] => current($get_list_elem['result'][0][$arr_name_field_1[5]]),
                            $arr_name_field_1[6] => current($get_list_elem['result'][0][$arr_name_field_1[6]]),
                            $arr_name_field_1[7] => current($get_list_elem['result'][0][$arr_name_field_1[7]]),
                            $arr_name_field_1[8] => current($get_list_elem['result'][0][$arr_name_field_1[8]]),
                            $arr_name_field_1[9] => current($get_list_elem['result'][0][$arr_name_field_1[9]]),
                            $arr_name_field_1[10] => current($get_list_elem['result'][0][$arr_name_field_1[10]]),
                            $arr_name_field_1[11] => current($get_list_elem['result'][0][$arr_name_field_1[11]]),
                            $arr_name_field_1[12] => current($get_list_elem['result'][0][$arr_name_field_1[12]])
                        ]
                    ]
                );
            } else {}
            //Проверка на изменение города назначения
            if($item_info['PROPERTY_VALUES']['list_field_7'] != $city_to) {
                $add_comment_deal_city_to = CRest::call(
                    'crm.timeline.comment.add',
                    [
                        'fields' => [
                            'ENTITY_ID' => $iDealID,
                            'ENTITY_TYPE' => 'deal',
                            'COMMENT' => 'Город назначения изменен'
                        ]
                    ]
                );
    
                $update_entity_item = CRest::call(
                    'entity.item.update',
                    [
                        'ENTITY' => 'lists_entity',
                        'SECTION' => $id_section_get,
                        'ID' => $item_info['ID'],
                        'PROPERTY_VALUES' => [
                            'list_field_7' => $city_to
                        ]
                    ]
                );
                $get_fields_lists_1 = CRest::call(
                    'lists.field.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id']
                    ]
                );
                $arr_name_field_1 = array();
                foreach($get_fields_lists_1['result'] as $field_item_get) {
                    array_push($arr_name_field_1, $field_item_get['FIELD_ID']);
                }
    
                $get_list_elem = CRest::call(
                    'lists.element.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_item_new']
                    ]
                );
    
                
                $update_list_elem = CRest::call(
                    'lists.element.update',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_item_new'],
                        'FIELDS' => [
                            $arr_name_field_1[0] => $get_list_elem['result'][0][$arr_name_field_1[0]],
                            $arr_name_field_1[1] => current($get_list_elem['result'][0][$arr_name_field_1[1]]),
                            $arr_name_field_1[2] => current($get_list_elem['result'][0][$arr_name_field_1[2]]),
                            $arr_name_field_1[3] => current($get_list_elem['result'][0][$arr_name_field_1[3]]),
                            $arr_name_field_1[4] => current($get_list_elem['result'][0][$arr_name_field_1[4]]),
                            $arr_name_field_1[5] => current($get_list_elem['result'][0][$arr_name_field_1[5]]),
                            $arr_name_field_1[6] => $city_to,
                            $arr_name_field_1[7] => current($get_list_elem['result'][0][$arr_name_field_1[7]]),
                            $arr_name_field_1[8] => current($get_list_elem['result'][0][$arr_name_field_1[8]]),
                            $arr_name_field_1[9] => current($get_list_elem['result'][0][$arr_name_field_1[9]]),
                            $arr_name_field_1[10] => current($get_list_elem['result'][0][$arr_name_field_1[10]]),
                            $arr_name_field_1[11] => current($get_list_elem['result'][0][$arr_name_field_1[11]]),
                            $arr_name_field_1[12] => current($get_list_elem['result'][0][$arr_name_field_1[12]])
                        ]
                    ]
                );
            } else {}
    
            //Проверка на изменение даты начала тура
            if($item_info['PROPERTY_VALUES']['date_from'] != $date_from_origin) {
                $add_comment_deal_date_from = CRest::call(
                    'crm.timeline.comment.add',
                    [
                        'fields' => [
                            'ENTITY_ID' => $iDealID,
                            'ENTITY_TYPE' => 'deal',
                            'COMMENT' => 'Дата начала тура изменена'
                        ]
                    ]
                );
                $delete_element_list_1 = CRest::call(
                    'lists.element.delete',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_item_new']
                    ]
                );

                $get_elem_list_tour_2 = CRest::call(
                    'lists.element.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => '243',
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_lists_item_id']
                    ]
                );

                $busy_count_update_2 = (int)(current($get_elem_list_tour_2['result'][0]['PROPERTY_2137'])) - 1;
                $free_count_update_2 = (int)(current($get_elem_list_tour_2['result'][0]['PROPERTY_2141'])) + 1;
                $id_list_1 = $item_info['PROPERTY_VALUES']['lists_id'];
                $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$id_list_1.'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';
                $update_elements_tour_2 = CRest::call(
                    'lists.element.update',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => '243',
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_lists_item_id'],
                        'FIELDS' => [
                            'NAME' => $get_elem_list_tour_2['result'][0]['NAME'],
                            'PROPERTY_2131' => current($get_elem_list_tour_2['result'][0]['PROPERTY_2131']),
                            'PROPERTY_2133' => current($get_elem_list_tour_2['result'][0]['PROPERTY_2133']),
                            'PROPERTY_2135' => current($get_elem_list_tour_2['result'][0]['PROPERTY_2135']),
                            'PROPERTY_2139' => $free_count_update_2,
                            'PROPERTY_2137' => $busy_count_update_2,
                            'PROPERTY_2141' => $link_lists_tour,
                            'PROPERTY_2129' => current($get_elem_list_tour_2['result'][0]['PROPERTY_2129'])
                        ]
                    ]
                ); 
                $list_id_del = $item_info['PROPERTY_VALUES']['lists_id'];
                $elem_id_tour_del = $item_info['PROPERTY_VALUES']['list_lists_item_id'];

                
                $get_element_lists_validate = CRest::call(
                    'lists.element.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $list_id_del
                    ]
                );

                if($get_element_lists_validate['total'] != 0) {
                    
                } elseif($get_element_lists_validate['total'] == 0) {
                    $delete_list_1 = CRest::call(
                        'lists.delete', 
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_ID' => $list_id_del
                        ]
                    );
                    $delete_elem_list_tour_1 = CRest::call(
                        'lists.element.delete',
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_ID' => '243',
                            'ELEMENT_ID' => $elem_id_tour_del
                        ]
                    );
                }

                

                $lists_name_new = $product_name.' '.$date_from_origin.'-'.$date_to_origin;

                $get_lists = CRest::call(
                    'lists.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists'
                    ]
                );
                $total_lists = $get_lists['total'];

                $calls = ceil($total_lists / 50); 
                $current_call = 0;
                $result_lists = array();  
                do{
                    $current_call++;
                    if(($current_call % 2) == true){
                        sleep(1);
                        $get_info_lists = CRest::call(
                            'lists.get',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'start' => ($current_call - 1) * 50  
                            ] 
                        );
                        
                        $result_lists = array_merge($result_lists, $get_info_lists);
                    } else {
                        $get_info_lists = CRest::call(
                            'lists.get',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'start' => ($current_call - 1) * 50  
                            ] 
                        );
                        
                        $result_lists = array_merge($result_lists, $get_info_lists);
                    }
                    
                } while($current_call < $calls);
                
                

                $count_name_list_3 = 0;
                $count_name_list_4 = 0;
                
                foreach($result_lists['result'] as $lists_item) {
                    $lists_name_old = $lists_item['NAME'];
                    
                    
                    if((string)$lists_name_new === (string)$lists_name_old){
                        $count_name_list_3++;
                        $id_list_new = $lists_item['ID'];
                        
                    } elseif((string)$lists_name_new != (string)$lists_name_old) {
                        $count_name_list_4++;
                    }
                }
                

                if($count_name_list_3 == 0) {
                    /*$delete_entity_item_old = CRest::call(
                        'entity.item.delete',
                        [
                            'ENTITY' => 'lists_entity',
                            'ID' => $item_info['ID']
                        ]
                    );*/
                    $lists_id_new = rand(1, PHP_INT_MAX);
                    $add_lists_1 = CRest::call(
                        'lists.add',
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_CODE' => $lists_id_new,
                            'FIELDS' => [
                                'NAME' => $lists_name_new
                            ],
                            'MESSAGES' => [
                                'ELEMENT_NAME' => 'Элемент',
                                'ELEMENTS_NAME' => 'Элементы',
                                'ELEMENT_ADD' => 'Добавить элемент',
                                'ELEMENT_EDIT' => 'Редактировать элемент',
                                'ELEMENT_DELETE' => 'Удалить элемент',
                                'SECTION_NAME' => 'Раздел',
                                'SECTIONS_NAME' => 'Разделы',
                                'SECTION_ADD' => 'Добавить раздел',
                                'SECTION_EDIT' => 'Редактировать раздел',
                                'SECTION_DELETE' => 'Удалить раздел'
                            ],
                            'RIGHTS' => [
                                'SG4_A' => 'W',
                                'SG4_E' => 'W',
                                'SG4_K' => 'W',
                                'AU' => 'W',
                                'G2' => 'W'
                            ]
                        ]
                    );
                    $id_list = $add_lists_1['result'];
                        $add_fields_batch = CRest::callBatch([
                            'add_field_1' => [
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Номер элемента',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'N',
                                        'SORT' => '20',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_2' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Телефон',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '30',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_3' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => '	Дата рождения',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '40',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_4' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => '	Город',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '50',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_5' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Сделка',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'SORT' => '60',
                                        'TYPE' => 'S:ECrm',
                                        'DEFAULT_VALUE' => 'null',
                                        'USER_TYPE_SETTINGS' => [
                                        'DEAL' => 'Y',
                                        'VISIBLE' => 'Y'
                                        ],
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_6' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Место прилета/приезда туриста',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '70',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_7' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Пакет',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '80',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_8' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Полная стоимость',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '90',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_9' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Внесена предоплата',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '100',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_10' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Осталось оплатить!',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '110',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_11' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Доп. услуги и их количество',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '120',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_12' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Важная информация',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '130',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ]
                        ]);
                        
                        
            
                        $get_fields_lists_1 = CRest::call(
                            'lists.field.get',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $add_lists_1['result']
                            ]
                        );
                        $arr_name_field_2 = array();
                        foreach($get_fields_lists_1['result'] as $field_item_get) {
                            array_push($arr_name_field_2, $field_item_get['FIELD_ID']);
                        }
                            
                            $add_elem_old_lists = CRest::call(
                                'lists.element.add',
                                [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'ELEMENT_CODE' => rand(1, PHP_INT_MAX),
                                    'FIELDS' => [
                                        $arr_name_field_2[0] => $item_info['PROPERTY_VALUES']['list_field_1'],
                                        $arr_name_field_2[1] => $item_info['PROPERTY_VALUES']['list_field_2'],
                                        $arr_name_field_2[2] => $item_info['PROPERTY_VALUES']['list_field_3'],
                                        $arr_name_field_2[3] => $item_info['PROPERTY_VALUES']['list_field_4'],
                                        $arr_name_field_2[4] => $city_from[0],
                                        $arr_name_field_2[5] => $iDealID,
                                        $arr_name_field_2[6] => $city_to,
                                        $arr_name_field_2[7] => $package,
                                        $arr_name_field_2[8] => $money_all,
                                        $arr_name_field_2[9] => $money_from,
                                        $arr_name_field_2[10] => $money_to,
                                        $arr_name_field_2[11] => $service,
                                        $arr_name_field_2[12] => $comments
                                    ]
                                ]
                            );
                            
                            $item_entity_update = CRest::call(
                                'entity.item.update',
                                [
                                    'ENTITY' => 'lists_entity',
                                    'SECTION' => $id_section_get_item,
                                    'ID' => $item_info['ID'],
                                    'PROPERTY_VALUES' => [
                                        'lists_id' => $add_lists_1['result'],
                                        'id_deal' => $iDealID,
                                        'id_contact' => $item_info['PROPERTY_VALUES']['id_contact'],
                                        'list_field_1' => $item_info['PROPERTY_VALUES']['list_field_1'],
                                        'list_field_2' => $item_info['PROPERTY_VALUES']['list_field_2'],
                                        'list_field_3' => $item_info['PROPERTY_VALUES']['list_field_3'],
                                        'list_field_4' => $item_info['PROPERTY_VALUES']['list_field_4'],
                                        'list_field_5' => $city_from[0],
                                        'list_field_6' => $iDealID,
                                        'list_field_7' => $city_to,
                                        'list_field_8' => $package,
                                        'list_field_9' => $money_all,
                                        'list_field_10' => $money_from,
                                        'list_field_11' => $money_to,
                                        'list_field_12' => $service,
                                        'list_field_13' => $comments,
                                        'list_item_new' => $add_elem_old_lists['result'],
                                        'list_lists_item_id' => 'null',
                                        'product_id' => $product_id,
                                        'date_from' => $date_from_origin,
                                        'date_to' => $date_to_origin
                                    ]
                                ]
                            );
                            
                        

                        $get_elements_tour = CRest::call(
                            'lists.element.get',
                            [
                                'IBLOCK_ID' => '243',
                                'IBLOCK_TYPE_ID' => 'lists',
                                'FILTER' => [
                                    'NAME' => $product_name
                                ]
                            ]
                        );

                        $total_elem_lists_tour = (int)($get_elements_tour['total']);
                
                        $count_elem_update_1 = 0;
                        $count_elem_add_1 = 0;
                        if($total_elem_lists_tour != 0) {
                            foreach($get_elements_tour['result'] as $elem_res) {
                            
                                $prop_106 = current($elem_res['PROPERTY_2131']);
                                
                                $prop_108 = current($elem_res['PROPERTY_2133']);
                                
                                if($prop_106 == $date_from_origin && $prop_108 == $date_to_origin){
                                    $id_elem_res = $elem_res['ID'];
                                    $count_elem_update_1 = 1;
                                } else {
                                    $count_elem_add_1 = 1;
                                }
                            }
                            
                            if($count_elem_update_1 == 1) {
    
                                $get_element_tour_info = CRest::call(
                                    'lists.element.get',
                                    [
                                        'IBLOCK_ID' => '243',
                                        'IBLOCK_TYPE_ID' => 'lists',
                                        'ELEMENT_ID' => $id_elem_res
                                    ]
                                );
                                foreach($get_element_tour_info['result'] as $res_info) {
                                    $count_pass = current($res_info['PROPERTY_2135']);
                                    $count_busy_pass = current($res_info['PROPERTY_2137']) + 1;
                                    
                                    
                                    $count_free_pass = $count_pass - $count_busy_pass;
                                    $elem_update_id = $res_info['ID'];
                                    $array_current_elem = current($res_info['PROPERTY_2141']);
                                }            
                                
                                $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$add_lists_1['result'].'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';
                                $update_elements_tour = CRest::call(
                                    'lists.element.update',
                                    [
                                        'IBLOCK_TYPE_ID' => 'lists',
                                        'IBLOCK_ID' => '243',
                                        'ELEMENT_ID' => $id_elem_res,
                                        'FIELDS' => [
                                            'NAME' => $product_name,
                                            'PROPERTY_2131' => $date_from_origin,
                                            'PROPERTY_2133' => $date_to_origin,
                                            'PROPERTY_2135' => $count_pass,
                                            'PROPERTY_2139' => $count_free_pass,
                                            'PROPERTY_2137' => $count_busy_pass,
                                            'PROPERTY_2141' => $link_lists_tour,
                                            'PROPERTY_2129' => $region
                                        ]
                                    ]
                                ); 
                                $update_item_entity = CRest::call(
                                    'entity.item.update',
                                    [
                                        'ENTITY' => 'lists_entity',
                                        'SECTION' => $id_section_get_item,
                                        'ID' => $item_info['ID'],
                                        'PROPERTY_VALUES' => [
                                            'list_lists_item_id' => $id_elem_res
                                        ]
                                    ]
                                );
                            } elseif ($count_elem_add_1 == 1 && $count_elem_update_1 == 0) {
                                $count_pass = 20;
                                $count_busy_pass = 1;
                                $count_free_pass = $count_pass - $count_busy_pass;
                                $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$add_lists_1['result'].'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';
                    
                                $elem_id_tour = rand(1, PHP_INT_MAX);
                                $add_elements_tour = CRest::call(
                                    'lists.element.add',
                                    [
                                        'IBLOCK_TYPE_ID' => 'lists',
                                        'IBLOCK_ID' => '243',
                                        'ELEMENT_CODE' => rand(1, PHP_INT_MAX),
                                        'FIELDS' => [
                                            'NAME' => $product_name,
                                            'PROPERTY_2131' => $date_from_origin,
                                            'PROPERTY_2133' => $date_to_origin,
                                            'PROPERTY_2135' => $count_pass,
                                            'PROPERTY_2139' => $count_free_pass,
                                            'PROPERTY_2137' => $count_busy_pass,
                                            'PROPERTY_2141' => $link_lists_tour,
                                            'PROPERTY_2129' => $region
                                        ]
                                    ]
                                );
                    
                                
                                    $update_item_entity = CRest::call(
                                        'entity.item.update',
                                        [
                                            'ENTITY' => 'lists_entity',
                                            'SECTION' => $id_section_get_item,
                                            'ID' => $item_info['ID'],
                                            'PROPERTY_VALUES' => [
                                                'list_lists_item_id' => $add_elements_tour['result']
                                            ]
                                        ]
                                    );
                                
                                
                            }
                        } else {
                            $count_pass = 20;
                            $count_busy_pass = 0 + $product_quantity_deal_field;
                            $count_free_pass = $count_pass - $count_busy_pass;
                            $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$add_lists_1['result'].'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';

                            $elem_id_tour = rand(1, PHP_INT_MAX);
                            $add_elements_tour = CRest::call(
                                'lists.element.add',
                                [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => '243',
                                    'ELEMENT_CODE' => rand(1, PHP_INT_MAX),
                                    'FIELDS' => [
                                        'NAME' => $product_name,
                                        'PROPERTY_2131' => $date_from_origin,
                                        'PROPERTY_2133' => $date_to_origin,
                                        'PROPERTY_2135' => $count_pass,
                                        'PROPERTY_2139' => $count_free_pass,
                                        'PROPERTY_2137' => $count_busy_pass,
                                        'PROPERTY_2141' => $link_lists_tour,
                                        'PROPERTY_2129' => $region
                                    ]
                                ]
                            );

                            $update_item_entity = CRest::call(
                                'entity.item.update',
                                [
                                    'ENTITY' => 'lists_entity',
                                    'SECTION' => $id_section_get_item,
                                    'ID' => $item_info['ID'],
                                    'PROPERTY_VALUES' => [
                                        'list_lists_item_id' => $add_elements_tour['result']
                                    ]
                                ]
                            );
                        }
                        
                        
                
                            $get_info_field_deal = CRest::call(
                                'crm.deal.get',
                                [
                                    'ID' => $iDealID
                                ]
                            );
                    
                            $field_value_deal = $get_info_field_deal['result']['UF_CRM_1631876593355'];
                    
                            if($field_value_deal == $package) {
                    
                            } else {
                                $update_deal = CRest::call(
                                    'crm.deal.update',
                                    [
                                        'ID' => $iDealID,
                                        'fields' => [
                                            'UF_CRM_1631876593355' => $package
                                        ]
                                    ]
                                );
                            }
                    
                } elseif ($count_name_list_3 > 0) {
                    $get_fields_lists = CRest::call(
                        'lists.field.get',
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_ID' => $id_list_new
                        ]
                    );
                    $arr_name_field = array();
                    foreach($get_fields_lists['result'] as $field_item_get) {
                        array_push($arr_name_field, $field_item_get['FIELD_ID']);
                    }
                    
                    
                    $get_total_elem_list = CRest::call(
                        'lists.element.get',
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_ID' => $id_list_new
                        ]
                    );

                    $count_elem = (int)($get_total_elem_list['total']) + 1;
                        $add_elem_old_lists = CRest::call(
                            'lists.element.add',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $id_list_new,
                                'ELEMENT_CODE' => rand(1, PHP_INT_MAX),
                                'FIELDS' => [
                                    $arr_name_field[0] => $item_info['PROPERTY_VALUES']['list_field_1'],
                                    $arr_name_field[1] => $count_elem,
                                    $arr_name_field[2] => $item_info['PROPERTY_VALUES']['list_field_3'],
                                    $arr_name_field[3] => $item_info['PROPERTY_VALUES']['list_field_4'],
                                    $arr_name_field[4] => $item_info['PROPERTY_VALUES']['list_field_5'],
                                    $arr_name_field[5] => $iDealID,
                                    $arr_name_field[6] => $city_to,
                                    $arr_name_field[7] => $package,
                                    $arr_name_field[8] => $money_all,
                                    $arr_name_field[9] => $money_from,
                                    $arr_name_field[10] => $money_to,
                                    $arr_name_field[11] => $service,
                                    $arr_name_field[12] => $comments
                                ]
                            ]
                        );
                        $item_entity_update = CRest::call(
                            'entity.item.update',
                            [
                                'ENTITY' => 'lists_entity',
                                'SECTION' => $id_section_get_item,
                                'ID' => $item_info['ID'],
                                'PROPERTY_VALUES' => [
                                    'lists_id' => $id_list_new,
                                    'id_deal' => $iDealID,
                                    'id_contact' => $item_info['PROPERTY_VALUES']['id_contact'],
                                    'list_field_1' => $item_info['PROPERTY_VALUES']['list_field_1'],
                                    'list_field_2' => $item_info['PROPERTY_VALUES']['list_field_2'],
                                    'list_field_3' => $item_info['PROPERTY_VALUES']['list_field_3'],
                                    'list_field_4' => $item_info['PROPERTY_VALUES']['list_field_4'],
                                    'list_field_5' => $city_from[0],
                                    'list_field_6' => $iDealID,
                                    'list_field_7' => $city_to,
                                    'list_field_8' => $package,
                                    'list_field_9' => $money_all,
                                    'list_field_10' => $money_from,
                                    'list_field_11' => $money_to,
                                    'list_field_12' => $service,
                                    'list_field_13' => $comments,
                                    'list_item_new' => $add_elem_old_lists['result'],
                                    'product_id' => $product_id,
                                    'date_from' => $date_from_origin,
                                    'date_to' => $date_to_origin
                                ]
                            ]
                        );  
                }
                
                //удалить элементы из старого списка
                //Проверить остались ли элементы в старом списке, если нет, то удалить список
                //изменить элемент в списке туров если элементы остались в старом списке, либо удалить элемент
                //Проверить все списки
                //если есть список с новыми данными, то создать там элементы и изменить элемент в списке туров
                //если списка нет, то создать его и создать элемент в списке тура

            } else{}
    
            //Проверка на изменение даты окончания тура
            if($item_info['PROPERTY_VALUES']['date_to'] != $date_to_origin) {
                $add_comment_deal_date_to = CRest::call(
                    'crm.timeline.comment.add',
                    [
                        'fields' => [
                            'ENTITY_ID' => $iDealID,
                            'ENTITY_TYPE' => 'deal',
                            'COMMENT' => 'Дата окончания изменена'
                        ]
                    ]
                );
                $delete_element_list_1 = CRest::call(
                    'lists.element.delete',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_item_new']
                    ]
                );

                $get_elem_list_tour_2 = CRest::call(
                    'lists.element.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => '243',
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_lists_item_id']
                    ]
                );

                $busy_count_update_2 = (int)(current($get_elem_list_tour_2['result'][0]['PROPERTY_2137'])) - 1;
                $free_count_update_2 = (int)(current($get_elem_list_tour_2['result'][0]['PROPERTY_2141'])) + 1;
                $id_list_1 = $item_info['PROPERTY_VALUES']['lists_id'];
                $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$id_list_1.'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';
                $update_elements_tour_2 = CRest::call(
                    'lists.element.update',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => '243',
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_lists_item_id'],
                        'FIELDS' => [
                            'NAME' => $get_elem_list_tour_2['result'][0]['NAME'],
                            'PROPERTY_2131' => current($get_elem_list_tour_2['result'][0]['PROPERTY_2131']),
                            'PROPERTY_2133' => current($get_elem_list_tour_2['result'][0]['PROPERTY_2133']),
                            'PROPERTY_2135' => current($get_elem_list_tour_2['result'][0]['PROPERTY_2135']),
                            'PROPERTY_2139' => $free_count_update_2,
                            'PROPERTY_2137' => $busy_count_update_2,
                            'PROPERTY_2141' => $link_lists_tour,
                            'PROPERTY_2129' => current($get_elem_list_tour_2['result'][0]['PROPERTY_2129'])
                        ]
                    ]
                ); 
                $list_id_del = $item_info['PROPERTY_VALUES']['lists_id'];
                $elem_id_tour_del = $item_info['PROPERTY_VALUES']['list_lists_item_id'];

                
                $get_element_lists_validate = CRest::call(
                    'lists.element.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $list_id_del
                    ]
                );

                if($get_element_lists_validate['total'] != 0) {
                    
                } elseif($get_element_lists_validate['total'] == 0) {
                    $delete_list_1 = CRest::call(
                        'lists.delete', 
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_ID' => $list_id_del
                        ]
                    );
                    $delete_elem_list_tour_1 = CRest::call(
                        'lists.element.delete',
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_ID' => '243',
                            'ELEMENT_ID' => $elem_id_tour_del
                        ]
                    );
                }

                

                $lists_name_new = $product_name.' '.$date_from_origin.'-'.$date_to_origin;

                $get_lists = CRest::call(
                    'lists.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists'
                    ]
                );
                $total_lists = $get_lists['total'];

                $calls = ceil($total_lists / 50); 
                $current_call = 0;
                $result_lists = array();  
                do{
                    $current_call++;
                    if(($current_call % 2) == true){
                        sleep(1);
                        $get_info_lists = CRest::call(
                            'lists.get',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'start' => ($current_call - 1) * 50  
                            ] 
                        );
                        
                        $result_lists = array_merge($result_lists, $get_info_lists);
                    } else {
                        $get_info_lists = CRest::call(
                            'lists.get',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'start' => ($current_call - 1) * 50  
                            ] 
                        );
                        
                        $result_lists = array_merge($result_lists, $get_info_lists);
                    }
                    
                } while($current_call < $calls);
                
                

                $count_name_list_3 = 0;
                $count_name_list_4 = 0;
                
                foreach($result_lists['result'] as $lists_item) {
                    $lists_name_old = $lists_item['NAME'];
                    
                    
                    if((string)$lists_name_new === (string)$lists_name_old){
                        $count_name_list_3++;
                        $id_list_new = $lists_item['ID'];
                        
                    } elseif((string)$lists_name_new != (string)$lists_name_old) {
                        $count_name_list_4++;
                    }
                }
                

                if($count_name_list_3 == 0) {
                    /*$delete_entity_item_old = CRest::call(
                        'entity.item.delete',
                        [
                            'ENTITY' => 'lists_entity',
                            'ID' => $item_info['ID']
                        ]
                    );*/
                    $lists_id_new = rand(1, PHP_INT_MAX);
                    $add_lists_1 = CRest::call(
                        'lists.add',
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_CODE' => $lists_id_new,
                            'FIELDS' => [
                                'NAME' => $lists_name_new
                            ],
                            'MESSAGES' => [
                                'ELEMENT_NAME' => 'Элемент',
                                'ELEMENTS_NAME' => 'Элементы',
                                'ELEMENT_ADD' => 'Добавить элемент',
                                'ELEMENT_EDIT' => 'Редактировать элемент',
                                'ELEMENT_DELETE' => 'Удалить элемент',
                                'SECTION_NAME' => 'Раздел',
                                'SECTIONS_NAME' => 'Разделы',
                                'SECTION_ADD' => 'Добавить раздел',
                                'SECTION_EDIT' => 'Редактировать раздел',
                                'SECTION_DELETE' => 'Удалить раздел'
                            ],
                            'RIGHTS' => [
                                'SG4_A' => 'W',
                                'SG4_E' => 'W',
                                'SG4_K' => 'W',
                                'AU' => 'W',
                                'G2' => 'W'
                            ]
                        ]
                    );
                    $id_list = $add_lists_1['result'];
                        $add_fields_batch = CRest::callBatch([
                            'add_field_1' => [
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Номер элемента',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'N',
                                        'SORT' => '20',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_2' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Телефон',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '30',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_3' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => '	Дата рождения',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '40',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_4' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => '	Город',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '50',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_5' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Сделка',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'SORT' => '60',
                                        'TYPE' => 'S:ECrm',
                                        'DEFAULT_VALUE' => 'null',
                                        'USER_TYPE_SETTINGS' => [
                                        'DEAL' => 'Y',
                                        'VISIBLE' => 'Y'
                                        ],
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_6' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Место прилета/приезда туриста',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '70',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_7' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Пакет',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '80',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_8' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Полная стоимость',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '90',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_9' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Внесена предоплата',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '100',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_10' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Осталось оплатить!',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '110',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_11' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Доп. услуги и их количество',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '120',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_12' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Важная информация',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '130',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ]
                        ]);
                        
                        
            
                        $get_fields_lists_1 = CRest::call(
                            'lists.field.get',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $add_lists_1['result']
                            ]
                        );
                        $arr_name_field_2 = array();
                        foreach($get_fields_lists_1['result'] as $field_item_get) {
                            array_push($arr_name_field_2, $field_item_get['FIELD_ID']);
                        }
                            
                            $add_elem_old_lists = CRest::call(
                                'lists.element.add',
                                [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'ELEMENT_CODE' => rand(1, PHP_INT_MAX),
                                    'FIELDS' => [
                                        $arr_name_field_2[0] => $item_info['PROPERTY_VALUES']['list_field_1'],
                                        $arr_name_field_2[1] => $item_info['PROPERTY_VALUES']['list_field_2'],
                                        $arr_name_field_2[2] => $item_info['PROPERTY_VALUES']['list_field_3'],
                                        $arr_name_field_2[3] => $item_info['PROPERTY_VALUES']['list_field_4'],
                                        $arr_name_field_2[4] => $city_from[0],
                                        $arr_name_field_2[5] => $iDealID,
                                        $arr_name_field_2[6] => $city_to,
                                        $arr_name_field_2[7] => $package,
                                        $arr_name_field_2[8] => $money_all,
                                        $arr_name_field_2[9] => $money_from,
                                        $arr_name_field_2[10] => $money_to,
                                        $arr_name_field_2[11] => $service,
                                        $arr_name_field_2[12] => $comments
                                    ]
                                ]
                            );
                            
                            $item_entity_update = CRest::call(
                                'entity.item.update',
                                [
                                    'ENTITY' => 'lists_entity',
                                    'SECTION' => $id_section_get_item,
                                    'ID' => $item_info['ID'],
                                    'PROPERTY_VALUES' => [
                                        'lists_id' => $add_lists_1['result'],
                                        'id_deal' => $iDealID,
                                        'id_contact' => $item_info['PROPERTY_VALUES']['id_contact'],
                                        'list_field_1' => $item_info['PROPERTY_VALUES']['list_field_1'],
                                        'list_field_2' => $item_info['PROPERTY_VALUES']['list_field_2'],
                                        'list_field_3' => $item_info['PROPERTY_VALUES']['list_field_3'],
                                        'list_field_4' => $item_info['PROPERTY_VALUES']['list_field_4'],
                                        'list_field_5' => $city_from[0],
                                        'list_field_6' => $iDealID,
                                        'list_field_7' => $city_to,
                                        'list_field_8' => $package,
                                        'list_field_9' => $money_all,
                                        'list_field_10' => $money_from,
                                        'list_field_11' => $money_to,
                                        'list_field_12' => $service,
                                        'list_field_13' => $comments,
                                        'list_item_new' => $add_elem_old_lists['result'],
                                        'list_lists_item_id' => 'null',
                                        'product_id' => $product_id,
                                        'date_from' => $date_from_origin,
                                        'date_to' => $date_to_origin
                                    ]
                                ]
                            );
                            
                        

                        $get_elements_tour = CRest::call(
                            'lists.element.get',
                            [
                                'IBLOCK_ID' => '243',
                                'IBLOCK_TYPE_ID' => 'lists',
                                'FILTER' => [
                                    'NAME' => $product_name
                                ]
                            ]
                        );

                        $total_elem_lists_tour = (int)($get_elements_tour['total']);
                
                        $count_elem_update_1 = 0;
                        $count_elem_add_1 = 0;
                        if($total_elem_lists_tour != 0) {
                            foreach($get_elements_tour['result'] as $elem_res) {
                            
                                $prop_106 = current($elem_res['PROPERTY_2131']);
                                
                                $prop_108 = current($elem_res['PROPERTY_2133']);
                                
                                if($prop_106 == $date_from_origin && $prop_108 == $date_to_origin){
                                    $id_elem_res = $elem_res['ID'];
                                    $count_elem_update_1 = 1;
                                } else {
                                    $count_elem_add_1 = 1;
                                }
                            }
                            
                            if($count_elem_update_1 == 1) {
    
                                $get_element_tour_info = CRest::call(
                                    'lists.element.get',
                                    [
                                        'IBLOCK_ID' => '243',
                                        'IBLOCK_TYPE_ID' => 'lists',
                                        'ELEMENT_ID' => $id_elem_res
                                    ]
                                );
                                foreach($get_element_tour_info['result'] as $res_info) {
                                    $count_pass = current($res_info['PROPERTY_2135']);
                                    $count_busy_pass = current($res_info['PROPERTY_2137']) + 1;
                                    
                                    
                                    $count_free_pass = $count_pass - $count_busy_pass;
                                    $elem_update_id = $res_info['ID'];
                                    $array_current_elem = current($res_info['PROPERTY_2141']);
                                }            
                                
                                $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$add_lists_1['result'].'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';
                                $update_elements_tour = CRest::call(
                                    'lists.element.update',
                                    [
                                        'IBLOCK_TYPE_ID' => 'lists',
                                        'IBLOCK_ID' => '243',
                                        'ELEMENT_ID' => $id_elem_res,
                                        'FIELDS' => [
                                            'NAME' => $product_name,
                                            'PROPERTY_2131' => $date_from_origin,
                                            'PROPERTY_2133' => $date_to_origin,
                                            'PROPERTY_2135' => $count_pass,
                                            'PROPERTY_2139' => $count_free_pass,
                                            'PROPERTY_2137' => $count_busy_pass,
                                            'PROPERTY_2141' => $link_lists_tour,
                                            'PROPERTY_2129' => $region
                                        ]
                                    ]
                                ); 
                                $update_item_entity = CRest::call(
                                    'entity.item.update',
                                    [
                                        'ENTITY' => 'lists_entity',
                                        'SECTION' => $id_section_get_item,
                                        'ID' => $item_info['ID'],
                                        'PROPERTY_VALUES' => [
                                            'list_lists_item_id' => $id_elem_res
                                        ]
                                    ]
                                );
                            } elseif ($count_elem_add_1 == 1 && $count_elem_update_1 == 0) {
                                $count_pass = 20;
                                $count_busy_pass = 1;
                                $count_free_pass = $count_pass - $count_busy_pass;
                                $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$add_lists_1['result'].'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';
                    
                                $elem_id_tour = rand(1, PHP_INT_MAX);
                                $add_elements_tour = CRest::call(
                                    'lists.element.add',
                                    [
                                        'IBLOCK_TYPE_ID' => 'lists',
                                        'IBLOCK_ID' => '243',
                                        'ELEMENT_CODE' => rand(1, PHP_INT_MAX),
                                        'FIELDS' => [
                                            'NAME' => $product_name,
                                            'PROPERTY_2131' => $date_from_origin,
                                            'PROPERTY_2133' => $date_to_origin,
                                            'PROPERTY_2135' => $count_pass,
                                            'PROPERTY_2139' => $count_free_pass,
                                            'PROPERTY_2137' => $count_busy_pass,
                                            'PROPERTY_2141' => $link_lists_tour,
                                            'PROPERTY_2129' => $region
                                        ]
                                    ]
                                );
                    
                                
                                    $update_item_entity = CRest::call(
                                        'entity.item.update',
                                        [
                                            'ENTITY' => 'lists_entity',
                                            'SECTION' => $id_section_get_item,
                                            'ID' => $item_info['ID'],
                                            'PROPERTY_VALUES' => [
                                                'list_lists_item_id' => $add_elements_tour['result']
                                            ]
                                        ]
                                    );
                                
                                
                            }
                        } else {
                            $count_pass = 20;
                            $count_busy_pass = 0 + $product_quantity_deal_field;
                            $count_free_pass = $count_pass - $count_busy_pass;
                            $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$add_lists_1['result'].'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';

                            $elem_id_tour = rand(1, PHP_INT_MAX);
                            $add_elements_tour = CRest::call(
                                'lists.element.add',
                                [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => '243',
                                    'ELEMENT_CODE' => rand(1, PHP_INT_MAX),
                                    'FIELDS' => [
                                        'NAME' => $product_name,
                                        'PROPERTY_2131' => $date_from_origin,
                                        'PROPERTY_2133' => $date_to_origin,
                                        'PROPERTY_2135' => $count_pass,
                                        'PROPERTY_2139' => $count_free_pass,
                                        'PROPERTY_2137' => $count_busy_pass,
                                        'PROPERTY_2141' => $link_lists_tour,
                                        'PROPERTY_2129' => $region
                                    ]
                                ]
                            );

                            $update_item_entity = CRest::call(
                                'entity.item.update',
                                [
                                    'ENTITY' => 'lists_entity',
                                    'SECTION' => $id_section_get_item,
                                    'ID' => $item_info['ID'],
                                    'PROPERTY_VALUES' => [
                                        'list_lists_item_id' => $add_elements_tour['result']
                                    ]
                                ]
                            );
                        }
                        
                        
                
                            $get_info_field_deal = CRest::call(
                                'crm.deal.get',
                                [
                                    'ID' => $iDealID
                                ]
                            );
                    
                            $field_value_deal = $get_info_field_deal['result']['UF_CRM_1631876593355'];
                    
                            if($field_value_deal == $package) {
                    
                            } else {
                                $update_deal = CRest::call(
                                    'crm.deal.update',
                                    [
                                        'ID' => $iDealID,
                                        'fields' => [
                                            'UF_CRM_1631876593355' => $package
                                        ]
                                    ]
                                );
                            }
                    
                } elseif ($count_name_list_3 > 0) {
                    $get_fields_lists = CRest::call(
                        'lists.field.get',
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_ID' => $id_list_new
                        ]
                    );
                    $arr_name_field = array();
                    foreach($get_fields_lists['result'] as $field_item_get) {
                        array_push($arr_name_field, $field_item_get['FIELD_ID']);
                    }
                    
                    
                    $get_total_elem_list = CRest::call(
                        'lists.element.get',
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_ID' => $id_list_new
                        ]
                    );

                    $count_elem = (int)($get_total_elem_list['total']) + 1;
                        $add_elem_old_lists = CRest::call(
                            'lists.element.add',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $id_list_new,
                                'ELEMENT_CODE' => rand(1, PHP_INT_MAX),
                                'FIELDS' => [
                                    $arr_name_field[0] => $item_info['PROPERTY_VALUES']['list_field_1'],
                                    $arr_name_field[1] => $count_elem,
                                    $arr_name_field[2] => $item_info['PROPERTY_VALUES']['list_field_3'],
                                    $arr_name_field[3] => $item_info['PROPERTY_VALUES']['list_field_4'],
                                    $arr_name_field[4] => $item_info['PROPERTY_VALUES']['list_field_5'],
                                    $arr_name_field[5] => $iDealID,
                                    $arr_name_field[6] => $city_to,
                                    $arr_name_field[7] => $package,
                                    $arr_name_field[8] => $money_all,
                                    $arr_name_field[9] => $money_from,
                                    $arr_name_field[10] => $money_to,
                                    $arr_name_field[11] => $service,
                                    $arr_name_field[12] => $comments
                                ]
                            ]
                        );
                        $item_entity_update = CRest::call(
                            'entity.item.update',
                            [
                                'ENTITY' => 'lists_entity',
                                'SECTION' => $id_section_get_item,
                                'ID' => $item_info['ID'],
                                'PROPERTY_VALUES' => [
                                    'lists_id' => $id_list_new,
                                    'id_deal' => $iDealID,
                                    'id_contact' => $item_info['PROPERTY_VALUES']['id_contact'],
                                    'list_field_1' => $item_info['PROPERTY_VALUES']['list_field_1'],
                                    'list_field_2' => $item_info['PROPERTY_VALUES']['list_field_2'],
                                    'list_field_3' => $item_info['PROPERTY_VALUES']['list_field_3'],
                                    'list_field_4' => $item_info['PROPERTY_VALUES']['list_field_4'],
                                    'list_field_5' => $city_from[0],
                                    'list_field_6' => $iDealID,
                                    'list_field_7' => $city_to,
                                    'list_field_8' => $package,
                                    'list_field_9' => $money_all,
                                    'list_field_10' => $money_from,
                                    'list_field_11' => $money_to,
                                    'list_field_12' => $service,
                                    'list_field_13' => $comments,
                                    'list_item_new' => $add_elem_old_lists['result'],
                                    'product_id' => $product_id,
                                    'date_from' => $date_from_origin,
                                    'date_to' => $date_to_origin
                                ]
                            ]
                        );  
                }
                //удалить элементы из старого списка
                //Проверить остались ли элементы в старом списке, если нет, то удалить список
                //изменить элемент в списке туров если элементы остались в старом списке, либо удалить элемент
                //Проверить все списки
                //если есть список с новыми данными, то создать там элементы и изменить элемент в списке туров
                //если списка нет, то создать его и создать элемент в списке тура
            } else {}
    
            //Проверка на изменение продукта в сделке
            if($item_info['PROPERTY_VALUES']['product_id'] != $product_id) {
                $add_comment_deal_product = CRest::call(
                    'crm.timeline.comment.add',
                    [
                        'fields' => [
                            'ENTITY_ID' => $iDealID,
                            'ENTITY_TYPE' => 'deal',
                            'COMMENT' => 'Товар был изменен'
                        ]
                    ]
                );
                $delete_element_list_1 = CRest::call(
                    'lists.element.delete',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_item_new']
                    ]
                );

                $get_elem_list_tour_2 = CRest::call(
                    'lists.element.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => '243',
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_lists_item_id']
                    ]
                );

                $busy_count_update_2 = (int)(current($get_elem_list_tour_2['result'][0]['PROPERTY_2137'])) - 1;
                $free_count_update_2 = (int)(current($get_elem_list_tour_2['result'][0]['PROPERTY_2141'])) + 1;
                $id_list_1 = $item_info['PROPERTY_VALUES']['lists_id'];
                $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$id_list_1.'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';
                $update_elements_tour_2 = CRest::call(
                    'lists.element.update',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => '243',
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_lists_item_id'],
                        'FIELDS' => [
                            'NAME' => $get_elem_list_tour_2['result'][0]['NAME'],
                            'PROPERTY_2131' => current($get_elem_list_tour_2['result'][0]['PROPERTY_2131']),
                            'PROPERTY_2133' => current($get_elem_list_tour_2['result'][0]['PROPERTY_2133']),
                            'PROPERTY_2135' => current($get_elem_list_tour_2['result'][0]['PROPERTY_2135']),
                            'PROPERTY_2139' => $free_count_update_2,
                            'PROPERTY_2137' => $busy_count_update_2,
                            'PROPERTY_2141' => $link_lists_tour,
                            'PROPERTY_2129' => current($get_elem_list_tour_2['result'][0]['PROPERTY_2129'])
                        ]
                    ]
                ); 
                $list_id_del = $item_info['PROPERTY_VALUES']['lists_id'];
                $elem_id_tour_del = $item_info['PROPERTY_VALUES']['list_lists_item_id'];

                
                $get_element_lists_validate = CRest::call(
                    'lists.element.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $list_id_del
                    ]
                );

                if($get_element_lists_validate['total'] != 0) {
                    
                } elseif($get_element_lists_validate['total'] == 0) {
                    $delete_list_1 = CRest::call(
                        'lists.delete', 
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_ID' => $list_id_del
                        ]
                    );
                    $delete_elem_list_tour_1 = CRest::call(
                        'lists.element.delete',
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_ID' => '243',
                            'ELEMENT_ID' => $elem_id_tour_del
                        ]
                    );
                }

                

                $lists_name_new = $product_name.' '.$date_from_origin.'-'.$date_to_origin;

                $get_lists = CRest::call(
                    'lists.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists'
                    ]
                );
                $total_lists = $get_lists['total'];

                $calls = ceil($total_lists / 50); 
                $current_call = 0;
                $result_lists = array();  
                do{
                    $current_call++;
                    if(($current_call % 2) == true){
                        sleep(1);
                        $get_info_lists = CRest::call(
                            'lists.get',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'start' => ($current_call - 1) * 50  
                            ] 
                        );
                        
                        $result_lists = array_merge($result_lists, $get_info_lists);
                    } else {
                        $get_info_lists = CRest::call(
                            'lists.get',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'start' => ($current_call - 1) * 50  
                            ] 
                        );
                        
                        $result_lists = array_merge($result_lists, $get_info_lists);
                    }
                    
                } while($current_call < $calls);
                
                

                $count_name_list_3 = 0;
                $count_name_list_4 = 0;
                
                foreach($result_lists['result'] as $lists_item) {
                    $lists_name_old = $lists_item['NAME'];
                    
                    
                    if((string)$lists_name_new === (string)$lists_name_old){
                        $count_name_list_3++;
                        $id_list_new = $lists_item['ID'];
                        
                    } elseif((string)$lists_name_new != (string)$lists_name_old) {
                        $count_name_list_4++;
                    }
                }
                

                if($count_name_list_3 == 0) {
                    /*$delete_entity_item_old = CRest::call(
                        'entity.item.delete',
                        [
                            'ENTITY' => 'lists_entity',
                            'ID' => $item_info['ID']
                        ]
                    );*/
                    $lists_id_new = rand(1, PHP_INT_MAX);
                    $add_lists_1 = CRest::call(
                        'lists.add',
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_CODE' => $lists_id_new,
                            'FIELDS' => [
                                'NAME' => $lists_name_new
                            ],
                            'MESSAGES' => [
                                'ELEMENT_NAME' => 'Элемент',
                                'ELEMENTS_NAME' => 'Элементы',
                                'ELEMENT_ADD' => 'Добавить элемент',
                                'ELEMENT_EDIT' => 'Редактировать элемент',
                                'ELEMENT_DELETE' => 'Удалить элемент',
                                'SECTION_NAME' => 'Раздел',
                                'SECTIONS_NAME' => 'Разделы',
                                'SECTION_ADD' => 'Добавить раздел',
                                'SECTION_EDIT' => 'Редактировать раздел',
                                'SECTION_DELETE' => 'Удалить раздел'
                            ],
                            'RIGHTS' => [
                                'SG4_A' => 'W',
                                'SG4_E' => 'W',
                                'SG4_K' => 'W',
                                'AU' => 'W',
                                'G2' => 'W'
                            ]
                        ]
                    );
                    $id_list = $add_lists_1['result'];
                        $add_fields_batch = CRest::callBatch([
                            'add_field_1' => [
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Номер элемента',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'N',
                                        'SORT' => '20',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_2' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Телефон',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '30',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_3' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => '	Дата рождения',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '40',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_4' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => '	Город',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '50',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_5' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Сделка',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'SORT' => '60',
                                        'TYPE' => 'S:ECrm',
                                        'DEFAULT_VALUE' => 'null',
                                        'USER_TYPE_SETTINGS' => [
                                        'DEAL' => 'Y',
                                        'VISIBLE' => 'Y'
                                        ],
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_6' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Место прилета/приезда туриста',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '70',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_7' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Пакет',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '80',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_8' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Полная стоимость',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '90',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_9' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Внесена предоплата',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '100',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_10' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Осталось оплатить!',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '110',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_11' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Доп. услуги и их количество',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '120',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ],
                            'add_field_12' =>[
                                'method' => 'lists.field.add',
                                'params' => [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'FIELDS' => [
                                        'NAME' => 'Важная информация',
                                        'IS_REQUIRED' => 'N',
                                        'MULTIPLE' => 'N',
                                        'TYPE' => 'S',
                                        'SORT' => '130',
                                        'DEFAULT_VALUE' => 'null',
                                        'SETTINGS' => [
                                            'SHOW_ADD_FORM' => 'Y',
                                            'SHOW_EDIT_FORM' => 'Y',
                                            'ADD_READ_ONLY_FIELD' => 'Y',
                                            'EDIT_READ_ONLY_FIELD' => 'Y',
                                            'SHOW_FIELD_PREVIEW' => 'Y'
                                        ]
                                    ]
                                ]
                            ]
                        ]);
                        
                        
            
                        $get_fields_lists_1 = CRest::call(
                            'lists.field.get',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $add_lists_1['result']
                            ]
                        );
                        $arr_name_field_2 = array();
                        foreach($get_fields_lists_1['result'] as $field_item_get) {
                            array_push($arr_name_field_2, $field_item_get['FIELD_ID']);
                        }
                            
                            $add_elem_old_lists = CRest::call(
                                'lists.element.add',
                                [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists_1['result'],
                                    'ELEMENT_CODE' => rand(1, PHP_INT_MAX),
                                    'FIELDS' => [
                                        $arr_name_field_2[0] => $item_info['PROPERTY_VALUES']['list_field_1'],
                                        $arr_name_field_2[1] => $item_info['PROPERTY_VALUES']['list_field_2'],
                                        $arr_name_field_2[2] => $item_info['PROPERTY_VALUES']['list_field_3'],
                                        $arr_name_field_2[3] => $item_info['PROPERTY_VALUES']['list_field_4'],
                                        $arr_name_field_2[4] => $city_from[0],
                                        $arr_name_field_2[5] => $iDealID,
                                        $arr_name_field_2[6] => $city_to,
                                        $arr_name_field_2[7] => $package,
                                        $arr_name_field_2[8] => $money_all,
                                        $arr_name_field_2[9] => $money_from,
                                        $arr_name_field_2[10] => $money_to,
                                        $arr_name_field_2[11] => $service,
                                        $arr_name_field_2[12] => $comments
                                    ]
                                ]
                            );
                            
                            $item_entity_update = CRest::call(
                                'entity.item.update',
                                [
                                    'ENTITY' => 'lists_entity',
                                    'SECTION' => $id_section_get_item,
                                    'ID' => $item_info['ID'],
                                    'PROPERTY_VALUES' => [
                                        'lists_id' => $add_lists_1['result'],
                                        'id_deal' => $iDealID,
                                        'id_contact' => $item_info['PROPERTY_VALUES']['id_contact'],
                                        'list_field_1' => $item_info['PROPERTY_VALUES']['list_field_1'],
                                        'list_field_2' => $item_info['PROPERTY_VALUES']['list_field_2'],
                                        'list_field_3' => $item_info['PROPERTY_VALUES']['list_field_3'],
                                        'list_field_4' => $item_info['PROPERTY_VALUES']['list_field_4'],
                                        'list_field_5' => $city_from[0],
                                        'list_field_6' => $iDealID,
                                        'list_field_7' => $city_to,
                                        'list_field_8' => $package,
                                        'list_field_9' => $money_all,
                                        'list_field_10' => $money_from,
                                        'list_field_11' => $money_to,
                                        'list_field_12' => $service,
                                        'list_field_13' => $comments,
                                        'list_item_new' => $add_elem_old_lists['result'],
                                        'list_lists_item_id' => 'null',
                                        'product_id' => $product_id,
                                        'date_from' => $date_from_origin,
                                        'date_to' => $date_to_origin
                                    ]
                                ]
                            );
                            
                        

                        $get_elements_tour = CRest::call(
                            'lists.element.get',
                            [
                                'IBLOCK_ID' => '243',
                                'IBLOCK_TYPE_ID' => 'lists',
                                'FILTER' => [
                                    'NAME' => $product_name
                                ]
                            ]
                        );

                        $total_elem_lists_tour = (int)($get_elements_tour['total']);
                
                        $count_elem_update_1 = 0;
                        $count_elem_add_1 = 0;
                        if($total_elem_lists_tour != 0) {
                            foreach($get_elements_tour['result'] as $elem_res) {
                            
                                $prop_106 = current($elem_res['PROPERTY_2131']);
                                
                                $prop_108 = current($elem_res['PROPERTY_2133']);
                                
                                if($prop_106 == $date_from_origin && $prop_108 == $date_to_origin){
                                    $id_elem_res = $elem_res['ID'];
                                    $count_elem_update_1 = 1;
                                } else {
                                    $count_elem_add_1 = 1;
                                }
                            }
                            
                            if($count_elem_update_1 == 1) {
    
                                $get_element_tour_info = CRest::call(
                                    'lists.element.get',
                                    [
                                        'IBLOCK_ID' => '243',
                                        'IBLOCK_TYPE_ID' => 'lists',
                                        'ELEMENT_ID' => $id_elem_res
                                    ]
                                );
                                foreach($get_element_tour_info['result'] as $res_info) {
                                    $count_pass = current($res_info['PROPERTY_2135']);
                                    $count_busy_pass = current($res_info['PROPERTY_2137']) + 1;
                                    
                                    
                                    $count_free_pass = $count_pass - $count_busy_pass;
                                    $elem_update_id = $res_info['ID'];
                                    $array_current_elem = current($res_info['PROPERTY_2141']);
                                }            
                                
                                $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$add_lists_1['result'].'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';
                                $update_elements_tour = CRest::call(
                                    'lists.element.update',
                                    [
                                        'IBLOCK_TYPE_ID' => 'lists',
                                        'IBLOCK_ID' => '243',
                                        'ELEMENT_ID' => $id_elem_res,
                                        'FIELDS' => [
                                            'NAME' => $product_name,
                                            'PROPERTY_2131' => $date_from_origin,
                                            'PROPERTY_2133' => $date_to_origin,
                                            'PROPERTY_2135' => $count_pass,
                                            'PROPERTY_2139' => $count_free_pass,
                                            'PROPERTY_2137' => $count_busy_pass,
                                            'PROPERTY_2141' => $link_lists_tour,
                                            'PROPERTY_2129' => $region
                                        ]
                                    ]
                                ); 
                                $update_item_entity = CRest::call(
                                    'entity.item.update',
                                    [
                                        'ENTITY' => 'lists_entity',
                                        'SECTION' => $id_section_get_item,
                                        'ID' => $item_info['ID'],
                                        'PROPERTY_VALUES' => [
                                            'list_lists_item_id' => $id_elem_res
                                        ]
                                    ]
                                );
                            } elseif ($count_elem_add_1 == 1 && $count_elem_update_1 == 0) {
                                $count_pass = 20;
                                $count_busy_pass = 1;
                                $count_free_pass = $count_pass - $count_busy_pass;
                                $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$add_lists_1['result'].'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';
                    
                                $elem_id_tour = rand(1, PHP_INT_MAX);
                                $add_elements_tour = CRest::call(
                                    'lists.element.add',
                                    [
                                        'IBLOCK_TYPE_ID' => 'lists',
                                        'IBLOCK_ID' => '243',
                                        'ELEMENT_CODE' => rand(1, PHP_INT_MAX),
                                        'FIELDS' => [
                                            'NAME' => $product_name,
                                            'PROPERTY_2131' => $date_from_origin,
                                            'PROPERTY_2133' => $date_to_origin,
                                            'PROPERTY_2135' => $count_pass,
                                            'PROPERTY_2139' => $count_free_pass,
                                            'PROPERTY_2137' => $count_busy_pass,
                                            'PROPERTY_2141' => $link_lists_tour,
                                            'PROPERTY_2129' => $region
                                        ]
                                    ]
                                );
                    
                                
                                    $update_item_entity = CRest::call(
                                        'entity.item.update',
                                        [
                                            'ENTITY' => 'lists_entity',
                                            'SECTION' => $id_section_get_item,
                                            'ID' => $item_info['ID'],
                                            'PROPERTY_VALUES' => [
                                                'list_lists_item_id' => $add_elements_tour['result']
                                            ]
                                        ]
                                    );
                                
                                
                            }
                        } else {
                            $count_pass = 20;
                            $count_busy_pass = 0 + $product_quantity_deal_field;
                            $count_free_pass = $count_pass - $count_busy_pass;
                            $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$add_lists_1['result'].'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';

                            $elem_id_tour = rand(1, PHP_INT_MAX);
                            $add_elements_tour = CRest::call(
                                'lists.element.add',
                                [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => '243',
                                    'ELEMENT_CODE' => rand(1, PHP_INT_MAX),
                                    'FIELDS' => [
                                        'NAME' => $product_name,
                                        'PROPERTY_2131' => $date_from_origin,
                                        'PROPERTY_2133' => $date_to_origin,
                                        'PROPERTY_2135' => $count_pass,
                                        'PROPERTY_2139' => $count_free_pass,
                                        'PROPERTY_2137' => $count_busy_pass,
                                        'PROPERTY_2141' => $link_lists_tour,
                                        'PROPERTY_2129' => $region
                                    ]
                                ]
                            );

                            $update_item_entity = CRest::call(
                                'entity.item.update',
                                [
                                    'ENTITY' => 'lists_entity',
                                    'SECTION' => $id_section_get_item,
                                    'ID' => $item_info['ID'],
                                    'PROPERTY_VALUES' => [
                                        'list_lists_item_id' => $add_elements_tour['result']
                                    ]
                                ]
                            );
                        }
                        
                        
                
                            $get_info_field_deal = CRest::call(
                                'crm.deal.get',
                                [
                                    'ID' => $iDealID
                                ]
                            );
                    
                            $field_value_deal = $get_info_field_deal['result']['UF_CRM_1631876593355'];
                    
                            if($field_value_deal == $package) {
                    
                            } else {
                                $update_deal = CRest::call(
                                    'crm.deal.update',
                                    [
                                        'ID' => $iDealID,
                                        'fields' => [
                                            'UF_CRM_1631876593355' => $package
                                        ]
                                    ]
                                );
                            }
                    
                } elseif ($count_name_list_3 > 0) {
                    $get_fields_lists = CRest::call(
                        'lists.field.get',
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_ID' => $id_list_new
                        ]
                    );
                    $arr_name_field = array();
                    foreach($get_fields_lists['result'] as $field_item_get) {
                        array_push($arr_name_field, $field_item_get['FIELD_ID']);
                    }
                    
                    
                    $get_total_elem_list = CRest::call(
                        'lists.element.get',
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_ID' => $id_list_new
                        ]
                    );

                    $count_elem = (int)($get_total_elem_list['total']) + 1;
                        $add_elem_old_lists = CRest::call(
                            'lists.element.add',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $id_list_new,
                                'ELEMENT_CODE' => rand(1, PHP_INT_MAX),
                                'FIELDS' => [
                                    $arr_name_field[0] => $item_info['PROPERTY_VALUES']['list_field_1'],
                                    $arr_name_field[1] => $count_elem,
                                    $arr_name_field[2] => $item_info['PROPERTY_VALUES']['list_field_3'],
                                    $arr_name_field[3] => $item_info['PROPERTY_VALUES']['list_field_4'],
                                    $arr_name_field[4] => $item_info['PROPERTY_VALUES']['list_field_5'],
                                    $arr_name_field[5] => $iDealID,
                                    $arr_name_field[6] => $city_to,
                                    $arr_name_field[7] => $package,
                                    $arr_name_field[8] => $money_all,
                                    $arr_name_field[9] => $money_from,
                                    $arr_name_field[10] => $money_to,
                                    $arr_name_field[11] => $service,
                                    $arr_name_field[12] => $comments
                                ]
                            ]
                        );
                        $item_entity_update = CRest::call(
                            'entity.item.update',
                            [
                                'ENTITY' => 'lists_entity',
                                'SECTION' => $id_section_get_item,
                                'ID' => $item_info['ID'],
                                'PROPERTY_VALUES' => [
                                    'lists_id' => $id_list_new,
                                    'id_deal' => $iDealID,
                                    'id_contact' => $item_info['PROPERTY_VALUES']['id_contact'],
                                    'list_field_1' => $item_info['PROPERTY_VALUES']['list_field_1'],
                                    'list_field_2' => $item_info['PROPERTY_VALUES']['list_field_2'],
                                    'list_field_3' => $item_info['PROPERTY_VALUES']['list_field_3'],
                                    'list_field_4' => $item_info['PROPERTY_VALUES']['list_field_4'],
                                    'list_field_5' => $city_from[0],
                                    'list_field_6' => $iDealID,
                                    'list_field_7' => $city_to,
                                    'list_field_8' => $package,
                                    'list_field_9' => $money_all,
                                    'list_field_10' => $money_from,
                                    'list_field_11' => $money_to,
                                    'list_field_12' => $service,
                                    'list_field_13' => $comments,
                                    'list_item_new' => $add_elem_old_lists['result'],
                                    'product_id' => $product_id,
                                    'date_from' => $date_from_origin,
                                    'date_to' => $date_to_origin
                                ]
                            ]
                        );  
                }

            } else {}

            //Проверка на изменение важной информации
            if($item_info['PROPERTY_VALUES']['list_field_13'] != $comments) {
                $add_comment_deal_info_0 = CRest::call(
                    'crm.timeline.comment.add',
                    [
                        'fields' => [
                            'ENTITY_ID' => $iDealID,
                            'ENTITY_TYPE' => 'deal',
                            'COMMENT' => 'Важная информация изменена'
                        ]
                    ]
                );
                $update_entity_item = CRest::call(
                    'entity.item.update',
                    [
                        'ENTITY' => 'lists_entity',
                        'SECTION' => $id_section_get,
                        'ID' => $item_info['ID'],
                        'PROPERTY_VALUES' => [
                            'list_field_13' => $comments
                        ]
                    ]
                );
                $get_fields_lists_1 = CRest::call(
                    'lists.field.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id']
                    ]
                );
                $arr_name_field_1 = array();
                foreach($get_fields_lists_1['result'] as $field_item_get) {
                    array_push($arr_name_field_1, $field_item_get['FIELD_ID']);
                }
    
                $get_list_elem = CRest::call(
                    'lists.element.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_item_new']
                    ]
                );
    
                
                $update_list_elem = CRest::call(
                    'lists.element.update',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_item_new'],
                        'FIELDS' => [
                            $arr_name_field_1[0] => $get_list_elem['result'][0][$arr_name_field_1[0]],
                            $arr_name_field_1[1] => current($get_list_elem['result'][0][$arr_name_field_1[1]]),
                            $arr_name_field_1[2] => current($get_list_elem['result'][0][$arr_name_field_1[2]]),
                            $arr_name_field_1[3] => current($get_list_elem['result'][0][$arr_name_field_1[3]]),
                            $arr_name_field_1[4] => current($get_list_elem['result'][0][$arr_name_field_1[4]]),
                            $arr_name_field_1[5] => current($get_list_elem['result'][0][$arr_name_field_1[5]]),
                            $arr_name_field_1[6] => current($get_list_elem['result'][0][$arr_name_field_1[6]]),
                            $arr_name_field_1[7] => current($get_list_elem['result'][0][$arr_name_field_1[7]]),
                            $arr_name_field_1[8] => current($get_list_elem['result'][0][$arr_name_field_1[8]]),
                            $arr_name_field_1[9] => current($get_list_elem['result'][0][$arr_name_field_1[9]]),
                            $arr_name_field_1[10] => current($get_list_elem['result'][0][$arr_name_field_1[10]]),
                            $arr_name_field_1[11] => current($get_list_elem['result'][0][$arr_name_field_1[11]]),
                            $arr_name_field_1[12] => $comments
                        ]
                    ]
                );
            } else {}
            //Проверка на изменение доп услуг
            if($item_info['PROPERTY_VALUES']['list_field_12'] != $service) {
                $add_comment_deal_info_1 = CRest::call(
                    'crm.timeline.comment.add',
                    [
                        'fields' => [
                            'ENTITY_ID' => $iDealID,
                            'ENTITY_TYPE' => 'deal',
                            'COMMENT' => 'Доп услуги были изменены'
                        ]
                    ]
                );
                $update_entity_item = CRest::call(
                    'entity.item.update',
                    [
                        'ENTITY' => 'lists_entity',
                        'SECTION' => $id_section_get,
                        'ID' => $item_info['ID'],
                        'PROPERTY_VALUES' => [
                            'list_field_12' => $service
                        ]
                    ]
                );
                $get_fields_lists_1 = CRest::call(
                    'lists.field.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id']
                    ]
                );
                $arr_name_field_1 = array();
                foreach($get_fields_lists_1['result'] as $field_item_get) {
                    array_push($arr_name_field_1, $field_item_get['FIELD_ID']);
                }
    
                $get_list_elem = CRest::call(
                    'lists.element.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_item_new']
                    ]
                );
    
                
                $update_list_elem = CRest::call(
                    'lists.element.update',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_item_new'],
                        'FIELDS' => [
                            $arr_name_field_1[0] => $get_list_elem['result'][0][$arr_name_field_1[0]],
                            $arr_name_field_1[1] => current($get_list_elem['result'][0][$arr_name_field_1[1]]),
                            $arr_name_field_1[2] => current($get_list_elem['result'][0][$arr_name_field_1[2]]),
                            $arr_name_field_1[3] => current($get_list_elem['result'][0][$arr_name_field_1[3]]),
                            $arr_name_field_1[4] => current($get_list_elem['result'][0][$arr_name_field_1[4]]),
                            $arr_name_field_1[5] => current($get_list_elem['result'][0][$arr_name_field_1[5]]),
                            $arr_name_field_1[6] => current($get_list_elem['result'][0][$arr_name_field_1[6]]),
                            $arr_name_field_1[7] => current($get_list_elem['result'][0][$arr_name_field_1[7]]),
                            $arr_name_field_1[8] => current($get_list_elem['result'][0][$arr_name_field_1[8]]),
                            $arr_name_field_1[9] => current($get_list_elem['result'][0][$arr_name_field_1[9]]),
                            $arr_name_field_1[10] => current($get_list_elem['result'][0][$arr_name_field_1[10]]),
                            $arr_name_field_1[11] => $service,
                            $arr_name_field_1[12] => current($get_list_elem['result'][0][$arr_name_field_1[12]])
                        ]
                    ]
                );

            } else {}

            //Проверка на изменение суммы предоплаты
            if($item_info['PROPERTY_VALUES']['list_field_10'] != $money_from) {
                $add_comment_deal_info_1 = CRest::call(
                    'crm.timeline.comment.add',
                    [
                        'fields' => [
                            'ENTITY_ID' => $iDealID,
                            'ENTITY_TYPE' => 'deal',
                            'COMMENT' => 'сумма предоплаты была изменена'
                        ]
                    ]
                );
                $update_entity_item = CRest::call(
                    'entity.item.update',
                    [
                        'ENTITY' => 'lists_entity',
                        'SECTION' => $id_section_get,
                        'ID' => $item_info['ID'],
                        'PROPERTY_VALUES' => [
                            'list_field_10' => $money_from,
                            'list_field_11' => $money_to
                        ]
                    ]
                );
                $get_fields_lists_1 = CRest::call(
                    'lists.field.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id']
                    ]
                );
                $arr_name_field_1 = array();
                foreach($get_fields_lists_1['result'] as $field_item_get) {
                    array_push($arr_name_field_1, $field_item_get['FIELD_ID']);
                }
    
                $get_list_elem = CRest::call(
                    'lists.element.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_item_new']
                    ]
                );
    
                
                $update_list_elem = CRest::call(
                    'lists.element.update',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_item_new'],
                        'FIELDS' => [
                            $arr_name_field_1[0] => $get_list_elem['result'][0][$arr_name_field_1[0]],
                            $arr_name_field_1[1] => current($get_list_elem['result'][0][$arr_name_field_1[1]]),
                            $arr_name_field_1[2] => current($get_list_elem['result'][0][$arr_name_field_1[2]]),
                            $arr_name_field_1[3] => current($get_list_elem['result'][0][$arr_name_field_1[3]]),
                            $arr_name_field_1[4] => current($get_list_elem['result'][0][$arr_name_field_1[4]]),
                            $arr_name_field_1[5] => current($get_list_elem['result'][0][$arr_name_field_1[5]]),
                            $arr_name_field_1[6] => current($get_list_elem['result'][0][$arr_name_field_1[6]]),
                            $arr_name_field_1[7] => current($get_list_elem['result'][0][$arr_name_field_1[7]]),
                            $arr_name_field_1[8] => current($get_list_elem['result'][0][$arr_name_field_1[8]]),
                            $arr_name_field_1[9] => $money_from,
                            $arr_name_field_1[10] => $money_to,
                            $arr_name_field_1[11] => current($get_list_elem['result'][0][$arr_name_field_1[11]]),
                            $arr_name_field_1[12] => current($get_list_elem['result'][0][$arr_name_field_1[12]])
                        ]
                    ]
                );
            } else {}
            //Проверка на изменение суммы заказа
            /*if($item_info['PROPERTY_VALUES']['list_field_9'] != $money_all) {
                $add_comment_deal_info_1 = CRest::call(
                    'crm.timeline.comment.add',
                    [
                        'fields' => [
                            'ENTITY_ID' => $iDealID,
                            'ENTITY_TYPE' => 'deal',
                            'COMMENT' => 'Сумма товаров была изменена'
                        ]
                    ]
                );
                $update_entity_item = CRest::call(
                    'entity.item.update',
                    [
                        'ENTITY' => 'lists_entity',
                        'SECTION' => $id_section_get,
                        'ID' => $item_info['ID'],
                        'PROPERTY_VALUES' => [
                            'list_field_9' => $money_all,
                            'list_field_10' => $money_from,
                            'list_field_11' => $money_to
                        ]
                    ]
                );
                $get_fields_lists_1 = CRest::call(
                    'lists.field.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id']
                    ]
                );
                $arr_name_field_1 = array();
                foreach($get_fields_lists_1['result'] as $field_item_get) {
                    array_push($arr_name_field_1, $field_item_get['FIELD_ID']);
                }
    
                $get_list_elem = CRest::call(
                    'lists.element.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_item_new']
                    ]
                );
    
                
                $update_list_elem = CRest::call(
                    'lists.element.update',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                        'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_item_new'],
                        'FIELDS' => [
                            $arr_name_field_1[0] => $get_list_elem['result'][0][$arr_name_field_1[0]],
                            $arr_name_field_1[1] => current($get_list_elem['result'][0][$arr_name_field_1[1]]),
                            $arr_name_field_1[2] => current($get_list_elem['result'][0][$arr_name_field_1[2]]),
                            $arr_name_field_1[3] => current($get_list_elem['result'][0][$arr_name_field_1[3]]),
                            $arr_name_field_1[4] => current($get_list_elem['result'][0][$arr_name_field_1[4]]),
                            $arr_name_field_1[5] => current($get_list_elem['result'][0][$arr_name_field_1[5]]),
                            $arr_name_field_1[6] => current($get_list_elem['result'][0][$arr_name_field_1[6]]),
                            $arr_name_field_1[7] => current($get_list_elem['result'][0][$arr_name_field_1[7]]),
                            $arr_name_field_1[8] => $money_all,
                            $arr_name_field_1[9] => $money_from,
                            $arr_name_field_1[10] => $money_to,
                            $arr_name_field_1[11] => current($get_list_elem['result'][0][$arr_name_field_1[11]]),
                            $arr_name_field_1[12] => current($get_list_elem['result'][0][$arr_name_field_1[12]])
                        ]
                    ]
                );
            } else {}*/
            //Проверка на изменение кол-ва контактов в сделке
            if(count($contact_id) < $get_total_item_elem) {
                $add_comment_deal_contact_1 = CRest::call(
                    'crm.timeline.comment.add',
                    [
                        'fields' => [
                            'ENTITY_ID' => $iDealID,
                            'ENTITY_TYPE' => 'deal',
                            'COMMENT' => 'Кол-во контактов уменьшилось'
                        ]
                    ]
                );
                //Получить контакты через цикл и сравнить их с текущими
                //если контакт есть в элементе, то его оставить, а остальные удалить элементы из списка и хранилища
                
                if(count($contact_id) > 1) {
                    foreach($contact_id as $contact_get) {

                        $get_contact_info_1 = CRest::call(
                            'crm.contact.get',
                            [
                                'id' => $contact_get['CONTACT_ID']
                            ]
                        );
                        $name_contact_1 = $get_contact_info_1['result']['LAST_NAME'].' '.$get_contact_info_1['result']['NAME'].' '.$get_contact_info_1['result']['SECOND_NAME'];
                        $birthday_1 = explode('T', $get_contact_info_1['result']['BIRTHDATE']);
                        $name_contact_2 = $item_info['PROPERTY_VALUES']['list_field_1'];
                        
                        if($name_contact_1 === $name_contact_2) {
                            
                            
                        } else {
                            
                            $delete_elem_list_1 = CRest::call(
                                'lists.element.delete',
                                [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                                    'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_item_new']
                                ]
                            );
    
    
                            $get_elem_list_tour_1 = CRest::call(
                                'lists.element.get',
                                [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => '243',
                                    'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_lists_item_id']
                                ]
                            );
    
                            $busy_count_update_1 = (int)(current($get_elem_list_tour_1['result'][0]['PROPERTY_2137'])) - 1;
                            $free_count_update_1 = (int)(current($get_elem_list_tour_1['result'][0]['PROPERTY_2141'])) + 1;
                            $id_list_1 = $item_info['PROPERTY_VALUES']['lists_id'];
                            $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$id_list_1.'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';
                            $update_elements_tour_1 = CRest::call(
                                'lists.element.update',
                                [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => '243',
                                    'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_lists_item_id'],
                                    'FIELDS' => [
                                        'NAME' => $get_elem_list_tour_1['result'][0]['NAME'],
                                        'PROPERTY_2131' => current($get_elem_list_tour_1['result'][0]['PROPERTY_2131']),
                                        'PROPERTY_2133' => current($get_elem_list_tour_1['result'][0]['PROPERTY_2133']),
                                        'PROPERTY_2135' => current($get_elem_list_tour_1['result'][0]['PROPERTY_2135']),
                                        'PROPERTY_2139' => $free_count_update_1,
                                        'PROPERTY_2137' => $busy_count_update_1,
                                        'PROPERTY_2141' => $link_lists_tour,
                                        'PROPERTY_2129' => current($get_elem_list_tour_1['result'][0]['PROPERTY_2129'])
                                    ]
                                ]
                            ); 
    
                            $entity_item_del = CRest::call(
                                'entity.item.delete',
                                [
                                    'ENTITY' => 'lists_entity',
                                    'ID' => $item_info['ID']
                                ]
                            );
                        }
                    }
                } else {
                    $delete_elem_list_1 = CRest::call(
                        'lists.element.delete',
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                            'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_item_new']
                        ]
                    );


                    $get_elem_list_tour_1 = CRest::call(
                        'lists.element.get',
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_ID' => '243',
                            'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_lists_item_id']
                        ]
                    );

                    $get_count_lists = CRest::call(
                        'lists.element.get',
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id']
                        ]
                    );

                    $count_elem_lists = $get_count_lists['total'];

                    if($count_elem_lists > 0) {

                    } elseif($count_elem_lists == 0) {
                        $delete_lists_tour_1 = CRest::call(
                            'lists.delete',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id']
                            ]
                        );
                    }

                    $busy_count_update_1 = (int)(current($get_elem_list_tour_1['result'][0]['PROPERTY_2137'])) - 1;
                    $free_count_update_1 = (int)(current($get_elem_list_tour_1['result'][0]['PROPERTY_2141'])) + 1;

                    if ($busy_count_update_1 == 0) {
                        $delete_elem_list = CRest::call(
                            'lists.element.delete',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => '243',
                                'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_lists_item_id']
                            ]
                        );

                        
                    } else {
                        $id_list_1 = $item_info['PROPERTY_VALUES']['lists_id'];
                        $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$id_list_1.'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';
                        $update_elements_tour_1 = CRest::call(
                            'lists.element.update',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => '243',
                                'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_lists_item_id'],
                                'FIELDS' => [
                                    'NAME' => $get_elem_list_tour_1['result'][0]['NAME'],
                                    'PROPERTY_2131' => current($get_elem_list_tour_1['result'][0]['PROPERTY_2131']),
                                    'PROPERTY_2133' => current($get_elem_list_tour_1['result'][0]['PROPERTY_2133']),
                                    'PROPERTY_2135' => current($get_elem_list_tour_1['result'][0]['PROPERTY_2135']),
                                    'PROPERTY_2139' => $free_count_update_1,
                                    'PROPERTY_2137' => $busy_count_update_1,
                                    'PROPERTY_2141' => $link_lists_tour,
                                    'PROPERTY_2129' => current($get_elem_list_tour_1['result'][0]['PROPERTY_2129'])
                                ]
                            ]
                        ); 
                    }
                    

                    $entity_item_del = CRest::call(
                        'entity.item.delete',
                        [
                            'ENTITY' => 'lists_entity',
                            'ID' => $item_info['ID']
                        ]
                    );
                }

                

                
            } elseif(count($contact_id) > $get_total_item_elem) {
                $add_comment_deal_contact_2 = CRest::call(
                    'crm.timeline.comment.add',
                    [
                        'fields' => [
                            'ENTITY_ID' => $iDealID,
                            'ENTITY_TYPE' => 'deal',
                            'COMMENT' => 'Кол-во контактов увеличилось'
                        ]
                    ]
                );
                foreach($contact_id as $contact_get) {

                    $get_contact_info_1 = CRest::call(
                        'crm.contact.get',
                        [
                            'id' => $contact_get['CONTACT_ID']
                        ]
                    );
                    $name_contact_1 = $get_contact_info_1['result']['LAST_NAME'].' '.$get_contact_info_1['result']['NAME'].' '.$get_contact_info_1['result']['SECOND_NAME'];
                    $birthday_1 = explode('T', $get_contact_info_1['result']['BIRTHDATE']);
                    $name_contact_2 = $item_info['PROPERTY_VALUES']['list_field_1'];
                    
                    if($name_contact_1 === $name_contact_2) {
                        
                        
                    } else {
                        
                        
                        $get_fields_lists_2 = CRest::call(
                            'lists.field.get',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id']
                            ]
                        );

                        $arr_name_field_2 = array();
                        foreach($get_fields_lists_2['result'] as $field_item_get) {
                            array_push($arr_name_field_2, $field_item_get['FIELD_ID']);
                        }

                        $get_count_elem_list = CRest::call(
                            'lists.element.get',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id']
                            ]
                        );

                        $total_elem_list = (int)($get_count_elem_list['total']) + 1;
                        $add_elem_old_lists = CRest::call(
                                'lists.element.add',
                                [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $item_info['PROPERTY_VALUES']['lists_id'],
                                    'ELEMENT_CODE' => rand(1, PHP_INT_MAX),
                                    'FIELDS' => [
                                        $arr_name_field_2[0] => $name_contact_1,
                                        $arr_name_field_2[1] => $total_elem_list,
                                        $arr_name_field_2[2] => $get_contact_info_1['result']['PHONE'][0]['VALUE'],
                                        $arr_name_field_2[3] => $birthday_1[0],
                                        $arr_name_field_2[4] => $city_from[0],
                                        $arr_name_field_2[5] => $iDealID,
                                        $arr_name_field_2[6] => $city_to,
                                        $arr_name_field_2[7] => $package,
                                        $arr_name_field_2[8] => $money_all,
                                        $arr_name_field_2[9] => $money_from,
                                        $arr_name_field_2[10] => $money_to,
                                        $arr_name_field_2[11] => $service,
                                        $arr_name_field_2[12] => $comments
                                    ]
                                ]
                            );
                            
                            $item_entity_add = CRest::call(
                                'entity.item.add',
                                [
                                    'ENTITY' => 'lists_entity',
                                    'SECTION' => $id_section_get,
                                    'NAME' => $name_contact_1,
                                    'PROPERTY_VALUES' => [
                                        'lists_id' => $item_info['PROPERTY_VALUES']['lists_id'],
                                        'id_deal' => $iDealID,
                                        'id_contact' => $get_contact_info_1['result']['ID'],
                                        'list_field_1' => $name_contact_1,
                                        'list_field_2' => $total_elem_list,
                                        'list_field_3' => $get_contact_info_1['result']['PHONE'][0]['VALUE'],
                                        'list_field_4' => $birthday_1[0],
                                        'list_field_5' => $city_from[0],
                                        'list_field_6' => $iDealID,
                                        'list_field_7' => $city_to,
                                        'list_field_8' => $package,
                                        'list_field_9' => $money_all,
                                        'list_field_10' => $money_from,
                                        'list_field_11' => $money_to,
                                        'list_field_12' => $service,
                                        'list_field_13' => $comments,
                                        'list_item_new' => $add_elem_old_lists['result'],
                                        'list_lists_item_id' => $item_info['PROPERTY_VALUES']['list_lists_item_id'],
                                        'product_id' => $product_id,
                                        'date_from' => $date_from_origin,
                                        'date_to' => $date_to_origin
                                    ]
                                ]
                            );
                       
                       $get_elem_list_tour_1 = CRest::call(
                            'lists.element.get',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => '243',
                                'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_lists_item_id']
                            ]
                        );

                        $busy_count_update_1 = (int)(current($get_elem_list_tour_1['result'][0]['PROPERTY_2137'])) + 1;
                        $free_count_update_1 = (int)(current($get_elem_list_tour_1['result'][0]['PROPERTY_2141'])) - 1;
                        $id_list_1 = $item_info['PROPERTY_VALUES']['lists_id'];
                        $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$id_list_1.'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';
                        $update_elements_tour_1 = CRest::call(
                            'lists.element.update',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => '243',
                                'ELEMENT_ID' => $item_info['PROPERTY_VALUES']['list_lists_item_id'],
                                'FIELDS' => [
                                    'NAME' => $get_elem_list_tour_1['result'][0]['NAME'],
                                    'PROPERTY_2131' => current($get_elem_list_tour_1['result'][0]['PROPERTY_2131']),
                                    'PROPERTY_2133' => current($get_elem_list_tour_1['result'][0]['PROPERTY_2133']),
                                    'PROPERTY_2135' => current($get_elem_list_tour_1['result'][0]['PROPERTY_2135']),
                                    'PROPERTY_2139' => $free_count_update_1,
                                    'PROPERTY_2137' => $busy_count_update_1,
                                    'PROPERTY_2141' => $link_lists_tour,
                                    'PROPERTY_2129' => current($get_elem_list_tour_1['result'][0]['PROPERTY_2129'])
                                ]
                            ]
                        );

                    }
                }

            } else {
                
            }
        }
    } else {
        $lists_name_new = $product_name.' '.$date_from_origin.'-'.$date_to_origin;
        $get_lists = CRest::call(
            'lists.get',
            [
                'IBLOCK_TYPE_ID' => 'lists'
            ]
        );
        $total_lists = $get_lists['total'];

        

        $calls = ceil($total_lists / 50); 
        $current_call = 0;
        $result_lists = array();  
        do{
            $current_call++;
            if(($current_call % 2) == true){
                sleep(1);
                $get_info_lists = CRest::call(
                    'lists.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'start' => ($current_call - 1) * 50  
                    ] 
                );
                
                $result_lists = array_merge($result_lists, $get_info_lists);
            } else {
                $get_info_lists = CRest::call(
                    'lists.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'start' => ($current_call - 1) * 50  
                    ] 
                );
                
                $result_lists = array_merge($result_lists, $get_info_lists);
            }
            
        } while($current_call < $calls);
        

        $count_name_list_1 = 0;
        $count_name_list_2 = 0;
        
        foreach($result_lists['result'] as $lists_item) {
            $lists_name_old = $lists_item['NAME'];
            
            if($lists_name_new == $lists_name_old){
                $count_name_list_1 = 1;
                $id_list = $lists_item['ID'];
            } else if($lists_name_new != $lists_name_old){
                $count_name_list_2 = 1;
            }
        }


        if($count_name_list_1 == 1){
        
            $get_fields_lists = CRest::call(
                'lists.field.get',
                [
                    'IBLOCK_TYPE_ID' => 'lists',
                    'IBLOCK_ID' => $id_list
                ]
            );
            $arr_name_field = array();
            foreach($get_fields_lists['result'] as $field_item_get) {
                array_push($arr_name_field, $field_item_get['FIELD_ID']);
            }
            
            $get_field_count = CRest::call(
                'lists.element.get',
                [
                    'IBLOCK_TYPE_ID' => 'lists',
                    'IBLOCK_ID' => $id_list
                ]
            );
            
            
            
        

            $get_section = CRest::call(
                'entity.section.get',
                [
                    'ENTITY' => 'lists_entity',
                    'FILTER' => [
                        '=NAME' => $iDealID
                    ]
                    
                ]
            );
            $section_id = $get_section['result'][0]['ID'];

            if($section_id != 'null') {
                
            } else {
                $add_section = CRest::call(
                    'entity.section.add',
                    [
                        'ENTITY' => 'lists_entity',
                        'NAME' => $iDealID
                    ]
                );
                $section_id = $add_section['result'];
            }

            $j = 0;
            $array_entity_id_new = array();
            foreach($contact_id as $id) {
                $name_found_1 = '0';
                $name_found_0 = '0';
                
                $get_contact_info = CRest::call(
                    'crm.contact.get',
                    [
                        'id' => $id['CONTACT_ID']
                    ]
                );
                $name_contact = $get_contact_info['result']['LAST_NAME'].' '.$get_contact_info['result']['NAME'].' '.$get_contact_info['result']['SECOND_NAME'];
                $birthday = explode('T', $get_contact_info['result']['BIRTHDATE']);

                $entity_item_get_to_update = CRest::call(
                    'entity.item.get',
                    [
                        'ENTITY' => 'lists_entity',
                        'filter' => [
                            '=SECTION' => $section_id,
                            '=NAME' => $name_contact
                        ]
                    ]
                );

                $item_get_id_to_update = $entity_item_get_to_update['result'][0]['ID'];
                $get_field_name = CRest::call(
                    'lists.element.get',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => $id_list
                    ]
                );

                foreach($get_field_name['result'] as $elem_name) {
                    if($elem_name[$arr_name_field[0]] != $name_contact && current($elem_name[$arr_name_field[3]]) != $birthday[0]) {
                        $name_found_1 = 'Не найдено совпадений';
                        $count_elem = (int)(current($elem_name[$arr_name_field[1]]));
                    } elseif ($elem_name[$arr_name_field[0]] == $name_contact && current($elem_name[$arr_name_field[3]]) == $birthday[0]) {
                        $name_found_0 = 'Совпадение найдено';
                        $count_elem = (int)(current($elem_name[$arr_name_field[1]]));
                        $update_elem_lists = CRest::call(
                            'lists.element.update',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $id_list,
                                'ELEMENT_ID' => $elem_name['ID'],
                                'FIELDS' => [
                                    $arr_name_field[0] => $name_contact,
                                    $arr_name_field[1] => $count_elem,
                                    $arr_name_field[2] => $get_contact_info['result']['PHONE'][0]['VALUE'],
                                    $arr_name_field[3] => $birthday[0],
                                    $arr_name_field[4] => $city_from[0],
                                    $arr_name_field[5] => $iDealID,
                                    $arr_name_field[6] => $city_to,
                                    $arr_name_field[7] => $package,
                                    $arr_name_field[8] => $money_all,
                                    $arr_name_field[9] => $money_from,
                                    $arr_name_field[10] => $money_to,
                                    $arr_name_field[11] => $service,
                                    $arr_name_field[12] => $comments
                                ]
                            ]
                        );

                        $item_entity_add = CRest::call(
                            'entity.item.update',
                            [
                                'ENTITY' => 'lists_entity',
                                'ID' => $item_get_id_to_update,
                                'PROPERTY_VALUES' => [
                                    'lists_id' => $id_list,
                                    'id_deal' => $iDealID,
                                    'id_contact' => $get_contact_info['result']['ID'],
                                    'list_field_1' => $name_contact,
                                    'list_field_2' => $count_elem,
                                    'list_field_3' => $get_contact_info['result']['PHONE'][0]['VALUE'],
                                    'list_field_4' => $birthday[0],
                                    'list_field_5' => $city_from[0],
                                    'list_field_6' => $iDealID,
                                    'list_field_7' => $city_to,
                                    'list_field_8' => $package,
                                    'list_field_9' => $money_all,
                                    'list_field_10' => $money_from,
                                    'list_field_11' => $money_to,
                                    'list_field_12' => $service,
                                    'list_field_13' => $comments,
                                    'list_item_new' => $elem_name['ID'],
                                    'product_id' => $product_id,
                                    'date_from' => $date_from_origin,
                                    'date_to' => $date_to_origin
                                ]
                            ]
                        );
                    }
                }

                if ($name_found_1 == 'Не найдено совпадений' && $name_found_0 != 'Совпадение найдено') {
                    $count_elem++;
                    $j++;
                    $add_elem_old_lists = CRest::call(
                        'lists.element.add',
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_ID' => $id_list,
                            'ELEMENT_CODE' => rand(1, PHP_INT_MAX),
                            'FIELDS' => [
                                $arr_name_field[0] => $name_contact,
                                $arr_name_field[1] => $count_elem,
                                $arr_name_field[2] => $get_contact_info['result']['PHONE'][0]['VALUE'],
                                $arr_name_field[3] => $birthday[0],
                                $arr_name_field[4] => $city_from[0],
                                $arr_name_field[5] => $iDealID,
                                $arr_name_field[6] => $city_to,
                                $arr_name_field[7] => $package,
                                $arr_name_field[8] => $money_all,
                                $arr_name_field[9] => $money_from,
                                $arr_name_field[10] => $money_to,
                                $arr_name_field[11] => $service,
                                $arr_name_field[12] => $comments
                            ]
                        ]
                    );

                    $item_entity_add = CRest::call(
                        'entity.item.add',
                        [
                            'ENTITY' => 'lists_entity',
                            'SECTION' => $section_id,
                            'NAME' => $name_contact,
                            'PROPERTY_VALUES' => [
                                'lists_id' => $id_list,
                                'id_deal' => $iDealID,
                                'id_contact' => $get_contact_info['result']['ID'],
                                'list_field_1' => $name_contact,
                                'list_field_2' => $count_elem,
                                'list_field_3' => $get_contact_info['result']['PHONE'][0]['VALUE'],
                                'list_field_4' => $birthday[0],
                                'list_field_5' => $city_from[0],
                                'list_field_6' => $iDealID,
                                'list_field_7' => $city_to,
                                'list_field_8' => $package,
                                'list_field_9' => $money_all,
                                'list_field_10' => $money_from,
                                'list_field_11' => $money_to,
                                'list_field_12' => $service,
                                'list_field_13' => $comments,
                                'list_item_new' => $add_elem_old_lists['result'],
                                'list_lists_item_id' => 'null',
                                'product_id' => $product_id,
                                'date_from' => $date_from_origin,
                                'date_to' => $date_to_origin
                            ]
                        ]
                    );
                    
                    array_push($array_entity_id_new, $item_entity_add['result']);
                }
                
            }
            
            
        } elseif($count_name_list_1 == 0 && $count_name_list_2 == 1) {
                $lists_id_new = rand(1, PHP_INT_MAX);
                $add_lists = CRest::call(
                    'lists.add',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_CODE' => $lists_id_new,
                        'FIELDS' => [
                            'NAME' => $lists_name_new
                        ],
                        'MESSAGES' => [
                            'ELEMENT_NAME' => 'Элемент',
                            'ELEMENTS_NAME' => 'Элементы',
                            'ELEMENT_ADD' => 'Добавить элемент',
                            'ELEMENT_EDIT' => 'Редактировать элемент',
                            'ELEMENT_DELETE' => 'Удалить элемент',
                            'SECTION_NAME' => 'Раздел',
                            'SECTIONS_NAME' => 'Разделы',
                            'SECTION_ADD' => 'Добавить раздел',
                            'SECTION_EDIT' => 'Редактировать раздел',
                            'SECTION_DELETE' => 'Удалить раздел'
                        ],
                        'RIGHTS' => [
                            'SG4_A' => 'W',
                            'SG4_E' => 'W',
                            'SG4_K' => 'W',
                            'AU' => 'W',
                            'G2' => 'W'
                        ]
                    ]
                );
                $id_list = $add_lists['result'];
                    $add_fields_batch = CRest::callBatch([
                        'add_field_1' => [
                            'method' => 'lists.field.add',
                            'params' => [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $add_lists['result'],
                                'FIELDS' => [
                                    'NAME' => 'Номер элемента',
                                    'IS_REQUIRED' => 'N',
                                    'MULTIPLE' => 'N',
                                    'TYPE' => 'N',
                                    'SORT' => '20',
                                    'DEFAULT_VALUE' => 'null',
                                    'SETTINGS' => [
                                        'SHOW_ADD_FORM' => 'Y',
                                        'SHOW_EDIT_FORM' => 'Y',
                                        'ADD_READ_ONLY_FIELD' => 'Y',
                                        'EDIT_READ_ONLY_FIELD' => 'Y',
                                        'SHOW_FIELD_PREVIEW' => 'Y'
                                    ]
                                ]
                            ]
                        ],
                        'add_field_2' =>[
                            'method' => 'lists.field.add',
                            'params' => [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $add_lists['result'],
                                'FIELDS' => [
                                    'NAME' => 'Телефон',
                                    'IS_REQUIRED' => 'N',
                                    'MULTIPLE' => 'N',
                                    'TYPE' => 'S',
                                    'SORT' => '30',
                                    'DEFAULT_VALUE' => 'null',
                                    'SETTINGS' => [
                                        'SHOW_ADD_FORM' => 'Y',
                                        'SHOW_EDIT_FORM' => 'Y',
                                        'ADD_READ_ONLY_FIELD' => 'Y',
                                        'EDIT_READ_ONLY_FIELD' => 'Y',
                                        'SHOW_FIELD_PREVIEW' => 'Y'
                                    ]
                                ]
                            ]
                        ],
                        'add_field_3' =>[
                            'method' => 'lists.field.add',
                            'params' => [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $add_lists['result'],
                                'FIELDS' => [
                                    'NAME' => '	Дата рождения',
                                    'IS_REQUIRED' => 'N',
                                    'MULTIPLE' => 'N',
                                    'TYPE' => 'S',
                                    'SORT' => '40',
                                    'DEFAULT_VALUE' => 'null',
                                    'SETTINGS' => [
                                        'SHOW_ADD_FORM' => 'Y',
                                        'SHOW_EDIT_FORM' => 'Y',
                                        'ADD_READ_ONLY_FIELD' => 'Y',
                                        'EDIT_READ_ONLY_FIELD' => 'Y',
                                        'SHOW_FIELD_PREVIEW' => 'Y'
                                    ]
                                ]
                            ]
                        ],
                        'add_field_4' =>[
                            'method' => 'lists.field.add',
                            'params' => [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $add_lists['result'],
                                'FIELDS' => [
                                    'NAME' => '	Город',
                                    'IS_REQUIRED' => 'N',
                                    'MULTIPLE' => 'N',
                                    'TYPE' => 'S',
                                    'SORT' => '50',
                                    'DEFAULT_VALUE' => 'null',
                                    'SETTINGS' => [
                                        'SHOW_ADD_FORM' => 'Y',
                                        'SHOW_EDIT_FORM' => 'Y',
                                        'ADD_READ_ONLY_FIELD' => 'Y',
                                        'EDIT_READ_ONLY_FIELD' => 'Y',
                                        'SHOW_FIELD_PREVIEW' => 'Y'
                                    ]
                                ]
                            ]
                        ],
                        'add_field_5' =>[
                            'method' => 'lists.field.add',
                            'params' => [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $add_lists['result'],
                                'FIELDS' => [
                                    'NAME' => 'Сделка',
                                    'IS_REQUIRED' => 'N',
                                    'MULTIPLE' => 'N',
                                    'SORT' => '60',
                                    'TYPE' => 'S:ECrm',
                                    'DEFAULT_VALUE' => 'null',
                                    'USER_TYPE_SETTINGS' => [
                                    'DEAL' => 'Y',
                                    'VISIBLE' => 'Y'
                                    ],
                                    'SETTINGS' => [
                                        'SHOW_ADD_FORM' => 'Y',
                                        'SHOW_EDIT_FORM' => 'Y',
                                        'ADD_READ_ONLY_FIELD' => 'Y',
                                        'EDIT_READ_ONLY_FIELD' => 'Y',
                                        'SHOW_FIELD_PREVIEW' => 'Y'
                                    ]
                                ]
                            ]
                        ],
                        'add_field_6' =>[
                            'method' => 'lists.field.add',
                            'params' => [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $add_lists['result'],
                                'FIELDS' => [
                                    'NAME' => 'Место прилета/приезда туриста',
                                    'IS_REQUIRED' => 'N',
                                    'MULTIPLE' => 'N',
                                    'TYPE' => 'S',
                                    'SORT' => '70',
                                    'DEFAULT_VALUE' => 'null',
                                    'SETTINGS' => [
                                        'SHOW_ADD_FORM' => 'Y',
                                        'SHOW_EDIT_FORM' => 'Y',
                                        'ADD_READ_ONLY_FIELD' => 'Y',
                                        'EDIT_READ_ONLY_FIELD' => 'Y',
                                        'SHOW_FIELD_PREVIEW' => 'Y'
                                    ]
                                ]
                            ]
                        ],
                        'add_field_7' =>[
                            'method' => 'lists.field.add',
                            'params' => [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $add_lists['result'],
                                'FIELDS' => [
                                    'NAME' => 'Пакет',
                                    'IS_REQUIRED' => 'N',
                                    'MULTIPLE' => 'N',
                                    'TYPE' => 'S',
                                    'SORT' => '80',
                                    'DEFAULT_VALUE' => 'null',
                                    'SETTINGS' => [
                                        'SHOW_ADD_FORM' => 'Y',
                                        'SHOW_EDIT_FORM' => 'Y',
                                        'ADD_READ_ONLY_FIELD' => 'Y',
                                        'EDIT_READ_ONLY_FIELD' => 'Y',
                                        'SHOW_FIELD_PREVIEW' => 'Y'
                                    ]
                                ]
                            ]
                        ],
                        'add_field_8' =>[
                            'method' => 'lists.field.add',
                            'params' => [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $add_lists['result'],
                                'FIELDS' => [
                                    'NAME' => 'Полная стоимость',
                                    'IS_REQUIRED' => 'N',
                                    'MULTIPLE' => 'N',
                                    'TYPE' => 'S',
                                    'SORT' => '90',
                                    'DEFAULT_VALUE' => 'null',
                                    'SETTINGS' => [
                                        'SHOW_ADD_FORM' => 'Y',
                                        'SHOW_EDIT_FORM' => 'Y',
                                        'ADD_READ_ONLY_FIELD' => 'Y',
                                        'EDIT_READ_ONLY_FIELD' => 'Y',
                                        'SHOW_FIELD_PREVIEW' => 'Y'
                                    ]
                                ]
                            ]
                        ],
                        'add_field_9' =>[
                            'method' => 'lists.field.add',
                            'params' => [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $add_lists['result'],
                                'FIELDS' => [
                                    'NAME' => 'Внесена предоплата',
                                    'IS_REQUIRED' => 'N',
                                    'MULTIPLE' => 'N',
                                    'TYPE' => 'S',
                                    'SORT' => '100',
                                    'DEFAULT_VALUE' => 'null',
                                    'SETTINGS' => [
                                        'SHOW_ADD_FORM' => 'Y',
                                        'SHOW_EDIT_FORM' => 'Y',
                                        'ADD_READ_ONLY_FIELD' => 'Y',
                                        'EDIT_READ_ONLY_FIELD' => 'Y',
                                        'SHOW_FIELD_PREVIEW' => 'Y'
                                    ]
                                ]
                            ]
                        ],
                        'add_field_10' =>[
                            'method' => 'lists.field.add',
                            'params' => [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $add_lists['result'],
                                'FIELDS' => [
                                    'NAME' => 'Осталось оплатить!',
                                    'IS_REQUIRED' => 'N',
                                    'MULTIPLE' => 'N',
                                    'TYPE' => 'S',
                                    'SORT' => '110',
                                    'DEFAULT_VALUE' => 'null',
                                    'SETTINGS' => [
                                        'SHOW_ADD_FORM' => 'Y',
                                        'SHOW_EDIT_FORM' => 'Y',
                                        'ADD_READ_ONLY_FIELD' => 'Y',
                                        'EDIT_READ_ONLY_FIELD' => 'Y',
                                        'SHOW_FIELD_PREVIEW' => 'Y'
                                    ]
                                ]
                            ]
                        ],
                        'add_field_11' =>[
                            'method' => 'lists.field.add',
                            'params' => [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $add_lists['result'],
                                'FIELDS' => [
                                    'NAME' => 'Доп. услуги и их количество',
                                    'IS_REQUIRED' => 'N',
                                    'MULTIPLE' => 'N',
                                    'TYPE' => 'S',
                                    'SORT' => '120',
                                    'DEFAULT_VALUE' => 'null',
                                    'SETTINGS' => [
                                        'SHOW_ADD_FORM' => 'Y',
                                        'SHOW_EDIT_FORM' => 'Y',
                                        'ADD_READ_ONLY_FIELD' => 'Y',
                                        'EDIT_READ_ONLY_FIELD' => 'Y',
                                        'SHOW_FIELD_PREVIEW' => 'Y'
                                    ]
                                ]
                            ]
                        ],
                        'add_field_12' =>[
                            'method' => 'lists.field.add',
                            'params' => [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $add_lists['result'],
                                'FIELDS' => [
                                    'NAME' => 'Важная информация',
                                    'IS_REQUIRED' => 'N',
                                    'MULTIPLE' => 'N',
                                    'TYPE' => 'S',
                                    'SORT' => '130',
                                    'DEFAULT_VALUE' => 'null',
                                    'SETTINGS' => [
                                        'SHOW_ADD_FORM' => 'Y',
                                        'SHOW_EDIT_FORM' => 'Y',
                                        'ADD_READ_ONLY_FIELD' => 'Y',
                                        'EDIT_READ_ONLY_FIELD' => 'Y',
                                        'SHOW_FIELD_PREVIEW' => 'Y'
                                    ]
                                ]
                            ]
                        ]
                    ]);
                        
                        
                        $add_section = CRest::call(
                            'entity.section.add',
                            [
                                'ENTITY' => 'lists_entity',
                                'NAME' => $iDealID
                            ]
                        );
                        $section_id = $add_section['result'];

                        $get_fields_lists = CRest::call(
                            'lists.field.get',
                            [
                                'IBLOCK_TYPE_ID' => 'lists',
                                'IBLOCK_ID' => $add_lists['result']
                            ]
                        );
                        $arr_name_field = array();
                        foreach($get_fields_lists['result'] as $field_item_get) {
                            array_push($arr_name_field, $field_item_get['FIELD_ID']);
                        }
                        $count_elem = 0;
                        $array_entity_id_new = array();
                        foreach($contact_id as $id) {
                        $count_elem++;
                            $get_contact_info = CRest::call(
                                'crm.contact.get',
                                [
                                    'id' => $id['CONTACT_ID']
                                ]
                            );
                            $name_contact = $get_contact_info['result']['LAST_NAME'].' '.$get_contact_info['result']['NAME'].' '.$get_contact_info['result']['SECOND_NAME'];
                            $birthday = explode('T', $get_contact_info['result']['BIRTHDATE']);
                            $add_elem_old_lists = CRest::call(
                                'lists.element.add',
                                [
                                    'IBLOCK_TYPE_ID' => 'lists',
                                    'IBLOCK_ID' => $add_lists['result'],
                                    'ELEMENT_CODE' => rand(1, PHP_INT_MAX),
                                    'FIELDS' => [
                                        $arr_name_field[0] => $name_contact,
                                        $arr_name_field[1] => $count_elem,
                                        $arr_name_field[2] => $get_contact_info['result']['PHONE'][0]['VALUE'],
                                        $arr_name_field[3] => $birthday[0],
                                        $arr_name_field[4] => $city_from[0],
                                        $arr_name_field[5] => $iDealID,
                                        $arr_name_field[6] => $city_to,
                                        $arr_name_field[7] => $package,
                                        $arr_name_field[8] => $money_all,
                                        $arr_name_field[9] => $money_from,
                                        $arr_name_field[10] => $money_to,
                                        $arr_name_field[11] => $service,
                                        $arr_name_field[12] => $comments
                                    ]
                                ]
                            );
                            
                            $item_entity_add = CRest::call(
                                'entity.item.add',
                                [
                                    'ENTITY' => 'lists_entity',
                                    'SECTION' => $section_id,
                                    'NAME' => $name_contact,
                                    'PROPERTY_VALUES' => [
                                        'lists_id' => $add_lists['result'],
                                        'id_deal' => $iDealID,
                                        'id_contact' => $get_contact_info['result']['ID'],
                                        'list_field_1' => $name_contact,
                                        'list_field_2' => $count_elem,
                                        'list_field_3' => $get_contact_info['result']['PHONE'][0]['VALUE'],
                                        'list_field_4' => $birthday[0],
                                        'list_field_5' => $city_from[0],
                                        'list_field_6' => $iDealID,
                                        'list_field_7' => $city_to,
                                        'list_field_8' => $package,
                                        'list_field_9' => $money_all,
                                        'list_field_10' => $money_from,
                                        'list_field_11' => $money_to,
                                        'list_field_12' => $service,
                                        'list_field_13' => $comments,
                                        'list_item_new' => $add_elem_old_lists['result'],
                                        'list_lists_item_id' => 'null',
                                        'product_id' => $product_id,
                                        'date_from' => $date_from_origin,
                                        'date_to' => $date_to_origin
                                    ]
                                ]
                            );
                            array_push($array_entity_id_new, $item_entity_add['result']);
                        }
                
            
            

        }
        
        $get_elements_tour = CRest::call(
            'lists.element.get',
            [
                'IBLOCK_ID' => '243',
                'IBLOCK_TYPE_ID' => 'lists',
                'FILTER' => [
                    'NAME' => $product_name
                ]
            ]
        );

        $count_elem_update = 0;
        $count_elem_add = 1;
        foreach($get_elements_tour['result'] as $elem_res) {
            
            $prop_106 = current($elem_res['PROPERTY_2131']);
            
            $prop_108 = current($elem_res['PROPERTY_2133']);
            if($prop_106 == $date_from_origin && $prop_108 == $date_to_origin){
                $id_elem_res = $elem_res['ID'];
                $count_elem_update = 1;
            } else {
                $count_elem_add = 1;
            }
        }
        
        
        if($count_elem_update == '1') {
            $get_element_tour_info = CRest::call(
                'lists.element.get',
                [
                    'IBLOCK_ID' => '243',
                    'IBLOCK_TYPE_ID' => 'lists',
                    'ELEMENT_ID' => $id_elem_res
                ]
            );
            foreach($get_element_tour_info['result'] as $res_info) {
                $count_pass = current($res_info['PROPERTY_2135']);
                if($name_found_0 == 'Совпадение найдено') {
                    $count_busy_pass = current($res_info['PROPERTY_2137']);
                } else {
                    $count_busy_pass = current($res_info['PROPERTY_2137']) + $j;
                }
                
                $count_free_pass = $count_pass - $count_busy_pass;
                $elem_update_id = $res_info['ID'];
            }            
            
            $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$id_list.'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';
            if($count_free_pass <= '3') {
                $message_notify = 'В туре '.$product_name.' '.$date_from_origin.' '.$date_to_origin.' осталось свободных мест '.$count_free_pass.'. '.$link_lists_tour.'.';
                $add_notify = CRest::call(
                    'im.notify.system.add',
                    [
                        'USER_ID' => $user_notify,
                        'MESSAGE' => $message_notify
                    ]
                );

                $update_elements_tour = CRest::call(
                    'lists.element.update',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => '243',
                        'ELEMENT_ID' => $id_elem_res,
                        'FIELDS' => [
                            'NAME' => $product_name,
                            'PROPERTY_2131' => $date_from_origin,
                            'PROPERTY_2133' => $date_to_origin,
                            'PROPERTY_2135' => $count_pass,
                            'PROPERTY_2139' => $count_free_pass,
                            'PROPERTY_2137' => $count_busy_pass,
                            'PROPERTY_2141' => $link_lists_tour,
                            'PROPERTY_2129' => $region
                        ]
                    ]
                ); 
            } else {
                
                $update_elements_tour = CRest::call(
                    'lists.element.update',
                    [
                        'IBLOCK_TYPE_ID' => 'lists',
                        'IBLOCK_ID' => '243',
                        'ELEMENT_ID' => $id_elem_res,
                        'FIELDS' => [
                            'NAME' => $product_name,
                            'PROPERTY_2131' => $date_from_origin,
                            'PROPERTY_2133' => $date_to_origin,
                            'PROPERTY_2135' => $count_pass,
                            'PROPERTY_2139' => $count_free_pass,
                            'PROPERTY_2137' => $count_busy_pass,
                            'PROPERTY_2141' => $link_lists_tour,
                            'PROPERTY_2129' => $region
                        ]
                    ]
                ); 
            }
        } elseif ($count_elem_add == '1' && $count_elem_update == '0') {
            $count_pass = 20;
            $count_busy_pass = 0 + $product_quantity_deal_field;
            $count_free_pass = $count_pass - $count_busy_pass;
            $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$id_list.'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';

            $elem_id_tour = rand(1, PHP_INT_MAX);
            $add_elements_tour = CRest::call(
                'lists.element.add',
                [
                    'IBLOCK_TYPE_ID' => 'lists',
                    'IBLOCK_ID' => '243',
                    'ELEMENT_CODE' => rand(1, PHP_INT_MAX),
                    'FIELDS' => [
                        'NAME' => $product_name,
                        'PROPERTY_2131' => $date_from_origin,
                        'PROPERTY_2133' => $date_to_origin,
                        'PROPERTY_2135' => $count_pass,
                        'PROPERTY_2139' => $count_free_pass,
                        'PROPERTY_2137' => $count_busy_pass,
                        'PROPERTY_2141' => $link_lists_tour,
                        'PROPERTY_2129' => $region
                    ]
                ]
            );

            foreach($array_entity_id_new as $item_id_update) {
                $update_item_entity = CRest::call(
                    'entity.item.update',
                    [
                        'ENTITY' => 'lists_entity',
                        'SECTION' => $section_id,
                        'ID' => $item_id_update,
                        'PROPERTY_VALUES' => [
                            'list_lists_item_id' => $add_elements_tour['result']
                        ]
                    ]
                );
            }
            
        }

        $get_info_field_deal = CRest::call(
            'crm.deal.get',
            [
                'ID' => $iDealID
            ]
        );

        $field_value_deal = $get_info_field_deal['result']['UF_CRM_1631876593355'];

        if($field_value_deal == $package) {

        } else {
            $update_deal = CRest::call(
                'crm.deal.update',
                [
                    'ID' => $iDealID,
                    'fields' => [
                        'UF_CRM_1631876593355' => $package
                    ]
                ]
            );
        }
        
    }
    


    
}


function executeREST($method, $params, $auth, &$call_count, $debug = false) {

    $call_count++;

    // тормозим перед выполнением каждого третьего запроса
    if ($call_count == 2) {
        
        sleep(1);
        $call_count = 0;
    }

        $queryUrl = 'https://'.$auth['domain'].'/rest/'.$method.'.json';
        $queryData = http_build_query(array_merge($params, array("auth" => $auth["access_token"])));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $queryUrl,
            CURLOPT_POSTFIELDS => $queryData,
        ));

        $result = json_decode(curl_exec($curl), true);
        curl_close($curl);


    return $result;

}






