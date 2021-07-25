<?php 

return [
    'LibUserPerm\\Model\\UserPerm' => [
        'data' => [
            'name' => [
                'manage_venue' => ['group'=>'Venue','about'=>'Allow user to manage venue'],
                'publish_venue' => ['group'=>'Venue','about'=>'Allow user to publish venue'],
                'remove_venue' => ['group'=>'Venue','about'=>'Allow user to remove venue']
            ]
        ]
    ]
];
