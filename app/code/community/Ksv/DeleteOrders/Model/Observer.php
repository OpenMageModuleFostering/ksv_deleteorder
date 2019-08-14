<?php
class Ksv_DeleteOrders_Model_Observer
{
    const SALES_ORDER_GRID_NAME = 'sales_order_grid';
    
    public function addOptionToSelect($observer)
    {
        if (self::SALES_ORDER_GRID_NAME == $observer->getEvent()->getBlock()->getId()) {
            $massBlock = $observer->getEvent()->getBlock()->getMassactionBlock();
            if ($massBlock) {
                $massBlock->addItem('ksv_delete_orders', array(
                    'label'=> Mage::helper('core')->__('Delete'),
                    'url'  => Mage::getUrl('ksv_delete_orders', array('_secure'=>true)),
                    'confirm' => Mage::helper('core')->__('Are you sure to delete the selected orders?'),
                ));
            }
        }
    }
    
    public function deleteOrderFromGrid($observer)
    {
        // This is actually not needed for databases with working foreign keys but some databases are corrupt :(
        $order = $observer->getOrder();
        if ($order->getId()) {
            $coreResource = Mage::getSingleton('core/resource');
            $writeConnection = $coreResource->getConnection('core_write');
            $salesOrderGridTable = $coreResource->getTableName('sales_flat_order_grid');
            $query = sprintf('Delete from %s where entity_id = %s', $salesOrderGridTable, (int)$order->getId());
            $writeConnection->raw_query($query);
        }
    }
}
