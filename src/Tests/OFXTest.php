<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class OFXTest extends TestCase
{
    public function testFileExists()
    {
        $ofxFile = __DIR__ . '/ofx_sample.ofx';
        $this->assertFileExists($ofxFile);
    }

    public function testExceptions()
    {
        $wrongOfxFile = 'wrong_sample.ofx';
        $this->expectException(WeslleyRAraujo\OFX\Core\OFXException::class);
        $this->expectExceptionMessage("File {$wrongOfxFile} not found.");
        $OFX = new WeslleyRAraujo\OFX\OFX($wrongOfxFile);

        $emptyFile = __DIR__ . '/ofx_empty_file_sample.ofx';
        $this->expectException(WeslleyRAraujo\OFX\Core\OFXException::class);
        $this->expectExceptionMessage("OFX content is empty.");
        $OFX = new WeslleyRAraujo\OFX\OFX($emptyFile);
    }

    public function testTypes()
    {
        $ofxFile = __DIR__ . '/ofx_sample.ofx';
        $OFX = new WeslleyRAraujo\OFX\OFX($ofxFile);

        $transactionList = $OFX->getTransactionList();
        $transactionListAsArray = json_decode(json_encode($transactionList), true);

        $this->assertIsObject($OFX->getDateStart());
        $this->assertIsObject($OFX->getDateEnd());
        $this->assertIsArray($transactionListAsArray);
        $this->assertIsArray($OFX->getHeaders());
        $this->assertNull($OFX->getOrg());
        $this->assertIsString($OFX->getBankId());
        $this->assertIsString($OFX->getAccountId());
        $this->assertEquals($OFX->getDateStart()::class, \DateTimeImmutable::class);
        $this->assertEquals($OFX->getDateEnd()::class, \DateTimeImmutable::class);
    }
}