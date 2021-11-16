<?php

namespace bandpress\Views\ComponentViews;
use \bandpress\Models\User;

class Nav{

    private $user;

    private $navItems = [];

    public function __construct($data=null){
      
        
        if( $user = is_user_logged_in() ){

          // set left to the user name;
          $user= new User(wp_get_current_user());
          $this->user = $user;
          $logo_link = [$this->user->display_name()=>"/users/".$this->user->slug()];
          $this->setSectionLinks("branding_logo", $logo_link);

          $session_links = [];
          // provide Admin Link
          if (current_user_can("access_admin")){
            $session_links["Admin"] = "/wp-admin";
          }
          $session_links["Sign Off"]="/logout";
          $session_links["Picken Chicken"]="/pickenchicken";
          $this->setSectionLinks("session_links", $session_links);
        }
        else{
          $logo_link = ["Fork, The Internet."=>"#"];
          $this->setSectionLinks("branding_logo", $logo_link);
          
          // TBD:  Make Package Linking automatic
          $session_links=["Sign Up"=>"/register","Sign On"=>"/login"];
          $this->setSectionLinks("session_links", $session_links);
        }
        ?>
        <?php
        
    }

    public function __destruct(){

    }

    public function setSectionLinks($section_name, $links=[]){
      $this->navItems[$section_name] = $links;
    }

    public function render(){
        ?>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
          <div class="container-fluid">
            <?php
              foreach($this->navItems['branding_logo'] as $display=>$url):
            ?>
            <a class="navbar-brand" href="<?=$url;?>"><?= $display;?></a>
            <?php endforeach;?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
              <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <?php foreach($this->navItems['session_links'] as $display=>$url):?>
                <li class="nav-item">
                  <a class="nav-link" aria-current="page" href="<?=$url;?>"><?=$display;?></a>
                </li>
                <?php endforeach;?>

                <?php if($this->hasPackageLinks()):                
                        foreach($this->navItems['package_links'] as $display=>$url):?>
                <li class="nav-item">
                  <a class="nav-link" aria-current="page" href="<?=$url;?>"><?= $display; ?></a>
                </li>
                <?php endforeach; endif;?>
              </ul>
            </div>
          </div>
        </nav>
        <?php
    }

    private function hasPackageLinks(){
      return isset($this->navItems['package_links']);
    }

    private function render_user(){
      ?>
       
<?php
    }

    private function render_nouser(){
      ?>
       <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Fork The Internet</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item">
          <a class="nav-link" href="/register">Sign Up</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/login">Sign On</a>
        </li>
      </ul>
      
    </div>
  </div>
</nav>
<?php
    }
}
?>