<?php

namespace FondOfSpryker\Zed\JellyfishSalesOrderPayone\Communication\Plugin;

use ArrayObject;
use FondOfSpryker\Zed\JellyfishSalesOrderExtension\Dependency\Plugin\JellyfishOrderExpanderPostMapPluginInterface;
use Generated\Shared\Transfer\JellyfishOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfSpryker\Zed\JellyfishSalesOrderPayone\Persistence\JellyfishSalesOrderPayoneRepositoryInterface getRepository()
 */
class JellyfishOrderPaymentExpanderPostMapPlugin extends AbstractPlugin implements JellyfishOrderExpanderPostMapPluginInterface
{
    /**
     * @var array
     */
    protected $transactionIds = [];

    /**
     * @param \Generated\Shared\Transfer\JellyfishOrderTransfer $jellyfishOrderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrder
     *
     * @return \Generated\Shared\Transfer\JellyfishOrderTransfer
     */
    public function expand(
        JellyfishOrderTransfer $jellyfishOrderTransfer,
        SpySalesOrder $salesOrder
    ): JellyfishOrderTransfer {
        $jellyfishOrderTransfer
            ->setPayments($this->updatePayments($jellyfishOrderTransfer->getPayments(), $salesOrder));

        return $jellyfishOrderTransfer;
    }

    /**
     * @param \ArrayObject $payments
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrder
     *
     * @return \ArrayObject
     */
    protected function updatePayments(ArrayObject $payments, SpySalesOrder $salesOrder): ArrayObject
    {
        $updatedPayments = new ArrayObject();

        foreach ($salesOrder->getOrdersJoinSalesPaymentMethodType() as $salesPayment) {
            foreach ($payments as $paymentTransfer) {
                $paymentMethodType = $salesPayment->getSalesPaymentMethodType();
                if (
                    $paymentTransfer->getAmount() === $salesPayment->getAmount()
                    && $paymentTransfer->getProvider() === $paymentMethodType->getPaymentProvider()
                    && $paymentTransfer->getMethod() === $paymentMethodType->getPaymentMethod()
                ) {
                    $paymentTransfer->setTransactionId($this->getTransactionId($salesPayment->getIdSalesPayment()));
                }

                $updatedPayments->append($paymentTransfer);
            }
        }

        return $updatedPayments;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string
     */
    protected function getTransactionId(int $idSalesOrder): string
    {
        if (array_key_exists($idSalesOrder, $this->transactionIds) === false) {
            $this->transactionIds[$idSalesOrder] = $this->getRepository()->findPaymentTransactionIdByIdSalesPayment($idSalesOrder);
        }

        return $this->transactionIds[$idSalesOrder];
    }
}
