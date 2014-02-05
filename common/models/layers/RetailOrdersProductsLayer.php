<?php

class RetailOrdersProductsLayer extends RetailOrdersProducts {

    public function getDataProvider($criteria = null) {
        return RetailOrdersProductsHelper::getDataProvider($criteria);
    }

    public function updateField($params = []) {
        return RetailOrdersProductsHelper::updateField($params);
    }

    public function getPostData() {
        $name = get_class(RetailOrdersProductsHelper::getModel());
        return $_POST[$name];
    }

    public function getRetailOrdersProduct($id, $scenario = null) {
        return RetailOrdersProductsHelper::getRetailOrdersProduct($id, $scenario);
    }
}

?>
