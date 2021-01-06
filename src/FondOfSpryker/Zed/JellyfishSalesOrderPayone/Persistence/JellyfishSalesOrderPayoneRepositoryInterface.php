<?php

namespace FondOfSpryker\Zed\JellyfishSalesOrderPayone\Persistence;

use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\SalesPaymentTransfer;

interface JellyfishSalesOrderPayoneRepositoryInterface
{
    /**
     * @param  int  $idSalesPayment
     *
     * @return \Generated\Shared\Transfer\SalesPaymentTransfer
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function findSalesPaymentByIdSalesPayment(int $idSalesPayment): SalesPaymentTransfer;

    /**
     * @param  int  $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PayonePaymentTransfer
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function findPaymentPayoneByIdSalesOrder(int $idSalesOrder): PayonePaymentTransfer;

    /**
     * @param  int  $idSalesPayment
     *
     * @return string
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function findPaymentTransactionIdByIdSalesPayment(int $idSalesPayment): string;
}
