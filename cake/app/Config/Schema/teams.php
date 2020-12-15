<?php

class TeamsSchema extends CakeSchema {

    public $hr_teams = array(
		'id' => array(
            'type' => 'integer',
            'length' => 10,
            'null' => false,
            'key' => 'primary'
        ),
		'name' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
		'deleted' => array(
            'type' => 'boolean',
            'null' => false,
            'default' => 0
        ),
		'created' => array(
            'type' => 'datetime',
            'null' => true
        ),
		'modified' => array(
            'type' => 'datetime',
            'null' => true
        )
	);

    public $hr_team_members = array(
		'id' => array(
            'type' => 'integer',
            'length' => 10,
            'null' => false,
            'key' => 'primary'
        ),
		'hr_team_id' => array(
            'type' => 'integer',
            'length' => 10,
            'null' => false
        ),
        'hr_worker_id' => array(
            'type' => 'integer',
            'length' => 10,
            'null' => false
        ),
		'team_leader' => array(
            'type' => 'boolean',
            'null' => false,
            'default' => 0
        ),
		'deleted' => array(
            'type' => 'boolean',
            'null' => false,
            'default' => 0
        ),
		'created' => array(
            'type' => 'datetime',
            'null' => true
        ),
		'modified' => array(
            'type' => 'datetime',
            'null' => true
        ),
        'indexes' => array(
            'ix_hr_team_id' => array(
                'column' => 'hr_team_id'
            ),
            'ix_hr_worker_id' => array(
                'column' => 'hr_worker_id'
            )
        )
	);

}
