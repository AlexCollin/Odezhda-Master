<?php

class SocialController extends RetailController {

    public $catalogData;
    public $categories;

    public function actionVk() {
        $params['access_token']=Yii::app()->request->getQuery('access_token');
        $params['user_id']=Yii::app()->request->getQuery('user_id');
        if ($params['access_token']){
            //порядок сортировки
            switch (Yii::app()->request->getQuery('order')) {
                case 'hits':
                    $criteria['order'] = '[[count_orders]] DESC';
                    break;
                case 'date':
                    $criteria['order'] = '[[date_add]] DESC';
                    break;
                case 'price_down':
                    $criteria['order'] = '[[price]] ASC';
                    break;
                case 'price_up':
                    $criteria['order'] = '[[price]] DESC';
                    break;
                default:
                    $criteria['order'] = '[[date_add]] DESC';
            }

            $model = new CatalogModel();

            // получение товаров в категории
            $data = $model->getDataProvider($criteria,Yii::app()->params['socialPageSize']);
            $dataProvider=$data['dataProvider'];
            $limitPrice=$data['priceLimit'];

            // общее кол-во доступных товаров
            // не считает товары если нет опций

            $totalCount = $dataProvider->getTotalItemCount();

            // пагинация
            $pages = new CPagination($totalCount);
            $pages->pageSize = Yii::app()->params['socialPageSize'];
            $dataProvider->setPagination($pages);

            if(!Yii::app()->request->getQuery('order')){
                $this->layout = '//layouts/main_social';
                $this->render('/social/index',compact('pages','dataProvider','totalCount','limitPrice'));
            }
            else{
                $this->renderPartial('/social/index',compact('pages','dataProvider','totalCount','limitPrice'));
            }
        } else{
            $this->error();
        }

    }
}