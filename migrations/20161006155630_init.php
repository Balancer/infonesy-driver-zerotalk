<?php

use Phinx\Migration\AbstractMigration;

class Init extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */

/*
	CREATE TABLE comment (comment_id INTEGER, body TEXT, topic_uri TEXT, added DATETIME, json_id INTEGER REFERENCES json (json_id));
	INSERT INTO "comment" VALUES(1, 'Ку!', '1_1PniNzyi8fygvwyBaLpA9oBDVWZ5fXuJUw', 1475485485, 4);
*/

    public function up()
    {
		$table = $this->table('users');
		$table
			// Internal (non-ZeroTalk) user id.
			->addColumn('id', 'integer')
			->addColumn('zerotalk_user_id', 'string', ['length' => 40])
			->addColumn('cert_user_id', 'string', ['length' => 255])
			->save();

		$table = $this->table('topics');
		$table
			// Internal (non-ZeroTalk) user id.
			->addColumn('id', 'integer')
			// N_hash
			->addColumn('zerotalk_topic_uri', 'string', ['length' => 64])
			->addColumn('title', 'text')
			->addColumn('text', 'text')
			->addColumn('added', 'timestamp')
			->create();

		$table = $this->table('comments');
		$table
			// Internal (non-ZeroTalk) user id.
			->addColumn('id', 'integer')
			// Internal id, non-ZeroTalk
			->addColumn('topic_id', 'integer')
			// Internal id, non-ZeroTalk
			->addColumn('user_id', 'integer')
			// for user in topic
			->addColumn('comment_id', 'integer')
			->addColumn('text', 'text')
			->addColumn('added', 'timestamp')
			->create();
    }
}
