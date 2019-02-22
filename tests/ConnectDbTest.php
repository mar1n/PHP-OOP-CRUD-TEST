<?php
/**
 * Created by PhpStorm.
 * User: Szymon
 * Date: 18/02/2019
 * Time: 09:26
 */

use PHPUnit\Framework\TestCase;

class CrudTest extends TestCase {

    public function testConnect() {

        $connectDb = new connectDb();
        $crud = new crud($connectDb);

        $this->assertSame('Connection OK; waiting to send.', $crud->getConnect());
    }
}