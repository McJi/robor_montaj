<?
require_once (__DIR__.'/crest.php');
$iDealID = htmlspecialchars($_REQUEST['properties']['id']);

if ($iDealID > 0) { 
    $get_deal = CRest::call(
        'crm.deal.get',
        [
            'id' => $iDealID
        ]
    );  

    $date_from = explode('T', $get_deal['result']['UF_CRM_1546973064714']);
    $date_to = explode('T', $get_deal['result']['UF_CRM_1546972629975']);
    $arr_users_delete =  $get_deal['result']['UF_CRM_1632120361'];
  	

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

    foreach($get_product_deal['result'] as $product_item) {
        $product_id = $product_item['PRODUCT_ID'];
        $product_name = $product_item['PRODUCT_NAME'];
    }
    
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
    
    
    $package = $get_info_section['result']['NAME'];
    $region = $get_info_section_1['result']['NAME'];
    $date_from_origin = (date('d.m.Y',strtotime($date_from[0])));
    $date_to_origin = (date('d.m.Y',strtotime($date_to[0])));
    
    $lists_name_new = $product_name.' '.$date_from_origin.'-'.$date_to_origin;
    $get_lists = CRest::call(
        'lists.get',
        [
            'IBLOCK_TYPE_ID' => 'lists'
        ]
    );

    $count_name_list_1 = 0;
    $count_name_list_2 = 0;
    
    foreach($get_lists['result'] as $lists_item) {
        if($lists_name_new == $lists_item['NAME']){
            $count_name_list_1 = 1;
            $id_list = $lists_item['ID'];
        } else {
            $count_name_list_2 = 1;
        }
    }

    if($count_name_list_1 == '1'){
       
        $get_elem_lists = CRest::call(
            'lists.element.get',
            [
                'IBLOCK_TYPE_ID' => 'lists',
                'IBLOCK_ID' => $id_list
            ]
        );

        foreach($contact_id as $id) {
            $get_name_user = CRest::call(
                'crm.contact.get',
                [
                    'ID' =>  $id['CONTACT_ID']
                ]
            );

            $name_contact = $get_name_user['result']['LAST_NAME'].' '.$get_name_user['result']['NAME'].' '.$get_name_user['result']['SECOND_NAME'];
            
            foreach($get_elem_lists['result'] as $elem_item) {
                if($elem_item['NAME'] == $name_contact) {

                    $id_elem_del = $elem_item['ID'];
                    $del_elem_lists = CRest::call(
                        'lists.element.delete',
                        [
                            'IBLOCK_TYPE_ID' => 'lists',
                            'IBLOCK_ID' => $id_list,
                            'ELEMENT_ID' => $id_elem_del
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
                    
                    $count_elem_update = 0;
                    $count_elem_add = 1;
                    foreach($get_elements_tour['result'] as $elem_res) {
                        
                        $prop_106 = current($elem_res['PROPERTY_2131']);
                        
                        $prop_108 = current($elem_res['PROPERTY_2133']);
                        if($prop_106 == $date_from_origin && $prop_108 == $date_to_origin){
                            $id_elem_res = $elem_res['ID'];
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
                                $count_busy_pass = (int)(current($res_info['PROPERTY_2137'])) - 1;
                                
                                $count_free_pass = $count_pass - $count_busy_pass;
                                $elem_update_id = $res_info['ID'];
                            }            
                            
                            $link_lists_tour = '<a href="https://youtour.bitrix24.ru/company/lists/'.$id_list.'/view/0/?list_section_id=" target="_blank">Ссылка на список туристов</a>';
    
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
                    }
                      
                }
            }
            
        }


    } elseif($count_name_list_1 != '1' && $count_name_list_2 = '1') {
        $res = 'Такого тура нет';
    }

    $add_comment = CRest::call(
        'crm.timeline.comment.add',
        [
            'fields' => [
                'ENTITY_ID' => $iDealID,
                'ENTITY_TYPE' => 'deal',
                'COMMENT' => $res
            ]
        ]
    );

}








