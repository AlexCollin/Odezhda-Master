<?php
// todo: привести в порядок код - убрать комментированный код, добавить описание методам

/**
 * Контроллер по умолчанию
 */
class RetailSiteController extends RetailController {
    public $catalogData;
    public $categories;

    /**
     * Actions attached to this controller
     * @return array
     */
    public function actions() {
        return [
            'error' => 'SimpleErrorAction',
            'logout' => 'LogoutAction',
        ];
    }

    public function actionIndex() {

        $categoriesModel = new ShopCategoriesModel();
        $this->categories = $categoriesModel->getClearCategoriesList();
        $catalogModel =new CatalogModel();
        $this->catalogData= $catalogModel->frontCatalogData();

        $this->render("/site/index");
    }

    
    public function actionProduct() {
        $this->render("/site/product");
    }

    public function actionCatalog(){
        $this->render('/site/catalog');
    }

    /**
     * Авторизация пользователей
     */
    public function actionLogin(){
        $user = Yii::app()->user;
        $this->redirectAwayAlreadyAuthenticatedUsers($user);
        $model = new RetailLoginForm();
        $formData = Yii::app()->request->getPost(get_class($model), false);

        if ($formData) {

            $model->setAttributes($formData,false);

            if ($model->validate(array('username', 'password')) && $model->login())
                $this->redirect($user->returnUrl);
        }

//        $this->controller->layout = '//layouts/blank';
//        $this->controller->render('login', compact('model'));
//        $this->render('/site/index');
    }

    /**
     * Редирект авторизированных пользователей
     * @param $user - модель пользователя
     */
    private function redirectAwayAlreadyAuthenticatedUsers($user) {
        if (!$user->isGuest)
            $this->redirect('/');
//            $this->redirect(Yii::app()->request->baseUrl);
    }

    /**
     * Регистрация пользователей.
     * После регистрации пользователь становится авторизированным
     */
    public function actionRegistration() {
        $user = Yii::app()->user;
        $this->redirectAwayAlreadyAuthenticatedUsers($user);

        $model = new RetailRegisterForm();
        $formData = Yii::app()->request->getPost(get_class($model), false);

        if ($formData) {
            $model->setAttributes($formData,false);
            if ($model->registration()){
//                $this->redirect($user->returnUrl);
                $this->renderPartial('/layouts/parts/successRegister');
                Yii::app()->end();
            }
            else {
                //todo дописать
//                $this->renderPartial('/layouts/parts/register',compact('errors'));
            }
        }
        $this->renderPartial('/layouts/parts/register');
    }

    /**
     * Обработка запроса на скидку
     */
    public function actionDiscountSend(){
//        $name = Yii::app()->request->getPost('name');
//        $email = Yii::app()->request->getPost('email');
//
//        $sender = Yii::app()->email;
//
//        $sender->to = 'admin@example.com';
//        $sender->subject = 'Запрос на скидку';
//        $sender->message = 'Имя: '.$name."\n".'Email: '.$email;
//        $sender->send();

        // todo: добавить увемоление о событии
        $this->redirect('/');
    }
}