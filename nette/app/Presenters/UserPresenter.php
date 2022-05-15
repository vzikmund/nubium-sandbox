<?php
declare(strict_types=1);

namespace App\Presenters;


use App\Forms\UserFormFactory;
use Nette\Application\UI\Form;
use Nette\Application\AbortException;

final class UserPresenter extends BasePresenter
{

    /** @var UserFormFactory @inject */
    public UserFormFactory $userFormFactory;

    /**
     * @return void
     * @throws AbortException
     */
    public function actionDefault():void{
        if(!$this->getUser()->isLoggedIn()){
            $this->redirect("signIn");
        }
    }

    /**
     * @return void
     * @throws AbortException
     */
    public function actionRegistration(): void
    {
        if ($this->getUser()->isLoggedIn()) {
            $this->redirect("default");
        }
    }

    /**
     * @return void
     * @throws AbortException
     */
    public function actionSignIn(): void
    {
        if ($this->getUser()->isLoggedIn()) {
            $this->redirect("default");
        }
    }


    /**
     * Vytvoreni registracniho formulare
     * @return Form
     */
    protected function createComponentRegistrationForm(): Form
    {
        return $this->userFormFactory->createRegister(
            function () {
                # presmerovani na prihlasovaci stranku
                $this->flashMessage("Registrace byla úspěšná, můžete se přihlásit.", "success");
                $this->redirect("signIn");
            }
        );
    }

    /**
     * Přihlašovací formulář
     * @return Form
     */
    protected function createComponentSignInForm():Form{
        return $this->userFormFactory->createSignIn(function(){
            $this->redirect("default");
        });
    }

    /**
     * Formular pro zmenu hesla
     * @return Form
     */
    protected function createComponentChangePassForm():Form{
        return $this->userFormFactory->createChangePassword(
            function(){
                $this->flashMessage("Heslo bylo úspěšně změněno.", "success");
                $this->redirect("this");
            }
        );
    }

    /**
     * @return void
     * @throws AbortException
     */
    public function handleLogout():void{
        $this->getUser()->logout(true);
        $this->redirect("Homepage:");
    }

}