<?php

namespace App\Controller;

use App\Core\Helpers;
use App\Core\ReceivePassword;
use App\Core\Session;
use App\Core\User as UserClean;
use App\Core\Verificator;
use App\Core\View;
use App\Core\FormBuilder;
use App\Core\Recaptcha;
use App\Model\User as UserModel;
use App\Model\ReceivePassword as ReceivePasswordModel;

class User
{
    public function login()
    {
        $user = new UserModel();

        if (!empty($_POST)) {

            $user->setEmail(htmlspecialchars($_POST["email"]));
            $user->setPassword(htmlspecialchars($_POST["password"]));
            $user->login(["email" => $_POST['email']]);

        }


        $view = new View("login");
        $form = FormBuilder::render($user->getLoginForm());
        $view->assign("form", $form);
    }


    public function logout()
    {
        echo "Se déconnecter";
    }


    public function register()
    {
        $user = new UserModel();
        $session = new Session();

        if (!empty($_POST)) {


            $data = array_merge($_POST, $_FILES);
            $verification = Verificator::checkForm($user->getRegisterForm(), $data);

            if ($verification) {
//                $user = $user->getBy("id", 33);
//
//                $user->setEmail("y.sssvhvhvhvsjjjjsss@gmail.com");
//
//                //$user->setPassword("Test1234");
//                //$user->setLastname("SKrzypCZK   ");
//                //$user->setFirstname("  YveS");
//                //$user->generateToken();
//
                $user->setFirstname(htmlspecialchars($_POST["firstname"]));
                $user->setLastname(htmlspecialchars($_POST["lastname"]));
                $user->setEmail(htmlspecialchars($_POST["email"]));
                $user->setPassword(htmlspecialchars($_POST["password"]));

                $user->generateToken((Helpers::createToken()));

                $user->save();

            }

            $session->set("success", "Your registration is OK!");
        }

        $view = new View("Register");
        $form = FormBuilder::render($user->getRegisterForm());
        $view->assign("form", $form);
    }

    public function recoverPassword()
    {
        $user = new UserModel();
        $receivePasswordManager = new ReceivePasswordModel();
        $receivePass = new ReceivePassword();

        if (!empty($_POST)) {
            echo "<pre>";

            $user = $user->getBy("email", $_POST['email']);

            $idUser = $user->getId();

            $receivePasswordManager->setToken(Helpers::createToken());
            $receivePasswordManager->setIdUser($idUser);
            $receivePasswordManager->setEmail($user->getEmail());
            $receivePasswordManager->save();
            $receivePass->sendPasswordResetEmail($receivePasswordManager);

        }

        $view = new View("forgotPassword");
        $form = FormBuilder::render($receivePasswordManager->getForgetPswdForm());
        $view->assign("form", $form);

    }

    public function changePassword()
    {
        echo "aze";
    }

}











