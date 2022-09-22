<?
require_once (__DIR__.'/crest.php');

$get_old_elements_tour = CRest::call(
    'lists.element.get',
    [
        'IBLOCK_ID' => '243',
        'IBLOCK_TYPE_ID' => 'lists'
    ]
);

foreach($get_old_elements_tour['result'] as $elem_old_res) {
    $current_date = date('d.m.Y');
    
    $prop_108_old = date(current($elem_old_res['PROPERTY_2133']));

    if(strtotime($current_date) > strtotime($prop_108_old)) {
        $id_elem = $elem_old_res['ID'];
        $array_current_elem = current($elem_old_res['PROPERTY_2141']);
        $add_elements_archive = CRest::call(
            'lists.element.add',
            [
                'IBLOCK_TYPE_ID' => 'lists',
                'IBLOCK_ID' => '65',
                'ELEMENT_CODE' => rand(1, PHP_INT_MAX),
                'FIELDS' => [
                    'NAME' => $elem_old_res['NAME'],
                    'PROPERTY_193' => current($elem_old_res['PROPERTY_2131']),
                    'PROPERTY_195' => current($elem_old_res['PROPERTY_2133']),
                    'PROPERTY_197' => current($elem_old_res['PROPERTY_2135']),
                    'PROPERTY_201' => current($elem_old_res['PROPERTY_2139']),
                    'PROPERTY_199' => current($elem_old_res['PROPERTY_2137']),
                    'PROPERTY_203' => $array_current_elem['TEXT'],
                    'PROPERTY_227' => current($elem_old_res['PROPERTY_2129'])
                ]
            ]
        );

        $delete_elem = CRest::call(
            'lists.element.delete',
            [
                'IBLOCK_ID' => '243',
                'IBLOCK_TYPE_ID' => 'lists',
                'ELEMENT_ID' => $id_elem
            ]
        );
        
    } elseif (strtotime($current_date) < strtotime($prop_108_old)) {

    }
}