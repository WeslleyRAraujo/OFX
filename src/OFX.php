<?php

namespace WeslleyRAraujo\OFX;

use WeslleyRAraujo\OFX\Core\OFXContentFormatter;
use WeslleyRAraujo\OFX\Core\OFXException;
use WeslleyRAraujo\OFX\Core\OFXStream;

class OFX
{
    private string $accountId;
    private string $bankId;
    private \DateTimeImmutable $dateEnd;
    private \DateTimeImmutable $dateStart;
    private null|string $org;
    private \SimpleXMLElement $transactionList;

    /**
     * Body Content
     *
     * @var \SimpleXMLElement
     */
    private \SimpleXMLElement $bodyContent;

    /**
     * Header infos of OFX
     *
     * @var array
     */
    private array $headers = [];

    /**
     * Set the OFX file path
     *
     * @param string $filePath OFX file path
     * @return OFX
     */
    public function __construct(private string $filePath)
    {
        $ofxContent = (new OFXStream($filePath))->read();
        $OFXContentFormatter = (new OFXContentFormatter($ofxContent))->format();
        $this->bodyContent = $OFXContentFormatter->getBody();
        $this->headers = $OFXContentFormatter->getHeader();
        $this->feedAttributes();
    }

    /**
     * Returns the OFX headers
     *
     * @return array
     */
    public function getHeaders() : array
    {
        return $this->headers;
    }

    /**
     * Date start
     *
     * @return \DateTimeImmutable
     */
    public function getDateStart() :\DateTimeImmutable
    {
        return $this->dateStart;
    }

    /**
     * Date end
     *
     * @return \DateTimeImmutable
     */
    public function getDateEnd() :\DateTimeImmutable
    {
        return $this->dateEnd;
    }

    /**
     * Transaction list
     *
     * @return object
     */
    public function getTransactionList() :\SimpleXMLElement
    {
        return $this->transactionList;
    }

    /**
     * Organization
     *
     * @return null|string
     */
    public function getOrg() :null|string
    {
        return $this->org;
    }

    /**
     * Account ID
     *
     * @return stirng
     */
    public function getAccountId() :string
    {
        return $this->accountId;
    }

    /**
     * Bank Id
     *
     * @return string
     */
    public function getBankId() :string
    {
        return $this->bankId;
    }

    /**
     * Feed class attributes
     *
     * @return void
     */
    private function feedAttributes() :void
    {
        $datePattern = "/\[\-.*?\]/";
        $dateStart = $this->bodyContent->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKTRANLIST->DTSTART;
        $dateStart = preg_replace($datePattern, '', $dateStart);
        $dateEnd = $this->bodyContent->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKTRANLIST->DTEND;
        $dateEnd = preg_replace($datePattern, '', $dateEnd);

        $this->dateStart        = new \DateTimeImmutable($dateStart);
        $this->dateEnd          = new \DateTimeImmutable($dateEnd);
        $this->transactionList  = $this->bodyContent->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKTRANLIST;
        $this->org              = $this->bodyContent->SIGNONMSGSRSV1->SONRS->FI->ORG;
        $this->accountId        = $this->bodyContent->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKACCTFROM->ACCTID;
        $this->bankId           = $this->bodyContent->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKACCTFROM->BANKID;
    }
}