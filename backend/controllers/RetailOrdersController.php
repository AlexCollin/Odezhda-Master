<?php

/**
 * Class RetailOrdersController
 */
class RetailOrdersController extends BackendController {

    public $gridDataProvider;

    public $pageTitle = 'Розничные заказы: список';
    public $pageButton = [];
    public $model;

    public function actionIndex() {

        $criteria = [
            'text_search' => [
                'value' => $this->userStateParam('text_search'),
            ],
            'filters' => $this->userStateParam('filters'),
            'order' => [
                'field' => $this->userStateParam('order_field'),
                'direction' => $this->userStateParam('order_direct'),
            ],
            'page_size' => $this->userStateParam('page_size', CPagination::DEFAULT_PAGE_SIZE)
        ];

        //$this->model = new RetailOrders('update');

        $gridDataProvider = RetailOrdersHelper::getDataProvider($criteria);
        $gridDataProvider->setSort(false);

        $statuses = $deliveryPoints = [];

        foreach (RetailOrdersStatuses::model()->findAll() as $status) {
            $statuses[$status['id']] = $status['name'];
        }

        foreach (DeliveryPoints::model()->findAll() as $deliveryPoint) {
            $deliveryPoints[$deliveryPoint['id']] = $deliveryPoint['name'];
        }

        $this->render('index', compact('criteria','gridDataProvider','statuses', 'deliveryPoints'));
    }

    public function actionUpdate() {
        $params['field'] = Yii::app()->request->getPost('name');
        $params['id'] = Yii::app()->request->getPost('pk');
        $params['value'] = Yii::app()->request->getPost('value');

        //$this->model = new RetailOrders('update');
        if (!RetailOrdersHelper::updateField($params)) {
            $this->error(CHtml::errorSummary(RetailOrdersHelper::getModel(), 'Ошибка изменения данных розничного заказа'));
        }
    }

    public function actionAdd() {
        $this->actionEdit(null, 'add');
    }

    public function actionEdit($id, $scenario = 'edit') {
        $customers = $statuses = $deliveryPoints = /*$defaultProviders = $sellers =*/ $paymentMethods = $currencies = [];

        /*$customersModel = new Customer();
        foreach ($customersModel->findAll() as $customer) {
            $customers[$customer['id']] = $customer['firstname'].' '.$customer['lastname'].' ('.$customer['email'].')';
        }*/

        foreach (RetailOrdersStatusesLayer::model()->findAll() as $status) {
            $statuses[$status['id']] = $status['name'];
        }

        foreach (DeliveryPointsLayer::model()->findAll() as $deliveryPoint) {
            $deliveryPoints[$deliveryPoint['id']] = $deliveryPoint['name'];
        }

        /*foreach (DefaultProvidersLayer::model()->findAll() as $provider) {
            $defaultProviders[$provider['id']] = $provider['name'];
        }

        foreach (SellersLayer::model()->findAll() as $seller) {
            $sellers[$seller['id']] = $seller['ur'];
        }

        foreach (CurrenciesLayer::model()->findAll() as $currency) {
            $currencies[$currency['id']] = $currency['name'];
        }

        foreach (PaymentMethodsLayer::model()->findAll() as $method) {
            $paymentMethods[$method['id']] = $method['name'];
        }*/

        //todo: временно оставляю данные здесь, но лучше создать для PaymentMethods и Currencies таблицы в бд (как и для стран и областей)
        foreach([
            'Оплата (Для физических лиц)',
            'Оплата наличными при получении',
            'Оплата по квитанции Сбербанка РФ',
            'После сборки заказа Вам будет выставлен счет (Для юридических лиц)',
            'Предоплата на счёт',
                ] as $method)
            $paymentMethods[$method] = $method;

        $currencies = ['RUR'=>'RUR'];


        //$model = new RetailOrdersLayer($scenario);
        if (!$item = RetailOrdersHelper::getRetailOrderWithInfo($id, $scenario)){
            $this->error('Ошибка получения данных розничного заказа');
        }

        $form_action = Yii::app()->request->getPost('form_action');
        if (!empty($form_action)) {
            // записываем пришедшие с запросом значения в модель, чтобы не сбрасывать уже набранные данные в форме
            $item->setAttributes(RetailOrdersHelper::getPostData(),false);
            // записываем данные
            $result = $item->save();

            if (!$result) {
                // ошибка записи
                Yii::app()->user->setFlash(
                    TbHtml::ALERT_COLOR_ERROR,
                    CHtml::errorSummary($item, 'Ошибка ' . ($id ? 'сохранения' : 'добавления') . ' розничного заказа')
                );
            } else {
                // выкидываем сообщение
                Yii::app()->user->setFlash(
                    TbHtml::ALERT_COLOR_INFO,
                    'Розничный заказ ' . ($id ? 'сохранен' : 'добавлен')
                );
                if ($form_action == 'save') {
                    $this->redirect(['index']);
                    return;
                } else {
                    $this->redirect(['edit', 'id' => $item['id']]);
                    return;
                }
            }
        }

        $this->render('edit', compact('item', 'customers', 'statuses', 'paymentMethods', 'currencies'));
    }

    public function actionDelete($id) {
        $model = RetailOrders::model()->findByPk($id);
        if (!$model->delete()) {
            $this->error();
        } else {
            Yii::app()->user->setFlash(
                TbHtml::ALERT_COLOR_INFO,
                'Заказ удален'
            );
        }
    }

    public function actionMass() {
        $mass_action = Yii::app()->request->getParam('mass_action');
        $ids = array_unique(Yii::app()->request->getParam('ids'));
        switch ($mass_action) {
            case 'delete':
                foreach ($ids as $id) {
                    $this->actionDelete($id);
                }
                break;
        }

        $this->actionIndex();
    }
}
