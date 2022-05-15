<?php
declare(strict_types=1);

namespace App\Presenters;


use App\Model\UserModel;
use Nette\Application\UI\Presenter;
use Nette\Application\AbortException;

abstract class BasePresenter extends Presenter
{

    /** @var UserModel @inject */
    public UserModel $userModel;

    /**
     * @return void
     * @throws AbortException
     */
    public function startup()
    {
        parent::startup();

        if($this->getUser()->isLoggedIn()){
            if(!$this->userModel->isUserActive($this->getUser()->getId())){
                $this->getUser()->logout(true);
                $this->flashMessage("Váš učet byl deaktivován.", "warning");
                $this->redirect("Homepage:");
            }
        }

    }


}