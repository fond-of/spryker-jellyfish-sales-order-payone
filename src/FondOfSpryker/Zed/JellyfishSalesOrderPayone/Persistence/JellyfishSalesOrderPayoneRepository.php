<?php

namespace FondOfSpryker\Zed\JellyfishSalesOrderPayone\Persistence;

use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\SalesPaymentTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \FondOfSpryker\Zed\JellyfishSalesOrderPayone\Persistence\JellyfishSalesOrderPayonePersistenceFactory getFactory()
 */
class JellyfishSalesOrderPayoneRepository extends AbstractRepository implements JellyfishSalesOrderPayoneRepositoryInterface
{
    /**
     * @param int $idSalesPayment
     *
     * @return \Generated\Shared\Transfer\SalesPaymentTransfer
     */
    public function findSalesPaymentByIdSalesPayment(int $idSalesPayment): SalesPaymentTransfer
    {
        $salesPaymentEntity = $this->getFactory()->createSalesPaymentQuery()
            ->filterByIdSalesPayment($idSalesPayment)->findOne();

        return (new SalesPaymentTransfer())->fromArray($salesPaymentEntity->toArray(), true);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PayonePaymentTransfer
     */
    public function findPaymentPayoneByIdSalesOrder(int $idSalesOrder): PayonePaymentTransfer
    {
        $entity = $this->getFactory()->createPaymentPayoneQuery()
            ->filterByFkSalesOrder($idSalesOrder)->findOne();

        return (new PayonePaymentTransfer())->fromArray($entity->toArray(), true);
    }

    /**
     * @param int $idSalesPayment
     *
     * @return string
     */
    public function findPaymentTransactionIdByIdSalesPayment(int $idSalesPayment): string
    {
        $salesPaymentTransfer = $this->findSalesPaymentByIdSalesPayment($idSalesPayment);

        $paymentPayoneTransfer = $this->findPaymentPayoneByIdSalesOrder($salesPaymentTransfer->getFkSalesOrder());

        if ($paymentPayoneTransfer->getTransactionId() === null) {
            return '';
        }

        return (string)$paymentPayoneTransfer->getTransactionId();
    }
}
