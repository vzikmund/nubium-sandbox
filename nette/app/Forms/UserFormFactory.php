<?php
declare(strict_types=1);

namespace App\Forms;


use App\Model\UserModel;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;
use Nette\Security\User;


final class UserFormFactory
{

    /**
     * @param FormFactory $factory
     * @param UserModel $userModel
     * @param Passwords $passwords
     * @param User $user
     */
    public function __construct(
        private FormFactory $factory,
        private UserModel $userModel,
        private Passwords $passwords,
        private User $user
    )
    {
    }

    /**
     * Formular pro prihlaseni
     * @param callable $onSuccess
     * @return Form
     */
    public function createSignIn(callable $onSuccess): Form
    {
        $form = $this->factory->create();

        $form->addText("login", "Přihlašovací jméno")
            ->setRequired();

        $form->addPassword("password", "Heslo")
            ->setRequired();

        $form->addSubmit("send", "Přihlásit");

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {

            $row = $this->userModel->getByLogin($values->login)->fetch();

            # kontrola existence uzivatele
            if(!$row){
                $form->addError("Jméno nebo heslo není správné.");
                return;
            }

            # kontrola hesla
            if(!$this->passwords->verify($values->password, $row["password"])){
                $form->addError("Jméno nebo heslo není správné.");
                return;
            }

            if($this->passwords->needsRehash($row["password"])){
                $row->update(["password" => $this->passwords->hash($values->password)]);
            }

            $data = $row->toArray();
            unset($data["password"]);
            $identity = new SimpleIdentity($data["id"], null, $data);
            $this->user->login($identity);

            $onSuccess();
        };
        return $form;

    }


    /**
     * Vytvoreni formulare pro registraci
     * @param callable $onSuccess
     * @return Form
     */
    public function createRegister(callable $onSuccess): Form
    {

        $form = $this->factory->create();

        $form->addText("login", "Přihlašovací jméno")
            ->addRule($form::PATTERN, "V přihlašovacím jménu použijte pouze alfanumerické znaky, čárku nebo podtržítko", "[A-Za-z0-9_-]+")
            ->addRule($form::MAX_LENGTH, null, 30)
            ->setOption("description", "Povolené jsou pouze alfanumerické znaky, čárka a podtržítko")
            ->setRequired();

        $form->addText("first_name", "Jméno")
            ->addRule($form::MAX_LENGTH, null, 15)
            ->setRequired();

        $form->addText("last_name", "Příjmení")
            ->addRule($form::MAX_LENGTH, null, 15)
            ->setRequired();

        $form->addPassword("password", "Heslo")
            ->setRequired();

        $form->addSubmit("send", "Registrovat");
        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {

            try {
                $values->password = $this->passwords->hash($values->password);
                $this->userModel->createUser($values);
            } catch (UniqueConstraintViolationException $e) {
                $form->addError("Uživatel s tímto jménem již existuje, zvolte jiné.");
                return;
            }

            $onSuccess();

        };
        return $form;

    }


}