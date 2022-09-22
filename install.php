<?php
require_once (__DIR__.'/crest.php');

$result = CRest::installApp();
if($result['rest_only'] === false):?>
	<head>
		<script src="//api.bitrix24.com/api/v1/"></script>
		<?php if($result['install'] == true):?>
			<script>
				BX24.init(function(){
					BX24.installFinish();
				});
			</script>
		<?php endif;?>
	</head>
	<body>
		<?php if($result['install'] == true):?>
			installation has been finished
		<?php else:?>
			installation error
		<?php endif;?>
	</body>
<?php endif;

$lists_del = CRest::call(
	'bizproc.robot.delete',
	[
		'CODE' => 'lists_add_new',
	]
);

$entity_del = CRest::call(
	'entity.delete',
	[
		'ENTITY' => 'lists_entity'
	]
);


$lists_add = CRest::call(
	'bizproc.robot.add',
	[
		'CODE' => 'lists_add_new',
		'HANDLER' => 'https://server.21vek.it/lists_add_youtour_new/handler.php',
		'AUTH_USER_ID' => 1,
		'NAME' => 'Создание списков новое',
		'PROPERTIES' => [
			'id' => [
				'NAME' => 'ID сделки',
				'TYPE' => 'int'
			]
		]
	]
);

$user_delete = CRest::call(
	'bizproc.robot.add',
	[
		'CODE' => 'user_delete_new',
		'HANDLER' => 'https://server.21vek.it/lists_add_youtour_new/handler_1.php',
		'AUTH_USER_ID' => 1,
		'NAME' => 'Удаление контактов новое',
		'PROPERTIES' => [
			'id' => [
				'NAME' => 'ID сделки',
				'TYPE' => 'int'
			]
		]
	]
);

$entity_add = CRest::call(
	'entity.add',
	[
		'ENTITY' => 'lists_entity',
		'NAME' => 'lists_entity',
		'ACCESS' => [
			'U1' => 'X',
			'AU' => 'X'
		]
	]
);

$property_entity_add = CRest::callBatch([
	'property_item_add_1' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'lists_id',
			'NAME' => 'ID Списка',
			'TYPE' => 'N'
		]
	],
	'property_item_add_2' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'id_deal',
			'NAME' => 'ID Сделки',
			'TYPE' => 'N'
		]
	],
	'property_item_add_3' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'id_contact',
			'NAME' => 'ID Контактa',
			'TYPE' => 'S'
		]
	],
	'property_item_add_4' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'list_field_1',
			'NAME' => 'Значение списка поля 1',
			'TYPE' => 'S'
		]
	],
	'property_item_add_5' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'list_field_2',
			'NAME' => 'Значение списка поля 2',
			'TYPE' => 'S'
		]
	],
	'property_item_add_6' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'list_field_3',
			'NAME' => 'Значение списка поля 3',
			'TYPE' => 'S'
		]
	],
	'property_item_add_7' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'list_field_4',
			'NAME' => 'Значение списка поля 4',
			'TYPE' => 'S'
		]
	],
	'property_item_add_8' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'list_field_5',
			'NAME' => 'Значение списка поля 5',
			'TYPE' => 'S'
		]
	],
	'property_item_add_9' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'list_field_6',
			'NAME' => 'Значение списка поля 6',
			'TYPE' => 'S'
		]
	],
	'property_item_add_10' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'list_field_7',
			'NAME' => 'Значение списка поля 7',
			'TYPE' => 'S'
		]
	],
	'property_item_add_11' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'list_field_8',
			'NAME' => 'Значение списка поля 8',
			'TYPE' => 'S'
		]
	],
	'property_item_add_12' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'list_field_9',
			'NAME' => 'Значение списка поля 9',
			'TYPE' => 'S'
		]
	],
	'property_item_add_13' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'list_field_10',
			'NAME' => 'Значение списка поля 10',
			'TYPE' => 'S'
		]
	],
	'property_item_add_14' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'list_field_11',
			'NAME' => 'Значение списка поля 11',
			'TYPE' => 'S'
		]
	],
	'property_item_add_15' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'list_field_12',
			'NAME' => 'Значение списка поля 12',
			'TYPE' => 'S'
		]
	],
	'property_item_add_16' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'list_item_new',
			'NAME' => 'ID созданных элементов',
			'TYPE' => 'S'
		]
	],
	'property_item_add_17' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'list_lists_item_id',
			'NAME' => 'ID созданного элемента в списке туров',
			'TYPE' => 'S'
		]
	],
	'property_item_add_18' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'product_id',
			'NAME' => 'ID товаров',
			'TYPE' => 'S'
		]
	],
	'property_item_add_19' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'date_from',
			'NAME' => 'Дата начала тура',
			'TYPE' => 'S'
		]
	],
	'property_item_add_20' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'date_to',
			'NAME' => 'Дата окончания тура',
			'TYPE' => 'S'
		]
	],
	'property_item_add_21' => [
		'method' => 'entity.item.property.add',
		'params' => [
			'ENTITY' => 'lists_entity',
			'PROPERTY' => 'list_field_13',
			'NAME' => 'Значение списка поля 13',
			'TYPE' => 'S'
		]
	],
	
]);


