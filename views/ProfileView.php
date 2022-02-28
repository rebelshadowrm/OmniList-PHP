<?php
class ProfileView extends View {

    private $private = false;
    private $visiting = true;
    
    public function __construct($route, \Model $model) {
        parent::__construct($route, $model);
        

    if(isset($_GET['id'])) {

        $getId = filter_input(INPUT_GET, 'id'); 
        //check if id exists
        $user = $this->model->getUserById($getId);

        

        //will return false if the prefers_privacy is true
        //or if the user doesn't exist

        if($user) {

            $login_email = $user->getLoginEmail();
            $email = $user->getEmail();
            $alias = $user->getAlias();
            $id = $user->getId();
            $logged_in_at = $user->getLoggedInAt();
            $logged_in_at = date('M d, Y', strtotime($logged_in_at));
            $registered_at = $user->getRegisteredAt();
            $registered_at = date('M d, Y', strtotime($registered_at));

        } else {
            //to obscure sensitive information, not-exist and private are the same.

            $this->private = true;
            $this->model->data = <<<HTML
            <div class="privateContainer">
                <div class="private">
                    <h1 class="private__title">This profile is set to private</h1>
                    <p class="private__body">This user has selected to not allow their profile to be visible.</p>
                </div>
            </div>
            HTML;

            
        }


    } else {

        //if an id isn't supplied and there isn't a session, take the user to login
        if(!isset($_SESSION['user'])) {
            header('Location: index.php?route=login');
        } else {

        $this->visiting = false;

        //set user props for easier use in the HTML
        $user = $_SESSION['user'];

        $login_email = $user->getLoginEmail();
        $email = $user->getEmail();
        $alias = $user->getAlias();
        $id = $user->getId();
        $logged_in_at = $user->getLoggedInAt();
        $logged_in_at = date('M d, Y', strtotime($logged_in_at));
        $registered_at = $user->getRegisteredAt();
        $registered_at = date('M d, Y', strtotime($registered_at));
        }
    }
    
    if(!$this->private) {


    $this->model->setTitle("{$alias} Profile");

    $this->model->data = <<<HTML
        <div class="profile">
            <div class="profile__topbar">
                <h2 class="profile__title">{$alias}'s Profile</h2>
HTML;

    if(!$this->visiting) {
        $logged_in_at = 'Now';
    $this->model->data .= <<<HTML
        <a class="profile__edit" href="index.php?route=profile&edit"><i></i>Edit Profile</a>
HTML;
    } 

    if($this->visiting) {
        if($_SESSION['user']->getId() === $user->getId()) {
            $logged_in_at = 'Now';
            $this->model->data .= <<<HTML
                <a class="profile__edit" href="index.php?route=profile&edit"><i></i>Edit Profile</a>
HTML;
        }
    }

    $this->model->data .= <<<HTML
            </div>
            <div class="profile__content">
                <div class="profile__sidebar">
                    <img src="https://picsum.photos/150/200" height=200 width=150 class="profile__img">
                    <div class="profile__online">
                        <p>Last Online: </P>
                        <p class="lastOnline">{$logged_in_at}</p>
                    </div>
                    <div class="profile__registered">
                        <p>Joined: </p>
                        <p class="registeredAt">{$registered_at}</p>
                    </div>
                    <div class="friends">
                        <h2>Friends</h2>
                        <template id="friendsTemplate" data-id="{$id}">
                            <div class="friend">
                                <a class="friend__link">
                                    <img src="" alt="" class="friend__img">
                                </a>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="profile__statistics">
                    <h2>Lists</h2>
                    <div class="lists">
                        <a class="profile__link" href="index.php?route=manga_list">Manga List</a>
                        <div class="profile__sub">
                            <a class="profile__link--sub" href="index.php?route=manga_list">Completed</a>
                            <a class="profile__link--sub" href="index.php?route=manga_list">Reading</a>
                            <a class="profile__link--sub" href="index.php?route=manga_list">On-Hold</a>
                            <a class="profile__link--sub" href="index.php?route=manga_list">Dropped</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
HTML;


    }
    // tells the model to give data
    $this->model->getOutput();

    // sets the output from model data
    $this->setOutput();

    // main render call
    echo $this->output;
    }
}